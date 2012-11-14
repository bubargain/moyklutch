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
 * ���� FLEA_Exception_MustOverwrite �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: MustOverwrite.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_MustOverwrite �쳣ָʾĳ����������������������д
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_MustOverwrite extends FLEA_Exception
{
    var $prototypeMethod;

    /**
     * ���캯��
     *
     * @param string $prototypeMethod
     *
     * @return FLEA_Exception_MustOverwrite
     */
    function FLEA_Exception_MustOverwrite($prototypeMethod)
    {
        $this->prototypeMethod = $prototypeMethod;
        $code = 0x0102008;
        $msg = sprintf(_ET($code), $prototypeMethod);
        parent::FLEA_Exception($msg, $code);
    }
}
