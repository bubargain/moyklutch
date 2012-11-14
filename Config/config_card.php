<?php
//$_SCONFIG 配置信息 $_SGLOBAL 数据信息
$_SCONFIG = $_SGLOBAL = array();
//模板目录
//$_SCONFIG['template'] = 'default';
$_SCONFIG['Key'] = 'MoyKlutch NetWork';
//RBAC SESSION
$_SCONFIG['RBACSessionKey'] = 'YHJ_USERDATA';
// 密码过期时间
$_SCONFIG['password_expire'] = 3;
//会员卡模式  1 支持所有M1  2只支持自己的会员卡
$_SCONFIG['card_mode'] = 2;
//最大上传图片
$_SCONFIG['maxSize'] =2097152; //2M
//网站名
$_SCONFIG['sitename'] = 'MoyKlutch';
//积分名
$_SCONFIG['point_name'] = 'Credit';
//生成gif文件的高度
$_SCONFIG['gif_height'] = '800';
//限制卡的打印时间范围
$_SCONFIG['limit_time'] = '7';
//限制卡打印时间范围内的打印数量
$_SCONFIG['limit_count'] = '20';
?>