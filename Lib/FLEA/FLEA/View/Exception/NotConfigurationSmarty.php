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
 * ���� FLEA_View_Exception_NotConfigurationSmarty ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: NotConfigurationSmarty.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_View_Exception_NotConfigurationSmartySmarty ��ʾ������
 * û��Ϊ FLEA_View_Smarty �ṩ��ʼ�� Smarty ģ��������Ҫ������
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_Exception_NotConfigurationSmarty extends FLEA_Exception
{
    function FLEA_View_Exception_NotConfigurationSmarty()
    {
        $code = 0x0902001;
        parent::FLEA_Exception(_ET($code), $code);
    }
}
