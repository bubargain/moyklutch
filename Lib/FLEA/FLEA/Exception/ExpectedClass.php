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
 * ���� FLEA_Exception_ExpectedClass �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: ExpectedClass.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_ExpectedClass �쳣ָʾ��Ҫ����û���ҵ�
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_ExpectedClass extends FLEA_Exception
{
    /**
     * ������
     *
     * @var string
     */
    var $className;

    /**
     * �ඨ���ļ�
     *
     * @var string
     */
    var $classFile;

    /**
     * ָʾ�ļ��Ƿ����
     *
     * @var boolean
     */
    var $fileExists;

    /**
     * ���캯��
     *
     * @param string $className
     * @param string $file
     * @param boolean $fileExists
     *
     * @return FLEA_Exception_ExpectedClass
     */
    function FLEA_Exception_ExpectedClass($className, $file = null, $fileExists = false)
    {
        $this->className = $className;
        $this->classFile = $file;
        $this->fileExists = $fileExists;
        if ($file) {
            $code = 0x0102002;
            $msg = sprintf(_ET($code), $file, $className);
        } else {
            $code = 0x0102003;
            $msg = sprintf(_ET($code), $className);
        }
        parent::FLEA_Exception($msg, $code);
    }
}
