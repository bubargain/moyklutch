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
 * ���� FLEA_View_Simple ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Simple.php 1304 2008-08-31 03:00:59Z dualface $
 */

/**
 * FLEA_View_Simple ʵ����һ���򵥵ġ�ʹ�� PHP ������Ϊģ�����ԣ�
 * ���л��湦�ܵ�ģ������
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_View_Simple
{
    /**
     * ģ���ļ�����·��
     *
     * @var string
     */
    var $path;

    /**
     * �������ʱ��
     *
     * @access public
     * @var int
     */
    var $cacheLifetime;

    /**
     * ָʾ�Ƿ�ʹ�� cache
     *
     * @access public
     * @var boolean
     */
    var $enableCache;

    /**
     * �����ļ�����λ��
     *
     * @access public
     * @var string
     */
    var $cacheDir;

    /**
     * ģ�����
     *
     * @access private
     * @var array
     */
    var $vars = array();

    /**
     * ��������������ݵĻ���״̬
     *
     * @access private
     * @var array
     */
    var $cacheState = array();

    /**
     * ���캯��
     *
     * @param string $path ģ���ļ�����·��
     *
     * @return FLEA_View_Simple
     */
    function FLEA_View_Simple($path = null) {
        log_message('Construction FLEA_View_Simple', 'debug');

        $this->path = $path;
        $this->cacheLifetime = 900;
        $this->enableCache = true;
        $this->cacheDir = './cache';

		$viewConfig = (array)FLEA::getAppInf('viewConfig');
        $keys = array(
            'templateDir', 'cacheDir', 'cacheLifeTime', 'enableCache',
        );
        foreach ($keys as $key)
        {
            if (array_key_exists($key, $viewConfig))
            {
                $this->{$key} = $viewConfig[$key];
            }
        }
    }

    /**
     * ����ģ�����
     *
     * @param mixed $name ģ���������
     * @param mixed $value ��������
     */
    function assign($name, $value = null) {
        if (is_array($name) && is_null($value)) {
            $this->vars = array_merge($this->vars, $name);
        } else {
            $this->vars[$name] = $value;
        }
    }

    /**
     * ����ģ���������
     *
     * @param string $file ģ���ļ���
     * @param string $cacheId ���� ID�����ָ����ֵ���ʹ�ø����ݵĻ������
     *
     * @return string
     */
    function fetch($file, $cacheId = null) {
        if ($this->enableCache) {
            $cacheFile = $this->_getCacheFile($file, $cacheId);
            if ($this->isCached($file, $cacheId)) {
                return file_get_contents($cacheFile);
            }
        }

        // ����������ݲ�����
        extract($this->vars);
        ob_start();

        include($this->path . DIRECTORY_SEPARATOR . $file);
        $contents = ob_get_contents();
        ob_end_clean();

        if ($this->enableCache) {
            // ����������ݣ������滺��״̬
            $this->cacheState[$cacheFile] = file_put_contents($cacheFile, $contents) > 0;
        }

        return $contents;
    }

    /**
     * ��ʾָ��ģ�������
     *
     * @param string $file ģ���ļ���
     * @param string $cacheId ���� ID�����ָ����ֵ���ʹ�ø����ݵĻ������
     */
    function display($file, $cacheId = null) {
        echo $this->fetch($file, $cacheId);
    }

    /**
     * ��������Ƿ��Ѿ�������
     *
     * @param string $file ģ���ļ���
     * @param string $cacheId ���� ID
     *
     * @return boolean
     */
    function isCached($file, $cacheId = null) {
        // ������û����򷵻� false
        if (!$this->enableCache) { return false; }

        // ��������־��Ч���� true
        $cacheFile = $this->_getCacheFile($file, $cacheId);
        if (isset($this->cacheState[$cacheFile]) && $this->cacheState[$cacheFile]) {
            return true;
        }

        // ��黺���ļ��Ƿ����
        if (!is_readable($cacheFile)) { return false; }

        // ��黺���ļ��Ƿ��Ѿ�����
        $mtime = filemtime($cacheFile);
        if ($mtime == false) { return false; }
        if (($mtime + $this->cacheLifetime) < time()) {
            $this->cacheState[$cacheFile] = false;
            @unlink($cacheFile);
            return false;
        }

        $this->cacheState[$cacheFile] = true;
        return true;
    }

    /**
     * ���ָ���Ļ���
     *
     * @param string $file ģ����Դ��
     * @param string $cacheId ���� ID
     */
    function cleanCache($file, $cacheId = null) {
        @unlink($this->_getCacheFile($file, $cacheId));
    }

    /**
     * ������л���
     */
    function cleanAllCache() {
        foreach (glob($this->cacheDir . '/' . "*.php") as $filename) {
            @unlink($filename);
        }
    }

    /**
     * ���ػ����ļ���
     *
     * @param string $file
     * @param string $cacheId
     *
     * @return string
     */
    function _getCacheFile($file, $cacheId) {
        return $this->cacheDir . DIRECTORY_SEPARATOR . rawurlencode($file . '-' . $cacheId) . '.php';
    }
}
