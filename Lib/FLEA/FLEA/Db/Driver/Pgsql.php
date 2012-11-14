<?php
/////////////////////////////////////////////////////////////////////////////
// FleaPHP Framework
//
// ���ļ��ɡ�ҹè�ӡ������ش˸�л��
/////////////////////////////////////////////////////////////////////////////

/**
 * ���� FLEA_Db_Driver_Pgsql ����
 *
 * ��ҹè���ṩ���������޸ģ����ο� AdoDB �� MetaColumns() �� SelectLimit() ������
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ҹè�� yangyi.cn.gz #AT# gmail.com
 * @package Core
 * @version $Id: Pgsql.php 984 2007-10-19 09:44:53Z qeeyuan $
 */

/**
 * ���� pgsql ��չ�����ݿ���������
 *
 * @package Core
 * @author ҹè�� yangyi.cn.gz #AT# gmail.com
 * @version 1.1
 */
class FLEA_Db_Driver_Pgsql
{
    /**
     * ���� genSeq()��dropSeq() �� nextId() �� SQL ��ѯ���
     */
    var $NEXT_ID_SQL    = "SELECT NEXTVAL('%s')";
    var $CREATE_SEQ_SQL = "CREATE SEQUENCE %s START %s";
    var $DROP_SEQ_SQL   = "DROP SEQUENCE %s";

    /**
     * ������� true��false �� null �����ݿ�ֵ
     */
    var $TRUE_VALUE  = 1;
    var $FALSE_VALUE = 0;
    var $NULL_VALUE = 'NULL';

    /**
     * ���ڻ�ȡԪ���ݵ� SQL ��ѯ���
     */
    var $META_COLUMNS_SQL = "SELECT a.attname,t.typname,a.attlen,a.atttypmod,a.attnotnull,a.atthasdef,a.attnum FROM pg_class c, pg_attribute a,pg_type t WHERE relkind in ('r','v') AND (c.relname='%s' or c.relname = lower('%s')) and a.attname not like '....%%' AND a.attnum > 0 AND a.atttypid = t.oid AND a.attrelid = c.oid ORDER BY a.attnum";

    // ���ڻ�ȡԪ���ݵ� SQL ��ѯ��䣨������ Schema ʱʹ�ã�
    var $META_COLUMNS_SQL1 = "SELECT a.attname, t.typname, a.attlen, a.atttypmod, a.attnotnull, a.atthasdef, a.attnum FROM pg_class c, pg_attribute a, pg_type t, pg_namespace n WHERE relkind in ('r','v') AND (c.relname='%s' or c.relname = lower('%s')) and c.relnamespace=n.oid and n.nspname='%s' and a.attname not like '....%%' AND a.attnum > 0 AND a.atttypid = t.oid AND a.attrelid = c.oid ORDER BY a.attnum";

    // ��ȡ�����ֶεĲ�ѯ���
    var $META_KEY_SQL = "SELECT ic.relname AS index_name, a.attname AS column_name,i.indisunique AS unique_key, i.indisprimary AS primary_key FROM pg_class bc, pg_class ic, pg_index i, pg_attribute a WHERE bc.oid = i.indrelid AND ic.oid = i.indexrelid AND (i.indkey[0] = a.attnum OR i.indkey[1] = a.attnum OR i.indkey[2] = a.attnum OR i.indkey[3] = a.attnum OR i.indkey[4] = a.attnum OR i.indkey[5] = a.attnum OR i.indkey[6] = a.attnum OR i.indkey[7] = a.attnum) AND a.attrelid = bc.oid AND bc.relname = '%s'";

    // ��ȡ�ֶ�Ĭ��ֵ�Ĳ�ѯ���
    var $META_DEFAULT_SQL = "SELECT d.adnum as num, d.adsrc as def from pg_attrdef d, pg_class c where d.adrelid=c.oid and c.relname='%s' order by d.adnum";

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
     * ���һ�β�ѯ�Ľ��
     *
     * @var mixed
     */
    var $_lastrs = null;

    /**
     * ���캯��
     *
     * @param array $dsn
     */
    function FLEA_Db_Driver_Pgsql($dsn = false)
    {
        $tmp = (array)$dsn;
        unset($tmp['password']);
        $this->dsn = $dsn;
        $this->enableLog = !defined('DEPLOY_MODE') || DEPLOY_MODE != true;
        if (!function_exists('log_message')) {
            $this->enableLog = false;
        }
    }

    /**
     * �������ݿ�
     *
     * @param array $dsn
     *
     * @return boolean
     */
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
        $dsnstring = '';
        if (isset($dsn['host'])) {
            $dsnstring = 'host=' . $this->_addslashes($dsn['host']);
        }
        if (isset($dsn['port'])) {
            $dsnstring .= ' port=' . $this->_addslashes($dsn['port']);
        }
        if (isset($dsn['login'])) {
            $dsnstring .= ' user=' . $this->_addslashes($dsn['login']);
        }
        if (isset($dsn['password'])) {
            $dsnstring .= ' password=' . $this->_addslashes($dsn['password']);
        }
        if (isset($dsn['database'])) {
            $dsnstring .= ' dbname=' . $this->_addslashes($dsn['database']);
        }
        $dsnstring .= ' ';

        if (isset($dsn['options'])) {
            $this->conn = pg_connect($dsnstring, $dsn['options']);
        } else {
            $this->conn = pg_connect($dsnstring);
        }

        if (!$this->conn) {
            FLEA::loadClass('FLEA_Db_Exception_SqlQuery');
            $pos = strpos($dsnstring, 'password=');
            if ($pos !== false) {
                $dsnstring = substr($dsnstring, 0, $pos - 1) . substr($dsnstring, strpos($dsnstring, ' ', $pos + 1));
            }
            __THROW(new FLEA_Db_Exception_SqlQuery("pg_connect(\"{$dsnstring}\") failed!"));
            return false;
        }

        if (!$this->execute("set datestyle='ISO'")) { return false; }

        if (isset($dsn['charset']) && $dsn['charset'] != '') {
            $charset = $dsn['charset'];
        } else {
            $charset = FLEA::getAppInf('databaseCharset');
        }
        if (strtoupper($charset) == 'GB2312') { $charset = 'GBK'; }
        if ($charset != '') {
            pg_set_client_encoding($this->conn, $charset);
        }

        return true;
    }

    /**
     * �ر����ݿ�����
     */
    function close()
    {
        if ($this->conn) {
            pg_close($this->conn);
        }
        $this->conn = null;
        $this->lasterr = null;
        $this->lasterrcode = null;
        $this->_transCount = 0;
        $this->_transCommit = true;
    }

    /**
     * ִ��һ����ѯ������һ�� resource ���� boolean ֵ
     *
     * @param string $sql
     * @param array $inputarr
     * @param boolean $throw ָʾ��ѯ����ʱ�Ƿ��׳��쳣
     *
     * @return resource|boolean
     */
    function execute($sql, $inputarr = null, $throw = true)
    {
        if (is_array($inputarr)) {
            $sql = $this->_prepareSql($sql, $inputarr);
        }
        if ($this->enableLog) {
            $this->log[] = $sql;
            log_message("sql: {$sql}", 'debug');
        }
        $this->_lastrs = @pg_exec($this->conn, $sql);
        if ($this->_lastrs !== false) {
            $this->lasterr = null;
            $this->lasterrcode = null;
            return $this->_lastrs;
        }
        $this->lasterr = pg_errormessage($this->conn);
        $this->lasterrcode = null;
        if (!$throw) { return false; }

        FLEA::loadClass('FLEA_Db_Exception_SqlQuery');
        __THROW(new FLEA_Db_Exception_SqlQuery($sql, $this->lasterr));
        return false;
    }

    /**
     * ת���ַ���
     *
     * @param string $value
     *
     * @return mixed
     */
    function qstr($value)
    {
        if (is_bool($value)) { return $value ? $this->TRUE_VALUE : $this->FALSE_VALUE; }
        if (is_null($value)) { return $this->NULL_VALUE; }
        return "'" . pg_escape_string($value) . "'";
    }

    /**
     * �����ݱ�����ת��Ϊ��ȫ�޶���
     *
     * @param string $tableName
     *
     * @return string
     */
    function qtable($tableName)
    {
        if (substr($tableName, 0, 1) == '"') { return $tableName; }
        return '"' . $tableName . '"';
    }

    /**
     * ���ֶ���ת��Ϊ��ȫ�޶�����������Ϊ�ֶ��������ݿ�ؼ�����ͬ���µĴ���
     *
     * @param string $fieldName
     * @param string $tableName
     *
     * @return string
     */
    function qfield($fieldName, $tableName = null)
    {
        $pos = strpos($fieldName, '.');
        if ($pos !== false) {
            $tableName = substr($fieldName, 0, $pos);
            $fieldName = substr($fieldName, $pos + 1);
        }
        if ($tableName != "") {
            return "\"{$tableName}\".\"{$fieldName}\"";
        } else {
            return "\"{$fieldName}\"";
        }
    }

    /**
     * һ���Խ�����ֶ���ת��Ϊ��ȫ�޶���
     *
     * @param string|array $fields
     * @param string $tableName
     *
     * @return string
     */
    function qfields($fields, $tableName = null)
    {
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }
        $return = array();
        foreach ($fields as $fieldName) {
            $fieldName = trim($fieldName);
            if ($fieldName == '') { continue; }
            $pos = strpos($fieldName, '.');
            if ($pos !== false) {
                $tableName = substr($fieldName, 0, $pos);
                $fieldName = substr($fieldName, $pos + 1);
            }
            if ($tableName != '') {
                $return[] = "\"{$tableName}\".\"{$fieldName}\"";
            } else {
                $return[] = "\"{$fieldName}\"";
            }
        }
        return implode(', ', $return);
    }

    /**
     * Ϊ���ݱ������һ������ֵ��ʧ�ܷ��� false
     *
     * @param string $seqName
     * @param string $startValue
     *
     * @return int
     */
    function nextId($seqName = 'sdbo_seq', $startValue = 1)
    {
        $getNextId = sprintf($this->NEXT_ID_SQL, $seqName);
        $result = $this->execute($getNextId, null, false);
        if ($result == false) {
            // ���в����ڣ�����������
            if (!$this->createSeq($seqName, $startValue)) { return false; }
            if (!$result = $this->execute($getNextId)) { return false; }
        }

        $row = $this->fetchRow($result);
        $this->freeRes($result);
        return reset($row);
    }

    /**
     * ����һ���µ����У��ɹ����� true��ʧ�ܷ��� false
     *
     * @param string $seqName
     * @param int $startValue
     *
     * @return boolean
     */
    function createSeq($seqName = 'sdbo_seq', $startValue = 1)
    {
        return $this->execute(sprintf($this->CREATE_SEQ_SQL, $seqName, $startValue));
    }

    /**
     * ɾ��һ�����У��ɹ����� true��ʧ�ܷ��� false
     *
     * @param string $seqName
     */
    function dropSeq($seqName = 'sdbo_seq')
    {
        return $this->execute(sprintf($this->DROP_SEQ_SQL, $seqName));
    }

    /**
     * ��ȡ�����ֶε����һ��ֵ
     *
     * ���û�пɷ��ص�ֵ�����׳��쳣��
     *
     * @return mixed
     */
    function insertId()
    {
        require_once(FLEA_DIR . '/Exception/NotImplemented.php');
        __THROW(new FLEA_Exception_NotImplemented('insertId()', 'FLEA_Db_Driver_Pgsql'));
        return false;
    }

    /**
     * �������һ�����ݿ�����ܵ�Ӱ��ļ�¼��
     *
     * @return int
     */
    function affectedRows()
    {
        return pg_affected_rows($this->_lastrs);
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
        return pg_fetch_row($res);
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
        return pg_fetch_assoc($res);
    }

    /**
     * �ͷŲ�ѯ���
     *
     * @param resource $res
     */
    function freeRes($res)
    {
        pg_free_result($res);
    }

    /**
     * �����޶���¼���Ĳ�ѯ��PostgreSQL 7 ���ϰ汾���ã�
     *
     * @param string $sql
     * @param int $length
     * @param int $offset
     */
    function selectLimit($sql, $length = 'ALL', $offset = 0)
    {
        if (strtoupper($length) != 'ALL') { $length = (int)$length; }
        $sql = sprintf('%s LIMIT %d OFFSET %d', $sql, $length, (int)$offset);
        return $this->execute($sql);
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
        while ($row = pg_fetch_assoc($res)) {
            $data[] = $row;
        }
        pg_free_result($res);
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
    function & getAllGroupBy($sql, & $groupBy)
    {
        if (is_resource($sql)) {
            $res = $sql;
        } else {
            $res = $this->execute($sql);
        }
        $data = array();
        $row = pg_fetch_assoc($res);
        if ($row != false) {
            if ($groupBy === true) {
                $groupBy = key($row);
            }
            do {
                $rkv = $row[$groupBy];
                unset($row[$groupBy]);
                $data[$rkv][] = $row;
            } while ($row = pg_fetch_assoc($res));
        }
        pg_free_result($res);
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
        while ($row = pg_fetch_assoc($res)) {
            $fieldValue = $row[$field];
            unset($row[$field]);
            $data[$offset] = $row;
            $fieldValues[$offset] = $fieldValue;
            $reference[$fieldValue] =& $data[$offset];
            $offset++;
        }
        pg_free_result($res);
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
            if (!is_null($limit)) {
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
            while ($row = pg_fetch_assoc($res)) {
                $rkv = $row[$refKeyName];
                unset($row[$refKeyName]);
                $assocRowset[$rkv][$mappingName] = $row;
            }
        } else {
            // һ�Զ���װ���ݣ���Ҫ����Ƿ���ȫ NULL �ļ�¼
            while ($row = pg_fetch_assoc($res)) {
                $rkv = $row[$refKeyName];
                unset($row[$refKeyName]);
                $assocRowset[$rkv][$mappingName][] = $row;
            }
        }

        pg_free_result($res);
    }

    /**
     * ִ�в�ѯ�����ص�һ����¼�ĵ�һ���ֶ�
     *
     * @param string $sql
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
        $row = pg_fetch_row($res);
        pg_free_result($res);
        return isset($row[0]) ? $row[0] : null;
    }

    /**
     * ִ�в�ѯ�����ص�һ����¼
     *
     * @param string $sql
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
        $row = pg_fetch_assoc($res);
        pg_free_result($res);
        return $row;
    }

    /**
     * ִ�в�ѯ�����ؽ�����ĵ�һ��
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
        while ($row = pg_fetch_row($res)) {
            $data[] = $row[$col];
        }
        pg_free_result($res);
        return $data;
    }

    /**
     * ����ָ����������ͼ����Ԫ����
     *
     * ���ִ���ο� ADOdb ʵ�֡�
     *
     * ÿ���ֶΰ����������ԣ�
     *
     * name:            �ֶ���
     * scale:           С��λ��
     * type:            �ֶ�����
     * simpleType:      ���ֶ����ͣ������ݿ��޹أ�
     * maxLength:       ��󳤶�
     * notNull:         �Ƿ������� NULL ֵ
     * primaryKey:      �Ƿ�������
     * autoIncrement:   �Ƿ����Զ������ֶ�
     * binary:          �Ƿ��Ƕ���������
     * unsigned:        �Ƿ����޷�����ֵ
     * hasDefault:      �Ƿ���Ĭ��ֵ
     * defaultValue:    Ĭ��ֵ
     *
     * @param string $table
     * @param boolean $normalize ָʾ�Ƿ����ֶ���
     *
     * @return array
     */
    function & metaColumns($table, $normalize = true)
    {
        static $typeMap = array(
            'MONEY' => 'C',
            'INTERVAL' => 'C',
            'CHAR' => 'C',
            'CHARACTER' => 'C',
            'VARCHAR' => 'C',
            'NAME' => 'C',
            'BPCHAR' => 'C',
            '_VARCHAR' => 'C',
            'INET' => 'C',
            'MACADDR' => 'C',

            'TEXT' => 'X',
            'IMAGE' => 'B',
            'BLOB' => 'B',
            'BIT' => 'B',
            'VARBIT' => 'B',
            'BYTEA' => 'B',

            'BOOL' => 'L',
            'BOOLEAN' => 'L',

            'DATE' => 'D',

            'TIMESTAMP WITHOUT TIME ZONE' => 'T',
            'TIME' => 'T',
            'DATETIME' => 'T',
            'TIMESTAMP' => 'T',
            'TIMESTAMPTZ' => 'T',

            'SMALLINT' => 'I',
            'BIGINT' => 'I',
            'INTEGER' => 'I',
            'INT8' => 'I',
            'INT4' => 'I',
            'INT2' => 'I',

            'OID' => 'R',
            'SERIAL' => 'R',
        );

        $schema = false;
        $this->_findschema($table, $schema);

        if (!empty($this->META_KEY_SQL)) {
            // If we want the primary keys, we have to issue a separate query
            // Of course, a modified version of the metaColumnsSQL query using a
            // LEFT JOIN would have been much more elegant, but postgres does
            // not support OUTER JOINS. So here is the clumsy way.
            // fetch all result in once for performance.
            $keys = $this->getAll(sprintf($this->META_KEY_SQL, $table));
        }

        $rsdefa = array();
        if (!empty($this->META_DEFAULT_SQL)) {
            $sql = sprintf($this->META_DEFAULT_SQL, $table);
            $rsdef = $this->execute($sql);
            if ($rsdef) {
                while ($row = pg_fetch_assoc($rsdef)) {
                    $num = $row['num'];
                    $s = $row['def'];
                    if (strpos($s, '::') === false && strpos($s, "'") === 0) {
                        /* quoted strings hack... for now... fixme */
                        $s = substr($s, 1);
                        $s = substr($s, 0, strlen($s) - 1);
                    }

                    $rsdefa[$num] = $s;
                }
                pg_free_result($rsdef);
            }
            unset($rsdef);
        }

        if ($schema) {
            $rs = $this->execute(sprintf($this->META_COLUMNS_SQL1, $table, $table, $schema));
        } else {
            $rs = $this->execute(sprintf($this->META_COLUMNS_SQL, $table, $table));
        }
        if (!$rs) { return false; }

        $retarr = array();
        while (($row = pg_fetch_row($rs))) {
            $field = array();
            $field['name'] = $row[0];
            $field['type'] = $row[1];
            $field['maxLength'] = $row[2];
            $field['attnum'] = $row[6];

            if ($field['maxLength'] <= 0) {
                $field['maxLength'] = $row[3] - 4;
            }
            if ($field['maxLength'] <= 0) {
                $field['maxLength'] = -1;
            }

            if ($field['type'] == 'numeric') {
                $field['scale'] = $field['maxLength'] & 0xFFFF;
                $field['maxLength'] >>= 16;
            }
            // dannym
            // 5 hasdefault; 6 num-of-column
            $field['hasDefault'] = ($row[5] == 't');
            if ($field['hasDefault']) {
                $field['defaultValue'] = $rsdefa[$row[6]];
            }

            $field['notNull'] = $row[4] == 't';

            if (is_array($keys)) {
                foreach($keys as $key) {
                    if ($field['name'] == $key['column_name'] && $key['primary_key'] == 't') {
                        $field['primaryKey'] = true;
                    } else {
                        $field['primaryKey'] = false;
                    }
                    if ($field['name'] == $key['column_name'] && $key['unique_key'] == 't') {
                        $field['unique'] = true; // What name is more compatible?
                    } else {
                        $field['unique'] = false;
                    }
                }
            }

            $t = strtoupper($field['type']);
            if (isset($typeMap[$t])) {
                $field['simpleType'] = $typeMap[$t];
            } else {
                $field['simpleType'] = 'N';
            }

            if ($field['simpleType'] == 'I' && ($field['primaryKey'] != false || $field['unique'] != false)) {
                $field['simpleType'] = 'R';
            }

            $field['autoIncrement'] = false;

            if ($normalize) {
                $retarr[strtoupper($field['name'])] = $field;
            } else {
                $retarr[$field['name']] = $field;
            }
        }
        pg_free_result($rs);
        return $retarr;
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
        $this->_transCount += 1;
        if ($this->_transCount == 1) {
            $this->execute('BEGIN');
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
        if ($this->_transCount < 1) { return; }
        if ($this->_transCount > 1) {
            $this->_transCount -= 1;
            return;
        }
        $this->_transCount = 0;

        if ($this->_transCommit && $commitOnNoErrors) {
            $this->execute('COMMIT');
        } else {
            $this->execute('ROLLBACK');
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
            } else if (is_null($v)) {
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
     *  ���� PostgreSQL ��Ҫ��ת�� DSN �ַ�������
     *
     * @param string $s
     *
     * @return string
     */
    function _addslashes($s)
    {
        $len = strlen($s);
        if ($len == 0) return "''";
        if (strncmp($s,"'",1) === 0 && substr($s,$len-1) == "'") return $s; // already quoted
        return "'".addslashes($s)."'";
    }

    /**
     * �������ݿ�ģʽ�����ݱ������
     *
     * @param string $table
     * @param string $schema
     */
    function _findschema(& $table, & $schema)
    {
        if (!$schema && ($at = strpos($table, '.')) !== false) {
            $schema = substr($table, 0, $at);
            $table = substr($table, $at + 1);
        }
    }
}
