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
 * ���� FLEA_View_SmartyHelper ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: SmartyHelper.php 1039 2008-04-25 19:29:53Z qeeyuan $
 */

/**
 * FLEA_View_SmartyHelper ��չ�� Smarty �� TemplateLite ģ�����棬
 * �ṩ�� FleaPHP ���ù��ܵ�ֱ��֧�֡�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_SmartyHelper
{
    /**
     * ���캯��
     *
     * @param Smarty $tpl
     *
     * @return FLEA_View_SmartyHelper
     */
    function FLEA_View_SmartyHelper(& $tpl) {
        $tpl->register_function('url',          array(& $this, '_pi_func_url'));
        $tpl->register_function('webcontrol',   array(& $this, '_pi_func_webcontrol'));
        $tpl->register_function('_t',           array(& $this, '_pi_func_t'));
        $tpl->register_function('get_app_inf',  array(& $this, '_pi_func_get_app_inf'));
        $tpl->register_function('dump_ajax_js', array(& $this, '_pi_func_dump_ajax_js'));

        $tpl->register_modifier('parse_str',    array(& $this, '_pi_mod_parse_str'));
        $tpl->register_modifier('to_hashmap',   array(& $this, '_pi_mod_to_hashmap'));
        $tpl->register_modifier('col_values',   array(& $this, '_pi_mod_col_values'));
    }

    /**
     * �ṩ�� FleaPHP url() ������֧��
     */
    function _pi_func_url($params)
    {
        $controllerName = isset($params['controller']) ? $params['controller'] : null;
        unset($params['controller']);
        $actionName = isset($params['action']) ? $params['action'] : null;
        unset($params['action']);
        $anchor = isset($params['anchor']) ? $params['anchor'] : null;
        unset($params['anchor']);

        $options = array('bootstrap' => isset($params['bootstrap']) ? $params['bootstrap'] : null);
        unset($params['bootstrap']);

        $args = array();
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $args = array_merge($args, $value);
                unset($params[$key]);
            }
        }
        $args = array_merge($args, $params);

        return url($controllerName, $actionName, $args, $anchor, $options);
    }

    /**
     * �ṩ�� FleaPHP WebControls ��֧��
     */
    function _pi_func_webcontrol($params)
    {
        $type = isset($params['type']) ? $params['type'] : 'textbox';
        unset($params['type']);
        $name = isset($params['name']) ? $params['name'] : null;
        unset($params['name']);

        $ui =& FLEA::initWebControls();
        return $ui->control($type, $name, $params, true);
    }

    /**
     * �ṩ�� FleaPHP _T() ������֧��
     */
    function _pi_func_t($params)
    {
        return _T($params['key'], isset($params['lang']) ? $params['lang'] : null);
    }

    /**
     * �ṩ�� FLEA::getAppInf() ������֧��
     */
    function _pi_func_get_app_inf($params)
    {
        return FLEA::getAppInf($params['key']);
    }

    /**
     * ��� FLEA_Ajax ���ɵĽű�
     */
    function _pi_func_dump_ajax_js($params)
    {
        $wrapper = isset($params['wrapper']) ? (bool)$params['wrapper'] : true;
        $ajax =& FLEA::initAjax();
        /* @var $ajax FLEA_Ajax */
        return $ajax->dumpJs(true, $wrapper);
    }

    /**
     * ���ַ����ָ�Ϊ����
     */
    function _pi_mod_parse_str($string)
    {
        $arr = array();
        parse_str(str_replace('|', '&', $string), $arr);
        return $arr;
    }

    /**
     * ����ά����ת��Ϊ hashmap
     */
    function _pi_mod_to_hashmap($data, $f_key, $f_value = '')
    {
        $arr = array();
        if (!is_array($data)) { return $arr; }
        if ($f_value != '') {
            foreach ($data as $row) {
                $arr[$row[$f_key]] = $row[$f_value];
            }
        } else {
            foreach ($data as $row) {
                $arr[$row[$f_key]] = $row;
            }
        }
        return $arr;
    }

    /**
     * ��ȡ��ά������ָ���е�����
     */
    function _pi_mod_col_values($data, $f_value)
    {
        $arr = array();
        if (!is_array($data)) { return $arr; }
        foreach ($data as $row) {
            $arr[] = $row[$f_value];
        }
        return $arr;
    }
}
