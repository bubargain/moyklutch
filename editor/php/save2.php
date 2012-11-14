<?php
//header("Content-type: application/octet-stream");
//header("Content-length: " . strlen($_POST['htmlData']) . "");
//header("Content-Disposition: attachment; filename=coupon.html");
//echo $_POST['htmlData'];
         //  $coupon = "coupon4.html"; 
           //$cfp = fopen($coupon,'wb'); 
           //fwrite($cfp,$_POST['htmlData']); 
           //fclose($cfp);
?>

<?php
require('../../Config/config_card.php');
require('../../Lib/function_common.php');
include_once('simple_html_dom.php');
include_once('../class_image.php');
$html = str_get_html($_POST['content']);
$id=$_POST['tid'];
$ffid=$id;
$aa = asc2bin($html);
$content_2=pack('H*', $aa);
$str="../../img/printimage/$id.zip";
$handle = fopen ("$str", "wb");
fwrite($handle, $content_2);
fclose($handle);

function asc2bin ($html) {
	$data = "";
	$setting = '';
	//默认值
	$align = 'left'; //1B 61 ====   00=left  01=center   02=right
	$zoom = '1';  //1B 57 01
	$font = 'cnfont';  //汉字1C 26 英文1C 2E
	$line_height = 0; //打印机默认3  1B 31 00
	$fanse = 0; //取消反白 1D 42 00 反白 1D 42 01
	$echo_str='';
	$num=0;
	//需要反过来解析设置
	foreach(array_reverse($html->find('p')) as $p) {
		//设置值
		$temp_setting='';
		//反色打印
		if(strstr($p->class,'fanse')!=false){
			if($fanse== 0){
				//反色首行
				$temp_setting = '1D42010a1b57011c2e20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020';
				$font ='enfont';
				$zoom = '1';
				$fanse = 1;
			}
		}else{
			if($fanse!= 0){
				$fanse= 0;
				$temp_setting = '1D4200';
			}
		}
		//对齐方式
		if($p->align){
			if($p->align!= $align){
				$align = $p->align;
				if($align=='left'){
					$temp_setting .= '1B6100';
				}
				if($align=='center'){
					$temp_setting .= '1B6101';
				}
				if($align=='right'){
					$temp_setting .= '1B6102';
				}
			}
		}else{
			if($align!='left'){
				$temp_setting .= '1B6100';
				$align = 'left';
			}
		}
		if($p->first_child() && $p->first_child()->tag == 'img'){
			if($font!='cnfont'){
				$font='cnfont';
				$temp_setting .= '1C26';
			}
			if($zoom !=1){
				$zoom = 1;
				$temp_setting .= '1B5701';
			}
			if($line_height!=0){
				$line_height = 0;
				//设置行高
				$temp_lineheight[] = $line_height;
			}
		}else{
			//字体
			if(strstr($p->class,'font')!=false){
				if(strstr($p->class,$font)==false){
					if($font=='cnfont'){
						$font='enfont';
						$temp_setting .= '1C2E';
					}else{
						$font='cnfont';
						$temp_setting .= '1C26';
					}
				}
			}else{
				if($font != 'cnfont'){
					$temp_setting .= '1C26';
					$font = 'cnfont';
				}
			}
			//字体放大
			if(strstr($p->class,'font')!=false){
				if(strstr($p->class,$zoom)==false){
					if(strstr($p->class,'font1')!=false){
						$zoom= '1';
						$temp_setting .= '1B5701';
					}
					if(strstr($p->class,'font2')!=false){
						$zoom= '2';
						$temp_setting .= '1B5702';
					}
					if(strstr($p->class,'font3')!=false){
						$zoom= '3';
						$temp_setting .= '1B5703';
					}
				}
			}else{
				if($zoom != '1'){
					$temp_setting .= '1B5701';
					$zoom = '1';
				}
			}
			//字体大小
			$font_size = ($font == 'cnfont') ? (12*$zoom) : (6*$zoom);
			//行高
			if($p->style){
				if($line_height != intval(str_replace('line-height:','',$p->style)) - (($font == 'cnfont') ? $font_size*2 : $font_size/6*8)){
					$line_height = intval(str_replace('line-height:','',$p->style)) - (($font == 'cnfont') ? $font_size*2 : $font_size/6*8);
				}
			}else{
				if($line_height != 0){
					$line_height = 0;
				}
			}
			//字符串：
			$temp = str_replace('&nbsp;',' ',$p->plaintext);
			
			//截断文字
			while ($temp!=''){
				$out_str = explode('| |',getstr($temp,floor(384/$font_size),'utf-8'));
				$temp = substr($temp,strlen($out_str[0]));
				//设置行高
				$temp_lineheight[] = $line_height;
			}
			
		}
		//反色打印二次修正 修正输出空行后的设置变化
		if(strstr($p->class,'fanse')!=false){
			$font ='enfont';
			$zoom = '1';
		}
		//设置值
		$setting[] = $temp_setting;
		$fontsize[] = $font_size;
		$fanses[] = $fanse;
		$aligns[] = $align;
		$fonts[] = $font;
		$zooms[] = $zoom;
		
	}
	//修正行高数据
	$line_height = 0;
	foreach($temp_lineheight as $height) {
		$lineheight[] = floor(($line_height + $height)/2);
		$line_height = $height;
	}
	//设置字符串
	$line_height = 0;
	foreach($lineheight as $height) {
		if($line_height != $height){
			$lineheight_str[] = '1B31' . ((strlen(dechex($height))==2) ? dechex($height) : ('0' . dechex($height)));
			$line_height = $height;
		}else{
			$lineheight_str[] ='';
		}
	}
	//补空行
	$lineheight_str[] ='';
	//恢复正向排列
	$setting = array_reverse($setting);
	$lineheight_str = array_reverse($lineheight_str);
	$fontsize = array_reverse($fontsize);
	$fanses = array_reverse($fanses);
	$aligns = array_reverse($aligns);
    //var_dump($setting);
    //var_dump($fontsize);
    //var_dump($fanses);
    //var_dump($lineheight_str);
    //var_dump($aligns);
	
	//解析文字
	foreach($html->find('p') as $p) {
		if($p->first_child() && $p->first_child()->tag == 'img'){
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
			$margin_setting_top = '';
			$margin_setting_end = '';
			if($margin['left']!=0){
				$margin['left'] = round($margin['left']/6);
				$margin_setting_top .= '1B6C' . ((strlen(dechex($margin['left']))==2) ? dechex($margin['left']) : ('0' . dechex($margin['left'])));
				$margin_setting_end .='1B6C00';
			}
			if($margin['top']!=0){
				$margin_setting_end .= '1B4A' . ((strlen(dechex($margin['top']))==2) ? dechex($margin['top']) : ('0' . dechex($margin['top'])));
			}
			if($margin['bottom']!=0){
				$margin_setting_top .= '1B4A' . ((strlen(dechex($margin['bottom']))==2) ? dechex($margin['bottom']) : ('0' . dechex($margin['bottom'])));
			}
			/*if($margin['right']!=0){
				$margin['right'] = round($margin['right']/6);
				$margin_setting_top .= '1B51' . ((strlen(dechex($margin['right']))==2) ? dechex($margin['right']) : ('0' . dechex($margin['right'])));
				$margin_setting_end .='1B5100';
			}*/
			
			$img =new class_image($p->first_child()->src);
			$data[] =$lineheight_str[0] . $margin_setting_top . $img->saveAsHex('') . $margin_setting_end;
			array_shift($lineheight_str);
		}else{
			//字符串：
			$temp = str_replace('&nbsp;',' ',$p->plaintext);
			
			//截断文字
			while ($temp!=''){
				$out_str = explode('| |',getstr($temp,floor(384/$fontsize[0]),'utf-8'));
				$temp = substr($temp,strlen($out_str[0]));
				$out_str[0] = iconv( "UTF-8", "gb2312//IGNORE",$out_str[0]);
				//修正反色
				if($fanses[0] == 1){
					if($aligns[0]=='left'){
						//左
						while(strlen($out_str[0])<floor(384/$fontsize[0])){
							$out_str[0].= ' ';
						}
					}
					if($aligns[0]=='right'){
						//右
						while(strlen($out_str[0])<floor(384/$fontsize[0])){
							$out_str[0]= ' ' . $out_str[0];
						}
					}
					if($aligns[0]=='center'){
						//中
						while(strlen($out_str[0])<floor(384/$fontsize[0])){
							$out_str[0]= ' ' . $out_str[0];
							if(strlen($out_str[0])<floor(384/$fontsize[0])){
								$out_str[0].= ' ';
							}
						}
					}
					
				}else{
					$fanse= 0;
				}

				$len = strlen($out_str[0]); 
				$temp_hex = '';
				for ($i=0; $i<$len; $i++){
					$temp_hex .= sprintf("%02x",ord(substr($out_str[0],$i,1))); 
				}
				//补行
				if($fanses[0] == 1){
					$data[] =$lineheight_str[0]. '0a1b57011c2e20202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020';
					$data[] = '0a' . $temp_hex;
				}else{
					$data[] = $lineheight_str[0]. '0a' . $temp_hex;
				}

				array_shift($lineheight_str);
			}
			
		}
		//调用设置值
		$data[] = $setting[0];
		//删除已设置数组元素
		array_shift($setting);
		array_shift($fontsize);
		array_shift($fanses);
		array_shift($aligns);
	}
	//var_dump($data);
	$data = array_reverse($data);
	$data = implode('',$data);
	return '1b61001b57011c261b31001d4200'.$data.'0d0d0d0d0d0d0d';
}

///////////////生成gif文件，文件名：content+id.gif

$height = $_SCONFIG['gif_height'];
$width = 384;
$top = 0;
$left = 0;
$fanse = 0;
$base_dir = '../../img/gif/';
$prev_dir = dirname(__FILE__) . '/' . $base_dir;
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
//Header ('Content-type: image/gif');
//将图像发送至浏览器
//ImageGif($im);
// 生成唯一的文件名（重复的可能性极小）
//$id = md5(time() . rand(1,100000));
//保存预览图像
imagegif($im, $prev_dir . "$ffid.gif");
//清除资源
ImageDestroy($im);

?>
<?php
$html = $_POST['content'];
$id=$_POST['tid'];
define('THINK_PATH', './ThinkPHP');
define('APP_NAME', '');
require('../../App/Conf/config.php');
	global $db;
	include_once('class_mysql.php');
	if(empty($db)) 
	{
		$db = new dbstuff;
		$db->charset = 'utf8';
		$db->connect($array['DB_HOST'],$array['DB_USER'], $array['DB_PWD'],$array['DB_NAME']);
	}
global $db;
$time=time();
$db->query("update " . $array['DB_PREFIX'] . "ticket set html='$html',update_time=$time where id=$id");

echo ("<script>if (window.confirm('OK！')){ location.href('../../index.php/Ticket/index') }else{location.href('../../index.php/Ticket/index')}</script>");
?>