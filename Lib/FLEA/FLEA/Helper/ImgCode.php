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
 * ���� FLEA_Helper_ImgCode ��
 *
 * @copyright Copyright (c) 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @package Core
 * @version $Id: ImgCode.php 972 2007-10-09 20:56:54Z qeeyuan $
 */

/**
 * FLEA_Helper_ImgCode ��ʵ����һ���򵥵�ͼ����֤���������������м����֤����
 *
 * �������� session ʱ����֤��ᱣ���� session �С��÷���
 *
 * ģ��ҳ���У�����Ҫ��ʾ��֤��ĵط�ʹ��
 * <code>
 * <img src="<?php echo $this->_url('imgcode'); ?>" />
 * </code>
 *
 * ������Ϊ��ʾ��֤��Ŀ�������д imgcode ������
 * <code>
 * function actionImgcode() {
 *     $imgcode =& FLEA::getSingleton('FLEA_Helper_ImgCode');
 *     $imgcode->image();
 * }
 * </code>
 *
 * ��󣬶����û��ύ�ı���������֤��
 * <code>
 * function actionSubmit() {
 *     $imgcode =& FLEA::getSingleton('FLEA_Helper_ImgCode');
 *     // �ٶ���֤���ڱ��е��ֶ����� imgcode
 *     if ($imgcode->check($_POST['imgcode'])) {
 *         // ��֤ͨ��
 *     }
 * }
 * </code>
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class FLEA_Helper_ImgCode
{
    /**
     * ���ɵ���֤��
     *
     * @var string
     */
    var $_code;

    /**
     * ��֤�����ʱ��
     *
     * @var string
     */
    var $_expired;

    /**
     * ��֤��ͼƬ�����ͣ�Ĭ��Ϊ jpeg��
     *
     * @var string
     */
    var $imagetype = 'jpeg';

    /**
     * ָʾ�Ƿ���������֤��ͼƬʱ�������е���֤��
     *
     * �������е���֤��������û��ڸ�����ͬ��ҳ�涼����һ�µ���֤�롣
     * ֻ�������֤��ʹ�ú����е���֤��Ż�ʧЧ��
     *
     * @var boolean
     */
    var $keepCode = false;

    /**
     * ���캯��
     */
    function FLEA_Helper_ImgCode()
    {
        @session_start();

        $this->_code = isset($_SESSION['IMGCODE']) ?
                $_SESSION['IMGCODE'] : '';
        $this->_expired = isset($_SESSION['IMGCODE_EXPIRED']) ?
                $_SESSION['IMGCODE_EXPIRED'] : 0;
    }

    /**
     * ���ͼ����֤���Ƿ���Ч
     *
     * @param string $code
     *
     * @return boolean
     */
    function check($code)
    {
        $time = time();
        if ($time >= $this->_expired || strtoupper($code) != strtoupper($this->_code)) {
            return false;
        }
        return true;
    }

    /**
     * ���ͼ����֤���Ƿ���Ч�����ִ�Сд��
     *
     * @param string $code
     *
     * @return boolean
     */
    function checkCaseSensitive($code)
    {
        $time = time();
        if ($time >= $this->_expired || $code != $this->_code) {
            return false;
        }
        return true;
    }

    /**
     * ��� session �е� imgcode �����Ϣ
     */
    function clear()
    {
        unset($_SESSION['IMGCODE']);
        unset($_SESSION['IMGCODE_EXPIRED']);
    }

    /**
     * ���� GD �������֤��ͼ��
     *
     * Ŀǰ $options ����֧������ѡ�
     * -  paddingLeft, paddingRight, paddingTop, paddingBottom
     * -  border, borderColor
     * -  font, color, bgcolor
     *
     * ��� font Ϊ 0-5����ʹ�� GD �����õ����塣
     * ���Ҫָ�������ļ����� font ѡ�����Ϊ�����ļ��ľ���·�������磺
     * <code>
     * $options = array('font' => '/var/www/example/myfont.gdf');
     * image($type, $length, $lefttime, $options);
     * </code>
     *
     * @param int $type ��֤��������ַ����ͣ�0 - ���֡�1 - ��ĸ������ֵ - ���ֺ���ĸ
     * @param int $length ��֤�볤��
     * @param int $leftime ��֤����Чʱ�䣨�룩
     * @param array $options ����ѡ�����ָ�����塢��Ⱥ͸߶ȵȲ���
     */
    function image($type = 0, $length = 4, $lefttime = 900, $options = null)
    {
        if ($this->keepCode && $this->_code != '') {
            $code = $this->_code;
        } else {
            // ������֤��
            switch ($type) {
            case 0:
                $seed = '0123456789';
                break;
            case 1:
                $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            default:
                $seed = '346789ABCDEFGHJKLMNPQRTUVWXYabcdefghjklmnpqrtuvwxy';
            }
            if ($length <= 0) { $length = 4; }
            $code = '';
            list($usec, $sec) = explode(" ", microtime());
            srand($sec + $usec * 100000);
            $len = strlen($seed) - 1;
            for ($i = 0; $i < $length; $i++) {
                $code .= substr($seed, rand(0, $len), 1);
            }
            $_SESSION['IMGCODE'] = $code;
        }
        $_SESSION['IMGCODE_EXPIRED'] = time() + $lefttime;

        // ����ѡ��
        $paddingLeft = isset($options['paddingLeft']) ?
                (int)$options['paddingLeft'] : 3;
        $paddingRight = isset($options['paddingRight']) ?
                (int)$options['paddingRight'] : 3;
        $paddingTop = isset($options['paddingTop']) ?
                (int)$options['paddingTop'] : 2;
        $paddingBottom = isset($options['paddingBottom']) ?
                (int)$options['paddingBottom'] : 2;
        $color = isset($options['color']) ? $options['color'] : '0xffffff';
        $bgcolor = isset($options['bgcolor']) ? $options['bgcolor'] : '0x666666';
        $border = isset($options['border']) ? (int)$options['border'] : 1;
        $bdColor = isset($options['borderColor']) ? $options['borderColor'] : '0x000000';

        // ȷ��Ҫʹ�õ�����
        if (!isset($options['font'])) {
            $font = 5;
        } else if (is_int($options['font'])) {
            $font = (int)$options['font'];
            if ($font < 0 || $font > 5) { $font = 5; }
        } else {
            $font = imageloadfont($options['font']);
        }

        // ȷ�������Ⱥ͸߶�
        $fontWidth = imagefontwidth($font);
        $fontHeight = imagefontheight($font);

        // ȷ��ͼ��Ŀ�Ⱥ͸߶�
        $width = $fontWidth * strlen($code) + $paddingLeft + $paddingRight +
                $border * 2 + 1;
        $height = $fontHeight + $paddingTop + $paddingBottom + $border * 2 + 1;

        // ����ͼ��
        $img = imagecreate($width, $height);

        // ���Ʊ߿�
        if ($border) {
            list($r, $g, $b) = $this->_hex2rgb($bdColor);
            $borderColor = imagecolorallocate($img, $r, $g, $b);
            imagefilledrectangle($img, 0, 0, $width, $height, $borderColor);
        }

        // ���Ʊ���
        list($r, $g, $b) = $this->_hex2rgb($bgcolor);
        $backgroundColor = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, $border, $border,
                $width - $border - 1, $height - $border - 1, $backgroundColor);

        // ��������
        list($r, $g, $b) = $this->_hex2rgb($color);
        $textColor = imagecolorallocate($img, $r, $g, $b);
        imagestring($img, $font, $paddingLeft + $border, $paddingTop + $border,
                $code, $textColor);

        // ���ͼ��
        switch (strtolower($this->imagetype)) {
        case 'png':
            header("Content-type: " . image_type_to_mime_type(IMAGETYPE_PNG));
            imagepng($img);
            break;
        case 'gif':
            header("Content-type: " . image_type_to_mime_type(IMAGETYPE_GIF));
            imagegif($img);
            break;
        case 'jpg':
        default:
            header("Content-type: " . image_type_to_mime_type(IMAGETYPE_JPEG));
            imagejpeg($img);
        }

        imagedestroy($img);
        unset($img);
    }

    /**
     * �� 16 ������ɫֵת��Ϊ rgb ֵ
     *
     * @param string $hex
     *
     * @return array
     */
    function _hex2rgb($color, $defualt = 'ffffff')
    {
        $color = strtolower($color);
        if (substr($color, 0, 2) == '0x') {
            $color = substr($color, 2);
        } elseif (substr($color, 0, 1) == '#') {
            $color = substr($color, 1);
        }
        $l = strlen($color);
        if ($l == 3) {
            $r = hexdec(substr($color, 0, 1));
            $g = hexdec(substr($color, 1, 1));
            $b = hexdec(substr($color, 2, 1));
            return array($r, $g, $b);
        } elseif ($l != 6) {
            $color = $defualt;
        }

        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        return array($r, $g, $b);
    }
}
