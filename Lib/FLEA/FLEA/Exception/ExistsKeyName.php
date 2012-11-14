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
 * ���� FLEA_Exception_ExistsKeyName �쳣
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: ExistsKeyName.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Exception_ExistsKeyName �쳣ָʾ��Ҫ�ļ����Ѿ�����
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Exception_ExistsKeyName extends FLEA_Exception
{
    var $keyname;

    /**
     * ���캯��
     *
     * @param string $keyname
     *
     * @return FLEA_Exception_ExistsKeyName
     */
    function FLEA_Exception_ExistsKeyName($keyname)
    {
        $this->keyname = $keyname;
        parent::FLEA_Exception(sprintf(_ET(0x0102004), $keyname));
    }
}
