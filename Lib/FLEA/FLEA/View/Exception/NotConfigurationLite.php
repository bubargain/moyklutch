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
 * ���� FLEA_View_Exception_NotConfigurationLite ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: NotConfigurationLite.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_View_Exception_NotConfigurationLiteLite ��ʾ������
 * û��Ϊ FLEA_View_Lite �ṩ��ʼ�� TemplateLite ģ��������Ҫ������
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_Exception_NotConfigurationLite extends FLEA_Exception
{
    function FLEA_View_Exception_NotConfigurationLite()
    {
        $code = 0x0904001;
        parent::FLEA_Exception(_ET($code), $code);
    }
}
