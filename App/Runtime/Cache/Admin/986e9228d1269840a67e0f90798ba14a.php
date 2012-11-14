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
<!-- 查询区域 -->
<!-- 高级查询区域 -->
<div id="searchM">
<table width="1020" cellpadding="1" cellspacing="0">
  <form method='post' action="__URL__"><input type="hidden" name="flag" value="1" />
<tr>
<td width="206">用户ID：
  <input type="text" name="user_id" title="用户ID" size="10" value="<?php echo ($user_id); ?>"></td>
<td width="284">记录时间：
  <input type="text" name="time1" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">~<input type="text" name="time2" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($time2); ?>"></td><td width="125"><input type="submit" value="查询" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="6" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="78"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th><th width="181"><a href="javascript:sortBy('user_id','<?php echo ($sort); ?>','index')">用户</a></th><th width="183"><a href="javascript:sortBy('action_name','<?php echo ($sort); ?>','index')">模块</a></th><th width="361">操作</th><th width="361">操作对象</th><th width="202"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">记录时间</a></th></tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo (get_user($vo["user_id"])); ?></td>
<td><?php echo ($vo["action_name"]); ?></td>
<td><?php echo ($vo["operate"]); ?></td>
<td><?php echo ($vo["target"]); ?></td>
<td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="6" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->