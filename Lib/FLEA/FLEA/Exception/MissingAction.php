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
 * ���� FLEA_Exception_MissingAction �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: MissingAction.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_MissingAction ָʾ����Ŀ����� Action ����û���ҵ�
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_MissingAction extends FLEA_Exception
{
    /**
     * ������������
     *
     * @var string
     */
    var $controllerName;

    /**
     * ������������
     *
     * @var string
     */
    var $controllerClass;

    /**
     * ������
     *
     * @var string
     */
    var $actionName;

    /**
     * ����������
     *
     * @var string
     */
    var $actionMethod;

    /**
     * ���ò���
     *
     * @var mixed
     */
    var $arguments;

    /**
     * ���������ඨ���ļ�
     *
     * @var string
     */
    var $controllerClassFilename;

    /**
     * ���캯��
     *
     * @param string $controllerName
     * @param string $actionName
     * @param mixed $arguments
     * @param string $controllerClass
     * @param string $actionMethod
     *
     * @return FLEA_Exception_MissingAction
     */
    function FLEA_Exception_MissingAction($controllerName, $actionName,
             $arguments = null, $controllerClass = null, $actionMethod = null,
             $controllerClassFilename = null)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->arguments = $arguments;
        $this->controllerClass = $controllerClass;
        $this->actionMethod = $actionMethod;
        $this->controllerClassFilename = $controllerClassFilename;
        $code = 0x0103001;
        $msg = sprintf(_ET($code), $controllerName, $actionName);
        parent::FLEA_Exception($msg, $code);
    }
}
