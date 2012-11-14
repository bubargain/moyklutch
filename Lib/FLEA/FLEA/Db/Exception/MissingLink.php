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
 * ���� FLEA_Db_Exception_MissingLink �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: MissingLink.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Db_Exception_MissingLink �쳣ָʾ���Է��ʵĹ���������
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_Exception_MissingLink extends FLEA_Exception
{
    var $name;

    /**
     * ���캯��
     *
     * @param $name
     *
     * @return FLEA_Db_Exception_MissingLink
     */
    function FLEA_Db_Exception_MissingLink($name)
    {
        $this->name = $name;
        $code = 0x06ff009;
        parent::FLEA_Exception(sprintf(_ET($code), $name), $code);
    }
}
