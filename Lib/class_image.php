<?php
/**
 * ���� class_image ��
*/

class class_image
{
    /**
     * GD ��Դ���
     *
     * @var resource
     */
    var $_handle = null;
    var $_fileext = null;

    /**
     * ���캯��
     * �����߲���ֱ�ӹ�������ʵ��������Ӧ���� FLEA_Helper_Image::createFromFile()
     * ��̬��������һ�� FLEA_Helper_Image ���ʵ����
     *
     * @param resource $handle
     *
     * @return FLEA_Helper_Image
     */
    function class_image($filename, $fileext = null)
    {
        if (is_null($fileext))
        {
            $this->_fileext = pathinfo($filename, PATHINFO_EXTENSION);
        }
        else
        {
            $this->_fileext = $fileext;
        }
        $this->_fileext = strtolower($this->_fileext);
        $ext2functions = array(
            'jpg' => 'imagecreatefromjpeg',
            'jpeg'=> 'imagecreatefromjpeg',
            'png' => 'imagecreatefrompng',
            'gif' => 'imagecreatefromgif',
            'bmp' => 'imagecreatefrombmp',
        );
        if (!isset($ext2functions[$this->_fileext]))
        {
            return false;
        }

        $this->_handle = $ext2functions[$this->_fileext]($filename);
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
		    $vx = 0;
		    $vy = 0;
            if ($sy * $ratio < $height) {
                // �����ձ������ź��ͼ��߶�С��Ҫ��ĸ߶�ʱ��ֻ�з���ԭʼͼ�����ߵĲ�������
                $ratio = doubleval($sy) / doubleval($height);
                //ԭʼͼ����ߵ�ƫ����
                $vx = ($sx - $width * $ratio) / 2;
                $sx = $width * $ratio;
            } elseif ($sy * $ratio > $height) {
                // �����ձ������ź��ͼ��߶ȴ���Ҫ��ĸ߶�ʱ��ֻ�з���ԭʼͼ��ײ��Ĳ�������
                $ratio = doubleval($sx) / doubleval($width);
                $vy = ($sy - $height * $ratio) *0.1;
                $sy = $height * $ratio;
            }

            $args = array($dest, $this->_handle, 0, 0, $vx, $vy, $width, $height, $sx, $sy);
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

    function display()
    {
         $ext2functions = array(
            'jpg' => 'imagejpeg',
            'jpeg'=> 'imagejpeg',
            'png' => 'imagepng',
            'gif' => 'imagegif',
            'bmp' => 'imagepng',
        );
        if (!isset($ext2functions[$this->_fileext]))
        {
            return false;
        }

        $ext2functions[$this->_fileext]($this->_handle);
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
function imagecreatefrombmp($file)
{
        global  $CurrentBit, $echoMode;

        $f=fopen($file,"r");
        $Header=fread($f,2);

        if($Header=="BM")
        {
                $Size=freaddword($f);
                $Reserved1=freadword($f);
                $Reserved2=freadword($f);
                $FirstByteOfImage=freaddword($f);

                $SizeBITMAPINFOHEADER=freaddword($f);
                $Width=freaddword($f);
                $Height=freaddword($f);
                $biPlanes=freadword($f);
                $biBitCount=freadword($f);
                $RLECompression=freaddword($f);
                $WidthxHeight=freaddword($f);
                $biXPelsPerMeter=freaddword($f);
                $biYPelsPerMeter=freaddword($f);
                $NumberOfPalettesUsed=freaddword($f);
                $NumberOfImportantColors=freaddword($f);

                if($biBitCount<24)
                {
                        $img=imagecreate($Width,$Height);
                        $Colors=pow(2,$biBitCount);
                        for($p=0;$p<$Colors;$p++)
                        {
                                $B=freadbyte($f);
                                $G=freadbyte($f);
                                $R=freadbyte($f);
                                $Reserved=freadbyte($f);
                                $Palette[]=imagecolorallocate($img,$R,$G,$B);
                        };




                        if($RLECompression==0)
                        {
                                $Zbytek=(4-ceil(($Width/(8/$biBitCount)))%4)%4;

                                for($y=$Height-1;$y>=0;$y--)
                                {
                                        $CurrentBit=0;
                                        for($x=0;$x<$Width;$x++)
                                        {
                                                $C=freadbits($f,$biBitCount);
                                                imagesetpixel($img,$x,$y,$Palette[$C]);
                                        };
                                        if($CurrentBit!=0) {freadbyte($f);};
                                        for($g=0;$g<$Zbytek;$g++)
                                        freadbyte($f);
                                };

                        };
                };


                if($RLECompression==1) //$BI_RLE8
                {
                        $y=$Height;

                        $pocetb=0;

                        while(true)
                        {
                                $y--;
                                $prefix=freadbyte($f);
                                $suffix=freadbyte($f);
                                $pocetb+=2;

                                $echoit=false;

                                if($echoit)echo "Prefix: $prefix Suffix: $suffix<BR>";
                                if(($prefix==0)and($suffix==1)) break;
                                if(feof($f)) break;

                                while(!(($prefix==0)and($suffix==0)))
                                {
                                        if($prefix==0)
                                        {
                                                $pocet=$suffix;
                                                $Data.=fread($f,$pocet);
                                                $pocetb+=$pocet;
                                                if($pocetb%2==1) {freadbyte($f); $pocetb++;};
                                        };
                                        if($prefix>0)
                                        {
                                                $pocet=$prefix;
                                                for($r=0;$r<$pocet;$r++)
                                                $Data.=chr($suffix);
                                        };
                                        $prefix=freadbyte($f);
                                        $suffix=freadbyte($f);
                                        $pocetb+=2;
                                        if($echoit) echo "Prefix: $prefix Suffix: $suffix<BR>";
                                };

                                for($x=0;$x<strlen($Data);$x++)
                                {
                                        imagesetpixel($img,$x,$y,$Palette[ord($Data[$x])]);
                                };
                                $Data="";

                        };

                };


                if($RLECompression==2) //$BI_RLE4
                {
                        $y=$Height;
                        $pocetb=0;

                        /*while(!feof($f))
                        echo freadbyte($f)."_".freadbyte($f)."<BR>";*/
                        while(true)
                        {
                                //break;
                                $y--;
                                $prefix=freadbyte($f);
                                $suffix=freadbyte($f);
                                $pocetb+=2;

                                $echoit=false;

                                if($echoit)echo "Prefix: $prefix Suffix: $suffix<BR>";
                                if(($prefix==0)and($suffix==1)) break;
                                if(feof($f)) break;

                                while(!(($prefix==0)and($suffix==0)))
                                {
                                        if($prefix==0)
                                        {
                                                $pocet=$suffix;

                                                $CurrentBit=0;
                                                for($h=0;$h<$pocet;$h++)
                                                $Data.=chr(freadbits($f,4));
                                                if($CurrentBit!=0) freadbits($f,4);
                                                $pocetb+=ceil(($pocet/2));
                                                if($pocetb%2==1) {freadbyte($f); $pocetb++;};
                                        };
                                        if($prefix>0)
                                        {
                                                $pocet=$prefix;
                                                $i=0;
                                                for($r=0;$r<$pocet;$r++)
                                                {
                                                        if($i%2==0)
                                                        {
                                                                $Data.=chr($suffix%16);
                                                        }
                                                        else
                                                        {
                                                                $Data.=chr(floor($suffix/16));
                                                        };
                                                        $i++;
                                                };
                                        };
                                        $prefix=freadbyte($f);
                                        $suffix=freadbyte($f);
                                        $pocetb+=2;
                                        if($echoit) echo "Prefix: $prefix Suffix: $suffix<BR>";
                                };

                                for($x=0;$x<strlen($Data);$x++)
                                {
                                        imagesetpixel($img,$x,$y,$Palette[ord($Data[$x])]);
                                };
                                $Data="";

                        };

                };


                if($biBitCount==24)
                {
                        $img=imagecreatetruecolor($Width,$Height);
                        $Zbytek=$Width%4;

                        for($y=$Height-1;$y>=0;$y--)
                        {
                                for($x=0;$x<$Width;$x++)
                                {
                                        $B=freadbyte($f);
                                        $G=freadbyte($f);
                                        $R=freadbyte($f);
                                        $color=imagecolorexact($img,$R,$G,$B);
                                        if($color==-1) $color=imagecolorallocate($img,$R,$G,$B);
                                        imagesetpixel($img,$x,$y,$color);
                                }
                                for($z=0;$z<$Zbytek;$z++)
                                freadbyte($f);
                        };
                };
                return $img;

        };


        fclose($f);


};

function freadbyte($f)
{
        return ord(fread($f,1));
};

function freadword($f)
{
        $b1=freadbyte($f);
        $b2=freadbyte($f);
        return $b2*256+$b1;
};

function freaddword($f)
{
        $b1=freadword($f);
        $b2=freadword($f);
        return $b2*65536+$b1;
};
