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
 * ���� ___uri_filter ����
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Uri.php 1416 2008-10-16 14:44:19Z dualface $
 */

/**
 * ����Ӧ�ó������� 'urlMode' ���� $_GET ����
 *
 * �ú����ɿ���Զ����ã�Ӧ�ó�����Ҫ���øú�����
 */
function ___uri_filter()
{
    static $firstTime = true;

    if (!$firstTime) { return; }
    $firstTime = false;

    $pathinfo = !empty($_SERVER['PATH_INFO']) ?
                $_SERVER['PATH_INFO'] :
                (!empty($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '');

    $parts = explode('/', substr($pathinfo, 1));
    if (isset($parts[0]) && strlen($parts[0]))
    {
        $_GET[FLEA::getAppInf('controllerAccessor')] = $parts[0];
    }
    if (isset($parts[1]) && strlen($parts[1]))
    {
        $_GET[FLEA::getAppInf('actionAccessor')] = $parts[1];
    }

    $style = FLEA::getAppInf('urlParameterPairStyle');
    if ($style == '/') {
        for ($i = 2; $i < count($parts); $i += 2) {
            if (isset($parts[$i + 1])) {
                $_GET[$parts[$i]] = $parts[$i + 1];
            }
        }
    } else {
        for ($i = 2; $i < count($parts); $i++) {
            $p = $parts[$i];
            $arr = explode($style, $p);
            if (isset($arr[1])) {
                $_GET[$arr[0]] = $arr[1];
            }
        }
    }

    // �� $_GET �ϲ��� $_REQUEST��
    // ��ʱ��Ҫʹ�� $_REQUEST ͳһ���� url �е� id=? �����Ĳ���
    $_REQUEST = array_merge($_REQUEST, $_GET);
}

/**
 * ���ù�����
 */
if (defined('FLEA_VERSION')) {
    ___uri_filter();
}
