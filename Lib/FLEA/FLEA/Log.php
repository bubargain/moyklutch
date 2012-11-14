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
 * ���� FLEA_Log ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Log.php 999 2007-10-30 05:39:57Z qeeyuan $
 */

/**
 * ׷����־��¼
 *
 * @param string $msg
 * @param string $level
 */
function log_message($msg, $level = 'log', $title = '')
{
    static $instance = null;

    if (is_null($instance)) {
        $instance = array();
        $obj =& FLEA::getSingleton('FLEA_Log');
        $instance = array('obj' => & $obj);
    }

    return $instance['obj']->appendLog($msg, $level, $title);
}

/**
 * FLEA_Log ���ṩ��������־����
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Log
{
    /**
     * ���������ڼ����־���ڽ̱�����ʱ����־����д�뵽�ļ�
     *
     * @var string
     */
    var $_log = '';

    /**
     * ���ڸ�ʽ
     *
     * @var string
     */
    var $dateFormat = 'Y-m-d H:i:s';

    /**
     * ������־�ļ���Ŀ¼
     *
     * @var string
     */
    var $_logFileDir;

    /**
     * ������־���ļ���
     *
     * @var string
     */
    var $_logFilename;

    /**
     * �Ƿ�������־����
     *
     * @var boolean
     */
    var $_enabled = true;

    /**
     * Ҫд����־�ļ��Ĵ��󼶱�
     *
     * @var array
     */
    var $_errorLevel;

    /**
     * ���캯��
     *
     * @return FLEA_Log
     */
    function FLEA_Log()
    {
        $dir = FLEA::getAppInf('logFileDir');
        if (empty($dir)) {
            // ���û��ָ����־���Ŀ¼���򱣴浽�ڲ�����Ŀ¼��
            $dir = FLEA::getAppInf('internalCacheDir');
        }
        $dir = realpath($dir);
        if (substr($dir, -1) != DIRECTORY_SEPARATOR) {
            $dir .= DIRECTORY_SEPARATOR;
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            $this->_enabled = false;
        } else {
            $this->_logFileDir = $dir;
            $this->_logFilename = $this->_logFileDir . FLEA::getAppInf('logFilename');
            $errorLevel = explode(',', strtolower(FLEA::getAppInf('logErrorLevel')));
            $errorLevel = array_map('trim', $errorLevel);
            $errorLevel = array_filter($errorLevel, 'trim');
            $this->_errorLevel = array();
            foreach ($errorLevel as $e) {
               $this->_errorLevel[$e] = true;
            }

            global $___fleaphp_loaded_time;
            list($usec, $sec) = explode(" ", $___fleaphp_loaded_time);
            $this->_log = sprintf("[%s %s] ======= FleaPHP Loaded =======\n",
                date($this->dateFormat, $sec), $usec);

            if (isset($_SERVER['REQUEST_URI'])) {
                $this->_log .= sprintf("[%s] REQUEST_URI: %s\n",
                        date($this->dateFormat),
                        $_SERVER['REQUEST_URI']);
            }

            // ע��ű�����ʱҪ���еķ��������������־����д���ļ�
            register_shutdown_function(array(& $this, '__writeLog'));

            // ����ļ��Ƿ��Ѿ�����ָ����С
            if (file_exists($this->_logFilename)) {
                $filesize = filesize($this->_logFilename);
            } else {
                $filesize = 0;
            }
            $maxsize = (int)FLEA::getAppInf('logFileMaxSize');
            if ($maxsize >= 512) {
                $maxsize = $maxsize * 1024;
                if ($filesize >= $maxsize) {
                    // ʹ���µ���־�ļ���
                    $pathinfo = pathinfo($this->_logFilename);
                    $newFilename = $pathinfo['dirname'] . DS .
                        basename($pathinfo['basename'], '.' . $pathinfo['extension']) .
                        date('-Ymd-His') . '.' . $pathinfo['extension'];
                    rename($this->_logFilename, $newFilename);
                }
            }
        }
    }

    /**
     * ׷����־��Ϣ
     *
     * @param string $msg
     * @param string $level
     */
    function appendLog($msg, $level = 'log', $title = '')
    {
        if (!$this->_enabled) { return; }
        $level = strtolower($level);
        if (!isset($this->_errorLevel[$level])) { return; }

        $msg = sprintf("[%s] [%s] %s:%s\n", date($this->dateFormat), $level, $title, print_r($msg, true));
        $this->_log .= $msg;
    }

    /**
     * ����־��Ϣд�뻺��
     */
    function __writeLog()
    {
        global $___fleaphp_loaded_time;

        // ����Ӧ�ó���ִ��ʱ�䣨����������ļ���
        list($usec, $sec) = explode(" ", $___fleaphp_loaded_time);
        $beginTime = (float)$sec + (float)$usec;
        $endTime = microtime();
        list($usec, $sec) = explode(" ", $endTime);
        $endTime = (float)$sec + (float)$usec;
        $elapsedTime = $endTime - $beginTime;
        $this->_log .= sprintf("[%s %s] ======= FleaPHP End (elapsed: %f seconds) =======\n\n",
            date($this->dateFormat, $sec), $usec, $elapsedTime);

        $fp = fopen($this->_logFilename, 'a');
        if (!$fp) { return; }
        flock($fp, LOCK_EX);
        fwrite($fp, str_replace("\r", '', $this->_log));
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
