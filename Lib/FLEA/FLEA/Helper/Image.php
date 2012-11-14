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
 * ���� FLEA_Helper_Image ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: Image.php 1405 2008-10-14 09:56:43Z dualface $
 */

/**
 * FLEA_Helper_Image ���װ�����ͼ��Ĳ���
 *
 * �����߲���ֱ�ӹ�������ʵ��������Ӧ���� FLEA_Helper_Image::createFromFile()
 * ��̬��������һ�� FLEA_Helper_Image ���ʵ����
 *
 * ������ͼƬʱ����ȷ�� php �ܹ������㹻���ڴ档
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Helper_Image
{
    /**
     * GD ��Դ���
     *
     * @var resource
     */
    var $_handle = null;

    /**
     * ���캯��
     * �����߲���ֱ�ӹ�������ʵ��������Ӧ���� FLEA_Helper_Image::createFromFile()
     * ��̬��������һ�� FLEA_Helper_Image ���ʵ����
     *
     * @param resource $handle
     *
     * @return FLEA_Helper_Image
     */
    function FLEA_Helper_Image($handle)
    {
        $this->_handle = $handle;
    }

    /**
     * ��ָ���ļ����� Image ����
     *
     * �����ϴ����ļ�����������ʱ�ļ����в�û�а�����չ���������Ҫ��������ķ������� Image ����
     *
     * <code>
     * $ext = pathinfo($_FILES['postfile']['name'], PATHINFO_EXTENSION);
     * $image =& FLEA_Helper_Image::createFromFile($_FILES['postfile']['tmp_name'], $ext);
     * </code>
     *
     * @param string $filename
     * @param string $fileext
     *
     * @return FLEA_Helper_Image
     */
    function & createFromFile($filename, $fileext = null)
    {
        if (is_null($fileext)) {
            $fileext = pathinfo($filename, PATHINFO_EXTENSION);
        }
        $fileext = strtolower($fileext);
        $ext2functions = array(
            'jpg' => 'imagecreatefromjpeg',
            'jpeg' => 'imagecreatefromjpeg',
            'png' => 'imagecreatefrompng',
            'gif' => 'imagecreatefromgif',
        );
        if (!isset($ext2functions[$fileext])) {
            FLEA::loadClass('FLEA_Exception_NotImplemented');
            __THROW(new FLEA_Exception_NotImplemented('imagecreatefrom' . $fileext));
            return false;
        }

        $handle = $ext2functions[$fileext]($filename);
        $img =& new FLEA_Helper_Image($handle);
        return $img;
    }

    /**
     * ��������ͼ��ָ����С�������ϲ
     *
     * @param int $width
     * @param int $height
     */
    function resize($width, $height)
    {
        if (is_null($this->_handle)) { return; }
        $dest = imagecreatetruecolor($width, $height);
        imagecopyresized($dest, $this->_handle, 0, 0, 0, 0,
                $width, $height, imagesx($this->_handle), imagesy($this->_handle));
        imagedestroy($this->_handle);
        $this->_handle = $dest;
    }

    /**
     * ����ͼ��ָ����С�������Ϻã��ٶȱ� resize() ����
     *
     * @param int $width
     * @param int $height
     */
    function resampled($width, $height)
    {
        if (is_null($this->_handle)) { return; }
        $dest = imagecreatetruecolor($width, $height);
        imagecopyresampled($dest, $this->_handle, 0, 0, 0, 0,
                $width, $height, imagesx($this->_handle), imagesy($this->_handle));
        imagedestroy($this->_handle);
        $this->_handle = $dest;
    }

    /**
     * ����ͼ���С�������������Ų���
     *
     * @param int $width
     * @param int $height
     * @param string $pos
     * @param string $bgcolor
     */
    function resizeCanvas($width, $height, $pos = 'center', $bgcolor = '0xffffff')
    {
        if (is_null($this->_handle)) { return; }
        $dest = imagecreatetruecolor($width, $height);
        $sx = imagesx($this->_handle);
        $sy = imagesy($this->_handle);

        // ���� pos ������������ζ�λԭʼͼƬ
        switch (strtolower($pos)) {
        case 'left':
            $ox = 0;
            $oy = ($height - $sy) / 2;
            break;
        case 'right':
            $ox = $width - $sx;
            $oy = ($height - $sy) / 2;
            break;
        case 'top':
            $ox = ($width - $sx) / 2;
            $oy = 0;
            break;
        case 'bottom':
            $ox = ($width - $sx) / 2;
            $oy = $height - $sy;
            break;
        case 'top-left':
            $ox = $oy = 0;
            break;
        case 'top-right':
            $ox = $width - $sx;
            $oy = 0;
            break;
        case 'bottom-left':
            $ox = 0;
            $oy = $height - $sy;
            break;
        case 'bottom-right':
            $ox = $width - $sx;
            $oy = $height - $sy;
            break;
        default:
            $ox = ($width - $sx) / 2;
            $oy = ($height - $sy) / 2;
        }

        list($r, $g, $b) = $this->extractColor($bgcolor, '0xffffff');
        $bgcolor = imagecolorallocate($dest, $r, $g, $b);
        imagefilledrectangle($dest, 0, 0, $width, $height, $bgcolor);
        imagecolordeallocate($dest, $bgcolor);

        imagecopy($dest, $this->_handle, $ox, $oy, 0, 0, $sx, $sy);
        imagedestroy($this->_handle);
        $this->_handle = $dest;
    }

    /**
     * �ڱ���ͼ�񳤿�ȵ�����½�ͼ��ü���ָ����С
     *
     * @param int $width
     * @param int $height
     * @param boolean $highQuality
     * @param array $nocut
     */
    function crop($width, $height, $highQuality = true, $nocut = null)
    {
        if (is_null($this->_handle)) { return; }
        $dest = imagecreatetruecolor($width, $height);
        $sx = imagesx($this->_handle);
        $sy = imagesy($this->_handle);
        $ratio = doubleval($width) / doubleval($sx);

        if (!is_array($nocut)) {
            if ($nocut) {
                $nocut = array('enabled' => true, 'pos' => 'center', 'bgcolor' => '0xffffff');
            } else {
                $nocut = array('enabled' => false);
            }
        } else {
            $nocut['enabled'] = isset($nocut['enabled']) ? $nocut['enabled']: true;
            $nocut['pos'] = isset($nocut['pos']) ? $nocut['pos']: 'center';
            $nocut['bgcolor'] = isset($nocut['bgcolor']) ? $nocut['bgcolor']: '0xffffff';
        }

        if ($nocut['enabled']) {
            // �����ź������Ⱥ͸߶�
            if ($sy * $ratio > $height) {
                $ratio = doubleval($height) / doubleval($sy);
            }
            $dx = $sx * $ratio;
            $dy = $sy * $ratio;

            // ���� pos ������������ζ�λԭʼͼƬ
            switch (strtolower($nocut['pos'])) {
            case 'left':
                $ox = 0;
                $oy = ($height - $sy * $ratio) / 2;
                break;
            case 'right':
                $ox = $width - $sx * $ratio;
                $oy = ($height - $sy * $ratio) / 2;
                break;
            case 'top':
                $ox = ($width - $sx * $ratio) / 2;
                $oy = 0;
                break;
            case 'bottom':
                $ox = ($width - $sx * $ratio) / 2;
                $oy = $height - $sy * $ratio;
                break;
            case 'top-left':
                $ox = $oy = 0;
                break;
            case 'top-right':
                $ox = $width - $sx * $ratio;
                $oy = 0;
                break;
            case 'bottom-left':
                $ox = 0;
                $oy = $height - $sy * $ratio;
                break;
            case 'bottom-right':
                $ox = $width - $sx * $ratio;
                $oy = $height - $sy * $ratio;
                break;
            default:
                $ox = ($width - $sx * $ratio) / 2;
                $oy = ($height - $sy * $ratio) / 2;
            }

            list($r, $g, $b) = $this->extractColor($nocut['bgcolor'], '0xffffff');
            $bgcolor = imagecolorallocate($dest, $r, $g, $b);
            imagefilledrectangle($dest, 0, 0, $width, $height, $bgcolor);
            imagecolordeallocate($dest, $bgcolor);

            $args = array($dest, $this->_handle, $ox, $oy, 0, 0, $dx, $dy, $sx, $sy);
        } else {
            // ����ͼ�����
            if ($sy * $ratio < $height) {
                // �����ձ������ź��ͼ��߶�С��Ҫ��ĸ߶�ʱ��ֻ�з���ԭʼͼ���ұߵĲ�������
                $ratio = doubleval($sy) / doubleval($height);
                $sx = $width * $ratio;
            } elseif ($sy * $ratio > $height) {
                // �����ձ������ź��ͼ��߶ȴ���Ҫ��ĸ߶�ʱ��ֻ�з���ԭʼͼ��ײ��Ĳ�������
                $ratio = doubleval($sx) / doubleval($width);
                $sy = $height * $ratio;
            }

            $args = array($dest, $this->_handle, 0, 0, 0, 0, $width, $height, $sx, $sy);
        }

        if ($highQuality) {
            call_user_func_array('imagecopyresampled', $args);
        } else {
            call_user_func_array('imagecopyresized', $args);
        }

        imagedestroy($this->_handle);
        $this->_handle = $dest;
    }

    /**
     * ����Ϊ JPEG �ļ�
     *
     * @param string $filename
     * @param int $quality
     */
    function saveAsJpeg($filename, $quality = 80)
    {
        imagejpeg($this->_handle, $filename, $quality);
    }

    /**
     * ����Ϊ PNG �ļ�
     *
     * @param string $filename
     */
    function saveAsPng($filename)
    {
        imagepng($this->_handle, $filename);
    }

    /**
     * ����Ϊ GIF �ļ�
     *
     * @param string $filename
     */
    function saveAsGif($filename)
    {
        imagegif($this->_handle, $filename);
    }

    /**
     * ����ͼ��
     */
    function destory()
    {
        imagedestroy($this->_handle);
        $this->_handle = null;
    }

    /**
     * ��ʮ�����Ʊ�ʾ����ɫֵת��Ϊ rgb
     *
     * @param string $color
     * @param string $default
     *
     * @return array
     */
    function extractColor($color, $default = 'ffffff')
    {
        $hex = trim($color, '#&Hh');
        $len = strlen($hex);
        if ($len == 3) {
            $hex = "{$hex[0]}{$hex[0]}{$hex[1]}{$hex[1]}{$hex[2]}{$hex[2]}";
        } elseif ($len < 6) {
            $hex = $default;
        }
        $dec = hexdec($hex);
        return array(($dec >> 16) & 0xff, ($dec >> 8) & 0xff, $dec & 0xff);
    }
}
