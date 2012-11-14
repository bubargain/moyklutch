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
 * ���ļ������û��� FleaPHP 1.0.6x ϵ�п�����Ӧ�ó����ܹ��ڲ��������޸ĵ��������
 * FleaPHP 1.0.70 ���Ժ�İ汾���ּ��ݡ�
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Compatibility.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * ע��Ӧ�ó������ã��Ѿ���ʱ���� FLEA::loadAppInf() ���棩
 *
 * @param mixed $__config ��������������ļ���
 * @deprecated
 */
function register_app_inf($__flea_internal_config = null)
{
    FLEA::loadAppInf($__flea_internal_config);
}

/**
 * ȡ��ָ�����ֵ�����ֵ���Ѿ���ʱ���� FLEA::getAppInf() ���棩
 *
 * @param string $option
 *
 * @return mixed
 * @deprecated
 */
function get_app_inf($option)
{
    return FLEA::getAppInf($option);
}

/**
 * �޸�����ֵ���Ѿ���ʱ���� FLEA::setAppInf() ���棩
 *
 * @param string $option
 * @param mixed $data
 * @deprecated
 */
function set_app_inf($option, $data = null)
{
    FLEA::setAppInf($option, $data);
}

/**
 * �����ļ�����·�����Ѿ���ʱ���� FLEA::import() ���棩
 *
 * @param string $dir
 * @deprecated
 */
function import($dir)
{
    FLEA::import($dir);
}

/**
 * ����ָ�����ļ����Ѿ���ʱ���� FLEA::loadFile() ���棩
 *
 * @return boolean
 * @deprecated
 */
function load_file($filename, $loadOnce = false)
{
    return FLEA::loadFile($filename, $loadOnce);
}

/**
 * ����ָ����Ķ����ļ����Ѿ���ʱ���� FLEA::loadClass() ���棩
 *
 * @param string $filename
 *
 * @return boolean
 * @deprecated
 */
function load_class($className)
{
    return FLEA::loadClass($className);
}

/**
 * ���� FleaPHP ���������������ļ����ɹ������ļ�������·����ʧ�ܷ��� false���Ѿ���ʱ���� FLEA::getFilePath() ���棩
 *
 * @param string $filename
 *
 * @return string
 * @deprecated
 */
function get_file_path($filename, $return = false)
{
    return FLEA::getFilePath($filename, $return);
}

/**
 * ����ָ�������Ψһʵ�����Ѿ���ʱ���� FLEA::getSingleton() ���棩
 *
 * @param string $className
 *
 * @return object
 * @deprecated
 */
function & get_singleton($className)
{
    return FLEA::getSingleton($className);
}

/**
 * ��һ������ʵ��ע�ᵽ����ʵ���������Ѿ���ʱ���� FLEA::register() ���棩
 *
 * @param object $obj
 * @param string $name
 *
 * @return object
 * @deprecated
 */
function & reg(& $obj, $name = null)
{
    return FLEA::register($obj, $name);
}

/**
 * �Ӷ���ʵ��������ȡ��ָ�����ֵĶ���ʵ�����Ѿ���ʱ���� FLEA::registry() ���棩
 *
 * @param string $name
 *
 * @return object
 * @deprecated
 */
function & ref($name = null)
{
    return FLEA::registry($name);
}

/**
 * ���ָ�����ֵĶ����Ƿ��Ѿ�ע�ᣨ�Ѿ���ʱ���� FLEA::isRegistered() ���棩
 *
 * @param string $name
 *
 * @return boolean
 * @deprecated
 */
function check_reg($name)
{
    return FLEA::isRegistered($name);
}

/**
 * ��ȡָ����������ݣ�����������ݲ����ڻ�ʧЧ���򷵻� false���Ѿ���ʱ���� FLEA::getCache() ���棩
 *
 * @param string $cacheId ����ID����ͬ�Ļ�������Ӧ��ʹ�ò�ͬ��ID
 * @param int $time �������ʱ��򻺴���������
 * @param boolean $timeIsLifetime ָʾ $time ����������
 *
 * @return mixed ���ػ�������ݣ����治���ڻ�ʧЧ�򷵻� false
 * @deprecated
 */
function get_cache($cacheId, $time = 900, $timeIsLifetime = true, $cacheIdIsFilename = false)
{
    return FLEA::getCache($cacheId, $time, $timeIsLifetime, $cacheIdIsFilename);
}

/**
 * ����������д�뻺�棨�Ѿ���ʱ���� FLEA::writeCache() ���棩
 *
 * @param string $cacheId
 * @param mixed $data
 *
 * @return boolean
 * @deprecated
 */
function write_cache($cacheId, $data, $cacheIdIsFilename = false)
{
    return FLEA::writeCache($cacheId, $data, $cacheIdIsFilename);
}

/**
 * ɾ��ָ���Ļ������ݣ��Ѿ���ʱ���� FLEA::purgeCache() ���棩
 *
 * @param string $cacheId
 * @deprecated
 */
function purge_cache($cacheId, $cacheIdIsFilename = false)
{
    return FLEA::purgeCache($cacheId, $cacheIdIsFilename);
}

/**
 * ���ת�� HTML �����ַ�����ı�����ͬ�� echo h($text)���Ѿ���ʱ���� echo h() ���棩
 *
 * @param string $text
 * @deprecated
 */
function echo_h($text)
{
    echo htmlspecialchars($text);
}

/**
 * ���ת�� HTML �����ַ����ո�ͻ��з�����ı�����ͬ�� echo t($text)���Ѿ���ʱ���� echo t() ���棩
 *
 * @param string $text
 * @deprecated
 */
function echo_t($text)
{
    echo t($text);
}

/**
 * �������ݿ���ʶ���ʵ�����Ѿ���ʱ���� FLEA::getDBO() ���棩
 *
 * @param array $dsn
 *
 * @return SDBO
 * @deprecated
 */
function & get_dbo($dsn)
{
    return FLEA::getDBO($dsn);
}

/**
 * ���� DSN �ַ��������ذ��� DSN ������Ϣ�����飬ʧ�ܷ��� false���Ѿ���ʱ���� FLEA::parseDSN() ���棩
 *
 * @param string $dsnString
 *
 * @return array
 * @deprecated
 */
function parse_dsn($dsnString)
{
    return FLEA::parseDSN($dsnString);
}

/**
 * ׼�����л��������������ļ������й����������� session �ȣ��Ѿ���ʱ���� FLEA::init() ���棩
 *
 * @deprecated
 */
function __FLEA_PREPARE()
{
    FLEA::init();
}

/**
 * FLEA Ӧ�ó�����ڣ��Ѿ���ʱ���� FLEA::runMVC() ���棩
 *
 * @deprecated
 */
function run()
{
    FLEA::runMVC();
}

/**
 * ��ʼ�� Ajax������ FLEA_Ajax ����ʵ�����Ѿ���ʱ���� FLEA::initAjax() ���棩
 *
 * @return FLEA_Ajax
 * @deprecated
 */
function & init_ajax()
{
    return FLEA::initAjax();
}

/**
 * ��ʼ�� WebControls������ FLEA_WebControls ����ʵ�����Ѿ���ʱ���� FLEA::initWebControls ���棩
 *
 * @return FLEA_WebControls
 * @deprecated
 */
function & init_webcontrols()
{
    return FLEA::initWebControls();
}
