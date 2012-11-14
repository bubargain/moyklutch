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
<div id="main" class="main" >
<div class="content">
<div class="title">用户加分</div>
<div style="float:left;">
<form method=post id="order-pay-form"  action="__URL__/addscore" sid="charge">
<table cellpadding=3 cellspacing=3>
<tr>
	<td class="tRight" ><?php echo ($vo["account"]); ?>-当前积分：</td>
	<td class="tLeft" ><?php echo ($vo["score"]); ?></td><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" /><input type="hidden" name="score" value="<?php echo ($vo["score"]); ?>" />
</tr>
<tr>
	<td class="tRight" >请输入充值金额：</td>
	<td class="tLeft" ><input name="addscore" type="text" VALUE="1" />元(兑换规则一元兑换<?php echo ($rate); ?>个积分)</td>
</tr>
<tr>
	<td class="tRight" >备注：</td>
	<td class="tLeft" ><textarea name="remark" id="remark"></textarea></td>
</tr>
<tr>
	<td ></td>
	<td class="center"><div style="width:85%">
	<input type="submit" value="确定" class=" shadow submit">
	</div></td>
</tr>
</table>
</form>
</div>
</div>
</div>