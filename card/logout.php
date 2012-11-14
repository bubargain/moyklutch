<?php
require('../Config/config.php');
require('common.php');
dbconnect();

    /* 
        会员退出操作
		Key = VSoftXinYue NetWork
		MacNumber = TS00001
		Userid =5433543543
		Userpointuse=150     (用户本次操作使用的积分数)
		Printcount=1;3;7;22   (打印列表)
		
		未绑定会员卡用户退出后上传信息如下：
		Key = VSoftXinYue NetWork
		MacNumber = TS00001
		Userid =NULL
		Printcount=1;3;7;22  (打印列表)
		
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
if(empty($_POST['Userid']))
{
	//exit("error Userid");
	$_POST['Userid'] = '0';
}
//通过机器号取出打印机位置ID
$query = $_SGLOBAL['db']->query("select id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "location where macno='$_POST[MacNumber]'");
$wz = $_SGLOBAL['db']->fetch_array($query);
//取出会员ID
$query = $_SGLOBAL['db']->query("select id,score from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "user where card_id='$_POST[Userid]'");
$user = $_SGLOBAL['db']->fetch_array($query);
	
//打印时间
$printdate = time();
//优惠券ID
$pids = explode(';',$_POST['Printcount']);
//判断是否注册会员
if($user===false || $_POST['Userid'] == 'NULL'){
  $user['id'] = 0;	
}
//记录打印量
$i=0;
$sql = "select id,trade_id from ". $_SCONFIG['dbDSN']['dbTablePrefix']."ticket where id in(".implode(',',$pids).")";
$temp = (array)$_SGLOBAL['db']->query($sql);
$trades = array();
while ($row =  mysql_fetch_array($temp[0])) {
	$trades[$row['id']] = $row['trade_id'];
}
$wz['id'] = empty($wz['id'])?0:$wz['id'];
foreach($pids as $pid)
{
	$temp = isset($trades[$pid])?$trades[$pid]:0;
	$sql = "insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket_statistic(ticket_id,trade_id,position_id,user_id,create_time,action_type,type) Values($pid,$temp,$wz[id],$user[id],$printdate,1,1)";
	$_SGLOBAL['db']->query($sql);
	$i++;
}
//更新卡打印限制打印数量加一
//$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card set limit_count=limit_count+".$i." where card_id='$_POST[Userid]'");

echo "Action=finish" . "\r\n";
?>