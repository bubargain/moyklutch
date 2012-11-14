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
 * ���� FLEA_Exception_ExpectedFile �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: ExpectedFile.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_ExpectedFile �쳣ָʾ��Ҫ���ļ�û���ҵ�
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_ExpectedFile extends FLEA_Exception
{
    var $filename;

    /**
     * ���캯��
     *
     * @param string $filename
     *
     * @return FLEA_Exception_ExpectedFile
     */
    function FLEA_Exception_ExpectedFile($filename)
    {
        $this->filename = $filename;
        $code = 0x0102001;
        $msg = sprintf(_ET($code), $filename);
        parent::FLEA_Exception($msg, $code);
    }
}
