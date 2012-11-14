<?php
/**
 * 定义 class_image 类
*/

class class_image
{
    /**
     * GD 资源句柄
     *
     * @var resource
     */
    var $_handle = null;
    var $_fileext = null;

    /**
     * 构造函数
     * 开发者不能直接构造该类的实例，而是应该用 FLEA_Helper_Image::createFromFile()
     * 静态方法创建一个 FLEA_Helper_Image 类的实例。
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
     * 快速缩放图像到指定大小（质量较差）
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
     * 缩放图像到指定大小（质量较好，速度比 resize() 慢）
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
     * 调整图像大小，但不进行缩放操作
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

        // 根据 pos 属性来决定如何定位原始图片
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
     * 在保持图像长宽比的情况下将图像裁减到指定大小
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
            // 求缩放后的最大宽度和高度
            if ($sy * $ratio > $height) {
                $ratio = doubleval($height) / doubleval($sy);
            }
            $dx = $sx * $ratio;
            $dy = $sy * $ratio;

            // 根据 pos 属性来决定如何定位原始图片
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
            // 允许图像溢出
		    $vx = 0;
		    $vy = 0;
            if ($sy * $ratio < $height) {
                // 当按照比例缩放后的图像高度小于要求的高度时，只有放弃原始图像两边的部分内容
                $ratio = doubleval($sy) / doubleval($height);
                //原始图像左边的偏移量
                $vx = ($sx - $width * $ratio) / 2;
                $sx = $width * $ratio;
            } elseif ($sy * $ratio > $height) {
                // 当按照比例缩放后的图像高度大于要求的高度时，只有放弃原始图像底部的部分内容
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
     * 保存为 JPEG 文件
     *
     * @param string $filename
     * @param int $quality
     */
    function saveAsJpeg($filename, $quality = 80)
    {
        imagejpeg($this->_handle, $filename, $quality);
    }

    /**
     * 保存为 PNG 文件
     *
     * @param string $filename
     */
    function saveAsPng($filename)
    {
        imagepng($this->_handle, $filename);
    }

    /**
     * 保存为 GIF 文件
     *
     * @param string $filename
     */
    function saveAsGif($filename)
    {
		imagegif($this->_handle, $filename);
    }
    /**
     * 保存为 bmp 565 文件
     *
     * @param string $filename
     */
    function saveAsBmp($filename)
    {
		imagebmp($this->_handle, $filename, 16, 3);
    }

    /**
     * 保存为 HEX 文件
     *
     * @param string $filename
     */
    function saveAsHex($filename)
    {
        return imagehex($this->_handle, $filename);
    }

    /**
     * 销毁图像
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
     * 将十六进制表示的颜色值转换为 rgb
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

/**
 * 创建bmp格式图片
 *
 * @author: legend(legendsky@hotmail.com)
 * @link: http://www.ugia.cn/?p=96
 * @description: create Bitmap-File with GD library
 * @version: 0.1
 *
 * @param resource $im          图像资源
 * @param string   $filename    如果要另存为文件，请指定文件名，为空则直接在浏览器输出
 * @param integer  $bit         图像质量(1、4、8、16、24、32位)
 * @param integer  $compression 压缩方式，0为不压缩，1使用RLE8压缩算法进行压缩
 *
 * @return integer
 */
function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0)
{
    if (!in_array($bit, array(1, 4, 8, 16, 24, 32)))
    {
        $bit = 8;
    }
    else if ($bit == 32) // todo:32 bit
    {
        $bit = 24;
    }

    $bits = pow(2, $bit);
    
    // 调整调色板
    imagetruecolortopalette($im, true, $bits);
    $width  = imagesx($im);
    $height = imagesy($im);
    $colors_num = imagecolorstotal($im);
    
    if ($bit <= 8)
    {
        // 颜色索引
        $rgb_quad = '';
        for ($i = 0; $i < $colors_num; $i ++)
        {
            $colors = imagecolorsforindex($im, $i);
            $rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";
        }
        
        // 位图数据
        $bmp_data = '';

        // 非压缩
        if ($compression == 0 || $bit < 8)
        {
            if (!in_array($bit, array(1, 4, 8)))
            {
                $bit = 8;
            }

            $compression = 0;
            
            // 每行字节数必须为4的倍数，补齐。
            $extra = '';
            $padding = 4 - ceil($width / (8 / $bit)) % 4;
            if ($padding % 4 != 0)
            {
                $extra = str_repeat("\0", $padding);
            }
            
            for ($j = $height - 1; $j >= 0; $j --)
            {
                $i = 0;
                while ($i < $width)
                {
                    $bin = 0;
                    $limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;

                    for ($k = 8 - $bit; $k >= $limit; $k -= $bit)
                    {
                        $index = imagecolorat($im, $i, $j);
                        $bin |= $index << $k;
                        $i ++;
                    }

                    $bmp_data .= chr($bin);
                }
                
                $bmp_data .= $extra; 
            }
        }
        // RLE8 压缩
        else if ($compression == 1 && $bit == 8)
        {
            for ($j = $height - 1; $j >= 0; $j --)
            {
                $last_index = "\0";
                $same_num   = 0;
                for ($i = 0; $i <= $width; $i ++)
                {
                    $index = imagecolorat($im, $i, $j);
                    if ($index !== $last_index || $same_num > 255)
                    {
                        if ($same_num != 0)
                        {
                            $bmp_data .= chr($same_num) . chr($last_index);
                        }

                        $last_index = $index;
                        $same_num = 1;
                    }
                    else
                    {
                        $same_num ++;
                    }
                }

                $bmp_data .= "\0\0";
            }
            
            $bmp_data .= "\0\1";
        }

        $size_quad = strlen($rgb_quad);
        $size_data = strlen($bmp_data);
    }
    else
    {
        // 每行字节数必须为4的倍数，补齐。
        $rgb_quad = '';
        $extra = '';
        $padding = 4 - ($width * ($bit / 8)) % 4;
        if ($padding % 4 != 0)
        {
            $extra = str_repeat("\0", $padding);
        }

        // 位图数据
        $bmp_data = '';

        for ($j = $height - 1; $j >= 0; $j --)
        {
            for ($i = 0; $i < $width; $i ++)
            {
                $index  = imagecolorat($im, $i, $j);
                $colors = imagecolorsforindex($im, $index);
                
                if ($bit == 16)
                {
                    $bin = 0 << $bit;

                    $bin = (round($colors['red'] / 255*31)) << 11;
                    $bin |= (round($colors['green'] /255*63)) << 5;
                    $bin |= round($colors['blue'] /255*31);

                    $bmp_data .= pack("v", $bin);
                }
                else
                {
                    $bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
                }
                
                // todo: 32bit; 
            }

            $bmp_data .= $extra;
        }

        $size_quad = 0;
        $size_data = strlen($bmp_data);
        $colors_num = 0;
    }

    // 位图文件头
    $file_header = "BM" . pack("V3", 70 + $size_quad + $size_data, 0, 70 + $size_quad);

    // 位图信息头
    $info_header = pack("V3v2V*", 0x38, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0, 0xF800, 0x07E0, 0x001F, 0x0000);
    
    // 写入文件
    if ($filename != '')
    {
        $fp = fopen($filename, "wb");

        fwrite($fp, $file_header);
        fwrite($fp, $info_header);
        fwrite($fp, $rgb_quad);
        fwrite($fp, $bmp_data);
        fclose($fp);

        return 1;
    }
}
/**
* 创建hex格式图片
*
* @author: legend(legendsky@hotmail.com)
* @link: http://www.ugia.cn/?p=96
* @description: create Bitmap-File with GD library
* @version: 0.1
*
* @param resource $im          图像资源
* @param string   $filename    输出文件名
*
* @return integer
*/
function imagehex(&$im, $filename)
{
    
	//宽高
    $width  = imagesx($im);
    $height = imagesy($im);
	//低位宽
	$low_width = $width>255 ? chr($width-256) : chr($width);
	//高位宽
	$high_width = $width>255 ? chr(1) : chr(0);
	
    // 调整调色板
    imagetruecolortopalette($im, false, 2);
    $colors_num = imagecolorstotal($im);
    
	// 颜色索引
	$color0 = imagecolorsforindex($im, 0);
	$color1 = imagecolorsforindex($im, 1);
	// 图片数据
	$hex_data_top = pack("H*", '1B3100');
	$hex_data = '';
	$temp_hex_data = '';
	
	for ($j = 0; $j < $height; $j=$j +8)
	{
		//文件头
		$temp_hex_data= pack("H*", "0A1B4B") . $low_width . $high_width;
		$i = 0;
		while ($i < $width)
		{
			$bin = 0;

			for ($k = 7; $k >= 0; $k --)
			{
				if($j+(7-$k) <= ($height-1)) 
				{
					$index = imagecolorat($im, $i, $j+(7-$k));
					//判断索引那个颜色深，保证深点为1
					if($color0['blue']+$color0['green']+$color0['red'] < $color1['blue']+$color1['green']+$color1['red'])
					{
						$index = !$index;
					}

				}
				else
				{
					$index = 0;
				}
				//echo $index;
				$bin |= $index << $k;
			}
			$i ++;
			
			$temp_hex_data .= chr($bin);
		}
		$hex_data = $temp_hex_data . $hex_data;
	}
    $hex_data = $hex_data_top . $hex_data;
    // 写入文件
    if ($filename != '')
    {
        $fp = fopen($filename, "wb");
        fwrite($fp, $hex_data);
        fclose($fp);
  
        return 1;
    }else{
        $text_data = unpack('H*',$hex_data);
		return $text_data[1];
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
function freadbits($f, $count) {
global $CurrentBit, $SMode;
$Byte = freadbyte($f);
$LastCBit = $CurrentBit;
$CurrentBit += $count;
if ($CurrentBit == 8) {
   $CurrentBit = 0;
} else {
   fseek($f, ftell($f) - 1);
};
return RetBits($Byte, $LastCBit, $count);
};

function RetBits($byte, $start, $len) {
$bin = decbin8($byte);
$r = bindec(substr($bin, $start, $len));
return $r;

};
function decbin8($d) {
return decbinx($d, 8);
};
function decbinx($d, $n) {
$bin = decbin($d);
$sbin = strlen($bin);
for ($j = 0; $j < $n - $sbin; $j++)
   $bin = "0$bin";
return $bin;
};
