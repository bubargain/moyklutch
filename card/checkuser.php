<?php
//用户登录处理
require('../Config/config.php');
require('common.php');
    /*
        处理单个用户函数
        UserCheckOk:     1表示用户存在,0表示不存在)
        UserPoint:       用户积分
        CouponCount:     优惠券数量
        CouponID:        优惠券id，注意,最后一个图片22后面必须加;，除了这行以外,其他行后面不加;
    */
dbconnect();
global $_SGLOBAL, $_SCONFIG;
$user = $yhj = $row = $return_values = array();
$juanstr = '';


//**************************************************
//Need to check the key is belonged to our company
//where does the $Post['Key'] come from
////////////////////////////////////////////////*/

if($_POST['Key']!=$_SCONFIG['Key']){
	exit("error Key");
}

if(empty($_POST['Userid'])){
	exit("error Userid");
}

//exit('user='.$_POST['Userid']."\r\n".'key='.$_POST['Key']."\r\n");
$query = $_SGLOBAL['db']->query("select id,score,ticket_id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "user where card_id=".$_POST['Userid']);
$user = $_SGLOBAL['db']->fetch_array($query);


//检查卡的打印限制时间与打印数量****
$query = $_SGLOBAL['db']->query("select * from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card where card_id='$_POST[Userid]'");
$card = $_SGLOBAL['db']->fetch_array($query);



if($card!=false){
	//$totaltime=$_SCONFIG['limit_time']*3600+$card['limit_time'];
	//if($totaltime<time()){//如果超出时间范围，则更新最后使用时间和，限制开始时间，和打印数量置0
		
	//$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card set use_time=".time().",limit_time=".time().",limit_count=0 where card_id='$card[card_id]'");
	//}else{
		//更新最后使用时间
		$new_use_count = $card['use_count']+1;
		$updateoper=$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card set use_time=".time().", use_count=".$new_use_count." where card_id='$card[card_id]'");
	//	if($card['limit_count']>=$_SCONFIG['limit_count']){ //如果数量超出限制
	//	    exit("打印数量已超出限制");
	//		}
	
	//test output
		$fp = fopen('./data.txt',"a");
		fwrite($fp,"[record]: ".$card['card_id']." ; [new_use_count]=".$new_use_count.";;;; \n");
		fclose($fp);
		

	//}
}
//****
if($_SCONFIG['card_mode'] == 2){
	$query = $_SGLOBAL['db']->query("select card_id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card where card_id='$_POST[Userid]' and type_id=0");
	$card = $_SGLOBAL['db']->fetch_array($query);
	if($card === false && $user === false){
		$return_values['UserCheckOk'] = 2;
		make_return_value($return_values);
	}
}

//是否绑定会员卡 (whether bond to VIP card)

 
 
if($user === false){
	$query = $_SGLOBAL['db']->query("select card_id,password,create_time from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card where card_id='$_POST[Userid]'");
	$card = $_SGLOBAL['db']->fetch_array($query);
	//当前时间
	$create_time=time();
	//密码在$_SCONFIG['password_expire']天后过期,清理过期密码
	//if($card != false && $card['create_time']+$_SCONFIG['password_expire']*24*60*60 < $create_time){
		//$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card set password='' where card_id='$card[card_id]'");
	//}
	//没有产生过绑定密码或者密码已过期
	if($card === false||$card['create_time']+$_SCONFIG['password_expire']*24*60*60 < $create_time){
		//产生随机密码
		include_once('../Lib/function_common.php');
		//产生不重复密码
		while(true){
			$card_password = random(8);
			$query = $_SGLOBAL['db']->query("select password from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card where password='$card_password'");
			if($_SGLOBAL['db']->fetch_array($query) === false){
				break;
			}
		}
		// this is the first time to use this card , insert an new record
		if($card === false){
           $_SGLOBAL['db']->query("insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card (card_id,password,create_time,type_id) Values('$_POST[Userid]','$card_password',$create_time,1)");
		}else{
			//if($card['type_id']==0){
		    $_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card set password='$card_password',create_time=$create_time where card_id='$_POST[Userid]'");
			//}else{
			//$_SGLOBAL['db']->query("delete from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "card where card_id='$_POST[Userid]'");
			//}
		}
	}else{
		$card_password = $card['password'];
	}

	$return_values['UserCheckOk'] = 0;
	$return_values['UserPassword'] = $card_password;
	make_return_value($return_values);
}else{
	$return_values['UserCheckOk'] = 1;
	$return_values['UserPoint'] = $user['score'];
	//取出该会员收藏的优惠券
	$query = $_SGLOBAL['db']->query("select ticket_id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "collect where user_id=" . $user['id'] . " and (close_time+3600*24)>".time());
	while($row = $_SGLOBAL['db']->fetch_array($query)){
					$yhj[] = $row['ticket_id'];
	}

//	$ticket=explode(',',$user['ticket_id']);
//	for($i=0;$i<count($ticket);$i++)
//	{
//		if($ticket[$i]!=''){
//			$yhj[] = $ticket[$i];
//		}
//	}
	
	$juanstr = implode(';',$yhj);
	
	$return_values['QuanCount'] = count($yhj);
	if($return_values['QuanCount'] != 0){
		$return_values['CouponID']  = $juanstr;
	}
	make_return_value($return_values);
}
 

?>