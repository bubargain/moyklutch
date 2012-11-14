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
 * ���� FLEA_Language ��
 *
 * ʹ�ý��������֧�֣�Ҫ�����¹�����
 *
 * <strong>1�� �޸�Ӧ�ó�������</strong>
 * - multiLanguageSupport ָ��Ϊ true�����ö�����֧�֣�
 * - languageFilesDir ָ��һ��Ŀ¼����ʾ�����ļ����ڸ�Ŀ¼��
 * - defaultLanguage ָ��Ĭ�������������� chinese-utf8��chinese-gb2312 �ȡ�
 *
 * <strong>2�� ׼������Ŀ¼���ֵ��ļ�</strong>
 *
 * ���� languageFilesDir ָ�� d:\www\myapp\APP\Languages��
 * ��ô��Ҫ�� d:\www\myapp\APP\Languages �½�����ͬ���Ե���Ŀ¼��
 * ���� chinese-utf8��chinese-gb2312��
 *
 * ���Ӧ�ó��������Ҫ���� chinese-utf8 ���ֵ��ļ�����ôʵ�ʵ��ֵ��ļ��ʹ����
 * d:\www\myapp\APP\Languages\chinese-utf8\ Ŀ¼��
 *
 * �ֵ��ļ������ֿ������ⶨ���������һ�����塣
 * ���������û�������ֵ��ļ������� ui.php ���� UserInterface.php��
 *
 * �ֵ��ļ������ݺܼ򵥣������� return ����һ�����飬���磺
 *
 * <code>
 * <?php
 * // d:\www\myapp\APP\Languages\chinese-utf8\UserInterface.php
 * // ���� chinese-utf8 ���Ե��û������ֵ��ļ�
 *
 * return array(
 *     'applicationTitle' => 'Ӧ�ó���ı���',
 *     'authorName' => '������',
 *     'copyright' => '��Ȩ����',
 * );
 *
 * </code>
 *
 * <strong>3�� ��Ӧ�ó�����ʹ���ֵ��ļ�</strong>
 *
 * Ӧ�ó����� load_language($dictname, $language = null) ����ָ���������ļ���
 * $dictname ����ָ���ֵ�����$language ����ָ����������
 * ����û��ָ�� $language ��������ʹ��Ӧ�ó������� defaultLanguage ��ֵ��Ϊ
 * $language ������ֵ��
 *
 * <code>
 * // ���� chinese-utf8 ��ָ���ֵ��ļ�
 * load_language('UserInterface', 'chinese-utf8');
 * </code>
 *
 * <strong>4�� ��ȡ�ֵ��ж�Ӧ���ı�</strong>
 *
 * �����ֵ��ļ��󣬾Ϳ���ͨ���ֵ��ļ���ѯԭ�ĺͷ������ı��ˡ�
 * ʹ�� _T($key, $language = null) ��ȡָ���ַ����ķ����ı������磺
 *
 * <code>
 * echo _T('applicationTitle', 'chinese-utf8');
 * </code>
 *
 * ��ʾ�������ǰ���ֵ��ļ��ж����'Ӧ�ó���ı���'��
 *
 * <strong>5�� �򻯲���</strong>
 *
 * ÿ�ζ��� _T() ָ���ڶ���������Ȼ�����㣬�������ǿ�����Ӧ�ó���ʼִ��ʱ��
 * �Ȼ�ȡ�û�ѡ��Ľ������ԣ�Ȼ���� set_app_inf('defaultLanguage', $language)
 * ����Ĭ�����ԡ�
 *
 * ���������� _T() �Ͳ���ָ���ڶ��������ˡ�
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Language.php 1005 2007-11-03 07:43:55Z qeeyuan $
 */

/**
 * ���� FLEA_Language::get() ��ȡ����
 *
 * �÷���
 * <code>
 * $msg = _T('ENGLISH', 'chinese');
 * $msg = sprintf(_T('ENGLISH: %s'), 'chinese');
 * </code>
 *
 * @param string $key
 * @param string $language ָ��Ϊ '' ʱ��ʾ��Ĭ�����԰��л�ȡ����
 *
 * @return string
 */
function _T($key, $language = '')
{
    static $instance = null;
    if (!isset($instance['obj'])) {
        $instance = array();
        $obj =& FLEA::getSingleton('FLEA_Language');
        $instance = array('obj' => & $obj);
    }
    return $instance['obj']->get($key, $language);
}

/**
 * ���������ֵ��ļ�
 *
 * @param string $dictname
 * @param string $language ָ��Ϊ '' ʱ��ʾ���ֵ�����Ĭ�����԰���
 * @param boolean $noException
 *
 * @return boolean
 */
function load_language($dictname, $language = '', $noException = false)
{
    static $instance = null;
    if (!isset($instance['obj'])) {
        $instance = array();
        $obj =& FLEA::getSingleton('FLEA_Language');
        $instance = array('obj' => & $obj);
    }
    return $instance['obj']->load($dictname, $language, $noException);
}

/**
 * FLEA_Language �ṩ������ת������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Language
{
    /**
     * ���浱ǰ������ֵ�
     *
     * @var array
     */
    var $_dict = array();

    /**
     * ָʾ��Щ�����ļ��Ѿ�������
     *
     * @var array
     */
    var $_loadedFiles = array();

    /**
     * ���캯��
     *
     * @return FLEA_Language
     */
    function FLEA_Language()
    {
        $autoload = FLEA::getAppInf('autoLoadLanguage');
        if (!is_array($autoload)) {
            $autoload = explode(',', $autoload);
        }
        foreach ($autoload as $load) {
            $load = trim($load);
            if ($load != '') {
                $this->load($load);
            }
        }
    }

    /**
     * ����ָ�����Ե��ֵ��ļ�
     *
     * ���е������ļ������ա�����/�ֵ���.php������ʽ��������Ӧ�ó�������
     * 'languageFilesDir' ָ����Ŀ¼�С�Ĭ�ϵı���Ŀ¼Ϊ FLEA/Languages��
     *
     * ���û��ָ�� $language ��������������Ӧ�ó������� 'defaultLanguage'
     * ָ��������Ŀ¼�µ��ļ���
     *
     * $language �� $dicname ������ֻ��ʹ�� 26 ����ĸ��10 ������
     * �� ��-������_�� ���š�����ΪȫСд��
     *
     * @param string $dictname �ֵ��������� 'fleaphp'��'rbac'
     * @param string $language ָ��Ϊ '' ʱ��ʾ���ֵ�����Ĭ�����԰���
     * @param boolena $noException
     */
    function load($dictname, $language = '', $noException = false)
    {
        $dictnames = explode(',', $dictname);
        foreach ($dictnames as $dictname) {
            $dictname = trim($dictname);
            if ($dictname == '') { continue; }

            $dictname = preg_replace('/[^a-z0-9\-_]+/i', '', strtolower($dictname));
            $language = preg_replace('/[^a-z0-9\-_]+/i', '', strtolower($language));
            if ($language == '') {
                $language = FLEA::getAppInf('defaultLanguage');
                $default = true;
            } else {
                $default = false;
            }

            $filename = FLEA::getAppInf('languageFilesDir') . DS .
                $language . DS . $dictname . '.php';
            if (isset($this->_loadedFiles[$filename])) { continue; }

            if (is_readable($filename)) {
                $dict = require($filename);
                $this->_loadedFiles[$filename] = true;
                if (isset($this->_dict[$language])) {
                    $this->_dict[$language] = array_merge($this->_dict[$language], $dict);
                } else {
                    $this->_dict[$language] = $dict;
                }
                if ($default) {
                    $this->_dict[0] =& $this->_dict[$language];
                }
            } else if (!$noException) {
                FLEA::loadClass('FLEA_Exception_ExpectedFile');
                return __THROW(new FLEA_Exception_ExpectedFile($filename));
            }
        }
    }

    /**
     * ����ָ�����Ķ�Ӧ���Է��룬û���ҵ�����ʱ���ؼ�
     *
     * @param string $key
     * @param string $language ָ��Ϊ '' ʱ��ʾ��Ĭ�����԰��л�ȡ����
     *
     * @return string
     */
    function get($key, $language = '')
    {
        if ($language == '') { $language = 0; }
        return isset($this->_dict[$language][$key]) ?
            $this->_dict[$language][$key] :
            $key;
    }
}
