<?php
require('../../Config/config_card.php');
require('../../Lib/function_common.php');
include_once('../class_image.php');
$height = $_SCONFIG['gif_height'];
$width = 400;
$top = 0;
$left = 0;
$fanse = 0;
$base_dir = '../image/prev/';
$prev_dir = dirname(__FILE__) . '/' . $base_dir;
include_once('simple_html_dom.php');
$ffid= md5(time().rand(1,100000));
$html = str_get_html($_POST['htmlData']);
//$html = file_get_html('coupon.html');
//创建背景图
$im = ImageCreateTrueColor($width, $height);

//分配颜色
$white = ImageColorAllocate ($im, 255, 255, 255);
$black = ImageColorAllocate ($im, 0, 0, 0);

//白色填充
ImageFill($im, 0, 0, $white);

//循环解析P标签
foreach($html->find('p') as $p) {
	//解析图片
	if($p->first_child() && $p->first_child()->tag == 'img'){
		$temp_im = @imagecreatefromgif($p->first_child()->src); 
		list($src_width, $src_height, $src_type, $src_attr) = getimagesize($p->first_child()->src);
		$dst_width = ($p->first_child()->width) ? ($p->first_child()->width) : $src_width;
		$dst_height = ($p->first_child()->height) ? ($p->first_child()->height) : $src_height;
		
		//对齐方式
		if($p->align=='center'){
			$left = floor((384 - $dst_width)/2);
		}else{
			if($p->align=='right'){
				$left = 384 - $dst_width-1;
			}
			else{
				$left = 0;
			}
		}
		
		//margin
		if($p->first_child()->style){
			$temp_margin = explode( ' ' , str_replace('margin:','',$p->first_child()->style));
			if(count($temp_margin)==1){
				$margin = array();
				$margin['top'] = intval( $temp_margin[0] );
				$margin['right'] = intval( $temp_margin[0] );
				$margin['bottom'] = intval( $temp_margin[0] );
				$margin['left'] = intval( $temp_margin[0] );
			}
			if(count($temp_margin)==2){
				$margin = array();
				$margin['top'] = intval( $temp_margin[0] );
				$margin['right'] = intval( $temp_margin[1] );
				$margin['bottom'] = intval( $temp_margin[0] );
				$margin['left'] = intval( $temp_margin[1] );
			}
			if(count($temp_margin)==3){
				$margin = array();
				$margin['top'] = intval( $temp_margin[0] );
				$margin['right'] = intval( $temp_margin[1] );
				$margin['bottom'] = intval( $temp_margin[2] );
				$margin['left'] = intval( $temp_margin[1] );
			}
			if(count($temp_margin)==4){
				$margin = array();
				$margin['top'] = intval( $temp_margin[0] );
				$margin['right'] = intval( $temp_margin[1] );
				$margin['bottom'] = intval( $temp_margin[2] );
				$margin['left'] = intval( $temp_margin[3] );
			}
		}else{
			$margin = array();
			$margin['top'] = 0;
			$margin['right'] = 0;
			$margin['bottom'] = 0;
			$margin['left'] = 0;
		}
		
		imagecopyresized ( $im , $temp_im  , $left + $margin['left'] , $top + $margin['top'] , 0 , 0, $dst_width, $dst_height , $src_width , $src_height  );
		$top =  $top + $margin['top'] + $dst_height + $margin['bottom'];
	}else{
		//绘制字符串：
		$temp = str_replace('&nbsp;',' ',$p->plaintext);
		while ($temp!=''){
			$fontsize = 18;
			//中文字体
			if(strstr($p->class,'enfont')==false){
				if(strstr($p->class,'cnfont1')){
					//点数
					$fontsize = 18;
				}
				if(strstr($p->class,'cnfont2')){
					//点数
					$fontsize = 36;
				}
				if(strstr($p->class,'cnfont3')){
					//点数
					$fontsize = 54;
				}
				//截取字符串
				$out_str = explode('| |',getstr($temp,floor(32*18/$fontsize),'utf-8'));
				$temp = substr($temp,strlen($out_str[0]));
				//行高
				if($p->style){
					$line_height = intval(str_replace('line-height:','',$p->style))-$fontsize/3*4;
				}else{
					$line_height = 0;
				}
				//对齐方式
				if($p->align=='center'){
					$left = floor((384 - $out_str[1] * $fontsize/3*2)/2);
				}else{
					if($p->align=='right'){
						$left = 384 - $out_str[1] * $fontsize/3*2-1;
					}
					else{
						$left = 0;
					}
				}
				if(strstr($p->class,'fanse')==false){
					ImageTTFText($im,$fontsize,0,$left,$top+floor($fontsize/3*4*0.83)+floor($line_height/2),$black,"simsun.ttc",$out_str[0]); 
					$fanse = 0;
				}else{
					if($fanse == 0){
						imagerectangle($im,0,$top,383,$top+$fontsize/3*4+12,$black);
						$top+=6;
					}else{
						imagerectangle($im,0,$top,383,$top+$fontsize/3*4+6,$black);
					}
					imagefill($im,1,$top+1,$black);
					ImageTTFText($im,$fontsize,0,$left,$top+floor($fontsize/3*4*0.83),$white,"simsun.ttc",$out_str[0]); 
					$top+=6;
					$fanse = 1;
				}
			}else{
				if(strstr($p->class,'enfont1')){
					//点数
					$fontsize = 6;
				}
				if(strstr($p->class,'enfont2')){
					//点数
					$fontsize = 12;
				}
				if(strstr($p->class,'enfont3')){
					//点数
					$fontsize = 18;
				}
				//截取字符串
				$out_str = explode('| |',getstr($temp,floor(32*18/$fontsize),'utf-8'));
				$temp = substr($temp,strlen($out_str[0]));
				//行高
				if($p->style){
					$line_height = intval(str_replace('line-height:','',$p->style))-$fontsize/3*4;
				}else{
					$line_height = 0;
				}
				//对齐方式
				if($p->align=='center'){
					$left = floor((384 - $out_str[1] * $fontsize)/2);
				}else{
					if($p->align=='right'){
						$left = 384 - $out_str[1] * $fontsize-1;
					}
					else{
						$left = 0;
					}
				}
				if(strstr($p->class,'fanse')==false){
					for($i=0;$i<=$out_str[1];$i++){
						ImageTTFText($im,$fontsize,0,$left,$top+floor($fontsize/3*4*0.83)+floor($line_height/2),$black,"cour.ttf",substr($out_str[0],$i,1)); 
						$left+=$fontsize;
					}
						$fanse = 0;
				}else{
					if($fanse == 0){
						imagerectangle($im,0,$top,383,$top+$fontsize/3*4+12,$black);
						$top+=6;
					}else{
						imagerectangle($im,0,$top,383,$top+$fontsize/3*4+6,$black);
					}
					imagefill($im,1,$top+1,$black);
					for($i=0;$i<=$out_str[1];$i++){
						//y为极限位置，近似0.83
						ImageTTFText($im,$fontsize,0,$left,$top+floor($fontsize/3*4*0.83),$white,"cour.ttf",substr($out_str[0],$i,1)); 
						$left+=$fontsize;
					}
					$top+=6;
					$fanse = 1;
				}
			}
			$top += $fontsize/3*4+$line_height;
		}
	}
}
if(count($html->find('p')) > 0){
	//转成黑白两色
	imagetruecolortopalette($im, false, 2);
	$color0=imagecolorsforindex($im,0);
	$color1=imagecolorsforindex($im,1);
	if($color0["red"]+$color0["green"]+$color0["blue"]<$color1["red"]+$color1["green"]+$color1["blue"]){
		imagecolorset($im,0,0,0,0);
		imagecolorset($im,1,255,255,255);
	}else{
		imagecolorset($im,1,0,0,0);
		imagecolorset($im,0,255,255,255);
	}
}
//输出图像，定义头
Header ('Content-type: image/gif');
//将图像发送至浏览器
//ImageGif($im);
// 生成唯一的文件名（重复的可能性极小）
//$id = md5(time() . rand(1,100000));
//保存预览图像
imagegif($im, $prev_dir . "$ffid.gif");
//清除资源
ImageDestroy($im);
//输出文件名
echo("$ffid.gif");
?>