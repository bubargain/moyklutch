<?php
//用户登录处理
require('../Config/config.php');
require('common.php');
dbconnect();
/*
	发送的格式如下:
	Key = VSoftXinYue NetWork
	LocalTime = 54565445545554
	MacNumber = TS00001
	
	返回的数据格式
	如果没有更新数据,（所有符号均区分大小写）格式如下:
	Action=Null
	Action=finish    传输结束字符
	如果是按钮商家操作,格式如下:
	
	Action=DX		(行为是灯箱按钮配置更新)
	TotalCount=2		(此处为要更新的灯箱按钮数量，比如有2个灯箱按钮Ａ,Ｂ要更新)
	DX1=1;2;3;5 	(DX1表示第1个灯箱按钮，1，2，3，5为优惠券ID)
	DX8=4;10;23;35;43  (DX8表示第8个灯箱按钮)
	Action=Quan	(行为是优惠券缓存更新)
	QuanCount=4
	Quan=1;2009-12-27;0
*/
global $_SGLOBAL, $_SCONFIG;
if($_POST['Key']!=$_SCONFIG['Key'])
{
	exit("error Key");
}
if(empty($_POST['LocalTime']))
{
	exit("error LocalTime");
}
if(empty($_POST['MacNumber']))
{
	exit("error MacNumber");
}
$DXS = $quans = array();
//记录时间
$printdate = time();

//通过机器号取出打印机位置ID
$query = $_SGLOBAL['db']->query("select id,name,macno from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "location where macno='$_POST[MacNumber]'");
$wz = $_SGLOBAL['db']->fetch_array($query);

//取出该位置优惠券购买信息
$query = $_SGLOBAL['db']->query("select id,bt_position from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "exhibition where p_id = $wz[id] order by id");
while($row = $_SGLOBAL['db']->fetch_array($query))
{
	//取出优惠商家
	$shop_str = $_SGLOBAL['db']->query("select id,trade_id,p_id,status,update_time from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "tenancy where bid = $row[id] and start_time < $printdate and $printdate < close_time and status<>-1 and status<>-2 order by id desc" );
	$shop = $_SGLOBAL['db']->fetch_array($shop_str);
	//echo ("select id,trade_id,p_id,status,update_time from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "tenancy where bid = $row[id] and start_time < $printdate and $printdate < close_time" . "<br>");
	//var_dump($shop);
	if($shop === false)
	{
		$sql = "insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "error (mac_id,source,error_content,level,log_time) Values ('$wz[macno]','$wz[name]','$row[bt_position]_没有商家购买',2,$printdate)";
		$_SGLOBAL['db']->query($sql);
		$DXS[$row['bt_position']] ='NULL';
	}
	else
	{
		//取得商家名字
		$shang_str =$_SGLOBAL['db']->query("select title,update_time from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "trade where id = $shop[trade_id]");
		$shang = $_SGLOBAL['db']->fetch_array($shang_str);
		//if($shop['status'] == 0 || $shang['update_time'] >= $_POST['LocalTime'] )
		if(true)
		{
			//取得商家优惠券
			$quan_str = $_SGLOBAL['db']->query("select id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket where trade_id = $shop[trade_id] and start_time < $printdate and $printdate < close_time and status=1 order by sortnum desc");
			$quan = array();
			while($quan_row = $_SGLOBAL['db']->fetch_array($quan_str))
			{
				$quan[] = $quan_row['id'];
			}
			$out_quanstr = implode(';',$quan);
			//为空时为-1
			if($out_quanstr==''){
		        $sql = "insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "error (mac_id,source,error_content,level,log_time) Values ('$wz[id]','$wz[name]','$row[bt_position]_该商家没有优惠券',2,$printdate)";
				$out_quanstr='-1';
				//$test = $_SGLOBAL['db']->query("select id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "error where mac_id = '$wz[id]' and error_content='$row[bt_position]_该商家没有优惠券'");
				//if(!$test){
				//}
		        $_SGLOBAL['db']->query($sql);
			}
			$DXS[$row['bt_position']] = $shang['title']. ',' . $out_quanstr;
			//更新status为1()
			$_SGLOBAL['db']->query("update " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "tenancy set status=1 where id = $shop[id]");
			
		}/*
		else
		{
			//取得商家优惠券
			$quan_str = $_SGLOBAL['db']->query("select id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket where trade_id = $shop[trade_id] and start_time < $printdate and $printdate < close_time and update_time >= $_POST[LocalTime]");
			$quan = array();
			while($quan_row = $_SGLOBAL['db']->fetch_array($quan_str))
			{
				$quan[] = $quan_row['id'];
			}
			if(count($quan))
			{
				//取得商家优惠券
				$quan_str = $_SGLOBAL['db']->query("select id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket where trade_id = $shop[trade_id] and start_time < $printdate and $printdate < close_time and status=1 order by sortnum desc");
				$quan = array();
				while($quan_row = $_SGLOBAL['db']->fetch_array($quan_str))
				{
					$quan[] = $quan_row['id'];
				}
				$out_quanstr = implode(';',$quan);
				//为空时为-1
				if($out_quanstr==''){
					$out_quanstr='-1';
				//$test = $_SGLOBAL['db']->query("select id from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "error where mac_id = '$wz[id]' and error_content='$row[bt_position]_该商家没有优惠券'");
				//if(!$test){
		            $sql = "insert into " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "error (mac_id,source,error_content,level,log_time) Values ('$wz[id]','$wz[name]','$row[bt_position]_该商家没有优惠券',2,$printdate)";
				//}
		            $_SGLOBAL['db']->query($sql);
				}
				$DXS[$row['bt_position']] = $shang['title']. ',' . $out_quanstr;
			}
		}*/
	}
}
//取出待更新优惠券
$quan_str = $_SGLOBAL['db']->query("select id,close_time,score,update_time,status from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket where update_time >= $_POST[LocalTime] and $printdate < close_time and status<>0 order by update_time");
$quan = array();
while($quan_row = $_SGLOBAL['db']->fetch_array($quan_str))
{
	if($quan_row['status']==1){
		$quans[] = $quan_row['id'] . ';' . date("Y-m-d",$quan_row['close_time']) . ';' . $quan_row['score'] . ';' . $quan_row['update_time'];
	}
	if($quan_row['status']==-1){
		$quans[] = $quan_row['id'] . ';' . '1970-01-01' . ';' . '0' . ';' . $quan_row['update_time'];
	}
}
$result = "";
if(count($DXS))
{
	$result .= "Action=DX" . "\r\n";
	$result .= "TotalCount=" . count($DXS) . "\r\n";
	foreach($DXS as $key => $val)
	{
		$result .= $key . "=" . $val . "\r\n";
	}
}
if(count($quans))
{
	$result .= "Action=Quan" . "\r\n";
	$result .= "QuanCount=" . count($quans) . "\r\n";
	foreach($quans as $key => $val)
	{
		$result .= "Quan=" . $val . "\r\n";
	}
}
$coupon=array();
$fso  = opendir('../img/coupon/');
while($flist=readdir($fso)){
	if($flist!='.'&&$flist!='..'&&$flist!='Thumbs.db'){
		$coupon[] = explode('.',str_ireplace('.zip','',$flist));
	}
}
$coupon = array_sort($coupon,1,'asc',SORT_NUMERIC);
foreach ($coupon as $k=>$v){
	$coupon[$k] = implode('.',$v);
}
$xip=array();
$fso  = opendir('../img/xip/');
while($flist=readdir($fso)){
	if($flist!='.'&&$flist!='..'&&$flist!='Thumbs.db'){
		$xip[] = explode('.',str_ireplace('.zip','',$flist));
	}
}
$xip = array_sort($xip,1,'desc',SORT_NUMERIC);
$ui=array();
$fso  = opendir('../img/ui/');
while($flist=readdir($fso)){
	if($flist!='.'&&$flist!='..'&&$flist!='Thumbs.db'){
		$ui = str_ireplace('.zip','',$flist);
	}
}
$help=array();
$fso  = opendir('../img/help/');
while($flist=readdir($fso)){
	if($flist!='.'&&$flist!='..'&&$flist!='Thumbs.db'){
		$help = str_ireplace('.zip','',$flist);
	}
}
$result .= "Action=System" . "\r\n";
$result .= "Coupon=" .implode(';',$coupon). "\r\n";
$result .= "Ui=" . $ui . "\r\n";
$result .= "Help=" . $help . "\r\n";
$result .= "Xip=".implode('.',array_shift($xip)) . "\r\n";
if($result!='')
{
	$result .= "LocalTime=" . $printdate . "\r\n";
}
$result .= "Action=finish" . "\r\n";
exit($result);
?>