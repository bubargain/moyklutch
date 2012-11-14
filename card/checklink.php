<?php
//检查与主机的连接
require('../Config/config.php');
require('common.php');
dbconnect();
global $_SGLOBAL, $_SCONFIG;

if($_POST['Key']!=$_SCONFIG['Key'])
{
	//exit("error Key");
}
if(empty($_POST['MacNumber']))
{
	//exit("error MacNumber");
}
$time=time();
$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "location set link_time=$time where macno='$_POST[MacNumber]'");
//echo "fff\r\n";
//echo "gprsok\r\n";
echo "Action=finish" . "\r\n";
?>