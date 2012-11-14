<?php
/////////////////////////////////////////////////////////////////////////////
// FleaPHP Framework
//
// Copyright (c) 2005 - 2007 FleaPHP.org (www.fleaphp.org)
//
// ���Э�飬��鿴Դ�����и����� LICENSE.txt �ļ���
// ���߷��� http://www.fleaphp.org/ �����ϸ��Ϣ��
/////////////////////////////////////////////////////////////////////////////

/**
 * ���� FLEA_Rbac ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Rbac.php 999 2007-10-30 05:39:57Z qeeyuan $
 */

/**
 * FLEA_Rbac �ṩ���ڽ�ɫ��Ȩ�޼�����
 *
 * FLEA_Rbac �����ṩ�û�����ͽ�ɫ�������
 * ��Щ������ FLEA_Rbac_UsersManager �� FLEA_Rbac_RolesManager �ṩ��
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Rbac
{
    /**
     * ָʾ�� session ����ʲô���ֱ����û�����Ϣ
     *
     * @var string
     */
    var $_sessionKey = 'RBAC_USERDATA';

    /**
     * ָʾ�û������У���ʲô�������ɫ��Ϣ
     *
     * @var string
     */
    var $_rolesKey = 'RBAC_ROLES';

    /**
     * ���캯��
     *
     * @return FLEA_Rbac
     */
    function FLEA_Rbac()
    {
        $this->_sessionKey = FLEA::getAppInf('RBACSessionKey');
        if ($this->_sessionKey == 'RBAC_USERDATA') {
            trigger_error(_ET(0x0701005), E_USER_WARNING);
        }
    }

    /**
     * ���û����ݱ��浽 session ��
     *
     * @param array $userData
     * @param mixed $rolesData
     */
    function setUser($userData, $rolesData = null)
    {
        if ($rolesData) {
            $userData[$this->_rolesKey] = $rolesData;
        }
        $_SESSION[$this->_sessionKey] = $userData;
    }

    /**
     * ��ȡ������ session �е��û�����
     *
     * @return array
     */
    function getUser()
    {
        return isset($_SESSION[$this->_sessionKey]) ?
                $_SESSION[$this->_sessionKey] :
                null;
    }

    /**
     * �� session ������û�����
     */
    function clearUser()
    {
        unset($_SESSION[$this->_sessionKey]);
    }

    /**
     * ��ȡ session ���û���Ϣ�����Ľ�ɫ
     *
     * @return mixed
     */
    function getRoles()
    {
        $user = $this->getUser();
        return isset($user[$this->_rolesKey]) ?
                $user[$this->_rolesKey] :
                null;
    }

    /**
     * ��������ʽ�����û��Ľ�ɫ��Ϣ
     *
     * @return array
     */
    function getRolesArray()
    {
        $roles = $this->getRoles();
        if (is_array($roles)) { return $roles; }
        $tmp = array_map('trim', explode(',', $roles));
        return array_filter($tmp, 'trim');
    }

    /**
     * �����ʿ��Ʊ��Ƿ�����ָ���Ľ�ɫ����
     *
     * @param array $roles
     * @param array $ACT
     *
     * @return boolean
     */
    function check(& $roles, & $ACT)
    {
        $roles = array_map('strtoupper', $roles);
        if ($ACT['allow'] == RBAC_EVERYONE) {
            // ��� allow �������н�ɫ��deny û�����ã�����ͨ��
            if ($ACT['deny'] == RBAC_NULL) { return true; }
            // ��� deny Ϊ RBAC_NO_ROLE����ֻҪ�û����н�ɫ�ͼ��ͨ��
            if ($ACT['deny'] == RBAC_NO_ROLE) {
                if (empty($roles)) { return false; }
                return true;
            }
            // ��� deny Ϊ RBAC_HAS_ROLE����ֻ���û�û�н�ɫ��Ϣʱ�ż��ͨ��
            if ($ACT['deny'] == RBAC_HAS_ROLE) {
                if (empty($roles)) { return true; }
                return false;
            }
            // ��� deny ҲΪ RBAC_EVERYONE�����ʾ ACT �����˳�ͻ
            if ($ACT['deny'] == RBAC_EVERYONE) {
                FLEA::loadClass('FLEA_Rbac_Exception_InvalidACT');
                __THROW(new FLEA_Rbac_Exception_InvalidACT($ACT));
                return false;
            }

            // ֻ�� deny ��û���û��Ľ�ɫ��Ϣ������ͨ��
            foreach ($roles as $role) {
                if (in_array($role, $ACT['deny'], true)) { return false; }
            }
            return true;
        }

        do {
            // ��� allow Ҫ���û����н�ɫ�����û�û�н�ɫʱֱ�Ӳ�ͨ�����
            if ($ACT['allow'] == RBAC_HAS_ROLE) {
                if (!empty($roles)) { break; }
                return false;
            }

            // ��� allow Ҫ���û�û�н�ɫ�����û��н�ɫʱֱ�Ӳ�ͨ�����
            if ($ACT['allow'] == RBAC_NO_ROLE) {
                if (empty($roles)) { break; }
                return false;
            }

            if ($ACT['allow'] != RBAC_NULL) {
                // ��� allow Ҫ���û������ض���ɫ������м��
                $passed = false;
                foreach ($roles as $role) {
                    if (in_array($role, $ACT['allow'], true)) {
                        $passed = true;
                        break;
                    }
                }
                if (!$passed) { return false; }
            }
        } while (false);

        // ��� deny û�����ã�����ͨ��
        if ($ACT['deny'] == RBAC_NULL) { return true; }
        // ��� deny Ϊ RBAC_NO_ROLE����ֻҪ�û����н�ɫ�ͼ��ͨ��
        if ($ACT['deny'] == RBAC_NO_ROLE) {
            if (empty($roles)) { return false; }
            return true;
        }
        // ��� deny Ϊ RBAC_HAS_ROLE����ֻ���û�û�н�ɫ��Ϣʱ�ż��ͨ��
        if ($ACT['deny'] == RBAC_HAS_ROLE) {
            if (empty($roles)) { return true; }
            return false;
        }
        // ��� deny Ϊ RBAC_EVERYONE������ʧ��
        if ($ACT['deny'] == RBAC_EVERYONE) {
            return false;
        }

        // ֻ�� deny ��û���û��Ľ�ɫ��Ϣ������ͨ��
        foreach ($roles as $role) {
            if (in_array($role, $ACT['deny'], true)) { return false; }
        }
        return true;
    }

    /**
     * ��ԭʼ ACT ���з�������������������
     *
     * @param array $ACT
     *
     * @return array
     */
    function prepareACT($ACT)
    {
        $ret = array();
        $arr = array('allow', 'deny');
        foreach ($arr as $key) {
            do {
                if (!isset($ACT[$key])) {
                    $value = RBAC_NULL;
                    break;
                }

                if ($ACT[$key] == RBAC_EVERYONE || $ACT[$key] == RBAC_HAS_ROLE
                    || $ACT[$key] == RBAC_NO_ROLE || $ACT[$key] == RBAC_NULL) {
                    $value = $ACT[$key];
                    break;
                }

                $value = explode(',', strtoupper($ACT[$key]));
                $value = array_filter(array_map('trim', $value), 'trim');
                if (empty($value)) { $value = RBAC_NULL; }
            } while (false);
            $ret[$key] = $value;
        }

        return $ret;
    }
}
