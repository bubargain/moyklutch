<?php
// header("Content-type: text/html; charset=utf-8"); 
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

 
 //open the debug model
define( 'NO_CACHE_RUNTIME' , True);
define('APP_DEBUG',true);
 //define('SHOW_PAGE_TRACE',true);	
// 定义ThinkPHP框架路径
define('THINK_PATH', './ThinkPHP/');
define('STRIP_RUNTIME_SPACE',false);
//定义项目名称和路径
define('APP_NAME', 'App');
define('APP_PATH', './App/');

// 加载框架入口文件
require(THINK_PATH."/thinkphp.php");
//实例化一个网站应用实例
App::run();
