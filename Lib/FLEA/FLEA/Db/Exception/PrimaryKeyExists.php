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
 * ���� FLEA_Db_Exception_PrimaryKeyExists �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: PrimaryKeyExists.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Db_Exception_PrimaryKeyExists �쳣ָʾ�ڲ���Ҫ����ֵ��ʱ��ȴ�ṩ������ֵ
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_Exception_PrimaryKeyExists extends FLEA_Exception
{
    /**
     * �����ֶ���
     *
     * @var string
     */
    var $primaryKey;

    /**
     * �����ֶ�ֵ
     *
     * @var mixed
     */
    var $pkValue;

    /**
     * ���캯��
     *
     * @param string $pk
     * @param mixed $pkValue
     *
     * @return FLEA_Db_Exception_PrimaryKeyExists
     */
    function FLEA_Db_Exception_PrimaryKeyExists($pk, $pkValue = null)
    {
        $this->primaryKey = $pk;
        $this->pkValue = $pkValue;
        $code = 0x06ff004;
        $msg = sprintf(_ET($code), $pk, $pkValue);
        parent::FLEA_Exception($msg, $code);
    }
}
