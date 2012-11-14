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
<div class="title">添加商品 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1" action="__URL__/insert/" enctype="multipart/form-data">
<tr>
	<td class="tRight" >商品名：</td>
	<td class="tLeft" ><input type="text" name="title" value="" size="60"></td>
</tr>
<tr>
	<td class="tRight" >商品分类：</td>
	<td class="tLeft" >
	<select name="cid">
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select></td>
</tr>
<tr>
	<td class="tRight" >日期：</td>
	<td class="tLeft" ><input type="text" name="sectime" onFocus="ShowCalendar(this)" size="15" value="">
	开始时间：<input type="text" name="starttime" size="10" value="">&nbsp;&nbsp;
	结束时间：<input type="text" name="endtime" size="10" value="">
	</td>
</tr>
<tr>
	<td class="tRight" >商品数量：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="num" value=""></td>
</tr>
<tr>
	<td class="tRight" >商品积分：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="score" value=""></td>
</tr>
<tr>
	<td class="tRight" >报名扣分比：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="signupscore" value="">%</td>
</tr>
<tr>
	<td class="tRight" >失败扣分比：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="failscore" value="">%</td>
</tr>
<tr>
	<td class="tRight" >市场价格：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="price" value=""></td>
</tr>
<tr>
	<td class="tRight" >展示图片：</td>
	<td class="tLeft" ><input name="Img" type="file" id="Img"/></td>
</tr>
<tr>
	<td class="tRight tTop">产品详细介绍：</td>
	<td class="tLeft"><script type="text/javascript" src="__ROOT__/Public/Js/KindEditor/kindeditor.js"></script><script type="text/javascript"> KE.show({ id : 'content'  ,urlType : "absolute"});</script><textarea id="content" style="width:550px;height:220px" name="content" ></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center">
	<input type="submit" value="添 加"  class="small submit">
	</td>
</tr>
</table>
</form>
</div>
</div>