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
 * ���� FLEA_Db_Exception_SqlQuery �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: SqlQuery.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Db_Exception_SqlQuery �쳣ָʾһ�� SQL ���ִ�д���
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Db_Exception_SqlQuery extends FLEA_Exception
{
    /**
     * ��������� SQL ���
     *
     * @var string
     */
    var $sql;

    /**
     * ���캯��
     *
     * @param string $sql
     * @param string $msg
     * @param int $code
     *
     * @return FLEA_Db_Exception_SqlQuery
     */
    function FLEA_Db_Exception_SqlQuery($sql, $msg = 0, $code = 0)
    {
        $this->sql = $sql;
        if ($msg) {
            $code = 0x06ff005;
            $msg = sprintf(_ET($code), $msg, $sql, $code);
        } else {
            $code = 0x06ff006;
            $msg = sprintf(_ET($code), $sql, $code);
        }
        parent::FLEA_Exception($msg, $code);
    }
}
