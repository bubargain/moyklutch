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
 * ���� FLEA_Rbac_Exception_InvalidACTFile �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: InvalidACTFile.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Rbac_Exception_InvalidACTFile �쳣ָʾ�������� ACT �ļ���Ч
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Rbac_Exception_InvalidACTFile extends FLEA_Exception
{
    /**
     * ACT �ļ���
     *
     * @var string
     */
    var $actFilename;

    /**
     * ����������
     *
     * @var string
     */
    var $controllerName;

    /**
     * ��Ч�� ACT ����
     *
     * @var mixed
     */
    var $act;

    /**
     * ���캯��
     *
     * @param string $actFilename
     * @param string $controllerName
     * @param mixed $act
     *
     * @return FLEA_Rbac_Exception_InvalidACTFile
     */
    function FLEA_Rbac_Exception_InvalidACTFile($actFilename, $act, $controllerName = null)
    {
        $this->actFilename = $actFilename;
        $this->act = $act;
        $this->controllerName = $controllerName;

        if ($controllerName) {
            $code = 0x0701002;
            $msg = sprintf(_ET($code), $actFilename, $controllerName);
        } else {
            $code = 0x0701003;
            $msg = sprintf(_ET($code), $actFilename);
        }
        parent::FLEA_Exception($msg, $code);
    }
}
