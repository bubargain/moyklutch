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
 * ���� FLEA_Exception_TypeMismatch �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: TypeMismatch.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_TypeMismatch �쳣ָʾһ�����Ͳ�ƥ�����
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_TypeMismatch extends FLEA_Exception
{
    var $arg;
    var $expected;
    var $actual;

    /**
     * ���캯��
     *
     * @param string $arg
     * @param string $expected
     * @param string $actual
     *
     * @return FLEA_Exception_TypeMismatch
     */
    function FLEA_Exception_TypeMismatch($arg, $expected, $actual)
    {
        $this->arg = $arg;
        $this->expected = $expected;
        $this->actual = $actual;
        $code = 0x010200c;
        $msg = sprintf(_ET($code), $arg, $expected, $actual);
        parent::FLEA_Exception($msg, $code);
    }
}
