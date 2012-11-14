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
<div class="title">待审核商家列表</div>
<!--  功能操作区域  -->
<!-- 查询区域 -->
<!-- 高级查询区域 -->
<div id="fRig">
<div id="searchM">
<table width="1133" cellpadding="1" cellspacing="0">
  <form method='post' action="__URL__">
<tr><td><input type="submit" name="submit" value="导出" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
</div>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="9" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="59"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="127"><a href="javascript:sortBy('title','<?php echo ($sort); ?>','index')">商家名称</a></th>
<th width="128">电话</th>
<th width="145"><a href="javascript:sortBy('tags','<?php echo ($sort); ?>','index')">标签</a></th>
<th width="130"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">创建时间</a></th>
<th width="98"><a href="javascript:sortBy('status','<?php echo ($sort); ?>','index')">状态</a></th>
<th width="100"><a href="javascript:sortBy('getmoney','<?php echo ($sort); ?>','index')">结算金额</a></th>
<th width="82"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','index')">储值</a></th>
<th width="315">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" ondblclick="show_more('more<?php echo ($vo["id"]); ?>');" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo ($vo["title"]); ?></td>
<td><?php echo ($vo["phone"]); ?></td>
<td><?php echo ($vo["tags"]); ?></td>
<td><?php echo (date("Y-m-d",$vo["create_time"])); ?></td>
<td><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?>禁用<?php endif; ?></td>
<td><?php echo ($vo["getmoney"]); ?></td>
<td><?php echo ($vo["money"]); ?></td>
<td><a href="__APP__/Admin/Ticket/add/id/<?php echo ($vo["id"]); ?>">添加优惠券</a>&nbsp;&nbsp;<a href="javascript:showuser('<?php echo ($vo["id"]); ?>')">授权用户</a>&nbsp;&nbsp;<a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;&nbsp;<a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">删除</a></td>
</tr>
<tr class="row" ondblclick="hidden_more('more<?php echo ($vo["id"]); ?>')"><td colspan="9">
<div id="more<?php echo ($vo["id"]); ?>" class="listdiv">
<div style="float:left;padding-right:10px;"><?php if($vo["logo"] == ''): ?><img src="__ROOT__/Public/Uploads/noavatar.gif" width="48" height="48"><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="48" height="48"><?php endif; ?></div>
<div style="float:left;">
<li>商家英文：<?php echo ($vo["name"]); ?></li>
<li>手机：<?php echo ($vo["mobile"]); ?></li>
<li>传真：<?php echo ($vo["fax"]); ?></li>
<li>地址：<?php echo ($vo["address"]); ?></li>
<li>邮箱：<?php echo ($vo["email"]); ?></li>
<li>关键字：<?php echo ($vo["keyword"]); ?></li>
<li>公司介绍：<?php echo ($vo["introduce"]); ?></li>
<li>状态：<?php if(($vo["status"])  ==  "-1"): ?>已删除<?php endif; ?><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>禁用<?php endif; ?></li>
</div></div></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="9" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->