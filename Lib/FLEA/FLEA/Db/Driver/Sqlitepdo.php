<?php

/**
 * ���� FLEA_Db_Driver_Sqlite ��������PDOʵ�ַ���sqlite3���ݿ�
 * Editor by lonestone 2007 10 13
 * Email:wangyong.yichang@gmail.com
 * Version 0.1
 */

/**
 * ���� sqlite ʹ��pdo��չ�����ݿ���������
 */
class FLEA_Db_Driver_Sqlite
{
	/**
	 * ���� genSeq()��dropSeq() �� nextId() �� SQL ��ѯ���
	 */
	var $NEXT_ID_SQL = "UPDATE %s SET id = LAST_INSERT_ID(id + 1)";
	var $CREATE_SEQ_SQL = "CREATE TABLE %s (id INT NOT NULL)";
	var $INIT_SEQ_SQL = "INSERT INTO %s VALUES (%s)";
	var $DROP_SEQ_SQL = "DROP TABLE %s";

	/**
	 * ������� true��false �� null �����ݿ�ֵ
	 */
	var $TRUE_VALUE = 1;
	var $FALSE_VALUE = 0;
	var $NULL_VALUE = 'NULL';

	/**
	 * ���ڻ�ȡԪ���ݵ� SQL ��ѯ���
	 */
	var $META_COLUMNS_SQL = "SELECT sql FROM sqlite_master WHERE type='table' and name='%s'"; //sqlite ����������������ݿ��SQL

	/**
	 * ���ݿ�������Ϣ
	 *
	 * @var array
	 */
	var $dsn = null;

	/**
	 * ���ݿ����Ӿ��
	 *
	 * @var resource
	 */
	var $conn = null;

	/**
	 * ���� SQL ��ѯ����־
	 *
	 * @var array
	 */
	var $log = array();

	/**
	 * ָʾ�Ƿ��¼ SQL ��䣨����ģʽʱ������Ĭ��Ϊ false��
	 *
	 * @var boolean
	 */
	var $enableLog = false;

	/**
	 * ���һ�����ݿ�����Ĵ�����Ϣ
	 *
	 * @var mixed
	 */
	var $lasterr = null;

	/**
	 * ���һ�����ݿ�����Ĵ������
	 *
	 * @var mixed
	 */
	var $lasterrcode = null;

	/**
	 * ���һ�β���������� nextId() �������صĲ��� ID
	 *
	 * @var mixed
	 */
	var $_insertId = null;

	/**
	 * ָʾ������������
	 *
	 * @var int
	 */
	var $_transCount = 0;

	/**
	 * ָʾ�����Ƿ��ύ
	 *
	 * @var boolean
	 */
	var $_transCommit = true;

	/**
	 * ���캯��
	 *
	 * @param array $dsn
	 */
	function FLEA_Db_Driver_Sqlite( $dsn = false )
	{
		$tmp = ( array )$dsn;
		unset( $tmp['password'] );
		$this->dsn = $dsn;
		$this->enableLog = !defined( 'DEPLOY_MODE' ) || DEPLOY_MODE != true;
		if ( !function_exists( 'log_message' ) )
		{
			$this->enableLog = false;
		}
	}

	/**
	 * �������ݿ�
	 *
	 * @param array $dsn
	 * @return boolean
	 */
	function connect( $dsn = false )
	{
		$dsn = $dsn? $dsn : $this->dsn;
		$this->conn = false;
		if ( file_exists( $dsn['db'] ) )
		{
			try{
			$this->conn = new PDO( 'sqlite2:' . $dsn['db'] ); //����sqlite2
			} catch (PDOException $e) {
			}
			if ( !$this->conn ) $this->conn = new PDO( 'sqlite:' . $dsn['db'] ); //ʧ�ܺ���sqlite3
			
			if ( is_object( $this->conn ) )
			{
				return $this->conn;
			}
		}

		FLEA::loadClass( 'FLEA_Db_Exception_SqlQuery' );
		__THROW( new FLEA_Db_Exception_SqlQuery( "connect('{$dsn['db']}') failed! debug message:" . $ex->getMessage() ) );
		return false;
	}

	/**
	 * �ر����ݿ�����
	 */
	function close()
	{
		$this->conn = null;
		$this->lasterr = null;
		$this->lasterrcode = null;
		$this->_insertId = null;
		$this->_transCount = 0;
		$this->_transCommit = true;
	}

	/**
	 * ִ��һ����ѯ������һ�� resource ���� boolean ֵ
	 *
	 * @param string $sql
	 * @param array $inputarr
	 * @param boolean $throw ָʾ��ѯ����ʱ�Ƿ��׳��쳣
	 * @return resource |boolean
	 */
	function execute( $sql, $inputarr = null, $throw = true )
	{
		if ( substr( $sql, 0, 11 ) == "INSERT INTO" )
		{
			// ɾ��SQL�е�ָ���ı�SQLITE��֧���ڲ���������б�����ǰ��
			$len1 = strpos( $sql, '(' );
			$len2 = strpos( $sql, ')' );
			$len3 = strpos( $sql, 'VALUES' );
			$temp = array();
			if ( $len2 < $len3 )
			{
				$temp[] = substr( $sql, 0, $len1 );
				$temp[] = substr( $sql, $len1, $len2 - $len1 );
				$temp[] = substr( $sql, $len2 );
				$temp[1] = eregi_replace( "[a-z_0-9]+\\.", "", $temp[1] );
				$sql = implode( $temp );
			}
		}
		if ( is_array( $inputarr ) )
		{
			$sql = $this->_prepareSql( $sql, $inputarr );
		}
		if ( $this->enableLog )
		{
			$this->log[] = $sql;
			log_message( "sql:\n{$sql}", 'debug' );
		}

		$result = $this->conn->query( $sql );
		if ( $result !== false )
		{
			$this->lasterr = null;
			$this->lasterrcode = null;
			return $result;
		}
		$this->lasterrcode = $this->conn->errorCode();
		$this->lasterr = $this->conn->errorInfo();
		if ( !$throw )
		{
			return false;
		}

		FLEA::loadClass( 'FLEA_Db_Exception_SqlQuery' );
		__THROW( new FLEA_Db_Exception_SqlQuery( $sql, $this->lasterr[2], $this->lasterrcode ) );
		return false;
	}

	/**
	 * ת���ַ���
	 *
	 * @param string $value
	 * @return mixed
	 */
	function qstr( $value )
	{
		if ( is_bool( $value ) )
		{
			return $value ? $this->TRUE_VALUE : $this->FALSE_VALUE;
		}
		if ( is_null( $value ) )
		{
			return $this->NULL_VALUE;
		}
		return $this->conn->quote( $value );
	}

	/**
	 * �����ݱ�����ת��Ϊ��ȫ�޶���
	 *
	 * @param string $tableName
	 * @return string
	 */
	function qtable( $tableName )
	{
		return $tableName; //SQLite ��ת��֧�ֲ��Ǻܺã���������
	}

	/**
	 * ���ֶ���ת��Ϊ��ȫ�޶�����������Ϊ�ֶ��������ݿ�ؼ�����ͬ���µĴ���
	 *
	 * @param string $fieldName
	 * @param string $tableName
	 * @return string
	 */
	function qfield( $fieldName, $tableName = null )
	{
		$pos = strpos( $fieldName, '.' );
		if ( $pos !== false )
		{
			$tableName = substr( $fieldName, 0, $pos );
			$fieldName = substr( $fieldName, $pos + 1 );
		}
		if ( $tableName != '' )
		{
			if ( $fieldName != '*' )
			{
				return "{$tableName}.{$fieldName}";
			}
			else
			{
				return "{$tableName}.*";
			}
		}
		else
		{
			if ( $fieldName != '*' )
			{
				return "{$fieldName}";
			}
			else
			{
				return "*";
			}
		}
	}

	/**
	 * һ���Խ�����ֶ���ת��Ϊ��ȫ�޶���
	 *
	 * @param string $ |array $fields
	 * @param string $tableName
	 * @return string
	 */
	function qfields( $fields, $tableName = null )
	{
		if ( !is_array( $fields ) )
		{
			$fields = explode( ',', $fields );
		}
		$return = array();
		foreach ( $fields as $fieldName )
		{
			$fieldName = trim( $fieldName );
			if ( $fieldName == '' )
			{
				continue;
			}
			$pos = strpos( $fieldName, '.' );
			if ( $pos !== false )
			{
				$tableName = substr( $fieldName, 0, $pos );
				$fieldName = substr( $fieldName, $pos + 1 );
			}
			if ( $tableName != '' )
			{
				if ( $fieldName != '*' )
				{
					$return[] = "{$tableName}.{$fieldName}";
				}
				else
				{
					$return[] = "{$tableName}.*";
				}
			}
			else
			{
				if ( $fieldName != '*' )
				{
					$return[] = "{$fieldName}";
				}
				else
				{
					$return[] = '*';
				}
			}
		}
		return implode( ', ', $return );
	}

	/**
	 * Ϊ���ݱ������һ������ֵ
	 *
	 * @param string $seqName
	 * @param string $startValue
	 * @return int
	 */
	function nextId( $seqName = 'sdboseq', $startValue = 1 )
	{
		$result = $this->execute( sprintf( $this->NEXT_ID_SQL, $seqName ), null, false );
		if ( $result === false )
		{
			if ( !$this->createSeq( $seqName, $startValue ) )
			{
				return false;
			}
			$this->execute( sprintf( $this->NEXT_ID_SQL, $seqName ) );
		}
		$id = $this->insertId();
		if ( $id )
		{
			return $id;
		}
		if ( $this->execute( sprintf( $this->INIT_SEQ_SQL, $seqName, $startValue ) ) )
		{
			return $startValue;
		}
		return false;
	}

	/**
	 * ����һ���µ����У��ɹ����� true��ʧ�ܷ��� false
	 *
	 * @param string $seqName
	 * @param int $startValue
	 * @return boolean
	 */
	function createSeq( $seqName = 'sdboseq', $startValue = 1 )
	{
		if ( $this->execute( sprintf( $this->CREATE_SEQ_SQL, $seqName ) ) )
		{
			return $this->execute( sprintf( $this->INIT_SEQ_SQL, $seqName, $startValue - 1 ) );
		}
		else
		{
			return false;
		}
	}

	/**
	 * ɾ��һ������

	 * �����ʵ�������ݿ�ϵͳ�йء�
	 *
	 * @param string $seqName
	 */
	function dropSeq( $seqName = 'sdboseq' )
	{
		return $this->execute( sprintf( $this->DROP_SEQ_SQL, $seqName ) );
	}

	/**
	 * ��ȡ�����ֶε����һ��ֵ
	 *
	 * @return mixed
	 */
	function insertId()
	{
		return $this->conn->lastInsertId();
	}

	/**
	 * �������һ�����ݿ�����ܵ�Ӱ��ļ�¼��
	 *
	 * @return int
	 */
	function affectedRows()
	{
		return $this->conn->exec(); //�������select��Ч
	}

	/**
	 * �Ӽ�¼���з���һ������
	 *
	 * @param resouce $res
	 * @return array
	 */
	function fetchRow( $res )
	{
		$row = $res->fetch();
		$temp = array();
		foreach( $row as $key => $value )
		{
			$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
			$temp[$key] = $value;
		}
		return $temp;
	}

	/**
	 * �Ӽ�¼���з���һ�����ݣ��ֶ�����Ϊ����
	 *
	 * @param resouce $res
	 * @return array
	 */
	function fetchAssoc( $res )
	{
		$row = $res->fetch( PDO::FETCH_ASSOC );
		$temp = array();
		foreach( $row as $key => $value )
		{
			$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
			$temp[$key] = $value;
		}
		return $temp;
	}

	/**
	 * �ͷŲ�ѯ���
	 *
	 * @param resource $res
	 * @return boolean
	 */
	function freeRes( $res )
	{
		// return sqlite_free_result($res);
		return true; //sqlite û�������ĺ���
	}

	/**
	 * �����޶���¼���Ĳ�ѯ
	 *
	 * @param string $sql
	 * @param int $length
	 * @param int $offset
	 * @return resource
	 */
	function selectLimit( $sql, $length = null, $offset = null )
	{
		if ( $offset !== null )
		{
			$sql .= "\nLIMIT " . ( int )$offset;
			if ( $length !== null )
			{
				$sql .= ', ' . ( int )$length;
			}
			else
			{
				$sql .= ', 4294967294';
			}
		}elseif ( $length !== null )
		{
			$sql .= "\nLIMIT " . ( int )$length;
		}
		return $this->execute( $sql );
	}

	/**
	 * ִ��һ����ѯ�����ز�ѯ�����¼��
	 *
	 * @param string $ |resource $sql
	 * @return array
	 */
	function & getAll( $sql )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			$res = $this->execute( $sql );
		}
		$data = array();
		while ( $row = $res->fetch( PDO::FETCH_ASSOC ) )
		{
			$temp = array();
			foreach( $row as $key => $value )
			{
				$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
				$temp[$key] = $value;
			}
			$data[] = $temp;
		}
		return $data;
	}

	/**
	 * ִ��һ����ѯ�����ط����Ĳ�ѯ�����¼��

	 * $groupBy �������Ϊ�ַ�������������ʾ��������� $groupBy ����ָ�����ֶν��з��顣
��� $groupBy ����Ϊ true�����ʾ����ÿ�м�¼�ĵ�һ���ֶν��з��顣
	 *
	 * @param string $ |resource $sql
	 * @param string $ |int|boolean $groupBy
	 * @return array
	 */
	function & getAllGroupBy( $sql, $groupBy )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			$res = $this->execute( $sql );
		}
		$data = array();

		$row = $res->fetch( PDO::FETCH_ASSOC );
		if ( $row != false )
		{
			$temp = array();
			foreach( $row as $key => $value )
			{
				$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
				$temp[$key] = $value;
			}
			$row = $temp;
			if ( $groupBy === true )
			{
				$groupBy = key( $row );
			}
			do
			{
				$rkv = $row[$groupBy];
				unset( $row[$groupBy] );
				$data[$rkv][] = $row;
			}
			while ( $row = $res->fetch( PDO::FETCH_ASSOC ) );
		}
		return $data;
	}

	/**
	 * ִ��һ����ѯ�����ز�ѯ�����¼����ָ���ֶε�ֵ�����Լ��Ը��ֶ�ֵ�����ļ�¼��
	 *
	 * @param string $ |resource $sql
	 * @param string $field
	 * @param array $fieldValues
	 * @param array $reference
	 * @return array
	 */
	function getAllWithFieldRefs( $sql, $field, & $fieldValues, & $reference )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			$res = $this->execute( $sql );
		}

		$fieldValues = array();
		$reference = array();
		$offset = 0;
		$data = array();
		while ( $row = $res->fetch( PDO::FETCH_ASSOC ) )
		{
			$temp = array();
			foreach( $row as $key => $value )
			{
				$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
				$temp[$key] = $value;
			}
			$row = $temp;
			$fieldValue = $row[$field];
			unset( $row[$field] );
			$data[$offset] = $row;
			$fieldValues[$offset] = $fieldValue;
			$reference[$fieldValue] = & $data[$offset];
			$offset++;
		}
		return $data;
	}

	/**
	 * ִ��һ����ѯ���������ݰ���ָ���ֶη������ $assocRowset ��¼����װ��һ��
	 *
	 * @param string $ |resource $sql
	 * @param array $assocRowset
	 * @param string $mappingName
	 * @param boolean $oneToOne
	 * @param string $refKeyName
	 * @param mixed $limit
	 */
	function assemble( $sql, & $assocRowset, $mappingName, $oneToOne, $refKeyName, $limit = null )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			if ( $limit !== null )
			{
				if ( is_array( $limit ) )
				{
					list( $length, $offset ) = $limit;
				}
				else
				{
					$length = $limit;
					$offset = 0;
				}
				$res = $this->selectLimit( $sql, $length, $offset );
			}
			else
			{
				$res = $this->execute( $sql );
			}
		}

		if ( $oneToOne )
		{
			// һ��һ��װ����
			while ( $row = $res->fetch( PDO::FETCH_ASSOC ) )
			{
				$temp = array();
				foreach( $row as $key => $value )
				{
					$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
					$temp[$key] = $value;
				}
				$row = $temp;
				$rkv = $row[$refKeyName];
				unset( $row[$refKeyName] );
				$assocRowset[$rkv][$mappingName] = $row;
			}
		}
		else
		{
			// һ�Զ���װ����
			while ( $row = $res->fetch( PDO::FETCH_ASSOC ) )
			{
				$rkv = $row[$refKeyName];
				unset( $row[$refKeyName] );
				$temp = array();
				foreach( $row as $key => $value )
				{
					$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
					$temp[$key] = $value;
				}
				$assocRowset[$rkv][$mappingName][] = $temp;
			}
		}
	}

	/**
	 * ִ�в�ѯ�����ص�һ����¼�ĵ�һ���ֶ�
	 *
	 * @param string $ |resource $sql
	 * @return mixed
	 */
	function getOne( $sql )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			$res = $this->execute( $sql );
		}
		$row = $res->fetch( PDO::FETCH_NUM );
		// sqlite_free_result($res);
		return isset( $row[0] ) ? $row[0] : null;
	}

	/**
	 * ִ�в�ѯ�����ص�һ����¼
	 *
	 * @param string $ |resource $sql
	 * @return mixed
	 */
	function & getRow( $sql )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			$res = $this->execute( $sql );
		}
		$row = $res->fetch( PDO::FETCH_ASSOC );
		$temp = array();
		foreach( $row as $key => $value )
		{
			$key = eregi_replace( '^[a-z0-9_]+\.', '', $key );
			$temp[$key] = $value;
		}
		return $temp;
	}

	/**
	 * ִ�в�ѯ�����ؽ������ָ����
	 *
	 * @param string $ |resource $sql
	 * @param int $col Ҫ���ص��У�0 Ϊ��һ��
	 * @return mixed
	 */
	function & getCol( $sql, $col = 0 )
	{
		if ( is_object( $sql ) )
		{
			$res = $sql;
		}
		else
		{
			$res = $this->execute( $sql );
		}
		$data = array();
		while ( $row = $res->fetch( PDO::FETCH_NUM ) )
		{
			$data[] = $row[$col];
		}
		return $data;
	}

	/**
	 * ����ָ����������ͼ����Ԫ����
	 *
	 * ���ִ���ο� ADOdb ʵ�֡�

	 * ÿ���ֶΰ����������ԣ�
	 *
	 * name:            �ֶ���
scale:           С��λ��
	 * type:            �ֶ�����
	 * simpleType:      ���ֶ����ͣ������ݿ��޹أ�
maxLength:       ��󳤶�
notNull:         �Ƿ������� NULL ֵ
primaryKey:      �Ƿ�������
autoIncrement:   �Ƿ����Զ������ֶ�
binary:          �Ƿ��Ƕ���������
	 * unsigned:        �Ƿ����޷�����ֵ
hasDefault:      �Ƿ���Ĭ��ֵ
defaultValue:    Ĭ��ֵ
	 *
	 * @param string $table
	 * @return array
	 */
	function & metaColumns( $table )
	{
		/**
		 * C ����С�ڵ��� 250 ���ַ���
		 *    X ���ȴ��� 250 ���ַ���
		 *    B ����������
N ��ֵ���߸�����
		 *    D ����
		 *    T TimeStamp
		 *    L �߼�����ֵ
I ����
		 *    R �Զ������������
		 */
		static $typeMap = array( 'BIT' => 'I',
			'TINYINT' => 'I',
			'BOOL' => 'L',
			'BOOLEAN' => 'L',
			'SMALLINT' => 'I',
			'MEDIUMINT' => 'I',
			'INT' => 'I',
			'INTEGER' => 'I',
			'BIGINT' => 'I',
			'FLOAT' => 'N',
			'DOUBLE' => 'N',
			'DOUBLEPRECISION' => 'N',
			'FLOAT' => 'N',
			'DECIMAL' => 'N',
			'DEC' => 'N',

			'DATE' => 'D',
			'DATETIME' => 'T',
			'TIMESTAMP' => 'T',
			'TIME' => 'T',
			'YEAR' => 'I',

			'CHAR' => 'C',
			'NCHAR' => 'C',
			'VARCHAR' => 'C',
			'NVARCHAR' => 'C',
			'BINARY' => 'B',
			'VARBINARY' => 'B',
			'TINYBLOB' => 'X',
			'TINYTEXT' => 'X',
			'BLOB' => 'X',
			'TEXT' => 'X',
			'MEDIUMBLOB' => 'X',
			'MEDIUMTEXT' => 'X',
			'LONGBLOB' => 'X',
			'LONGTEXT' => 'X',
			'ENUM' => 'C',
			'SET' => 'C',
			);

		$rs = $this->execute( sprintf( $this->META_COLUMNS_SQL, $table ) );
		if ( !$rs )
		{
			return false;
		}
		$retarr = array();
		$sql = $rs->fetch( PDO::FETCH_NUM );
		$sql = $sql[0];
		$firstPar = strpos( $sql, '(' );
		$endPar = strrpos( $sql, ')' )-1;
		$sql = substr( $sql, ( $firstPar + 1 ), ( $endPar - $firstPar ) );
		$sql = str_replace( "\n", '', $sql );
		$sql = str_replace( "'", '', $sql );
		$ligne = explode( ',', $sql );
		// get index key
		$sql = "select sql from sqlite_master where type='index' and tbl_name='$table'";
		$rs = $this->execute( $sql );
		$sql = $rs->fetch( PDO::FETCH_NUM );
		$sql = $sql[0];
		$firstPar = strpos( $sql, '(' );
		$endPar = strrpos( $sql, ')' )-1;
		$sql = substr( $sql, ( $firstPar + 1 ), ( $endPar - $firstPar ) );
		$sql = str_replace( "\n", '', $sql );
		$sql = str_replace( "'", '', $sql );
		$temp = explode( ',', $sql );
		$index = array();
		foreach ( $temp as $value )
		{
			$value = trim( $value );
			if ( $value )
			{
				$index[$value] = true;
			}
		}

		while ( list( $ligneNum, $cont ) = each( $ligne ) )
		{
			$row = explode( ' ', trim( $cont ) );
			$field = array();
			$field['name'] = $row[0];
			$type = $row[1];
			$field['scale'] = null;
			$queryArray = false;
			if ( preg_match( '/^(.+)\((\d+),(\d+)/', $type, $queryArray ) )
			{
				$field['type'] = $queryArray[1];
				$field['maxLength'] = is_numeric( $queryArray[2] ) ? $queryArray[2] : -1;
				$field['scale'] = is_numeric( $queryArray[3] ) ? $queryArray[3] : -1;
			}elseif ( preg_match( '/^(.+)\((\d+)/', $type, $queryArray ) )
			{
				$field['type'] = $queryArray[1];
				$field['maxLength'] = is_numeric( $queryArray[2] ) ? $queryArray[2] : -1;
			}elseif ( preg_match( '/^(enum)\((.*)\)$/i', $type, $queryArray ) )
			{
				$field['type'] = $queryArray[1];
				$arr = explode( ",", $queryArray[2] );
				$field['enums'] = $arr;
				$zlen = max( array_map( "strlen", $arr ) ) - 2; // PHP >= 4.0.6
				$field['maxLength'] = ( $zlen > 0 ) ? $zlen : 1;
			}
			else
			{
				$field['type'] = $type;
				$field['maxLength'] = -1;
			}
			$field['simpleType'] = $typeMap[strtoupper( $field['type'] )];
			if ( $field['simpleType'] == 'C' && $field['maxLength'] > 250 )
			{
				$field['simpleType'] = 'X';
			}

			$temp = eregi( 'PRIMARY[[:space:]]KEY', $cont );
			$field['primaryKey'] = $temp || $index[$row[0]];
			$field['notNull'] = $field['primaryKey'] || ( strtoupper( $row[2] ) == 'NOT' );
			$field['autoIncrement'] = $temp;
			if ( $field['autoIncrement'] )
			{
				$field['simpleType'] = 'R';
			}
			$field['binary'] = ( strpos( $type, 'blob' ) !== false );
			$field['unsigned'] = ( strpos( $type, 'unsigned' ) !== false );

			if ( !$field['binary'] )
			{
				$d = $row[4];
				if ( $d != '' && $d != 'NULL' )
				{
					$field['hasDefault'] = true;
					$field['defaultValue'] = $d;
				}
				else
				{
					$field['hasDefault'] = false;
				}
			}
			$retarr[strtoupper( $field['name'] )] = $field;
		}
		return $retarr;
	}

	/**
	 * �������ݿ���Խ��ܵ����ڸ�ʽ
	 *
	 * @param int $timestamp
	 */
	function dbTimeStamp( $timestamp )
	{
		return date( 'Y-m-d H:i:s', $timestamp );
	}

	/**
	 * ��������
	 */
	function startTrans()
	{
		$this->_transCount += 1;
		try{
		$this->conn->rollBack();
		}
		catch(PDOException $e)
		{}
		$this->conn->beginTransaction();
	}

	/**
	 * ������񣬸��ݲ�ѯ�Ƿ����������ύ�����ǻع�����
	 *
	 * ��� $commitOnNoErrors ����Ϊ true�������������в�ѯ���ɹ����ʱ�����ύ���񣬷���ع�����
	 * ��� $commitOnNoErrors ����Ϊ false����ǿ�ƻع�����
	 *
	 * @param  $commitOnNoErrors ָʾ��û�д���ʱ�Ƿ��ύ����
	 */
	function completeTrans( $commitOnNoErrors = true )
	{
		if ( $this->_transCount < 1 )
		{
			return;
		}
		if ( $this->_transCount > 1 )
		{
			$this->_transCount -= 1;
			return;
		}
		$this->_transCount = 0;

		if ( $this->_transCommit && $commitOnNoErrors )
		{
			$this->conn->commit();
		}
		else
		{
			$this->conn->rollBack();
		}
	}

	/**
	 * ǿ��ָʾ�ڵ��� completeTrans() ʱ�ع�����
	 */
	function failTrans()
	{
		$this->_transCommit = true;
	}

	/**
	 * ���������Ƿ�ʧ�ܵ�״̬
	 */
	function hasFailedTrans()
	{
		if ( $this->_transCount > 0 )
		{
			return $this->_transCommit === false;
		}
		return false;
	}

	/**
	 * ���� SQL �����ṩ�Ĳ������飬�������յ� SQL ���
	 *
	 * @param string $sql
	 * @param array $inputarr
	 * @return string
	 */
	function _prepareSql( $sql, & $inputarr )
	{
		$sqlarr = explode( '?', $sql );
		$sql = '';
		$ix = 0;
		foreach ( $inputarr as $v )
		{
			$sql .= $sqlarr[$ix];
			$typ = gettype( $v );
			if ( $typ == 'string' )
			{
				$sql .= $this->qstr( $v );
			}
			else if ( $typ == 'double' )
			{
				$sql .= $this->qstr( str_replace( ',', '.', $v ) );
			}
			else if ( $typ == 'boolean' )
			{
				$sql .= $v ? $this->TRUE_VALUE : $this->FALSE_VALUE;
			}
			else if ( $v === null )
			{
				$sql .= 'NULL';
			}
			else
			{
				$sql .= $v;
			}
			$ix += 1;
		}
		if ( isset( $sqlarr[$ix] ) )
		{
			$sql .= $sqlarr[$ix];
		}

		return $sql;
	}
}
