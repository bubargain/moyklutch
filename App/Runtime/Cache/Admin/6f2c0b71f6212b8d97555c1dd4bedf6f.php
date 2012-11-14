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
<div class="title">编辑商家 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1" action="__URL__/update/" enctype="multipart/form-data">
<tr>
	<td class="tRight" >英文名：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire"  check='^\S+$' warning="用户名不能为空,且不能含有空格" name="name" value="<?php echo ($vo["name"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >商家名称：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="title" value="<?php echo ($vo["title"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >联系人：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="contact" value="<?php echo ($vo["contact"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >电话：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="phone" value="<?php echo ($vo["phone"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >手机：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="mobile" value="<?php echo ($vo["mobile"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >传真：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="fax" value="<?php echo ($vo["fax"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >邮箱：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="email" value="<?php echo ($vo["email"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >上传展示图片：</td>
	<td class="tLeft" ><input name="Img" type="file" id="Img"/><?php if($vo["logo"] == ''): ?><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="150" height="100"><?php endif; ?>(请上传尺寸为1063X709的图片)</td>
</tr>
<tr>
	<td class="tRight" >标签：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="tags" value="<?php echo ($vo["tags"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >排序位：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="sort" value="<?php echo ($vo["sort"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >关键字：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="keyword" value="<?php echo ($vo["keyword"]); ?>"></td>
</tr>
<tr>
	<td class="tRight">状态：</td>
	<td class="tLeft"><SELECT class="small bLeft"  name="status">
	<option <?php if(($vo["status"])  ==  "1"): ?>selected<?php endif; ?> value="1">启用</option>
	<option <?php if(($vo["status"])  ==  "0"): ?>selected<?php endif; ?> value="0">禁用</option>
	</SELECT></td>
</tr>
<tr>
	<td class="tRight tTop">商家介绍：</td>
	<td class="tLeft"><script type="text/javascript" src="__ROOT__/Public/Js/KindEditor/kindeditor.js"></script><script type="text/javascript"> KE.show({ id : 'introduce'  ,urlType : "absolute"});</script><textarea id="introduce" style="width:550px;height:220px" name="introduce" ><?php echo ($vo["introduce"]); ?></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" >
	<input type="submit" value="保 存"  class="small submit">
	</td>
</tr>
</form>
<form method='post' id="form1" action="__URL__/edit/id/<?php echo ($vo["id"]); ?>" enctype="multipart/form-data">
<tr>
	<td class="tRight" >上传更多展示图片：</td>
	<td class="tLeft" ><input name="Img" type="file" id="Img"/></td>
</tr>
<tr>
	<td></td>
	<td class="center"><input type="hidden" name="edit" value="1" />
	<input type="submit" value="上传"  class="small submit">
	</td>
</tr></form>
<tr><td colspan="2">
<?php  if(is_array($att)): foreach($att as $key=>$vo): ?><img src="__ROOT__<?php echo ($vo["path"]); ?>" width="150" height="100"><a href="__APP__/Admin/Trade/delattach/id/<?php echo ($vo["id"]); ?>">删除</a>&nbsp;<?php endforeach; endif; ?>
</td></tr>
</table>
</div>
</div>