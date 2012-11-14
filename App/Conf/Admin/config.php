<?php
   C('SAVEPATH','img/image/');  //上传文件保存路径
   C('THUMBPATH','img/imagethum/');  //上传文件缩略图保存路径
   C('MAXSIZE', 3145728);  //图片大小
   C('THUMBMAXWIDTH',400);  //缩略图对打宽度
   C('THUMDMAXHIGHT',400);  //缩略图最大高度
   
return array(
    //添加的配置
	'URL_DISPATCH_ON'=>1,
//    'URL_MODEL'=>2,
    'TOKEN_ON'=>FALSE,
	'USER_AUTH_ON'=>true,
    'SESSION_EXPIRE'=>60*60,
    'URL_CASE_INSENSITIVE'=>false,
	'USER_AUTH_TYPE'			=>1,		// 默认认证类型 1 登录认证 2 实时认证
    'RBAC_ROLE_TABLE'=>'think_role',
    'RBAC_USER_TABLE'=>'think_role_user',
    'RBAC_ACCESS_TABLE'=>'think_access',
    'RBAC_NODE_TABLE'=>'think_node',
	'USER_AUTH_KEY'			=>'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>'think_administrator',
	'USER_AUTH_MODEL'		=>'User',	// 默认验证数据表模型
	'AUTH_PWD_ENCODER'		=>'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'	=>'/Admin/Public/login',	// 默认认证网关
	'NOT_AUTH_MODULE'		=>'Public',		// 默认无需认证模块
	'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模块
	'NOT_AUTH_ACTION'		=>'',	// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'=>'',		// 默认需要认证操作
    'GUEST_AUTH_ON'          => false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'           =>    0,     // 游客的用户ID
	'SHOW_RUN_TIME'=>true,			// 运行时间显示
	'SHOW_ADV_TIME'=>true,			// 显示详细的运行时间
	'SHOW_DB_TIMES'=>true,			// 显示数据库查询和写入次数
	'SHOW_CACHE_TIMES'=>true,		// 显示缓存操作次数
	'SHOW_USE_MEM'=>true,			// 显示内存开销
    'LIKE_MATCH_FIELDS'=>'title|remark',
    'TAG_NESTED_LEVEL'=>3,
    'TMPL_ACTION_ERROR'     => 'Public:success', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => 'Public:success', // 默认成功跳转对应的模板文件
    'UPLOAD_FILE_RULE'		=>	'uniqid',			//  文件上传命名规则 例如 time uniqid com_create_guid 等 支持自定义函数 仅适用于内置的UploadFile类
	'PAGE_LIST_ROWS'=>15,    //默认分页显示记录数
);
?>