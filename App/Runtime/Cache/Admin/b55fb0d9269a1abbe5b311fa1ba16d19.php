<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script> 
<!--
function Juge(myform)
{
    if(myform.account.value == "")
	{
		alert("用户名不能为空!");
		myform.account.focus();
		return (false);
	}
	if (myform.password.value == "")
	{
		alert("密码不能为空!");
		myform.password.focus();
		return (false);
	}
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (filter.test(myform.email.value)) return true;
    else {
    alert('您的电子邮件格式不正确');
    return false;}
	
	var a=/^[1-2]\d{3}-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[0-1])$/ ;
	if (!myform.birthday.value.match(a)){ 
	alert("日期格式不正确!");
	}



}
-->
</script>
<div id="main" class="main" >
<div class="content">
<div class="title">添加帐号 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1" action="__URL__/insert" onSubmit="return Juge(this)">
<tr>
	<td class="tRight" align="left"><font color="#FF0000">红色为必填项</font></td><td class="tLeft" ></td>
</tr>
<tr>
	<td class="tRight" >用户名：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire"  check='^\S+$' warning="用户名不能为空,且不能含有空格" name="account" value=""></td>
</tr>
<tr>
	<td class="tRight" >密码：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire" name="password" value=""></td>
</tr>
<tr>
	<td class="tRight" >昵称：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="nickname" value=""></td>
</tr>
<tr>
	<td class="tRight" >生日：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="birthday" onFocus="ShowCalendar(this)" value=""></td>
</tr>
<tr>
	<td class="tRight" >地址：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="address" value=""></td>
</tr>
<tr>
	<td class="tRight" >邮箱：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire" name="email" value=""></td>
</tr>
<tr>
	<td class="tRight" >手机：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="mobile" value=""></td>
</tr>
<tr>
	<td class="tRight" >电话：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="phone" value=""></td>
</tr>
<tr>
	<td class="tRight" >会员卡：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="card_id" value=""></td>
</tr>
<tr>
	<td class="tRight">用户组：</td>
	<td class="tLeft"><SELECT class=" bLeft"  name="type_id">
	<option value="0">管理员</option>
	<option value="2" selected="selected">普通用户</option>
	</SELECT></td>
</tr>
<tr>
	<td class="tRight">状态：</td>
	<td class="tLeft"><SELECT class=" bLeft"  name="status">
    <option value="-1">已删除</option>
	<option value="1" selected="selected">启用</option>
	<option value="0">禁用</option>
	</SELECT></td>
</tr>
<tr>
	<td class="tRight tTop">备  注：</td>
	<td class="tLeft"><TEXTAREA class="large bLeft"  name="remark" rows="5" cols="57"></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center">
	<input type="submit" value="添 加" class="small submit"><br />
	</td>
</tr>
</table>
</form>
</div>
</div>