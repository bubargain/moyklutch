<?PHP
include_once('class_image.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
<?PHP
$base_dir = 'image/';
$upload_dir = dirname(__FILE__) . '/' . $base_dir;

$allowExts = 'jpg,png,gif';
$maxSize = 15000 * 1024;

include_once('class_fileUploader.php');
$uploader =& new class_fileUploader();
$files =& $uploader->getFiles();
foreach ($files as $file) {
	if (!$file->check($allowExts, $maxSize)) {
        // 上传的文件类型不符或者超过了大小限制。
        return false;
    }
    // 生成唯一的文件名（重复的可能性极小）
    $id = md5(time() . $file->getFilename() . $file->getSize() . $file->getTmpName());
    $filename = $id . '.' . strtolower($file->getExt());
    $file->move($base_dir  . 'source/' . $filename);
	
	$img =& new class_image($base_dir  . 'source/' . $filename);
	$width = imagesx($img->_handle);
	$height = imagesy($img->_handle);
	if($width>384){
		$newheight = floor(384/$width*$height);
		$img->resize(384,$newheight);
	}
	$img->saveAsGif($upload_dir . $id . ".gif");
}

//if(!empty($_POST['file']))
//{
//}
$contentid=$_REQUEST["id"];
$fso  = opendir($base_dir);
while($flist=readdir($fso)){
	if($flist!='.'&&$flist!='..'&&$flist!='Thumbs.db'&&$flist!='source'&&$flist!='prev'){
		echo '<div style="float:left; border:#eee solid 1px; padding:5px; margin:5px; overflow:hidden;"><img src="'. $base_dir . $flist .'" onclick="javascript:opener.KE.plugin.image.insert(\''.$contentid.'\', this.src);window.close();" width="180" height="110" style="cursor:pointer;" /></div>';
	}
}
closedir($fso)
?>
<div style="clear:left;">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <input type="file" name="file" />
  <input type="submit" name="Submit" value="提交" />
</form>
</div>
</body>
</html>


