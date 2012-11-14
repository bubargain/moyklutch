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
 * ���� FLEA_Exception_NotImplemented �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: NotImplemented.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_NotImplemented �쳣ָʾĳ������û��ʵ��
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_NotImplemented extends FLEA_Exception
{
    var $className;
    var $methodName;

    /**
     * ���캯��
     *
     * @param string $method
     * @param string $class
     *
     * @return FLEA_Exception_NotImplemented
     */
    function FLEA_Exception_NotImplemented($method, $class = '')
    {
        $this->className = $class;
        $this->methodName = $method;
        if ($class) {
            $code = 0x010200a;
            parent::FLEA_Exception(sprintf(_ET($code), $class, $method));
        } else {
            $code = 0x010200b;
            parent::FLEA_Exception(sprintf(_ET($code), $method));
        }
    }
}
