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
    if(myform.rend.value == "")
	{
		alert("用户名不能为空!");
		myform.rend.focus();
		return (false);
	}
	else if (myform.rend.value >12)
	{
		alert("不能大于12周!");
		myform.rend.value="12";
		myform.rend.focus();
		return (false);
	}
}
-->
</script>
<div id="main" class="main" >
<div class="content">
<div class="title">确认<font color="#990000"><?php echo ($title); ?></font>将购买的展位 [ <a href="__URL__">返回列表</a> ]</div>
<form action="__URL__/add5/" method="post" onSubmit="return Juge(this)"><input type="hidden" name="id" value="<?php echo ($id); ?>" /><input type="hidden" name="title" value="<?php echo ($title); ?>" />
<table width="1126" cellpadding="1" cellspacing="0" align="left">
<tr><td>租期：<input type="text" name="rend" size="4"  value="1">周 （最多12周）<input type="submit" value="确定购买" class="small hMargin shadow submit"></td></tr>
</table></div>
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="11" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="41"></th>
<th width="136">编号</th>
<th width="309">打印机位置</th>
<th width="239">按钮位置</th>
<th width="583">状态</th>
</tr>
<?php  if(is_array($exhlist)): foreach($exhlist as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<th width="41"><input name="dxlist[]" type="checkbox" checked="checked" value="<?php echo ($vo["id"]); ?>" /></th>
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo (get_position($vo["p_id"])); ?></td>
<td><?php echo ($vo["bt_position"]); ?></td>
<td><?php echo (get_rendinfo($vo["id"])); ?></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="11" class="bottomTd"></td></tr>
</table>
</div></form>
</div>