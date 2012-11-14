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
 * ���ļ�������һЩ�û����ļ�ϵͳ�����ĺ����Ͷ���
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: FileSystem.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * ����һ��Ŀ¼��
 *
 * �÷���
 * <code>
 * mkdirs('/top/second/3rd');
 * </code>
 *
 * @param string $dir
 * @param int $mode
 */
function mkdirs($dir, $mode = 0777)
{
    if (!is_dir($dir)) {
        mkdirs(dirname($dir), $mode);
        return mkdir($dir, $mode);
    }
    return true;
}

/**
 * ɾ��ָ��Ŀ¼�����µ������ļ�����Ŀ¼
 *
 * �÷���
 * <code>
 * // ɾ�� my_dir Ŀ¼�����µ������ļ�����Ŀ¼
 * rmdirs('/path/to/my_dir');
 * </code>
 *
 * ע�⣺ʹ�øú���Ҫ�ǳ��ǳ�С�ģ���������ɾ����Ҫ�ļ���
 *
 * @param string $dir
 */
function rmdirs($dir)
{
    $dir = realpath($dir);
    if ($dir == '' || $dir == '/' ||
        (strlen($dir) == 3 && substr($dir, 1) == ':\\'))
    {
        // ��ֹɾ����Ŀ¼
        return false;
    }

    // ����Ŀ¼��ɾ�������ļ�����Ŀ¼
    if(false !== ($dh = opendir($dir))) {
        while(false !== ($file = readdir($dh))) {
            if($file == '.' || $file == '..') { continue; }
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                if (!rmdirs($path)) { return false; }
            } else {
                unlink($path);
            }
        }
        closedir($dh);
        rmdir($dir);
        return true;
    } else {
        return false;
    }
}
