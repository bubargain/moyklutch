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
 * ���� FLEA_Controller_Action ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Action.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Controller_Action ʵ����һ�������������ĳ��࣬
 * Ϊ�������Լ��Ŀ������ṩ��һЩ����ĳ�Ա�����ͷ���
 *
 * �����߲�һ����Ҫ�������̳��������Լ��Ŀ�������
 * ��������������Լ��Ŀ��������Ի��һЩ�����ԡ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Controller_Action
{
    /**
     * ��ǰ���Ƶ����֣����� $this->url() ����
     *
     * @var string
     */
    var $_controllerName = null;

    /**
     * ��ǰ���õĶ�����
     *
     * @var string
     */
    var $_actionName = null;

    /**
     * ��ǰʹ�õĵ�����������
     *
     * @var FLEA_Dispatcher_Auth
     */
    var $_dispatcher = null;

    /**
     * Ҫʹ�õĿ���������
     *
     * @var array
     */
    var $components = array();

    /**
     * ��Ⱦ��ͼǰҪ���õ� callback ����
     *
     * @var array
     */
    var $_renderCallbacks = array();

    /**
     * ���캯��
     *
     * @param string $controllerName
     *
     * @return FLEA_Controller_Action
     */
    function FLEA_Controller_Action($controllerName)
    {
        $this->_controllerName = $controllerName;

        foreach ((array)$this->components as $componentName) {
            $this->{$componentName} =& $this->_getComponent($componentName);
        }
    }

    /**
     * ���ָ�����������
     *
     * @param string $componentName
     *
     * @return object
     */
    function & _getComponent($componentName)
    {
        static $instances = array();

        if (!isset($instances[$componentName])) {
            $componentClassName = FLEA::getAppInf('component.' . $componentName);
            FLEA::loadClass($componentClassName);
            $instances[$componentName] =& new $componentClassName($this);
        }
        return $instances[$componentName];
    }

    /**
     * ���ÿ��������֣��� dispatcher ����
     *
     * @param string $controllerName
     * @param string $actionName
     */
    function __setController($controllerName, $actionName)
    {
        $this->_controllerName = $controllerName;
        $this->_actionName = $actionName;
    }

    /**
     * ���õ�ǰ������ʹ�õĵ���������
     *
     * @param FLEA_Dispatcher_Simple $dispatcher
     */
    function __setDispatcher(& $dispatcher)
    {
        $this->_dispatcher =& $dispatcher;
    }

    /**
     * ��õ�ǰʹ�õ� Dispatcher
     *
     * @return FLEA_Dispatcher_Auth
     */
    function & _getDispatcher()
    {
        if (!is_object($this->_dispatcher)) {
            $this->_dispatcher =& FLEA::getSingleton(FLEA::getAppInf('dispatcher'));
        }
        return $this->_dispatcher;
    }

    /**
     * ���쵱ǰ�������� url ��ַ
     *
     * @param string $actionName
     * @param array $args
     * @param string $anchor
     *
     * @return string
     */
    function _url($actionName = null, $args = null, $anchor = null)
    {
        return url($this->_controllerName, $actionName, $args, $anchor);
    }

    /**
     * ת��������һ������������
     *
     * @param string $controllerName
     * @param string $actionName
     */
    function _forward($controllerName = null, $actionName = null)
    {
        $this->_dispatcher->setControllerName($controllerName);
        $this->_dispatcher->setActionName($actionName);
        $this->_dispatcher->dispatching();
    }

    /**
     * ������ͼ����
     *
     * @return object
     */
    function & _getView()
    {
        $viewClass = FLEA::getAppInf('view');
        if ($viewClass != 'PHP') {
            return FLEA::getSingleton($viewClass);
        } else {
            $view = false;
            return $view;
        }
    }

    /**
     * ִ��ָ������ͼ
     *
     * @param string $__flea_internal_viewName
     * @param array $data
     */
    function _executeView($__flea_internal_viewName, $data = null)
    {
        $viewClass = FLEA::getAppInf('view');
        if ($viewClass == 'PHP') {
            if (strtolower(substr($__flea_internal_viewName, -4)) != '.php') {
                $__flea_internal_viewName .= '.php';
            }
            $view = null;
            foreach ((array)$this->_renderCallbacks as $callback) {
                call_user_func_array($callback, array(& $data, & $view));
            }
            if (is_array($data)) { extract($data); }
            include($__flea_internal_viewName);
        } else {
            $view =& $this->_getView();
            foreach ((array)$this->_renderCallbacks as $callback) {
                call_user_func_array($callback, array(& $data, & $view));
            }
            if (is_array($data)) { $view->assign($data); }
            $view->display($__flea_internal_viewName);
        }
    }

    /**
     * �ж� HTTP �����Ƿ��� POST ����
     *
     * @return boolean
     */
    function _isPOST()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }

    /**
     * �ж� HTTP �����Ƿ���ͨ�� XMLHttp �����
     *
     * @return boolean
     */
    function _isAjax()
    {
        $r = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
        return $r == 'xmlhttprequest';
    }

    /**
     * Ϊָ���ؼ����¼�������������˸��¼���Ӧ����������
     *
     * @param string $controlName
     * @param string $event
     * @param string $action
     * @param array $attribs
     *
     * @return string
     */
    function _registerEvent($controlName, $event, $action, $attribs = null)
    {
        $ajax =& FLEA::initAjax();
        return $ajax->registerEvent($controlName, $event,
                url($this->_controllerName, $action), $attribs);
    }

    /**
     * ע��һ����ͼ��Ⱦ callback ����
     *
     * @param callback $callback
     */
    function _registerRenderCallback($callback)
    {
        $this->_renderCallbacks[] = $callback;
    }
}
