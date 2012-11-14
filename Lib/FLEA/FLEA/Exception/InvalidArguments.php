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
 * ���� FLEA_Exception_InvalidArguments �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: InvalidArguments.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_InvalidArguments �쳣ָʾһ����������
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_InvalidArguments extends FLEA_Exception
{
    var $arg;
    var $value;

    /**
     * ���캯��
     *
     * @param string $arg
     * @param mixed $value
     *
     * @return FLEA_Exception_InvalidArguments
     */
    function FLEA_Exception_InvalidArguments($arg, $value = null)
    {
        $this->arg = $arg;
        $this->value = $value;
        parent::FLEA_Exception(sprintf(_ET(0x0102006), $arg));
    }
}
