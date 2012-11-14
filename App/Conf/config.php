<?php
if (!defined('THINK_PATH')) exit();
return $array=array(
		'URL_MODEL'=>1,                 // 如果你的环境不支持PATHINFO 请设置为3
		'DB_TYPE'=>'mysql',
		'DB_HOST'=>'192.168.1.104',     //数据库所在IP地址
		'DB_USER'=>'root',     //数据库用户
		'DB_PWD' =>'RooT',  	 //数据库密码
		'DB_NAME'=>'ticket',     //数据库名
		'DB_PORT'=>'3306',
		'DB_PREFIX'=>'think_',
		'CACHE_TYPE'=>'file',
		'MEMBER_AUTH_KEY'=>'userid',
		'APP_DEBUG' => 0,
		'APP_GROUP_LIST'=>'Home,Admin',
		'DEFAULT_GROUP'=>'Home',

		//uc配置
		'UC_CONNECT'         => 'POST',
		'UC_IP'              => 'localhost',
		'UC_CLIENT_PATH'     => APP_NAME.'/uc_client/',
		'UC_API'             => 'http://www.uhquan.com/uc',
		'UC_CHARSET'         => 'utf8',
		'UC_APPID'           => '1',
		'UC_KEY'             => 'y8DbNdlahev8N2P8V6e4Yfo2t8SbL5614fUbaen5Q7v1Ram8pa65p5dbrez8A3J6',
		'MY_UC_KEY'          => 'y8DbNdlahev8N2P8V6e4Yfo2t8SbL5614fUbaen5Q7v1Ram8pa65p5dbrez8A3J6',
		);
?>