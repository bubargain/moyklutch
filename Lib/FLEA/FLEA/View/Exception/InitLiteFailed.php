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
 * ���� FLEA_View_Exception_InitLiteFailed ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Exception
 * @version $Id: InitLiteFailed.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_View_Exception_InitLiteFailed ָʾ FLEA_View_Lite �޷���ʼ�� TemplateLite ģ������
 *
 * @package Exception
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_Exception_InitLiteFailed extends FLEA_Exception
{
    var $filename;

    function FLEA_View_Exception_InitLiteFailed($filename)
    {
        $this->filename = $filename;
        $code = 0x0904002;
        parent::FLEA_Exception(sprintf(_ET($code), $filename), $code);
    }
}
