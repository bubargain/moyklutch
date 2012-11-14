<?php

//define('APP_DEBUG',true);
define('APP_NAME', 'App');

define('THINK_PATH', './ThinkPHP');
define('S_ROOT', substr(str_replace('\\', '/',dirname(__FILE__)),0,strrpos(str_replace('\\', '/',dirname(__FILE__)),'/')).'/');
require('config_card.php');
require('../App/Conf/config.php');
$_SCONFIG['dbDSN'] = array();
// 主机IP地址
$_SCONFIG['dbDSN']['host'] = $array['DB_HOST'];
// 登录账号
$_SCONFIG['dbDSN']['login'] = $array['DB_USER'];
// 登陆密码
$_SCONFIG['dbDSN']['password'] = $array['DB_PWD'];
// 库名称
$_SCONFIG['dbDSN']['database'] = $array['DB_NAME'];
//字符集
$_SCONFIG['dbDSN']['charset'] = 'utf-8';
//前缀
$_SCONFIG['dbDSN']['dbTablePrefix'] = $array['DB_PREFIX'];
//系统启动时间
$mtime = explode(' ', microtime());
$_SGLOBAL['timestamp'] = $mtime[1];
$_SGLOBAL['starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];

//同步登录 Cookie 设置
$_SCONFIG['cookiedomain'] = ''; 			// cookie 作用域
$_SCONFIG['cookiepath'] = '/';			// cookie 作用路径
?>
