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
<div id="main" class="main" >
<div class="content">
<div class="title">租赁编辑 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1"  action="__URL__/update/">
<tr>
	<td class="tRight" >商家：</td>
	<td class="tLeft" ><?php echo (get_trade($vo["trade_id"])); ?></td>
</tr>
<tr>
	<td class="tRight" >位置：</td>
	<td class="tLeft" ><?php echo (get_position($vo["p_id"])); ?></td>
</tr>
<tr>
	<td class="tRight" >按钮位置：</td>
	<td class="tLeft" ><?php echo ($vo["bt_position"]); ?></td>
</tr>
<tr>
	<td class="tRight" >开始时间：</td>
	<td class="tLeft" ><input type="text" name="start_time" size="10" onFocus="ShowCalendar(this)" value="<?php echo (date("Y-m-d",$vo["start_time"])); ?>"></td>
</tr>
<tr>
	<td class="tRight" >到期时间：</td>
	<td class="tLeft" ><input type="text" name="close_time" size="10" onFocus="ShowCalendar(this)" value="<?php echo (date("Y-m-d",$vo["close_time"])); ?>"></td>
</tr>
<tr>
	<td class="tRight">状态：</td>
	<td class="tLeft"><SELECT class="small bLeft"  name="status">
	<option <?php if(($vo["status"])  ==  "1"): ?>selected<?php endif; ?> value="1">正常</option>
	<option <?php if(($vo["status"])  ==  "0"): ?>selected<?php endif; ?> value="0">预订</option>
    <option <?php if(($vo["status"])  ==  "-1"): ?>selected<?php endif; ?> value="0">到期</option>
    <option <?php if(($vo["status"])  ==  "-2"): ?>selected<?php endif; ?> value="0">撤销</option>
	</SELECT></td>
</tr>
<tr>
	<td class="tRight tTop">备  注：</td>
	<td class="tLeft"><TEXTAREA class="large bLeft"  name="remark" rows="5" cols="57"><?php echo ($vo["remark"]); ?></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" >
	<input type="submit" value="保 存" class="small submit">
	</td>
</tr>
</table>
</form>
</div>
</div>