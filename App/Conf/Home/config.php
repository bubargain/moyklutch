<?php
//配置
return array(
    'APP_DEBUG'=>0,
	'URL_DISPATCH_ON'=>1,
	'TAGLIB_BUILD_IN'=> 'cx,Leishi',
    'TOKEN_ON'=>FALSE,
	'USER_AUTH_ON'=> FALSE,
    'SESSION_EXPIRE'=>60*60,
    'URL_CASE_INSENSITIVE'=>false,
	'USER_AUTH_TYPE'			=>1,		// 默认认证类型 1 登录认证 2 实时认证
    'RBAC_ROLE_TABLE'=>'think_role',
    'RBAC_USER_TABLE'=>'think_role_user',
    'RBAC_ACCESS_TABLE'=>'think_access',
    'RBAC_NODE_TABLE'=>'think_node',
	'USER_AUTH_KEY'			=>'auth',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>'admini',
	'USER_AUTH_MODEL'		=>'User',	// 默认验证数据表模型
	'AUTH_PWD_ENCODER'		=>'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'	=>'/Index/index',	// 默认认证网关
    'NOT_AUTH_MODULE'		=>'Index,Ticket,Shop,News,Huodong',		// 默认无需认证模块
);
?>