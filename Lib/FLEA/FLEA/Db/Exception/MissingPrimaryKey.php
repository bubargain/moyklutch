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
 * ���� FLEA_Db_Exception_MissingPrimaryKey �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: MissingPrimaryKey.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Db_Exception_MissingPrimaryKey �쳣ָʾû���ṩ�����ֶ�ֵ
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_Exception_MissingPrimaryKey extends FLEA_Exception
{
    /**
     * �����ֶ���
     *
     * @var string
     */
    var $primaryKey;

    /**
     * ���캯��
     *
     * @param string $pk
     *
     * @return FLEA_Db_Exception_MissingPrimaryKey
     */
    function FLEA_Db_Exception_MissingPrimaryKey($pk)
    {
        $this->primaryKey = $pk;
        $code = 0x06ff003;
        parent::FLEA_Exception(sprintf(_ET($code), $pk));
    }
}
