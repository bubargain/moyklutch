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
<div class="content" >
<div class="title">授权用户</div>
<div class="list">
<!-- 查询区域 -->
<table id="checkList" cellpadding="0" cellspacing="0" width="437" class="list">
<tr>
<td class="tLfet" colspan="2" align="left"><form method='post' id="form1" action="__URL__/touser"><input type="hidden" id="id" name="id" value="<?php echo ($id); ?>" >授权用户：<input type="text" id="account" name="account" value="" ><INPUT type="submit" value="提交" class="submit" style="height:25px"></form></td>
</tr>
<tr><td height="5" colspan="2" class="topTd"></td></tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr class="row"  align="center"><td width="52" height="28"><?php if($vo["logo"] == ''): ?><img src="__ROOT__/Public/Uploads/noavatar.gif" width="48" height="48"><?php else: ?><img src="__ROOT__<?php echo (getuseravtar($vo["user_id"])); ?>" width="48" height="48"><?php endif; ?><a href="__APP__/Trade/touser/id/<?php echo ($id); ?>/did/<?php echo ($vo["id"]); ?>">删除</a></td><td width="383" align="left"><?php echo (get_userinfo($vo["user_id"])); ?>&nbsp;&nbsp;&nbsp;&nbsp;授权时间：<?php echo (date("Y年-m月-d日 H-i-s",$vo["create_time"])); ?></td></tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="2" class="bottomTd"></td></tr>
</table></div>
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->