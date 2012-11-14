<?php
//用户登录处理
require('../Config/config.php');
require('common.php');
dbconnect();
/*
	上传信息格式如下:
	Key = VSoftXinYue NetWork
	Userid =5433543543
	Quan=54                   打印的优惠券的ID
	
	服务器返回的信息：
	许可打印：
	Action=OK
	禁止打印：
	Action=NO
	Error=1  1为金币不足，2为超出打印数量
	根据服务器指令决定是否打印	
*/
global $_SGLOBAL, $_SCONFIG;
if($_POST['Key']!=$_SCONFIG['Key'])
{
	exit("error Key");
}
if(empty($_POST['Userid']))
{
	exit("error Userid");
}
if(empty($_POST['Quan']))
{
	exit("error NoQuan");
}
//打印时间
$printdate = time();

//取出优惠券信息 
$pid = $_POST['Quan'];
$query = $_SGLOBAL['db']->query("select id,title,trade_id,score,money,num from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket where id = $pid");
$quan = $_SGLOBAL['db']->fetch_array($query);

//取出会员信息
$card_id = $_POST['Userid'];
$query = $_SGLOBAL['db']->query("select * from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "user where card_id = '$card_id'");
$user = $_SGLOBAL['db']->fetch_array($query);

//优惠券剩余可打印数,-1为不限量打印，数量为0禁止打印
if($quan['num']!=-1)
{
	//可打印数量为0
	if($quan['num']==0)
	{
		$return_values['Action'] = 'NO';
		$return_values['Error'] = '2';
	}
	else
	{
		//打印优惠券后的金币数	 
		$new_userpoint = $user['score'] - $quan['score'];
		
		//金币是否足够打印此优惠券
		if($new_userpoint >= 0)
		{
			//扣减金币
			$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "user set score=$new_userpoint where id = $user[id]");
			//优惠商家名称
			$query = $_SGLOBAL['db']->query("select title from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trade where id = $quan[trade_id]");
			$shop = $_SGLOBAL['db']->fetch_array($query);
			//记录金币日志
			$_SGLOBAL['db']->query("insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "pointlog (user_id,why,point,log_time) Values($user[id],'打印$shop[title]_$quan[title]',-$quan[score],$printdate)");
			//产生随机密码
			include_once('../Lib/function_common.php');
			//产生不重复密码
			while(true)
			{
				$quan_password = random(12,1);
				$query = $_SGLOBAL['db']->query("select password from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trademoney where password='$quan_password'");
				if($_SGLOBAL['db']->fetch_array($query) === false){
					break;
				}
			}
			//记录兑换日志
			$_SGLOBAL['db']->query("insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trademoney (trade_id,ticket_id,user_id,money,password,create_time) Values($quan[trade_id],$quan[id],$user[id],$quan[money],'$quan_password',$printdate)");
			
			//更新剩余券数量
			$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket set num=num-1 where id = $pid");
			$return_values['Action'] = 'OK';
			$return_values['Password'] = $quan_password;	
		}
		else
		{
			$return_values['Action'] = 'NO';
			$return_values['Error'] = '1';
		}
	}
}
else
{
	//打印优惠券后的金币数	 
	$new_userpoint = $user['score'] - $quan['score'];
	
	//金币是否足够打印此优惠券
	if($new_userpoint >= 0)
	{
		//扣减金币
		$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "user set score=$new_userpoint where id = $user[id]");
		//优惠商家名称
		$query = $_SGLOBAL['db']->query("select title from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trade where id = $quan[trade_id]");
		$shop = $_SGLOBAL['db']->fetch_array($query);
		//记录金币日志
		$_SGLOBAL['db']->query("insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "pointlog (user_id,why,point,log_time) Values($user[id],'打印$shop[title]_$quan[title]',-$quan[score],$printdate)");
		//产生随机密码
		include_once('../Lib/function_common.php');
		//产生不重复密码
		while(true)
		{
			$quan_password = random(10,1);
			$query = $_SGLOBAL['db']->query("select password from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trademoney where password='$quan_password'");
			if($_SGLOBAL['db']->fetch_array($query) === false){
				break;
			}
		}
		//记录兑换日志
		$_SGLOBAL['db']->query("insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trademoney (trade_id,ticket_id,user_id,money,password,create_time) Values($quan[trade_id],$quan[id],$user[id],$quan[money],'$quan_password',$printdate)");
		$return_values['Action'] = 'OK';
		$return_values['Password'] = $quan_password;	
	}
	else
	{
		$return_values['Action'] = 'NO';
		$return_values['Error'] = '1';
	}
}
make_return_value($return_values);
?>