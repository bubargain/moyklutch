<?php
//数据库连接
function dbconnect()
{
	global $_SGLOBAL, $_SCONFIG;
	include_once('../Lib/class_mysql.php');
	if(empty($_SGLOBAL['db'])) 
	{
		$_SGLOBAL['db'] = new dbstuff;
		$_SGLOBAL['db']->charset = 'utf8';
		$_SGLOBAL['db']->connect($_SCONFIG['dbDSN']['host'], $_SCONFIG['dbDSN']['login'], $_SCONFIG['dbDSN']['password'], $_SCONFIG['dbDSN']['database']);
	}
}

/*
	处理给打印机的返回结果
*/
function make_return_value($return_values = array())
{
	$result = "";
	if(empty($return_values))
	{
		exit("\r\n");
	}
	
	foreach($return_values as $key => $val)
	{
		$result .= $key . "=" . $val . "\r\n";
	}
	$result .= "Action=finish" . "\r\n";
	exit($result);
}
/*
   数组排序
*/
function array_sort($arr,$keys,$type='asc',$sorttype=SORT_REGULAR){ 
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue,$sorttype);
	}else{
		arsort($keysvalue,$sorttype);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array; 
} 

?>