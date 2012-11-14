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
<div class="title">类别列表</div>
<!--  功能操作区域  -->


<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<!-- 查询区域 -->
<table id="checkList" cellpadding="0" cellspacing="0" width="70%" class="list">
<tr><td height="5" colspan="6" class="topTd"></td></tr>
<tr><td><a href="__URL__/add"><img src="__PUBLIC__/Images/addc1.jpg"></a></td><td colspan="5"></td></tr>
<tr>
<?php  if(is_array($clist)): foreach($clist as $key=>$vo): ?><tr class="row"  align="center"><td width="14%" height="28"><?php echo ($vo["title"]); ?></td><td><a href="__URL__/add/pid/<?php echo ($vo["id"]); ?>"><img src="__PUBLIC__/Images/addc2.jpg" onclick=""></a></td><td width="15%"><?php echo ($vo["name"]); ?></td><td width="32%"><?php echo ($vo["remark"]); ?></td><td width="8%"><a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>/pid/<?php echo ($vo["pid"]); ?>">编辑</a></td><td width="7%"><a href="__URL__/foreverdelete/id/<?php echo ($vo["id"]); ?>">删除</a></td></tr>
<?php  if(is_array($listall)): foreach($listall as $key=>$voo): ?><?php if(($voo["pid"])  ==  $vo["id"]): ?><tr class="row" align="center"><td align="right"  height="28"></td><td width="24%"><?php echo ($voo["title"]); ?></td><td><?php echo ($voo["name"]); ?></td><td><?php echo ($voo["remark"]); ?></td><td><a href="__URL__/edit/id/<?php echo ($voo["id"]); ?>/pid/<?php echo ($voo["pid"]); ?>">编辑</a></td><td><a href="__URL__/foreverdelete/id/<?php echo ($voo["id"]); ?>">删除</a></td></tr><?php endif; ?><?php endforeach; endif; ?><?php endforeach; endif; ?>
<tr><td height="5" colspan="6" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->