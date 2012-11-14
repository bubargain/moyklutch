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
 * ���� FLEA_Session_Db ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Db.php 1032 2008-02-22 06:20:48Z qeeyuan $
 */

/**
 * FLEA_Session_Db ���ṩ�� session ���浽���ݿ������
 *
 * Ҫʹ�� FLEA_Session_Db�������������׼��������
 *
 * - ������Ҫ�����ݱ�
 *
 *     �ֶ���       ����             ��;
 *     sess_id     varchar(64)     �洢 session id
 *     sess_data   text            �洢 session ����
 *     activity    int(11)         �� session ���һ�ζ�ȡ/д��ʱ��
 *
 * - �޸�Ӧ�ó������� sessionProvider Ϊ FLEA_Session_Db
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Session_Db
{
    /**
     * ���ݿ���ʶ���
     *
     * @var FLEA_Db_Driver_Abstract
     */
    var $dbo = null;

    /**
     * ���� session �����ݱ����ƣ���Ӧ�ó������� sessionDbTableName ָ��
     *
     * @var string
     */
    var $tableName = null;

    /**
     * ���� session id ���ֶ�������Ӧ�ó������� sessionDbFieldId ָ��
     *
     * @var string
     */
    var $fieldId = null;

    /**
     * ���� session ���ݵ��ֶ�������Ӧ�ó������� sessionDbFieldData ָ��
     *
     * @var string
     */
    var $fieldData = null;

    /**
     * ���� session ����ʱ����ֶ�������Ӧ�ó������� sessionDbFieldActivity ָ��
     *
     * @var string
     */
    var $fieldActivity = null;

    /**
     * ָʾ session ����Ч��
     *
     * 0 ��ʾ�� PHP ���л���������������ֵΪ�������һ�λʱ��������ʧЧ
     *
     * @var int
     */
    var $lifeTime = 0;

    /**
     * ���캯��
     *
     * @return FLEA_Session_Db
     */
    function FLEA_Session_Db()
    {
        $this->tableName = FLEA::getAppInf('sessionDbTableName');
        $this->fieldId = FLEA::getAppInf('sessionDbFieldId');
        $this->fieldData = FLEA::getAppInf('sessionDbFieldData');
        $this->fieldActivity = FLEA::getAppInf('sessionDbFieldActivity');
        $this->lifeTime = (int)FLEA::getAppInf('sessionDbLifeTime');

        if (PHP4) {
            register_shutdown_function('session_write_close');
        }

        session_set_save_handler(
            array(& $this, 'sessionOpen'),
            array(& $this, 'sessionClose'),
            array(& $this, 'sessionRead'),
            array(& $this, 'sessionWrite'),
            array(& $this, 'sessionDestroy'),
            array(& $this, 'sessionGc')
        );
    }

    /**
     * ��������
     */
    function __destruct()
    {
        session_write_close();
    }

    /**
     * �� session
     *
     * @param string $savePath
     * @param string $sessionName
     *
     * @return boolean
     */
    function sessionOpen($savePath, $sessionName)
    {
        $dsnName = FLEA::getAppInf('sessionDbDSN');
        $dsn = FLEA::getAppInf($dsnName);
        $this->dbo =& FLEA::getDBO($dsn);
        if (!$this->dbo) { return false; }

        if (!empty($this->dbo->dsn['prefix'])) {
            $this->tableName = $this->dbo->dsn['prefix'] . $this->tableName;
        }
        $this->tableName = $this->dbo->qtable($this->tableName);
        $this->fieldId = $this->dbo->qfield($this->fieldId);
        $this->fieldData = $this->dbo->qfield($this->fieldData);
        $this->fieldActivity = $this->dbo->qfield($this->fieldActivity);

        $this->sessionGc(FLEA::getAppInf('sessionDbLifeTime'));

        return true;
    }

    /**
     * �ر� session
     *
     * @return boolean
     */
    function sessionClose()
    {
        return true;
    }

    /**
     * ��ȡָ�� id �� session ����
     *
     * @param string $sessid
     *
     * @return string
     */
    function sessionRead($sessid)
    {
        $sessid = $this->dbo->qstr($sessid);
        $sql = "SELECT {$this->fieldData} FROM {$this->tableName} WHERE {$this->fieldId} = {$sessid}";
        if ($this->lifeTime > 0) {
            $time = time() - $this->lifeTime;
            $sql .= " AND {$this->fieldActivity} >= {$time}";
        }

        return $this->dbo->getOne($sql);
    }

    /**
     * д��ָ�� id �� session ����
     *
     * @param string $sessid
     * @param string $data
     *
     * @return boolean
     */
    function sessionWrite($sessid, $data)
    {
        $sessid = $this->dbo->qstr($sessid);
        $sql = "SELECT COUNT(*) FROM {$this->tableName} WHERE {$this->fieldId} = {$sessid}";
        $data = $this->dbo->qstr($data);
        $activity = time();

        $fields = (array)$this->_beforeWrite($sessid);
        if ((int)$this->dbo->getOne($sql) > 0) {
            $sql = "UPDATE {$this->tableName} SET {$this->fieldData} = {$data}, {$this->fieldActivity} = {$activity}";
            if (!empty($fields)) {
                $arr = array();
                foreach ($fields as $field => $value) {
                    $arr[] = $this->dbo->qfield($field) . ' = ' . $this->dbo->qstr($value);
                }
                $sql .= ', ' . implode(', ', $arr);
            }
            $sql .= " WHERE {$this->fieldId} = {$sessid}";
        } else {
            $extraFields = '';
            $extraValues = '';
            if (!empty($fields)) {
                foreach ($fields as $field => $value) {
                    $extraFields .= ', ' . $this->dbo->qfield($field);
                    $extraValues .= ', ' . $this->dbo->qstr($value);
                }
            }

            $sql = "INSERT INTO {$this->tableName} ({$this->fieldId}, {$this->fieldData}, {$this->fieldActivity}{$extraFields}) VALUES ({$sessid}, {$data}, {$activity}{$extraValues})";
        }

        __TRY();
        $this->dbo->execute($sql);
        $ex = __CATCH();
        return !__IS_EXCEPTION($ex);
    }

    /**
     * ����ָ�� id �� session
     *
     * @param string $sessid
     *
     * @return boolean
     */
    function sessionDestroy($sessid)
    {
        $sessid = $this->dbo->qstr($sessid);
        $sql = "DELETE FROM {$this->tableName} WHERE {$this->fieldId} = {$sessid}";
        return $this->dbo->execute($sql);
    }

    /**
     * ������ڵ� session ����
     *
     * @param int $maxlifetime
     *
     * @return boolean
     */
    function sessionGc($maxlifetime)
    {
        if ($this->lifeTime > 0) {
            $maxlifetime = $this->lifeTime;
        }
        $time = time() - $maxlifetime;
        $sql = "DELETE FROM {$this->tableName} WHERE {$this->fieldActivity} < {$time}";
        $this->dbo->execute($sql);
        return true;
    }

    /**
     * ��ȡδ���ڵ� session ����
     *
     * @return int
     */
    function getOnlineCount($lifetime = -1)
    {
        if ($this->lifeTime > 0) {
            $lifetime = $this->lifeTime;
        } else if ($lifetime <= 0) {
            $lifetime = (int)ini_get('session.gc_maxlifetime');
            if ($lifetime <= 0) {
                $lifetime = 1440;
            }
        }
        $sql = "SELECT COUNT(*) FROM {$this->tableName}";
        if ($this->lifeTime > 0) {
            $time = time() - $lifetime;
            $sql .= " WHERE {$this->fieldActivity} >= {$time}";
        }
        return (int)$this->dbo->getOne($sql);
    }

    /**
     * ����Ҫд�� session �Ķ������ݣ�������Ӧ���ڼ̳����и��Ǵ˷���
     *
     * ���緵�أ�
     * return array(
     *      'username' => $username
     * );
     *
     * ���ݱ���Ҫ������Ӧ�� username �ֶΡ�
     *
     * @param string $sessid
     *
     * @return array
     */
    function _beforeWrite($sessid)
    {
        return array();
    }
}
