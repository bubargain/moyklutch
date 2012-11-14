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
 * ���� FLEA_Db_Exception_MissingDSN �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: MissingDSN.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Db_Exception_MissingDSN �쳣ָʾû���ṩ�������ݿ���Ҫ�� dbDSN Ӧ�ó�������
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_Exception_MissingDSN extends FLEA_Exception
{
    /**
     * ���캯��
     *
     * @return FLEA_Db_Exception_MissingDSN
     */
    function FLEA_Db_Exception_MissingDSN()
    {
        $code = 0x06ff002;
        parent::FLEA_Exception(_ET($code), $code);
    }
}
