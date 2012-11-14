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
<script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
<script> 
function Juge(myform)
{
    if(myform.title.value == "")
	{
		alert("商家名称不能为空!");
		myform.title.focus();
		return (false);
	}
	if(myform.mobile.value == ""&&myform.phone.value == "")
	{
		alert("手机和电话必填其一!");
		myform.title.focus();
		return (false);
	}
	if(myform.Img.value == "")
	{
		alert("请上传图片!");
		return (false);
	}
}
</script>
<div id="main" class="main" >
<div class="content">
<div class="title">新增商家 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method="post" id="form1" action="__URL__/insert/" enctype="multipart/form-data" onSubmit="return Juge(this)">
<tr>
	<td class="tRight" >英文名：</td>
	<td class="tLeft" ><input type="text" class="medium "  check='^\S+$' warning="用户名不能为空,且不能含有空格" name="name" value=""></td>
</tr>
<tr>
	<td class="tRight" >商家名称：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire" name="title" value=""></td>
</tr>
<tr>
	<td class="tRight" >联系人：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="contact" value=""></td>
</tr>
<tr>
	<td class="tRight" >电话：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire" name="phone" value=""></td>
</tr>
<tr>
	<td class="tRight" >手机：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire" name="mobile" value=""></td>
</tr>
<tr>
	<td class="tRight" >传真：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="fax" value=""></td>
</tr>
<tr>
	<td class="tRight" >邮箱：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="email" value=""></td>
</tr>
<tr>
	<td class="tRight" >上传展示图片：</td>
	<td class="tLeft" ><input name="Img" type="file" id="Img"/>(请上传尺寸为1063X709的图片)</td>
</tr>
<tr>
	<td class="tRight" >标签：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="tags" value=""></td>
</tr>
<tr>
	<td class="tRight" >排序位：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="sort" value=""></td>
</tr>
<tr>
	<td class="tRight" >关键字：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="keyword" value=""></td>
</tr>
<tr>
	<td class="tRight">状态：</td>
	<td class="tLeft"><SELECT class="small bLeft"  name="status">
	<option value="1">启用</option>
	<option value="0">禁用</option>
	</SELECT></td>
</tr>
<tr>
	<td class="tRight tTop">商家介绍：</td>
	<td class="tLeft"><script type="text/javascript" src="__ROOT__/Public/Js/KindEditor/kindeditor.js"></script><script type="text/javascript"> KE.show({ id : 'introduce'  ,urlType : "absolute"});</script><textarea id="introduce" style="width:550px;height:220px" name="introduce" ></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center">
	<input type="submit" value="添 加" class="small submit">
	<input type="reset" class="submit  small" value="清 空" >
	</td>
</tr>
</table>
</form>
</div>
</div>