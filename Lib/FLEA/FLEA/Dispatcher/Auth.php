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
 * ���� FLEA_Dispatcher_Auth ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Auth.php 1005 2007-11-03 07:43:55Z qeeyuan $
 */

// {{{ includes
FLEA::loadClass('FLEA_Dispatcher_Simple');
// }}}

/**
 * FLEA_Dispatcher_Auth ���� HTTP ���󣬲�ת�������ʵ� Controller ������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Dispatcher_Auth extends FLEA_Dispatcher_Simple
{
    /**
     * �����ṩ��֤����Ķ���ʵ��
     *
     * @var FLEA_Rbac
     */
    var $_auth;

    /**
     * ���캯��
     *
     * @param array $request
     *
     * @return FLEA_Dispatcher_Auth
     */
    function FLEA_Dispatcher_Auth(& $request)
    {
        parent::FLEA_Dispatcher_Simple($request);
        $this->_auth =& FLEA::getSingleton(FLEA::getAppInf('dispatcherAuthProvider'));
    }

    /**
     * ���ص�ǰʹ�õ���֤�������
     *
     * @return FLEA_Rbac
     */
    function & getAuthProvider()
    {
        return $this->_auth;
    }

    /**
     * ����Ҫʹ�õ���֤�������
     *
     * @param FLEA_Rbac $auth
     */
    function setAuthProvider(& $auth)
    {
        $this->_auth =& $auth;
    }

    /**
     * ͨ����֤�������� setUser �������û����ݱ��浽 session ��
     *
     * @param array $userData
     * @param mixed $rolesData
     */
    function setUser($userData, $rolesData = null)
    {
        $this->_auth->setUser($userData, $rolesData);
    }

    /**
     * ͨ����֤�������� getUser ������ session �л�ȡ������û�����
     *
     * @return array
     */
    function getUser()
    {
        return $this->_auth->getUser();
    }

    /**
     * ͨ����֤�������� getRolesArray ������ session �л�ȡ������û���ɫ����
     *
     * @return array
     */
    function getUserRoles()
    {
        return $this->_auth->getRolesArray();
    }

    /**
     * ͨ����֤�������� getUser ������������ session �е��û�����
     *
     * @return array
     */
    function clearUser()
    {
        $this->_auth->clearUser();
    }

    /**
     * ִ�п���������
     *
     * @return mixed
     */
    function dispatching()
    {
        $controllerName  = $this->getControllerName();
        $actionName      = $this->getActionName();
        $controllerClass = $this->getControllerClass($controllerName);

        if ($this->check($controllerName, $actionName, $controllerClass)) {
            // ���ͨ����ִ�п���������
            return $this->_executeAction($controllerName, $actionName, $controllerClass);
        } else {
            // ���ʧ��
            $callback = FLEA::getAppInf('dispatcherAuthFailedCallback');

            $rawACT = $this->getControllerACT($controllerName, $controllerClass);
            if (is_null($rawACT) || empty($rawACT)) { return true; }
            $ACT = $this->_auth->prepareACT($rawACT);
            $roles = $this->_auth->getRolesArray();
            $args = array($controllerName, $actionName, $controllerClass, $ACT, $roles);

            // ��������������˵� _onAuthFailed ��̬����������ø÷���
            if ($this->_loadController($controllerClass)) {
                $methods = get_class_methods($controllerClass);
                if (in_array('_onAuthFailed', $methods, true)) {
                    if (call_user_func_array(array($controllerClass, '_onAuthFailed'), $args) !== false) {
                        return false;
                    }
                }
            }

            if ($callback) {
                return call_user_func_array($callback, $args);
            } else {
                FLEA::loadClass('FLEA_Dispatcher_Exception_CheckFailed');
                __THROW(new FLEA_Dispatcher_Exception_CheckFailed($controllerName, $actionName, $rawACT, $roles));
                return false;
            }
        }
    }

    /**
     * ��鵱ǰ�û��Ƿ���Ȩ�޷���ָ���Ŀ������ͷ���
     *
     * ��֤�������£�
     *
     * 1��ͨ�� authProiver ��ȡ��ǰ�û��Ľ�ɫ��Ϣ��
     * 2������ getControllerACT() ��ȡָ���������ķ��ʿ��Ʊ�
     * 3������ ACT ���û���ɫ���м�飬ͨ���򷵻� true�����򷵻� false��
     *
     * @param string $controllerName
     * @param string $actionName
     * @param string $controllerClass
     *
     * @return boolean
     */
    function check($controllerName, $actionName = null, $controllerClass = null)
    {
        if (is_null($controllerClass)) {
            $controllerClass = $this->getControllerClass($controllerName);
        }
        if (is_null($actionName)) {
            $actionName = $this->getActionName();
        }
        // ���������û���ṩ ACT�������ṩ��һ���յ� ACT����ٶ������û�����
        $rawACT = $this->getControllerACT($controllerName, $controllerClass);
        if (is_null($rawACT) || empty($rawACT)) { return true; }

        $ACT = $this->_auth->prepareACT($rawACT);
        $ACT['actions'] = array();
        if (isset($rawACT['actions']) && is_array($rawACT['actions'])) {
            foreach ($rawACT['actions'] as $rawActionName => $rawActionACT) {
                if ($rawActionName !== ACTION_ALL) {
                    $rawActionName = strtolower($rawActionName);
                }
                $ACT['actions'][$rawActionName] = $this->_auth->prepareACT($rawActionACT);
            }
        }
        // ȡ���û���ɫ��Ϣ
        $roles = $this->_auth->getRolesArray();
        // ���ȼ���û��Ƿ���Է��ʸÿ�����
        if (!$this->_auth->check($roles, $ACT)) { return false; }

        // ��������֤�û��Ƿ���Է���ָ���Ŀ���������
        $actionName = strtolower($actionName);
        if (isset($ACT['actions'][$actionName])) {
            return $this->_auth->check($roles, $ACT['actions'][$actionName]);
        }

        // �����ǰҪ���ʵĿ���������û���� act ��ָ�������� act ���Ƿ��ṩ�� ACTION_ALL
        if (!isset($ACT['actions'][ACTION_ALL])) { return true; }
        return $this->_auth->check($roles, $ACT['actions'][ACTION_ALL]);
    }

    /**
     * ��ȡָ���������ķ��ʿ��Ʊ�ACT��
     *
     * @param string $controllerName
     * @param string $controllerClass
     *
     * @return array
     */
    function getControllerACT($controllerName, $controllerClass)
    {
        // ���ȳ��Դ�ȫ�� ACT ��ѯ�������� ACT
        $ACT = FLEA::getAppInfValue('globalACT', $controllerName);
        if ($ACT) { return $ACT; }

        $actFilename = FLEA::getFilePath($controllerClass . '.act.php');
        if (!$actFilename) {
            if (FLEA::getAppInf('autoQueryDefaultACTFile')) {
                $ACT = $this->getControllerACTFromDefaultFile($controllerName);
                if ($ACT) { return $ACT; }
            }

            if (FLEA::getAppInf('controllerACTLoadWarning')) {
                trigger_error(sprintf(_ET(0x0701006), $controllerName), E_USER_WARNING);
            }
            return FLEA::getAppInf('defaultControllerACT');
        }

        return $this->_loadACTFile($actFilename);
    }

    /**
     * ��Ĭ�� ACT �ļ�������ָ���������� ACT
     *
     * @param string $controllerName
     */
    function getControllerACTFromDefaultFile($controllerName)
    {
        $actFilename = realpath(FLEA::getAppInf('defaultControllerACTFile'));
        if (!$actFilename) {
            if (FLEA::getAppInf('controllerACTLoadWarning')) {
                trigger_error(sprintf(_ET(0x0701006), $controllerName), E_USER_WARNING);
            }
            return FLEA::getAppInf('defaultControllerACT');
        }

        $ACT = $this->_loadACTFile($actFilename);
        if ($ACT === false) { return false; }

        $ACT = array_change_key_case($ACT, CASE_UPPER);
        $controllerName = strtoupper($controllerName);
        return isset($ACT[$controllerName]) ?
            $ACT[$controllerName] :
            FLEA::getAppInf('defaultControllerACT');
    }

    /**
     * ���� ACT �ļ�
     *
     * @param string $actFilename
     *
     * @return mixed
     */
    function _loadACTFile($actFilename)
    {
        static $files = array();

        if (isset($files[$actFilename])) {
            return $files[$actFilename];
        }

        $ACT = require($actFilename);
        if (is_array($ACT)) {
            $files[$actFilename] = $ACT;
            return $ACT;
        }

        // ���������� ACT �ļ�û�з��� ACT ʱ�׳��쳣
        FLEA::loadClass('FLEA_Rbac_Exception_InvalidACTFile');
        __THROW(new FLEA_Rbac_Exception_InvalidACTFile($actFilename, $ACT));
        return false;
    }
}
