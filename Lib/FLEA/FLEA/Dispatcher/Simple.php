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
 * ���� FLEA_Dispatcher_Simple ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Simple.php 1028 2008-02-02 05:50:59Z qeeyuan $
 */

/**
 * FLEA_Dispatcher_Simple ���� HTTP ���󣬲�ת�������ʵ� Controller ������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Dispatcher_Simple
{
    /**
     * ������������Ϣ������
     *
     * @var array
     */
    var $_request;

    /**
     * ԭʼ��������Ϣ����
     *
     * @var array
     */
    var $_requestBackup;

    /**
     * ���캯��
     *
     * @param array $request
     *
     * @return FLEA_Dispatcher_Simple
     */
    function FLEA_Dispatcher_Simple(& $request)
    {
        $this->_requestBackup =& $request;

        $controllerAccessor = strtolower(FLEA::getAppInf('controllerAccessor'));
        $actionAccessor = strtolower(FLEA::getAppInf('actionAccessor'));

        $r = array_change_key_case($request, CASE_LOWER);
        $data = array('controller' => null, 'action' => null);
        if (isset($r[$controllerAccessor])) {
            $data['controller'] = $r[$controllerAccessor];
        }
        if (isset($r[$actionAccessor])) {
            $data['action'] = $r[$actionAccessor];
        }
        $this->_request = $data;
    }

    /**
     * �������з��� Controller��Action �� Package ���֣�Ȼ��ִ��ָ���� Action ����
     *
     * @return mixed
     */
    function dispatching()
    {
        $controllerName = $this->getControllerName();
        $actionName = $this->getActionName();
        return $this->_executeAction($controllerName, $actionName, $this->getControllerClass($controllerName));
    }

    /**
     * ִ��ָ���� Action ����
     *
     * @param string $controllerName
     * @param string $actionName
     * @param string $controllerClass
     *
     * @return mixed
     */
    function _executeAction($controllerName, $actionName, $controllerClass)
    {
        $callback = FLEA::getAppInf('dispatcherFailedCallback');

        // ȷ������������
        $actionPrefix = FLEA::getAppInf('actionMethodPrefix');
        $actionMethod = $actionPrefix . $actionName . FLEA::getAppInf('actionMethodSuffix');

        $controller = null;
        $controllerClassFilename = null;
        do {
            // ������ƶ�Ӧ���ඨ��
            if (!$this->_loadController($controllerClass)) { break; }

            // �������������
            FLEA::setAppInf('FLEA.internal.currentControllerName', $controllerName);
            FLEA::setAppInf('FLEA.internal.currentActionName', $actionName);
            $controller =& new $controllerClass($controllerName);
            if (!method_exists($controller, $actionMethod)) { break; }
            if (method_exists($controller, '__setController')) {
                $controller->__setController($controllerName, $actionName);
            }
            if (method_exists($controller, '__setDispatcher')) {
                $controller->__setDispatcher($this);
            }

            // ���� _beforeExecute() ����
            if (method_exists($controller, '_beforeExecute')) {
                $controller->_beforeExecute($actionMethod);
            }
            // ִ�� action ����
            $ret = $controller->{$actionMethod}();
            // ���� _afterExecute() ����
            if (method_exists($controller, '_afterExecute')) {
                $controller->_afterExecute($actionMethod);
            }
            return $ret;
        } while (false);

        if ($callback) {
            // ����Ƿ����Ӧ�ó������õĴ��������
            $args = array($controllerName, $actionName, $controllerClass);
            return call_user_func_array($callback, $args);
        }

        if (is_null($controller)) {
            FLEA::loadClass('FLEA_Exception_MissingController');
            __THROW(new FLEA_Exception_MissingController(
                    $controllerName, $actionName, $this->_requestBackup,
                    $controllerClass, $actionMethod, $controllerClassFilename));
            return false;
        }

        FLEA::loadClass('FLEA_Exception_MissingAction');
        __THROW(new FLEA_Exception_MissingAction(
                $controllerName, $actionName, $this->_requestBackup,
                $controllerClass, $actionMethod, $controllerClassFilename));
        return false;
    }

    /**
     * ��������ȡ�� Controller ����
     *
     * ���û��ָ�� Controller ���֣��򷵻������ļ��ж����Ĭ�� Controller ���֡�
     *
     * @return string
     */
    function getControllerName()
    {
        $controllerName = preg_replace('/[^a-z0-9_]+/i', '', $this->_request['controller']);
        if ($controllerName == '') {
            $controllerName = FLEA::getAppInf('defaultController');
        }
        if (FLEA::getAppInf('urlLowerChar')) {
            $controllerName = strtolower($controllerName);
        }
        return $controllerName;
    }

    /**
     * ����Ҫ���ʵĿ���������
     *
     * @param string $controllerName
     */
    function setControllerName($controllerName)
    {
        $this->_request['controller'] = $controllerName;
    }

    /**
     * ��������ȡ�� Action ����
     *
     * ���û��ָ�� Action ���֣��򷵻������ļ��ж����Ĭ�� Action ���֡�
     *
     * @return string
     */
    function getActionName()
    {
        $actionName = preg_replace('/[^a-z0-9]+/i', '', $this->_request['action']);
        if ($actionName == '') {
            $actionName = FLEA::getAppInf('defaultAction');
        }
        return $actionName;
    }

    /**
     * ����Ҫ���ʵĶ�������
     *
     * @param string $actionName
     */
    function setActionName($actionName)
    {
        $this->_request['action'] = $actionName;
    }

    /**
     * ����ָ����������Ӧ��������
     *
     * @param string $controllerName
     *
     * @return string
     */
    function getControllerClass($controllerName)
    {
        $controllerClass = FLEA::getAppInf('controllerClassPrefix');
        if (FLEA::getAppInf('urlLowerChar')) {
            $controllerClass .= ucfirst(strtolower($controllerName));
        } else {
            $controllerClass .= $controllerName;
        }
        return $controllerClass;
    }

    /**
     * ���� url ��ַ���ҳ����������ֺͶ�����
     *
     * @param string $url
     *
     * @return array
     */
    function parseUrl($url)
    {
        $url = parse_url($url);
        $args = array();
        parse_str($url['query'], $args);
        $args = array_change_key_case($args, CASE_LOWER);
        $controllerAccessor = strtolower(FLEA::getAppInf('controllerAccessor'));
        $actionAccessor = strtolower(FLEA::getAppInf('actionAccessor'));

        $controllerName = isset($args[$controllerAccessor]) ?
                $args[$controllerAccessor] : null;
        $actionName = isset($args[$actionAccessor]) ?
                $args[$actionAccessor] : null;

        unset($args[$controllerAccessor]);
        unset($args[$actionAccessor]);
        return array($controllerName, $actionName, $args);
    }

    /**
     * �����������
     *
     * @param string $controllerClass
     *
     * @return boolean
     */
    function _loadController($controllerClass)
    {
        $controllerClassFilename = FLEA::getFilePath($controllerClass . '.php', true);
        if (!is_readable($controllerClassFilename)) {
            return false;
        }
        include_once($controllerClassFilename);
        return class_exists($controllerClass);
    }
}
