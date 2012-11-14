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
 * ���� FLEA_Helper_SendFile ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: SendFile.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

// {{{ constants
define('SENDFILE_ATTACHMENT', 'attachment');
define('SENDFILE_INLINE', 'inline');
// }}}

/**
 * FLEA_Helper_SendFile ������������������ļ�
 *
 * ���� FLEA_Helper_SendFile��Ӧ�ó�����Խ���Ҫ���ļ�������
 * ������޷����ʵ�λ�á�Ȼ��ͨ�������ļ����ݷ��͸��������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Helper_SendFile
{
    /**
     * ������������ļ�����
     *
     * @param string $serverPath �ļ��ڷ������ϵ�·�������Ի������·����
     * @param string $filename ���͸���������ļ����������ܲ�Ҫʹ�����ģ�
     * @param string $mimeType ָʾ�ļ�����
     */
    function sendFile($serverPath, $filename, $mimeType = 'application/octet-stream')
    {
        header("Content-Type: {$mimeType}");
        $filename = '"' . htmlspecialchars($filename) . '"';
        $filesize = filesize($serverPath);
        $charset = FLEA::getAppInf('responseCharset');
        header("Content-Disposition: attachment; filename={$filename}; charset={$charset}");
        header('Pragma: cache');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header("Content-Length: {$filesize}");
        readfile($serverPath);
        exit;
    }
}
