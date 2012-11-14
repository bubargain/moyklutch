<?php
/////////////////////////////////////////////////////////////////////////////
// FleaPHP Framework
//
// Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
//
// ���Э�飬��鿴Դ�����и����� LICENSE.txt �ļ���
// ���߷��� http://www.fleaphp.org/ �����ϸ��Ϣ��
/////////////////////////////////////////////////////////////////////////////

/**
 * ���� FLEA_Db_Driver_Mssql ����
 *
 * �ǳ���л IceLoad Ϊ FleaPHP ���׵� MSSQL �������� FleaPHP ���Է���Ĳ��� Microsoft SQL Server ���ݿ⡣
 * ֧�ֵ� MSSQL ���ݿ���� MSDE2000��MSSQL2000/2005���� Express �棩��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author IceLoad
 * @package Core
 * @version $Id: Mssql.php 1060 2008-05-04 05:02:59Z qeeyuan $
 */

// {{{ includes
FLEA::loadClass('FLEA_Db_Driver_Abstract');
// }}}

// {{{ init
if (!class_exists('FLEA_Db_Driver_Mssql')) {
    // ���� MSSQL ת�����ڸ�ʽ���Ա㷵�� YYYY-MM-DD hh:mm:ss ��ʽ������
    ini_set('mssql.datetimeconvert', 0);
}
// }}}

/**
 * ���� mssql ��չ�����ݿ���������
 *
 * @package Core
 * @author IceLoad
 * @version 1.1
 */
class FLEA_Db_Driver_Mssql extends FLEA_Db_Driver_Abstract
{
    var $META_COLUMNS_SQL = "SELECT a.COLUMN_NAME AS Field, (CASE WHEN CHARACTER_MAXIMUM_LENGTH IS NULL THEN DATA_TYPE + '(' + CONVERT(VARCHAR,NUMERIC_PRECISION) + ')' ELSE DATA_TYPE + '(' + CONVERT(VARCHAR,CHARACTER_MAXIMUM_LENGTH) + ')' END) AS DataType, IS_NULLABLE AS Nullable, CONSTRAINT_TYPE AS PrimaryKey, COLUMN_DEFAULT AS DefaultValue, (CASE WHEN COLUMNPROPERTY(OBJECT_ID('%s'),a.COLUMN_NAME,'IsIdentity')=1 THEN 'auto_increment' ELSE '' END) AS Extra FROM INFORMATION_SCHEMA.COLUMNS AS a LEFT JOIN (INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS b INNER JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS c ON b.CONSTRAINT_NAME=c.CONSTRAINT_NAME  AND b.TABLE_NAME=c.TABLE_NAME) ON a.COLUMN_NAME=b.COLUMN_NAME AND a.TABLE_NAME=b.TABLE_NAME WHERE a.TABLE_NAME='%s'";
    var $PARAM_STYLE = DBO_PARAM_AT_NAMED;
    var $HAS_INSERT_ID  = true;
    var $HAS_AFFECTED_ROWS = true;

    function connect($dsn = false)
    {
        $this->lasterr = null;
        $this->lasterrcode = null;

        if ($this->conn && $dsn == false) { return true; }
        if (!$dsn) {
            $dsn = $this->dsn;
        } else {
            $this->dsn = $dsn;
        }
        if (isset($dsn['port']) && $dsn['port'] != '') {
            $host = $dsn['host'] . ':' . $dsn['port'];
        } else {
            $host = $dsn['host'];
        }
        if (!isset($dsn['login'])) { $dsn['login'] = ''; }
        if (!isset($dsn['password'])) { $dsn['password'] = ''; }
		$this->conn = mssql_connect($host, $dsn['login'], $dsn['password']);

        if (!$this->conn) {
            FLEA::loadClass('FLEA_Db_Exception_SqlQuery');
            return __THROW(new FLEA_Db_Exception_SqlQuery("mssql_connect('{$host}', '{$dsn['login']}') failed!", $this->_error(), $this->_errno()));
        }

        if (!mssql_select_db($dsn['database'], $this->conn)) {
            FLEA::loadClass('FLEA_Db_Exception_SqlQuery');
            return __THROW(new FLEA_Db_Exception_SqlQuery("SELECT DATABASE: '{$dsn['database']}' FAILED!", $this->_error($this->conn), $this->_errno($this->conn)));
        }

        return true;
    }

    /**
     * �ر����ݿ�����
     */
    function close()
    {
        if ($this->conn) { mssql_close($this->conn); }
        parent::close();
    }

    function selectDb($database)
    {
        if (!mssql_select_db($dsn['database'], $this->conn)) {
            FLEA::loadClass('FLEA_Db_Exception_SqlQuery');
            return __THROW(new FLEA_Db_Exception_SqlQuery("SELECT DATABASE: '{$database}' FAILED!", $this->_error($this->conn), $this->_errno($this->conn)));
        }
        return true;
    }

    function execute($sql, $inputarr = null, $throw = true)
    {
        if (is_array($inputarr)) {
            $sql = $this->bind($sql, $inputarr);
        }
        if ($this->enableLog) {
            $this->log[] = $sql;
            log_message("sql: {$sql}", 'debug');
        }

        $this->querycount++;
        $result = mssql_query($sql, $this->conn);
        if ($result !== false) {
            $this->lasterr = null;
            $this->lasterrcode = null;
            return $result;
        }
        $this->lasterr = $this->_error($this->conn);
        $this->lasterrcode = $this->_errno($this->conn);

        if ($throw) {
            FLEA::loadClass('FLEA_Db_Exception_SqlQuery');
            __THROW(new FLEA_Db_Exception_SqlQuery($sql, $this->lasterr, $this->lasterrcode));
        }
        return false;
    }

    function qstr($value)
    {
        if (is_int($value)) { return $value; }
        if (is_bool($value)) { return $value ? $this->TRUE_VALUE : $this->FALSE_VALUE; }
        if (is_null($value)) { return $this->NULL_VALUE; }
        return str_replace("'", "''", $value);
    }

    function qtable($tableName, $schema = null)
    {
        return $schema != '' ? "[{$schema}].[{$tableName}]" : "[{$tableName}]";
    }

    function qfield($fieldName, $tableName = null, $schema = null)
    {
        $fieldName = ($fieldName == '*') ? '*' : "[{$fieldName}]";
        return $tableName != '' ? $this->qtable($tableName, $schema) . '.' . $fieldName : $fieldName;
    }

    function _insertId()
    {
        return $this->getOne('SELECT SCOPE_IDENTITY()');
    }

    function _affectedRows()
    {
        return $this->getOne('SELECT @@rowcount');
    }

    function fetchRow($res)
    {
        return mssql_fetch_row($res);
    }

    function fetchAssoc($res)
    {
        return mssql_fetch_assoc($res);
    }

    function freeRes($res)
    {
        return mssql_free_result($res);
    }

    function selectLimit($sql, $length = null, $offset = null)
    {

    }

    function metaColumns($table)
    {
        /**
         *  C ����С�ڵ��� 250 ���ַ���
         *  X ���ȴ��� 250 ���ַ���
         *  B ����������
         *  N ��ֵ���߸�����
         *  D ����
         *  T TimeStamp
         *  L �߼�����ֵ
         *  I ����
         *  R �Զ������������
         */
        static $typeMap = array(
            'BIT'           => 'I',
            'TINYINT'       => 'I',
            'BOOL'          => 'L',
            'BOOLEAN'       => 'L',
            'SMALLINT'      => 'I',
            'MEDIUMINT'     => 'I',
            'INT'           => 'I',
            'INTEGER'       => 'I',
            'BIGINT'        => 'I',
            'FLOAT'         => 'N',
            'DOUBLE'        => 'N',
            'DOUBLEPRECISION' => 'N',
			'REAL'          => 'N',
			'NUMERIC'       => 'N',
            'DECIMAL'       => 'N',
            'DEC'           => 'N',
			'MONEY'         => 'N',
			'SMALLMONEY'    => 'N',


            'DATE'          => 'D',
            'DATETIME'      => 'T',
			'SMALLDATETIME' => 'T',
            'TIMESTAMP'     => 'T',
            'TIME'          => 'T',
            'YEAR'          => 'I',

            'CHAR'          => 'C',
            'NCHAR'         => 'C',
            'VARCHAR'       => 'C',
            'NVARCHAR'      => 'C',
            'BINARY'        => 'B',
            'VARBINARY'     => 'B',
            'TINYBLOB'      => 'X',
            'TINYTEXT'      => 'X',
            'BLOB'          => 'X',
            'TEXT'          => 'X',
			'NTEXT'         => 'X',
            'MEDIUMBLOB'    => 'X',
            'MEDIUMTEXT'    => 'X',
            'LONGBLOB'      => 'X',
            'LONGTEXT'      => 'X',
            'ENUM'          => 'C',
            'SET'           => 'C',
        );

        //$table = $this->qtable($table);echo sprintf($this->META_COLUMNS_SQL, $table, $table);exit;
        $rs = $this->execute(sprintf($this->META_COLUMNS_SQL, $table, $table));
        if (!$rs) { return false; }
        $retarr = array();
        while (($row = mssql_fetch_row($rs))) {
            $field = array();
            $field['name'] = $row[0];
            $type = $row[1];

            $field['scale'] = null;
            $queryArray = false;
            if (preg_match('/^(.+)\((\d+),(\d+)/', $type, $queryArray)) {
                $field['type'] = $queryArray[1];
                $field['maxLength'] = is_numeric($queryArray[2]) ? $queryArray[2] : -1;
                $field['scale'] = is_numeric($queryArray[3]) ? $queryArray[3] : -1;
            } elseif (preg_match('/^(.+)\((\d+)/', $type, $queryArray)) {
                $field['type'] = $queryArray[1];
                $field['maxLength'] = is_numeric($queryArray[2]) ? $queryArray[2] : -1;
            } elseif (preg_match('/^(enum)\((.*)\)$/i', $type, $queryArray)) {
                $field['type'] = $queryArray[1];
                $arr = explode(",",$queryArray[2]);
                $field['enums'] = $arr;
                $zlen = max(array_map("strlen",$arr)) - 2; // PHP >= 4.0.6
                $field['maxLength'] = ($zlen > 0) ? $zlen : 1;
            } else {
                $field['type'] = $type;
                $field['maxLength'] = -1;
            }
            $field['simpleType'] = $typeMap[strtoupper($field['type'])];
            if ($field['simpleType'] == 'C' && $field['maxLength'] > 255) {
                $field['simpleType'] = 'X';
            }
            $field['notNull'] = ($row[2] != 'YES');
			if (is_null($row[3])) { $row[3] = ''; }
            $field['primaryKey'] = ($row[3] == 'PRIMARY KEY');
            $field['autoIncrement'] = (strpos($row[5], 'auto_increment') !== false);
            if ($field['autoIncrement']) { $field['simpleType'] = 'R'; }
            $field['binary'] = (strpos($type,'blob') !== false);
            $field['unsigned'] = (strpos($type,'unsigned') !== false);

            if (!$field['binary']) {
                $d = is_null($row[4]) ? '' : $row[4];
				if ($d != '') {
					if (substr($d, 0, 1) == '(') {
						$d = substr($d, 1, strlen($d) - 2);
						if (substr($d, 0, 1) == "'") { $d = substr($d, 1, strlen($d) - 2); }
					}
				}
                if ($d != '') {
                    $field['hasDefault'] = true;
                    $field['defaultValue'] = $d;
                } else {
                    $field['hasDefault'] = false;
                }
            }
            $retarr[strtoupper($field['name'])] = $field;
        }
        mssql_free_result($rs);
        return $retarr;
    }

    /**
     * Ϊ���ݱ������һ������ֵ
     *
     * @param string $seqName
     * @param string $startValue
     *
     * @return int
     */
    function nextId($seqName = 'sdboseq', $startValue = 1)
    {
		$this->execute('BEGIN TRANSACTION ' . $seqName);
		$ok = $this->execute(sprintf($this->NEXT_ID_SQL, $seqName), null, false);
		if ($ok === false) {
			$this->execute(sprintf($this->CREATE_SEQ_SQL, $seqName));
			$ok = $this->execute(sprintf($this->INIT_SEQ_SQL, $seqName, $startValue));
			if (!$ok) {
				$this->execute('ROLLBACK TRANSACTION ' . $seqName);
				return false;
			}
			$this->execute('COMMIT TRANSACTION ' . $seqName);
			return $startValue;
		}
		$num = $this->query_scalar_function('SELECT id FROM ' . $seqName);
		$this->execute('COMMIT TRANSACTION ' . $seqName);
		return $num;
    }

    /**
     * ����һ���µ����У��ɹ����� true��ʧ�ܷ��� false
     *
     * @param string $seqName
     * @param int $startValue
     *
     * @return boolean
     */
    function createSeq($seqName = 'sdboseq', $startValue = 1)
    {
		$this->execute('BEGIN TRANSACTION ' . $seqName);
		$this->execute(sprintf($this->CREATE_SEQ_SQL, $seqName));
		$ok = $this->execute(sprintf($this->INIT_SEQ_SQL, $seqName, $startValue - 1));
		if (!$ok) {
			$this->execute('ROLLBACK TRANSACTION ' . $seqName);
			return false;
		}
		$this->execute('COMMIT TRANSACTION ' . $seqName);
		return true;
    }

    /**
     * ɾ��һ������
     *
     * �����ʵ�������ݿ�ϵͳ�йء�
     *
     * @param string $seqName
     */
    function dropSeq($seqName = 'sdboseq')
    {
        return $this->execute(sprintf($this->DROP_SEQ_SQL, $seqName));
    }

	function numRows($res)
	{
		return mssql_num_rows($res);
	}

    /**
     * �Ӽ�¼���з���һ������
     *
     * @param resouce $res
     *
     * @return array
     */
    function fetchRow($res)
    {
        return mssql_fetch_row($res);
    }

    /**
     * �Ӽ�¼���з���һ�����ݣ��ֶ�����Ϊ����
     *
     * @param resouce $res
     *
     * @return array
     */
    function fetchAssoc($res)
    {
        return mssql_fetch_assoc($res);
    }

    /**
     * �ͷŲ�ѯ���
     *
     * @param resource $res
     *
     * @return boolean
     */
    function freeRes($res)
    {
        return mssql_free_result($res);
    }

    /**
     * �����޶���¼���Ĳ�ѯ
     *
     * @param string $sql
     * @param int $length
     * @param int $offset
     *
     * @return resource
     */
    function selectLimit($sql, $length = null, $offset = null)
    {
		if(is_numeric($length)){
			$intPageSize = intval($length);
			if($intPageSize < 0){$intPageSize = 0;}
		}else{
			$intPageSize = 0;
		}
		if(is_numeric($offset)){
			$intStartPosition = intval($offset);
			if($intStartPosition < 0){$intStartPosition = 0;}
		}else{
			$intStartPosition = 0;
		}
		$strSQL = $sql;
		$strPattern = '/^\s*(SELECT\s+(ALL|DISTINCT)?(\s+TOP\s+\d+)?(.+))(\s+FROM\s+.+)';
		if(strripos($strSQL, 'WHERE')){
			$strPattern .= '(\s+WHERE\s+.+)';
		}
		if(strripos($strSQL, 'GROUP BY')){
			$strPattern .= '(\s+GROUP BY\s+.+)';
		}
		if(strripos($strSQL, 'HAVING')){
			$strPattern .= '(\s+HAVING\s+.+)';
		}
		if(strripos($strSQL, 'ORDER BY')){
			$strPattern .= '(\s+ORDER BY\s+.+)';
		}
		$strPattern .= '$/i';
		$arrMatches = array();
		if(preg_match($strPattern, $strSQL, $arrMatches)){
			$j = count($arrMatches);
			for($i = 0; $i < $j; $i ++){
				$arrMatches[$i] = trim($arrMatches[$i]);
			}
			if(empty($arrMatches[3]) && $j > 5){
				$strLimitSql = 'SELECT ' . $arrMatches[2] . ' TOP ' . $intPageSize . ' ' . $arrMatches[4];
				$strLimitSql .= ' ' . $arrMatches[5];
				$strAlias = '';
				if(strpos($arrMatches[5], ',')){
					$strAlias = substr($arrMatches[5], 0, strpos($arrMatches[5], ','));
				}elseif(stristr($arrMatches[5], " JOIN ")){
					$strAlias = stristr($arrMatches[5], " JOIN ");
					$strAlias = substr($arrMatches[5], 0, strpos($arrMatches[5], $strAlias));
				}
				if(! empty($strAlias)){
					$strAlias = trim(substr($strAlias, 4));
					$arrAlias = split(' ', $strAlias);
					$strAlias = $arrAlias[0];
					if(strtoupper($arrAlias[1]) == 'AS'){
						$strAlias = $arrAlias[2];
					}elseif(! in_array(strtoupper($arrAlias[1]), array('INNER','LEFT','JOIN','RIGHT','FULL'))){
						$strAlias = $arrAlias[1];
					}
					//$strAlias = trim(substr($strAlias, strrpos($strAlias, ' ')));
					if(! empty($strAlias))$strAlias .= '.';
				}
				if($j > 6){
					if(strtoupper(substr($arrMatches[6], 0, 5)) == 'WHERE'){
						$strLimitSql .= ' WHERE (' . substr($arrMatches[6], 5) . ') AND ' . $strAlias . 'IDENTITYCOL NOT IN (';
						$strLimitSql .= 'SELECT ' . $arrMatches[2] . ' TOP ' . $intStartPosition . ' ' . $strAlias . 'IDENTITYCOL ' . $arrMatches[5];
						for($i = 6; $i < $j; $i ++){
							$strLimitSql .= ' ' . $arrMatches[$i];
						}
						$strLimitSql .= ')';
						for($i = 7; $i < $j; $i ++){
							$strLimitSql .= ' ' . $arrMatches[$i];
						}
					}else{
						$strLimitSql .= ' WHERE ' . $strAlias . 'IDENTITYCOL NOT IN (';
						$strLimitSql .= 'SELECT ' . $arrMatches[2] . ' TOP ' . $intStartPosition . ' ' . $strAlias . 'IDENTITYCOL ' . $arrMatches[5];
						for($i = 6; $i < $j; $i ++){
							$strLimitSql .= ' ' . $arrMatches[$i];
						}
						$strLimitSql .= ')';
						for($i = 6; $i < $j; $i ++){
							$strLimitSql .= ' ' . $arrMatches[$i];
						}
					}
				}else{
					$strLimitSql .= ' WHERE ' . $strAlias . 'IDENTITYCOL NOT IN (';
					$strLimitSql .= 'SELECT ' . $arrMatches[2] . ' TOP ' . $intStartPosition . ' ' . $strAlias . 'IDENTITYCOL ' . $arrMatches[5];
					$strLimitSql .= ')';
				}
				return $this->execute($strLimitSql);
			}
			return false;
		}
		return false;
	}

    /**
     * ִ��һ����ѯ�����ز�ѯ�����¼��
     *
     * @param string|resource $sql
     *
     * @return array
     */
    function & getAll($sql)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }
        $data = array();
        while ($row = mssql_fetch_assoc($res)) {
            $data[] = $row;
        }
        mssql_free_result($res);
        return $data;
    }

    /**
     * ִ��һ����ѯ�����ط����Ĳ�ѯ�����¼��
     *
     * $groupBy �������Ϊ�ַ�������������ʾ��������� $groupBy ����ָ�����ֶν��з��顣
     * ��� $groupBy ����Ϊ true�����ʾ����ÿ�м�¼�ĵ�һ���ֶν��з��顣
     *
     * @param string|resource $sql
     * @param string|int|boolean $groupBy
     *
     * @return array
     */
    function & getAllGroupBy($sql, $groupBy)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }
        $data = array();

        $row = mssql_fetch_assoc($res);
        if ($row != false) {
            if ($groupBy === true) {
                $groupBy = key($row);
            }
            do {
                $rkv = $row[$groupBy];
                unset($row[$groupBy]);
                $data[$rkv][] = $row;
            } while ($row = mssql_fetch_assoc($res));
        }

        mssql_free_result($res);

        return $data;
    }

    /**
     * ִ��һ����ѯ�����ز�ѯ�����¼����ָ���ֶε�ֵ�����Լ��Ը��ֶ�ֵ�����ļ�¼��
     *
     * @param string|resource $sql
     * @param string $field
     * @param array $fieldValues
     * @param array $reference
     *
     * @return array
     */
    function getAllWithFieldRefs($sql, $field, & $fieldValues, & $reference)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }

        $fieldValues = array();
        $reference = array();
        $offset = 0;
        $data = array();
        while ($row = mssql_fetch_assoc($res)) {
            $fieldValue = $row[$field];
            unset($row[$field]);
            $data[$offset] = $row;
            $fieldValues[$offset] = $fieldValue;
            $reference[$fieldValue] =& $data[$offset];
            $offset++;
        }
        mssql_free_result($res);
        return $data;
    }

    /**
     * ִ��һ����ѯ���������ݰ���ָ���ֶη������ $assocRowset ��¼����װ��һ��
     *
     * @param string|resource $sql
     * @param array $assocRowset
     * @param string $mappingName
     * @param boolean $oneToOne
     * @param string $refKeyName
     * @param mixed $limit
     */
    function assemble($sql, & $assocRowset, $mappingName, $oneToOne, $refKeyName, $limit = null)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            if ($limit !== null) {
                if (is_array($limit)) {
                    list($length, $offset) = $limit;
                } else {
                    $length = $limit;
                    $offset = 0;
                }
                $res = $this->selectLimit($sql, $length, $offset);
            } else {
                $res = $this->execute($sql);
            }
        }

        if ($oneToOne) {
            // һ��һ��װ����
            while ($row = mssql_fetch_assoc($res)) {
                $rkv = $row[$refKeyName];
                unset($row[$refKeyName]);
                $assocRowset[$rkv][$mappingName] = $row;
            }
        } else {
            // һ�Զ���װ����
            while ($row = mssql_fetch_assoc($res)) {
                $rkv = $row[$refKeyName];
                unset($row[$refKeyName]);
                $assocRowset[$rkv][$mappingName][] = $row;
            }
        }

        mssql_free_result($res);
    }

    /**
     * ִ�в�ѯ�����ص�һ����¼�ĵ�һ���ֶ�
     *
     * @param string|resource $sql
     *
     * @return mixed
     */
    function getOne($sql)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }
        $row = mssql_fetch_row($res);
        mssql_free_result($res);
        return isset($row[0]) ? $row[0] : null;
    }

    /**
     * ִ�в�ѯ�����ص�һ����¼
     *
     * @param string|resource $sql
     *
     * @return mixed
     */
    function & getRow($sql)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }
        $row = mssql_fetch_assoc($res);
        mssql_free_result($res);
        return $row;
    }

    /**
     * ִ�в�ѯ�����ؽ������ָ����
     *
     * @param string|resource $sql
     * @param int $col Ҫ���ص��У�0 Ϊ��һ��
     *
     * @return mixed
     */
    function & getCol($sql, $col = 0)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }
        $data = array();
        while ($row = mssql_fetch_row($res)) {
            $data[] = $row[$col];
        }
        mssql_free_result($res);
        return $data;
    }

    /**
     * �������ݿ���Խ��ܵ����ڸ�ʽ
     *
     * @param int $timestamp
     */
    function dbTimeStamp($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * ��������
     */
    function startTrans()
    {
    }

    /**
     * ������񣬸��ݲ�ѯ�Ƿ����������ύ�����ǻع�����
     *
     * ��� $commitOnNoErrors ����Ϊ true�������������в�ѯ���ɹ����ʱ�����ύ���񣬷���ع�����
     * ��� $commitOnNoErrors ����Ϊ false����ǿ�ƻع�����
     *
     * @param $commitOnNoErrors ָʾ��û�д���ʱ�Ƿ��ύ����
     */
    function completeTrans($commitOnNoErrors = true)
    {
        return false;
    }

    /**
     * ǿ��ָʾ�ڵ��� completeTrans() ʱ�ع�����
     */
    function failTrans()
    {
        $this->_transCommit = false;
    }

    /**
     * ���������Ƿ�ʧ�ܵ�״̬
     */
    function hasFailedTrans()
    {
        return true;
    }

    /**
     * ���� SQL �����ṩ�Ĳ������飬�������յ� SQL ���
     *
     * @param string $sql
     * @param array $inputarr
     *
     * @return string
     */
    function _prepareSql($sql, & $inputarr)
    {
        $sqlarr = explode('?', $sql);
        $sql = '';
        $ix = 0;
        foreach ($inputarr as $v) {
            $sql .= $sqlarr[$ix];
            $typ = gettype($v);
            if ($typ == 'string') {
                $sql .= $this->qstr($v);
            } else if ($typ == 'double') {
                $sql .= $this->qstr(str_replace(',', '.', $v));
            } else if ($typ == 'boolean') {
                $sql .= $v ? $this->TRUE_VALUE : $this->FALSE_VALUE;
            } else if ($v === null) {
                $sql .= 'NULL';
            } else {
                $sql .= $v;
            }
            $ix += 1;
        }
        if (isset($sqlarr[$ix])) {
            $sql .= $sqlarr[$ix];
        }
        return $sql;
    }

    /**
     * ������һ�� MsSQL �����Ĵ������
     *
     * @param resource link_identifier $conn
     *
     * @return int
     */
	function _errno()
	{
		$errorSQL = "SELECT @@ERROR";
		if (empty($conn)) {
			$num = $this->query_scalar_function($errorSQL);
		} else {
			$num = $this->query_scalar_function($errorSQL, $conn);
		}
		return $num;
	}

    /**
     * ������һ�� MsSQL �����Ĵ����ı�
     *
     * @param resource link_identifier $conn
     *
     * @return string
     */
	function _error()
	{
		return mssql_get_last_message();
	}

    /**
     * ȡ����һ�� INSERT ���������� ID
     *
     * @param resource link_identifier $conn
     *
     * @return int
     */
	function mssql_insert_id(&$conn = false)
	{
		$identitySQL = 'SELECT @@IDENTITY'; // 'SELECT SCOPE_IDENTITY'; 'SELECT IDENT_CURRENT(table_name)' # for mssql 2000
		if (empty($conn)) {
			$id = $this->query_scalar_function($identitySQL);
		} else {
			$id = $this->query_scalar_function($identitySQL, $conn);
		}
		return $id;
	}

	function mssql_real_escape_string($value, &$conn = false)
	{
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		$value = str_replace("'", "''", $value);
		return $value;
	}

	function query_scalar_function($sql, &$conn = false)
	{
		if (empty($conn)) {
			$rs = @mssql_query($sql, $this->conn);
		} else {
			$rs = @mssql_query($sql, $conn);
		}
		if (!$rs) return false;
		$arr = mssql_fetch_array($rs);
		mssql_free_result($rs);
		if (is_array($arr)) {
			return $arr[0];
		} else {
			return -1;
		}
	}
}

/**
 * �� FLEA_Db_Driver_Mssql ��Ψһ�������� FLEA_Db_Driver_Mssqlt ֧��������
 *
 * Ҫ���Ĵ洢����Ϊ InnoDB ���� BDB��
 *
 * @package Core
 * @author ������ dualface@gmail.com
 * @version 1.1
 */
class FLEA_Db_Driver_Mssqlt extends FLEA_Db_Driver_Mssql
{
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
     * ��������
     */
    function startTrans()
    {
        $this->_transCount += 1;
        if ($this->_transCount == 1) {
            $this->execute('BEGIN TRAN');
        }
    }

    /**
     * ������񣬸��ݲ�ѯ�Ƿ����������ύ�����ǻع�����
     *
     * ��� $commitOnNoErrors ����Ϊ true�������������в�ѯ���ɹ����ʱ�����ύ���񣬷���ع�����
     * ��� $commitOnNoErrors ����Ϊ false����ǿ�ƻع�����
     *
     * @param $commitOnNoErrors ָʾ��û�д���ʱ�Ƿ��ύ����
     */
    function completeTrans($commitOnNoErrors = true)
    {
        if ($this->_transCount < 1) { return false; }
        if ($this->_transCount > 1) {
            $this->_transCount -= 1;
            return true;
        }
        $this->_transCount = 0;

        if ($this->_transCommit && $commitOnNoErrors) {
            $ret = $this->execute('COMMIT TRAN');
            return $ret;
        } else {
            $this->execute('ROLLBACK TRAN');
            return false;
        }
    }

    /**
     * ǿ��ָʾ�ڵ��� completeTrans() ʱ�ع�����
     */
    function failTrans()
    {
        $this->_transCommit = false;
    }

    /**
     * ���������Ƿ�ʧ�ܵ�״̬
     */
    function hasFailedTrans()
    {
        if ($this->_transCount > 0) {
            return $this->_transCommit === false;
        }
        return false;
    }
}
