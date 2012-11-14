<?php if (!defined('THINK_PATH')) exit();?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>『唐山优惠券管理平台』By ThinkPHP <?php echo (THINK_VERSION); ?></title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/Util/Calendar.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/Ajax/ThinkAjax.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/Util/ImageLoader.js"></script>
<script language="JavaScript">
<!--
//指定当前组模块URL地址 
var URL = '__URL__';
var APP	 =	 '__APP__';
var PUBLIC = '__PUBLIC__';
ThinkAjax.image = [	 '__PUBLIC__/Images/loading2.gif', '__PUBLIC__/Images/ok.gif','__PUBLIC__/Images/update.gif' ]
ImageLoader.add("__PUBLIC__/Images/bgline.gif","__PUBLIC__/Images/bgcolor.gif","__PUBLIC__/Images/titlebg.gif");
ImageLoader.startLoad();
//-->
</script>
</head>

<body onload="loadBar(0)">
<div id="loader" >页面加载中...</div>
<!-- 菜单区域  -->

<!-- 主页面开始 -->
<div id="main" class="main" >

<!-- 主体内容  -->
<div class="content" >
<div class="title">待审核用户列表</div>
<!--  功能操作区域  -->
<!-- 高级查询区域 -->
<div id="searchM">
<table width="968" cellpadding="1" cellspacing="0"><form method='post' action="__URL__">
<tr><td><input type="submit" name="submit" value="导出" class="small hMargin shadow submit"><input type="submit" name="submit" value="邮件群发" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
<!-- 功能操作区域结束 -->
<!-- 列表显示区域  -->
<div class="list">
<table cellpadding="0" cellspacing="0" class="list">
<tr><td height="5" colspan="12" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="50"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">编号</a></th><th width="130"><a href="javascript:sortBy('account','<?php echo ($sort); ?>','index')">用户名</a></th><th width="98"><a href="javascript:sortBy('card_id','<?php echo ($sort); ?>','index')">会员卡</a></th><th width="204">地址</th><th width="55">点击</th><th width="59">打印</th><th width="53">收藏</th><th width="75">用户组</th><th width="71"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','index')">金币</a></th><th width="90"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">注册时间</a></th><th width="215">备注</th><th width="120">操作</th></tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" ondblclick="show_more('more<?php echo ($vo["id"]); ?>');" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo ($vo["account"]); ?></td>
<td><?php echo ($vo["card_id"]); ?></td>
<td><?php echo ($vo["address"]); ?></td>
<td><a href="javascript:showcount('User',0,'<?php echo ($vo["id"]); ?>')"><?php echo (user_count($vo["id"],'0')); ?></a></td>
<td><a href="javascript:showcount('User',1,'<?php echo ($vo["id"]); ?>')"><?php echo (user_count($vo["id"],'1')); ?></a></td>
<td><a href="javascript:showcount('User',2,'<?php echo ($vo["id"]); ?>')"><?php echo (user_count($vo["id"],'2')); ?></a></td>
<td><?php if(($vo["type_id"])  ==  "0"): ?>管理员<?php endif; ?><?php if(($vo["type_id"])  ==  "1"): ?>商家<?php endif; ?><?php if(($vo["type_id"])  ==  "2"): ?>普通会员<?php endif; ?></td>
<td><?php echo ($vo["money"]); ?></td>
<td><?php echo (date("Y-m-d",$vo["create_time"])); ?></td>
<td><input type="text" id="remark<?php echo ($vo["id"]); ?>" name="remark" size="30" value="<?php echo ($vo["remark"]); ?>" style="border:#FFF 1px solid;" onchange="aupdate('User','<?php echo ($vo["id"]); ?>','remark')"></td>
<td><a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;<a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a></td>
</tr>
<tr ondblclick="hidden_more('more<?php echo ($vo["id"]); ?>')"><td colspan="13">
<div id="more<?php echo ($vo["id"]); ?>" class="listdiv">
<div style="float:left;padding-right:10px;"><?php if($vo["logo"] == ''): ?><img src="__ROOT__/Public/Uploads/noavatar.gif" width="48" height="48"><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="48" height="48"><?php endif; ?></div>
<div style="float:left;">
<li>管理的商家：<?php echo (get_trade($vo["id"])); ?></li>
<li>真实姓名：<?php echo ($vo["realname"]); ?></li>
<li>真实姓名：<?php echo ($vo["mobile"]); ?></li>
<li>性别：<?php echo ($vo["sex"]); ?></li>
<li>生日：<?php echo ($vo["birthday"]); ?></li>
<li>所在地区：<?php echo ($vo["region"]); ?></li>
<li>联系电话：<?php echo ($vo["phone"]); ?></li>
<li>地址：<?php echo ($vo["address"]); ?></li>
<li>邮寄地址：<?php echo ($vo["zip_code"]); ?></li>
<li>邮箱：<?php echo ($vo["email"]); ?></li>
<li>邮箱是否验证：<?php if(($vo["email_if"])  ==  "0"): ?>否<?php endif; ?><?php if(($vo["email_if"])  ==  "1"): ?>是<?php endif; ?></li>
<li>手机是否验证：<?php if(($vo["mobile_if"])  ==  "0"): ?>否<?php endif; ?><?php if(($vo["mobile_if"])  ==  "1"): ?>是<?php endif; ?></li>
<li>是否访问过wap网站：<?php if(($vo["wap_if"])  ==  "0"): ?>否<?php endif; ?><?php if(($vo["wap_if"])  ==  "1"): ?>是<?php endif; ?></li>
<li>用户状态：<?php if(($vo["status"])  ==  "-1"): ?>已删除<?php endif; ?><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>禁用<?php endif; ?></li>
</div></div></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="12" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->