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
 * ���� FLEA_Rbac_UsersManager ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: UsersManager.php 1037 2008-04-19 21:19:55Z qeeyuan $
 */

// {{{ constants
/**
 * ����ļ��ܷ�ʽ
 */
define('PWD_MD5',       1);
define('PWD_CRYPT',     2);
define('PWD_CLEARTEXT', 3);
define('PWD_SHA1',      4);
define('PWD_SHA2',      5);
// }}}

// {{{ includes
FLEA::loadClass('FLEA_Db_TableDataGateway');
// }}}

/**
 * UsersManager ������ FLEA_Db_TableDataGateway�����ڷ��ʱ����û���Ϣ�����ݱ�
 *
 * ������ݱ�����ֲ�ͬ��Ӧ�ô� FLEA_Rbac_UsersManager �����ಢʹ���Զ�������ݱ����֡������ֶ����ȡ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Rbac_UsersManager extends FLEA_Db_TableDataGateway
{
    /**
     * �����ֶ���
     *
     * @var string
     */
    var $primaryKey = 'user_id';

    /**
     * ���ݱ�����
     *
     * @var string
     */
    var $tableName = 'users';

    /**
     * �û����ֶε�����
     *
     * @var string
     */
    var $usernameField = 'username';

    /**
     * �����ʼ��ֶε�����
     *
     * @var string
     */
    var $emailField = 'email';

    /**
     * �����ֶε�����
     *
     * @var string
     */
    var $passwordField = 'password';

    /**
     * ��ɫ�ֶε�����
     *
     * @var string
     */
    var $rolesField = 'roles';

    /**
     * ������ܷ�ʽ
     *
     * @var int
     */
    var $encodeMethod = PWD_CRYPT;

    /**
     * �����ݽ����Զ���֤
     *
     * @var boolean
     */
    var $autoValidating = true;

    /**
     * ָ��������������������ֶ�
     *
     * @var array
     */
    var $functionFields = array(
        'registerIpField' => null,
        'lastLoginField' => null,
        'lastLoginIpField' => null,
        'loginCountField' => null,
        'isLockedField' => null,
    );

    /**
     * ���캯��
     */
    function FLEA_Rbac_UsersManager()
    {
        parent::FLEA_Db_TableDataGateway();
        $mn = strtoupper($this->emailField);
        if (isset($this->meta[$mn])) {
            $this->meta[$mn]['complexType'] = 'EMAIL';
        }
    }

    /**
     * ����ָ�� ID ���û�
     *
     * @param mixed $id
     * @param mixed $fields
     *
     * @return array
     */
    function findByUserId($id, $fields = '*')
    {
        return $this->findByField($this->primaryKey, $id, null, $fields);
    }

    /**
     * ����ָ���û������û�
     *
     * @param string $username
     * @param mixed $fields
     *
     * @return array
     */
    function findByUsername($username, $fields = '*')
    {
        return $this->findByField($this->usernameField, $username, null, $fields);
    }

    /**
     * ����ָ�������ʼ����û�
     *
     * @param string $email
     * @param mixed $fields
     *
     * @return array
     */
    function findByEmail($email, $fields = '*')
    {
        return $this->findByField($this->emailField, $email, null, $fields);
    }

    /**
     * ���ָ�����û�ID�Ƿ��Ѿ�����
     *
     * @param mixed $id
     *
     * @return boolean
     */
    function existsUserId($id)
    {
        return $this->findCount(array($this->primaryKey => $id)) > 0;
    }

    /**
     * ���ָ�����û����Ƿ��Ѿ�����
     *
     * @param string $username
     *
     * @return boolean
     */
    function existsUsername($username)
    {
        return $this->findCount(array($this->usernameField => $username)) > 0;
    }

    /**
     * ���ָ���ĵ����ʼ���ַ�Ƿ��Ѿ�����
     *
     * @param string $email
     *
     * @return boolean
     */
    function existsEmail($email)
    {
        return $this->findCount(array($this->emailField => $email)) > 0;
    }

    /**
     * �����û���¼�������½��û���¼������ֵ
     *
     * @param array $row
     *
     * @return mixed
     */
    function create(& $row)
    {
        if (isset($this->functionFields['registerIpField'])
            && $this->functionFields['registerIpField'] != '')
        {
            $row[$this->functionFields['registerIpField']] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        }
        return parent::create($row);
    }

    /**
     * ��ָ֤�����û����������Ƿ���ȷ����֤�ɹ�������û��ĵ�¼��Ϣ
     *
     * @param string $username �û���
     * @param string $password ����
     * @param boolean $returnUserdata ָʾ��֤ͨ�����Ƿ񷵻��û�����
     *
     * @return boolean|array
     *
     * @access public
     */
    function validateUser($username, $password, $returnUserdata = false)
    {
        if ($returnUserdata) {
            $user = $this->findByField($this->usernameField, $username);
        } else {
            $fields = array($this->primaryKey, $this->passwordField);
            if (isset($this->functionFields['loginCountField'])
                && $this->functionFields['loginCountField'] != '')
            {
                $fields[] = $this->functionFields['loginCountField'];
            }
            if (isset($this->functionFields['isLockedField'])
                && $this->functionFields['isLockedField'] != '')
            {
                $fields[] = $this->functionFields['isLockedField'];
            }
            $user = $this->findByField($this->usernameField, $username, null, $fields);
        }
        if (!$user) { return false; }
        if (isset($this->functionFields['isLockedField'])
            && $this->functionFields['isLockedField'] != '')
        {
            if ($user[$this->functionFields['isLockedField']]) {
                return false;
            }
        }
        if (!$this->checkPassword($password, $user[$this->passwordField])) {
            return false;
        }

        $update = array();

        if (isset($this->functionFields['lastLoginField'])
            && $this->functionFields['lastLoginField'] != '')
        {
            $update[$this->functionFields['lastLoginField']] = time();
        }

        if (isset($this->functionFields['lastLoginIpField'])
            && $this->functionFields['lastLoginIpField'] != '')
        {
            $update[$this->functionFields['lastLoginIpField']] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        }

        if (isset($this->functionFields['loginCountField'])
            && $this->functionFields['loginCountField'] != '')
        {
            $update[$this->functionFields['loginCountField']] = $user[$this->functionFields['loginCountField']] + 1;
        }

        if (!empty($update)) {
            $update[$this->primaryKey] = $user[$this->primaryKey];
            $this->update($update);
        }

        if ($returnUserdata) { return $user; }
        return true;
    }

    /**
     * ����ָ���û�������
     *
     * @param string $username �û���
     * @param string $oldPassword ����ʹ�õ�����
     * @param string $newPassword ������
     *
     * @return boolean
     *
     * @access public
     */
    function changePassword($username, $oldPassword, $newPassword)
    {
        $user = $this->findByField(
            $this->usernameField, $username, null,
            array($this->primaryKey, $this->passwordField)
        );
        if (!$user) { return false; }
        if (!$this->checkPassword($oldPassword, $user[$this->passwordField])) {
            return false;
        }

        $user[$this->passwordField] = $newPassword;
        return parent::update($user);
    }

    /**
     * ֱ�Ӹ�������
     *
     * @param string $username
     * @param string $newPassword
     *
     * @return boolean
     */
    function updatePassword($username, $newPassword)
    {
        $user = $this->findByField($this->usernameField, $username, null, $this->primaryKey);
        if (!$user) { return false; }

        $user[$this->passwordField] = $newPassword;
        return parent::update($user);
    }

    /**
     * ֱ�Ӹ�������
     *
     * @param mixed $userId
     * @param string $newPassword
     *
     * @return boolean
     */
    function updatePasswordById($userid, $newPassword)
    {
        $user = $this->findByField($this->primaryKey, $userid, null, $this->primaryKey);
        if (!$user) { return false; }

        $user[$this->passwordField] = $newPassword;
        return parent::update($user);
    }

    /**
     * �����������ĺ������Ƿ����
     *
     * @param string $cleartext ���������
     * @param string $cryptograph ����
     *
     * @return boolean
     *
     * @access public
     */
    function checkPassword($cleartext, $cryptograph)
    {
        switch ($this->encodeMethod) {
        case PWD_MD5:
            return (md5($cleartext) == rtrim($cryptograph));
        case PWD_CRYPT:
            return (crypt($cleartext, $cryptograph) == rtrim($cryptograph));
        case PWD_CLEARTEXT:
            return ($cleartext == rtrim($cryptograph));
        case PWD_SHA1:
            return (sha1($cleartext) == rtrim($cryptograph));
        case PWD_SHA2:
            return (hash('sha512', $cleartext) == rtrim($cryptograph));

        default:
            return false;
        }
    }

    /**
     * ����������ת��Ϊ����
     *
     * @param string $cleartext Ҫ���ܵ�����
     *
     * @return string
     *
     * @access public
     */
    function encodePassword($cleartext)
    {
        switch ($this->encodeMethod) {
        case PWD_MD5:
            return md5($cleartext);
        case PWD_CRYPT:
            return crypt($cleartext);
        case PWD_CLEARTEXT:
            return $cleartext;
        case PWD_SHA1:
            return sha1($cleartext);
        case PWD_SHA2:
            return hash('sha512', $cleartext);

        default:
            return false;
        }
    }

    /**
     * ����ָ���û��Ľ�ɫ������
     *
     * @param array $user
     *
     * @return array
     */
    function fetchRoles($user)
    {
        if ($this->existsLink($this->rolesField)) {
            $link =& $this->getLink($this->rolesField);
            $rolenameField = $link->assocTDG->rolesNameField;
        } else {
            $rolenameField = 'rolename';
        }

        if (!isset($user[$this->rolesField]) ||
            !is_array($user[$this->rolesField])) {
            return array();
        }
        $roles = array();
        foreach ($user[$this->rolesField] as $role) {
            if (!is_array($role)) {
                return array($user[$this->rolesField][$rolenameField]);
            }
            $roles[] = $role[$rolenameField];
        }
        return $roles;
    }

    /**
     * �����û���Ϣʱ����ֹ���������ֶ�
     *
     * @param array $row
     *
     * @return boolean
     */
    function update(& $row)
    {
        unset($row[$this->passwordField]);
        return parent::update($row);
    }

    /**
     * �ڸ��µ����ݿ�֮ǰ��������
     */
    function _beforeUpdateDb(& $row)
    {
        $this->_encodeRecordPassword($row);
        return true;
    }

    /**
     * �ڸ��µ����ݿ�֮ǰ��������
     */
    function _beforeCreateDb(& $row)
    {
        $this->_encodeRecordPassword($row);
        return true;
    }

    /**
     * ����¼����������ֶ�ֵ������תΪ���ܺ������
     *
     * @param array $row
     */
    function _encodeRecordPassword(& $row)
    {
        if (isset($row[$this->passwordField])) {
            $row[$this->passwordField] =
                $this->encodePassword($row[$this->passwordField]);
        }
    }
}
