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
 * ���� FLEA_View_Smarty ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Smarty.php 1005 2007-11-03 07:43:55Z qeeyuan $
 */

// {{{ includes

do {
    if (PHP5) {
        if (class_exists('Smarty', false)) { break; }
    } else {
        if (class_exists('Smarty')) { break; }
    }

    $viewConfig = FLEA::getAppInf('viewConfig');
    if (!isset($viewConfig['smartyDir']) && !defined('SMARTY_DIR')) {
        FLEA::loadClass('FLEA_View_Exception_NotConfigurationSmarty');
        return __THROW(new FLEA_View_Exception_NotConfigurationSmarty());
    }

    $filename = $viewConfig['smartyDir'] . '/Smarty.class.php';
    if (!is_readable($filename)) {
        FLEA::loadClass('FLEA_View_Exception_InitSmartyFailed');
        return __THROW(new FLEA_View_Exception_InitSmartyFailed($filename));
    }

    require($filename);
} while (false);

// }}}

/**
 * FLEA_View_Smarty �ṩ�˶� Smarty ģ�������֧��
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_Smarty extends Smarty
{
    /**
     * ���캯��
     *
     * @return FLEA_View_Smarty
     */
    function FLEA_View_Smarty() {
        parent::Smarty();

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
