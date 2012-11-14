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
 * ���� class_fileUploader �� class_fileUploader_file ����
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: FileUploader.php 1018 2007-12-04 23:41:47Z qeeyuan $
 */

/**
 * class_fileUploader ʵ����һ���򵥵ġ�����չ���ļ��ϴ�����
 *
 * ʹ�÷�����
 *
 * <code>
 * $allowExts = 'jpg,png,gif';
 * $maxSize = 150 * 1024; // 150KB
 * $uploadDir = dirname(__FILE__) . '/upload';
 *
 * FLEA::loadClass('class_fileUploader');
 * $uploader =& new class_fileUploader();
 * $files =& $uploader->getFiles();
 * foreach ($files as $file) {
 *     if (!$file->check($allowExts, $maxSize)) {
 *         // �ϴ����ļ����Ͳ������߳����˴�С���ơ�
 *         return false;
 *     }
 *     // ����Ψһ���ļ������ظ��Ŀ����Լ�С��
 *     $id = md5(time() . $file->getFilename() . $file->getSize() . $file->getTmpName());
 *     $filename = $id . '.' . strtolower($file->getExt());
 *     $file->move($uploadDir . '/' . $filename);
 * }
 * </code>
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class class_fileUploader
{
    /**
     * ���е� UploadFile ����ʵ��
     *
     * @var array
     */
    var $_files = array();

    /**
     * ���õ��ϴ��ļ���������
     *
     * @var int
     */
    var $_count;

    /**
     * ���캯��
     *
     * @param boolean $cascade
     *
     * @return class_fileUploader
     */
    function class_fileUploader($cascade = false)
    {
        if (is_array($_FILES)) {
            foreach ($_FILES as $field => $struct) {
                if (!isset($struct['error'])) { continue; }
                if (is_array($struct['error'])) {
                    $arr = array();
                    for ($i = 0; $i < count($struct['error']); $i++) {

                        if ($struct['error'][$i] != UPLOAD_ERR_NO_FILE) {
                            $arr[] =& new class_fileUploader_file($struct, $field, $i);
                            if (!$cascade) {
                                $this->_files["{$field}{$i}"] =& $arr[count($arr) - 1];
                            }
                        }
                    }
                    if ($cascade) {
                        $this->_files[$field] = $arr;
                    }
                } else {
                    if ($struct['error'] != UPLOAD_ERR_NO_FILE) {
                        $this->_files[$field] =& new class_fileUploader_file($struct, $field);
                    }
                }
            }
        }
        $this->_count = count($this->_files);
    }

    /**
     * ���õ��ϴ��ļ���������
     *
     * @return int
     */
    function getCount()
    {
        return $this->_count;
    }

    /**
     * �������е��ϴ��ļ�����
     *
     * @return array
     */
    function & getFiles()
    {
        return $this->_files;
    }

    /**
     * ���ָ�����ֵ��ϴ��ļ������Ƿ����
     *
     * @param string $name
     *
     * @return boolean
     */
    function existsFile($name)
    {
        return isset($this->_files[$name]);
    }

    /**
     * ����ָ�����ֵ��ϴ��ļ�����
     *
     * @param string $name
     *
     * @return class_fileUploader_file
     */
    function & getFile($name)
    {
        if (!isset($this->_files[$name])) {
            FLEA::loadClass('FLEA_Exception_ExpectedFile');
            return __THROW(new FLEA_Exception_ExpectedFile('$_FILES[' . $name . ']'));
        }
        return $this->_files[$name];
    }

    /**
     * ���ָ�����ϴ��ļ��Ƿ����
     *
     * @param string $name
     *
     * @return boolean
     */
    function isFileExist($name)
    {
        return isset($this->_files[$name]);
    }

    /**
     * �����ƶ��ϴ����ļ���Ŀ��Ŀ¼
     *
     * @param string $destDir
     */
    function batchMove($destDir)
    {
        foreach ($this->_files as $file) {
            /* @var $file class_fileUploader_file */
            $file->move($destDir . '/' . $file->getFilename());
        }
    }
}

/**
 * ��װһ���ϴ����ļ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class class_fileUploader_file
{
    /**
     * �ϴ��ļ���Ϣ
     *
     * @var array
     */
    var $_file = array();

    /**
     * �ϴ��ļ����������
     *
     * @var string
     */
    var $_name;

    /**
     * ���캯��
     *
     * @param array $struct
     * @param string $name
     * @param int $ix
     *
     * @return class_fileUploader_file
     */
    function class_fileUploader_file($struct, $name, $ix = false)
    {
        if ($ix !== false) {
            $s = array(
                'name' => $struct['name'][$ix],
                'type' => $struct['type'][$ix],
                'tmp_name' => $struct['tmp_name'][$ix],
                'error' => $struct['error'][$ix],
                'size' => $struct['size'][$ix],
            );
            $this->_file = $s;
        } else {
            $this->_file = $struct;
        }

        $this->_file['is_moved'] = false;
        $this->_name = $name;
    }

    /**
     * �����Զ�������
     *
     * @param string $name
     * @param mixed $value
     */
    function setAttribute($name, $value)
    {
        $this->_file[$name] = $value;
    }

    /**
     * ��ȡ�Զ�������
     *
     * @param string $name
     *
     * @return mixed
     */
    function getAttribute($name)
    {
        return $this->_file[$name];
    }

    /**
     * �����ϴ��ļ����������
     *
     * @return string
     */
    function getName()
    {
        return $this->_name;
    }

    /**
     * ָʾ�ϴ��Ƿ�ɹ�
     *
     * @return boolean
     */
    function isSuccessed()
    {
        return $this->_file['error'] == UPLOAD_ERR_OK;
    }

    /**
     * �����ϴ��������
     *
     * @return int
     */
    function getError()
    {
        return $this->_file['error'];
    }

    /**
     * ָʾ�ϴ��ļ��Ƿ��Ѿ�����ʱĿ¼�Ƴ�
     *
     * @return boolean
     */
    function isMoved()
    {
        return $this->_file['is_moved'];
    }

    /**
     * �����ϴ��ļ���ԭ��
     *
     * @return string
     */
    function getFilename()
    {
        return $this->_file['name'];
    }

    /**
     * �����ϴ��ļ�����"."����չ��
     *
     * @return string
     */
    function getExt()
    {
        if ($this->isMoved()) {
            return pathinfo($this->getNewPath(), PATHINFO_EXTENSION);
        } else {
            return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
        }
    }

    /**
     * �����ϴ��ļ��Ĵ�С���ֽ�����
     *
     * @return int
     */
    function getSize()
    {
        return $this->_file['size'];
    }

    /**
     * �����ϴ��ļ��� MIME ���ͣ���������ṩ�������ţ�
     *
     * @return string
     */
    function getMimeType()
    {
        return $this->_file['type'];
    }

    /**
     * �����ϴ��ļ�����ʱ�ļ���
     *
     * @return string
     */
    function getTmpName()
    {
        return $this->_file['tmp_name'];
    }

    /**
     * ����ļ�����·����ͨ�����ƶ������·���������ļ�����
     *
     * @return string
     */
    function getNewPath()
    {
        return $this->_file['new_path'];
    }

    /**
     * ����ϴ����ļ��Ƿ�ɹ��ϴ��������ϼ���������ļ����͡����ߴ磩
     *
     * �ļ���������չ��Ϊ׼�������չ���� , �ָ���� .jpg,.jpeg,.png��
     *
     * @param string $allowExts �������չ��
     * @param int $maxSize ���������ϴ��ֽ���
     *
     * @return boolean
     */
    function check($allowExts = null, $maxSize = null)
    {
        if (!$this->isSuccessed()) { return false; }

        if ($allowExts) {
            if (strpos($allowExts, ',')) {
                $exts = explode(',', $allowExts);
            } elseif (strpos($allowExts, '/')) {
                $exts = explode('/', $allowExts);
            } elseif (strpos($allowExts, '|')) {
                $exts = explode('|', $allowExts);
            } else {
                $exts = array($allowExts);
            }

            $filename = $this->getFilename();
            $fileexts = explode('.', $filename);
            array_shift($fileexts);
            $count = count($fileexts);
            $passed = false;
            $exts = array_filter(array_map('trim', $exts), 'trim');
            foreach ($exts as $ext) {
                if (substr($ext, 0, 1) == '.') {
                    $ext = substr($ext, 1);
                }
                $fileExt = implode('.', array_slice($fileexts, $count - count(explode('.', $ext))));
                if (strtolower($fileExt) == strtolower($ext)) {
                    $passed = true;
                    break;
                }
            }
            if (!$passed) {
                return false;
            }
        }

        if ($maxSize && $this->getSize() > $maxSize) {
            return false;
        }

        return true;
    }

    /**
     * �ƶ��ϴ��ļ���ָ��λ�ú��ļ���
     *
     * @param string $destPath
     */
    function move($destPath)
    {
        $this->_file['is_moved'] = true;
        $this->_file['new_path'] = $destPath;
        return move_uploaded_file($this->_file['tmp_name'], $destPath);
    }

    /**
     * ɾ���ϴ����ļ�
     */
    function remove()
    {
        if ($this->isMoved()) {
            unlink($this->getNewPath());
        } else {
            unlink($this->getTmpName());
        }
    }

    /**
     * ɾ���ƶ�����ļ�
     */
    function removeMovedFile()
    {
        if ($this->isMoved()) {
            unlink($this->getNewPath());
        }
    }
}
