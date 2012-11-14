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
 * ���� FLEA_Dispatcher_Exception_CheckFailed �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: CheckFailed.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Dispatcher_Exception_CheckFailed �쳣ָʾ�û���ͼ���ʵĿ�����������������û�����
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Dispatcher_Exception_CheckFailed extends FLEA_Exception
{
    var $controllerName;
    var $actionName;
    var $roles;
    var $act;

    /**
     * ���캯��
     *
     * @param string $controllerName
     * @param string $actionName
     * @param array $act
     * @param array $roles
     *
     * @return FLEA_Dispatcher_Exception_CheckFailed
     */
    function FLEA_Dispatcher_Exception_CheckFailed($controllerName, $actionName,
            $act = null, $roles = null)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->act = $act;
        $this->roles = $roles;
        $code = 0x0701004;
        $msg = sprintf(_ET($code), $controllerName, $actionName);
        parent::FLEA_Exception($msg, $code);
    }
}
