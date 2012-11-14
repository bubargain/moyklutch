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
 * ���� FLEA_Rbac_Exception_InvalidACT �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: InvalidACT.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Rbac_Exception_InvalidACT �쳣ָʾһ����Ч�� ACT
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Rbac_Exception_InvalidACT extends FLEA_Exception
{
    /**
     * ��Ч�� ACT ����
     *
     * @var mixed
     */
    var $act;

    /**
     * ���캯��
     *
     * @param mixed $act
     *
     * @return FLEA_Rbac_Exception_InvalidACT
     */
    function FLEA_Rbac_Exception_InvalidACT($act)
    {
        $this->act = $act;
        $code = 0x0701001;
        parent::FLEA_Exception(_ET($code), $code);
    }
}
