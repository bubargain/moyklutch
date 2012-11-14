<?php
require('../Config/config.php');
require('common.php');
dbconnect();

    /*
		Key = VSoftXinYue NetWork
		MacNumber = TS00001
		Error =打印机缺纸
    */
global $_SGLOBAL, $_SCONFIG;
if($_POST['Key']!=$_SCONFIG['Key'])
{
	exit("error Key");
}
if(empty($_POST['MacNumber']))
{
	exit("error MacNumber");
}
if(empty($_POST['Error']))
{
	exit("error Error");
}
//记录打印机错误
//记录时间
$printdate = time();

//通过机器号取出打印机位置ID
$query = $_SGLOBAL['db']->query("select name from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "location where macno='$_POST[MacNumber]'");
$wz = $_SGLOBAL['db']->fetch_array($query);

//记录打印量
$sql = "insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "error (source,mac_id,error_content,level,log_time) Values ('$wz[name]','$_POST[MacNumber]','$_POST[Error]',1,$printdate)";
$_SGLOBAL['db']->query($sql);
echo "Action=finish" . "\r\n";
?>