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
 * ���� FLEA_View_Lite ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Lite.php 1005 2007-11-03 07:43:55Z qeeyuan $
 */

// {{{ includes

do {
    if (PHP5) {
        if (class_exists('Template_Lite', false)) { break; }
    } else {
        if (class_exists('Template_Lite')) { break; }
    }

    $viewConfig = FLEA::getAppInf('viewConfig');
    if (!isset($viewConfig['liteDir'])) {
        FLEA::loadClass('FLEA_View_Exception_NotConfigurationLite');
        return __THROW(new FLEA_View_Exception_NotConfigurationLite());
    }

    $filename = $viewConfig['liteDir'] . '/class.template.php';
    if (!file_exists($filename)) {
        FLEA::loadClass('FLEA_View_Exception_InitLiteFailed');
        return __THROW(new FLEA_View_Exception_InitLiteFailed($filename));
    }

    require($filename);
} while (false);

// }}}

/**
 * FLEA_View_Lite �ṩ�˶� TemplateLite ģ�������֧��
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_Lite extends Template_Lite
{
    /**
     * ���캯��
     *
     * @return FLEA_View_Lite
     */
    function FLEA_View_Lite() {
        parent::Template_Lite();

        $viewConfig = FLEA::getAppInf('viewConfig');
        if (is_array($viewConfig)) {
            foreach ($viewConfig as $key => $value) {
                if (isset($this->{$key})) {
                    $this->{$key} = $value;
                }
            }
        }

        FLEA::loadClass('FLEA_View_SmartyHelper');
        new FLEA_View_SmartyHelper($this);
    }
}
