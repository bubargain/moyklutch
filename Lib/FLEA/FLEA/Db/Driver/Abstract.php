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
 * ���� FLEA_Db_Driver_Abstract ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Abstract.php 1025 2008-01-09 04:17:59Z qeeyuan $
 */

// {{{ constants
/**
 * �ʺ���Ϊ����ռλ��
 */
define('DBO_PARAM_QM',          '?');
/**
 * ð�ſ�ʼ����������
 */
define('DBO_PARAM_CL_NAMED',    ':');
/**
 * $���ſ�ʼ������
 */
define('DBO_PARAM_DL_SEQUENCE', '$');
/**
 * @��ʼ����������
 */
define('DBO_PARAM_AT_NAMED',    '@');

/**
 * FLEA_Db_Driver_Abstract ���������ݿ������ĳ��������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.1
 */
class FLEA_Db_Driver_Abstract
{
    /**
     * ������� true��false �� null �����ݿ�ֵ
     */
    var $TRUE_VALUE  = 1;
    var $FALSE_VALUE = 0;
    var $NULL_VALUE = 'NULL';

    /**
     * ���� genSeq()��dropSeq() �� nextId() �� SQL ��ѯ���
     */
    var $NEXT_ID_SQL    = null;
    var $CREATE_SEQ_SQL = null;
    var $INIT_SEQ_SQL   = null;
    var $DROP_SEQ_SQL   = null;

    /**
     * ���ڻ�ȡԪ���ݵ� SQL ��ѯ���
     */
    var $META_COLUMNS_SQL = null;

    /**
     * ָʾʹ�ú�����ʽ�Ĳ���ռλ��
     *
     * @var int
     */
    var $PARAM_STYLE = DBO_PARAM_QM;

    /**
     * ָʾ���ݿ��Ƿ��������ֶι���
     *
     * @var boolean
     */
    var $HAS_INSERT_ID  = false;

    /**
     * ָʾ���ݿ��Ƿ��ܻ�ø��¡�ɾ������Ӱ��ļ�¼������
     *
     * @var boolean
     */
    var $HAS_AFFECTED_ROWS = false;

    /**
     * ָʾ���ݿ��Ƿ�֧������
     *
     * @var boolean
     */
    var $HAS_TRANSACTION = false;

    /**
     * ָʾ���ݿ��Ƿ�֧�������е� SAVEPOINT ����
     *
     * @var boolean
     */
    var $HAS_SAVEPOINT = false;

    /**
     * ָʾ�Ƿ񽫲�ѯ����е��ֶ���ת��ΪȫСд
     *
     * @var boolean
     */
    var $RESULT_FIELD_NAME_LOWER = false;

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
     * ִ�еĲ�ѯ����
     *
     * @var int
     */
    var $querycount = 0;

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
     * ָʾ����ִ���ڼ��Ƿ����˴���
     *
     * @var boolean
     */
    var $_hasFailedQuery = false;

    /**
     * SAVEPOINT ��ջ
     *
     * @var array
     */
    var $_savepointStack = array();

    /**
     * ���캯��
     *
     * @param array $dsn
     */
    function FLEA_Db_Driver_Abstract($dsn = null)
    {
        $tmp = (array)$dsn;
        unset($tmp['password']);
        $this->dsn = $dsn;
        $this->enableLog = FLEA::getAppInf('logEnabled');
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
     * ѡ��Ҫ���������ݿ�
     *
     * @param string $database
     *
     * @return boolean
     */
    function selectDb($database)
    {
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
    }

    /**
     * ����ָ�������ͣ�����ֵ
     *
     * @param mixed $value
     * @param string $type
     *
     * @return mixed
     */
    function setValueByType($value, $type)
    {
        /**
         *  C CHAR �� VARCHAR �����ֶ�
         *  X TEXT �� CLOB �����ֶ�
         *  B ���������ݣ�BLOB��
         *  N ��ֵ���߸�����
         *  D ����
         *  T TimeStamp
         *  L �߼�����ֵ
         *  I ����
         *  R �Զ������������
         */
        switch (strtoupper($type)) {
        case 'I':
            return (int)$value;
        case 'N':
            return (float)$value;
        case 'L':
            return (bool)$value;
        default:
            return $value;
        }
    }

    /**
     * �����ݱ�����ת��Ϊ��ȫ�޶���
     *
     * @param string $tableName
     * @param string $schema
     *
     * @return string
     */
    function qtable($tableName, $schema = null)
    {
    }

    /**
     * ���ֶ���ת��Ϊ��ȫ�޶�����������Ϊ�ֶ��������ݿ�ؼ�����ͬ���µĴ���
     *
     * @param string $fieldName
     * @param string $tableName
     * @param string $schema
     *
     * @return string
     */
    function qfield($fieldName, $tableName = null, $schema = null)
    {
    }

    /**
     * һ���Խ�����ֶ���ת��Ϊ��ȫ�޶���
     *
     * @param string|array $fields
     * @param string $tableName
     * @param string $schema
     * @param boolean $returnArray
     *
     * @return string
     */
    function qfields($fields, $tableName = null, $schema = null, $returnArray = false)
    {
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
            $fields = array_map('trim', $fields);
        }
        $return = array();
        foreach ($fields as $fieldName) {
            $return[] = $this->qfield($fieldName, $tableName, $schema);
        }
        return $returnArray ? $return : implode(', ', $return);
    }

    /**
     * Ϊ���ݱ������һ������ֵ
     *
     * @param string $seqName
     * @param string $startValue
     *
     * @return int
     */
    function nextId($seqName = 'sdbo_seq', $startValue = 1)
    {
        $getNextIdSql = sprintf($this->NEXT_ID_SQL, $seqName);
        $result = $this->execute($getNextIdSql, null, false);
        if (!$result) {
            if (!$this->createSeq($seqName, $startValue)) { return false; }
            $result = $this->execute($getNextIdSql);
            if (!$result) { return false; }
        }

        if ($this->HAS_INSERT_ID) {
            return $this->_insertId();
        } else {
            $row = $this->fetchRow($result);
            $this->freeRes($result);
            $nextId = reset($row);
            $this->_insertId = $nextId;
            return $nextId;
        }
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
        if ($this->execute(sprintf($this->CREATE_SEQ_SQL, $seqName))) {
            return $this->execute(sprintf($this->INIT_SEQ_SQL, $seqName, $startValue - 1));
        } else {
            return false;
        }
    }

    /**
     * ɾ��һ������
     *
     * �����ʵ�������ݿ�ϵͳ�йء�
     *
     * @param string $seqName
     */
    function dropSeq($seqName = 'sdbo_seq')
    {
        return $this->execute(sprintf($this->DROP_SEQ_SQL, $seqName));
    }

    /**
     * ��ȡ���һ�� nextId ������õ�ֵ
     *
     * @return mixed
     */
    function insertId()
    {
        return $this->HAS_INSERT_ID ? $this->_insertId() : $this->_insertId;
    }

    /**
     * �������һ�����ݿ�����ܵ�Ӱ��ļ�¼��
     *
     * @return int
     */
    function affectedRows()
    {
        return $this->HAS_AFFECTED_ROWS ? $this->_affectedRows() : false;
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
        $res = is_resource($sql) ? $sql : $this->execute($sql);
        $fieldValues = array();
        $reference = array();
        $offset = 0;
        $data = array();

        while ($row = $this->fetchAssoc($res)) {
            $fieldValue = $row[$field];
            unset($row[$field]);
            $data[$offset] = $row;
            $fieldValues[$offset] = $fieldValue;
            $reference[$fieldValue] =& $data[$offset];
            $offset++;
        }
        $this->freeRes($res);
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
            while ($row = $this->fetchAssoc($res)) {
                $rkv = $row[$refKeyName];
                unset($row[$refKeyName]);
                $assocRowset[$rkv][$mappingName] = $row;
            }
        } else {
            // һ�Զ���װ����
            while ($row = $this->fetchAssoc($res)) {
                $rkv = $row[$refKeyName];
                unset($row[$refKeyName]);
                $assocRowset[$rkv][$mappingName][] = $row;
            }
        }
        $this->freeRes($res);
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
        $res = is_resource($sql) ? $sql : $this->execute($sql);
        $rowset = array();
        while ($row = $this->fetchAssoc($res)) {
            $rowset[] = $row;
        }
        $this->freeRes($res);
        return $rowset;
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
        $res = is_resource($sql) ? $sql : $this->execute($sql);
        $row = $this->fetchRow($res);
        $this->freeRes($res);
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
        $res = is_resource($sql) ? $sql : $this->execute($sql);
        $row = $this->fetchAssoc($res);
        $this->freeRes($res);
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
        $res = is_resource($sql) ? $sql : $this->execute($sql);
        $data = array();
        while ($row = $this->fetchRow($res)) {
            $data[] = $row[$col];
        }
        $this->freeRes($res);
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
        $row = $this->fetchAssoc($res);
        if ($row != false) {
            if ($groupBy === true) {
                $groupBy = key($row);
            }
            do {
                $rkv = $row[$groupBy];
                unset($row[$groupBy]);
                $data[$rkv][] = $row;
            } while ($row = $this->fetchAssoc($res));
        }
        $this->freeRes($res);
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
     *
     * @return array
     */
    function & metaColumns($table)
    {
    }

    /**
     * ����������ݱ������
     *
     * @param string $pattern
     * @param string $schema
     *
     * @return array
     */
    function metaTables($pattern = null, $schema = null)
    {
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
        if (!$this->HAS_TRANSACTION) { return false; }
        if ($this->_transCount == 0) {
            $this->_startTrans();
            $this->_hasFailedQuery = false;
        }
        $this->_transCount++;
        if ($this->_transCount > 1 && $this->HAS_SAVEPOINT) {
            $savepoint = 'savepoint_' . $this->_transCount;
            $this->execute("SAVEPOINT {$savepoint}");
            array_push($this->_savepointStack, $savepoint);
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
        if (!$this->HAS_TRANSACTION) { return false; }
        if ($this->_transCount == 0) { return; }
        $this->_transCount--;
        if ($this->_transCount > 0 && $this->HAS_SAVEPOINT) {
            $savepoint = array_pop($this->_savepointStack);
            if ($this->_hasFailedQuery || $commitOnNoErrors == false) {
                $this->execute("ROLLBACK TO SAVEPOINT {$savepoint}");
            }
        } else {
            $this->_completeTrans($commitOnNoErrors);
        }
    }

    /**
     * ǿ��ָʾ�ڵ��� completeTrans() ʱ�ع�����
     */
    function failTrans()
    {
        $this->_hasFailedQuery = true;
    }

    /**
     * ���������Ƿ�ʧ�ܵ�״̬
     */
    function hasFailedTrans()
    {
        return $this->HAS_TRANSACTION ? $this->_hasFailedQuery : false;
    }

    /**
     * ���� SQL �����ṩ�Ĳ������飬�������յ� SQL ���
     *
     * @param string $sql
     * @param array $inputarr
     *
     * @return string
     */
    function bind($sql, & $inputarr)
    {
        $arr = explode('?', $sql);
        $sql = array_shift($arr);
        foreach ($inputarr as $value) {
            if (isset($arr[0])) {
                $sql .= $this->qstr($value) . array_shift($arr);
            }
        }
        return $sql;
    }

    /**
     * ���ݰ�����¼���ݵ����鷵��һ����Ч�� SQL �����¼���
     *
     * @param array $row
     * @param string $table Ҫ��������ݱ�
     * @param string $schema
     *
     * @return string
     */
    function getInsertSQL(& $row, $table, $schema = null)
    {
        list($holders, $values) = $this->getPlaceholder($row);
        $holders = implode(',', $holders);
        $fields = $this->qfields(array_keys($values));
        $table = $this->qtable($table, $schema);
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$holders})";
        return $sql;
    }

    function getUpdateSQL(& $row, $pk, $table, $schema = null)
    {
        $pkv = $row[$pk];
        unset($row[$pk]);
        list($pairs, $values) = $this->getPlaceholderPair($row);
        $row[$pk] = $pkv;
        $pairs = implode(',', $pairs);
        $table = $this->qtable($table, $schema);
        $pk = $this->qfield($pk);
        $sql = "UPDATE {$table} SET {$pairs} WHERE {$pk} = " . $this->qstr($pkv);
        return $sql;
    }

    /**
     * ���������Ĳ���ռλ����ʽ�����ذ�������ռλ������Ч���ݵ�����
     *
     * @param array $inputarr
     * @param array $fields
     *
     * @return array
     */
    function getPlaceholder(& $inputarr, $fields = null)
    {
        $holders = array();
        $values = array();
        if (is_array($fields)) {
            $fields = array_change_key_case(array_flip($fields), CASE_LOWER);
            foreach (array_keys($inputarr) as $key) {
                if (!isset($fields[strtolower($key)])) { continue; }
                if ($this->PARAM_STYLE == DBO_PARAM_QM) {
                    $holders[] = $this->PARAM_STYLE;
                } else {
                    $holders[] = $this->PARAM_STYLE . $key;
                }
                $values[$key] =& $inputarr[$key];
            }
        } else {
            foreach (array_keys($inputarr) as $key) {
                if ($this->PARAM_STYLE == DBO_PARAM_QM) {
                    $holders[] = $this->PARAM_STYLE;
                } else {
                    $holders[] = $this->PARAM_STYLE . $key;
                }
                $values[$key] =& $inputarr[$key];
            }
        }
        return array($holders, $values);
    }

    /**
     * ���������Ĳ���ռλ����ʽ�����ذ���������ռλ���ַ����ԡ���Ч���ݵ�����
     *
     * @param array $inputarr
     * @param array $fields
     *
     * @return array
     */
    function getPlaceholderPair(& $inputarr, $fields = null)
    {
        $pairs = array();
        $values = array();
        if (is_array($fields)) {
            $fields = array_change_key_case(array_flip($fields), CASE_LOWER);
            foreach (array_keys($inputarr) as $key) {
                if (!isset($fields[strtolower($key)])) { continue; }
                $qkey = $this->qfield($key);
                if ($this->PARAM_STYLE == DBO_PARAM_QM) {
                    $pairs[] = "{$qkey}={$this->PARAM_STYLE}";
                } else {
                    $pairs[] = "{$qkey}={$this->PARAM_STYLE}{$key}";
                }
                $values[$key] =& $inputarr[$key];
            }
        } else {
            foreach (array_keys($inputarr) as $key) {
                $qkey = $this->qfield($key);
                if ($this->PARAM_STYLE == DBO_PARAM_QM) {
                    $pairs[] = "{$qkey}={$this->PARAM_STYLE}";
                } else {
                    $pairs[] = "{$qkey}={$this->PARAM_STYLE}{$key}";
                }
                $values[$key] =& $inputarr[$key];
            }
        }
        return array($pairs, $values);
    }
}
