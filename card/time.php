<?php
//用户登录处理
require('../Config/config.php');
require('common.php');
dbconnect();
/*
	上传信息格式如下:
	Key = VSoftXinYue NetWork
	
	服务器返回的信息：
	Time=2011-01-16 21:47:01 
*/
global $_SGLOBAL, $_SCONFIG;
if($_POST['Key']!=$_SCONFIG['Key'])
{
	exit("error Key");
}
//当前时间
date_default_timezone_set('Europe/Moscow');
$printdate = time();

echo date("Y-m-d H:i:s",$printdate) . "\r\n";
echo "Action=finish" . "\r\n";
?>