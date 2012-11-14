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
<div class="title">待审核优惠券列表</div>
<!--  功能操作区域  -->
<!-- 查询区域 -->
<!-- 功能操作区域结束 -->
<div id="fRig">
<div id="searchM">
<table width="1133" cellpadding="1" cellspacing="0">
  <form method='post' action="__URL__">
<tr><td><input type="submit" name="submit" value="导出" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
</div>
<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="12" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="54"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="109">优惠券名称</th>
<th width="115"><a href="javascript:sortBy('cate_id','<?php echo ($sort); ?>','index')">分类</a></th>
<th width="115"><a href="javascript:sortBy('trade_id','<?php echo ($sort); ?>','index')">商家</a></th>
<th width="115"><a href="javascript:sortBy('score','<?php echo ($sort); ?>','index')">打印所需积分</a></th>
<th width="164"><a href="javascript:sortBy('start_time','<?php echo ($sort); ?>','index')">开始时间</a></th>
<th width="105"><a href="javascript:sortBy('close_time','<?php echo ($sort); ?>','index')">结束时间</a></th>
<th width="86"><a href="javascript:sortBy('click_count','<?php echo ($sort); ?>','index')">点击</a></th>
<th width="86"><a href="javascript:sortBy('collect_count','<?php echo ($sort); ?>','index')">收藏</a></th>
<th width="86"><a href="javascript:sortBy('print_count','<?php echo ($sort); ?>','index')">打印</a></th>
<th width="74">状态</th>
<th width="74">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" ondblclick="show_more('more<?php echo ($vo["id"]); ?>');" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo ($vo["name"]); ?></td>
<td><?php echo (get_cate($vo["cate_id"])); ?></td>
<td><?php echo (get_trade($vo["trade_id"])); ?></td>
<td><?php echo ($vo["score"]); ?></td>
<td><?php echo (date("Y-m-d",$vo["start_time"])); ?></td>
<td><?php echo (date("Y-m-d",$vo["close_time"])); ?></td>
<td><a href="javascript:showcount('Ticket',0,'<?php echo ($vo["id"]); ?>')"><?php echo (ticket_count($vo["id"],'0')); ?></a></td>
<td><a href="javascript:showcount('Ticket',2,'<?php echo ($vo["id"]); ?>')"><?php echo (ticket_count($vo["id"],'2')); ?></a></td>
<td><a href="javascript:showcount('Ticket',1,'<?php echo ($vo["id"]); ?>')"><?php echo (ticket_count($vo["id"],'1')); ?></a></td>
<td>
<?php if(($vo["status"])  ==  "-1"): ?>禁用<?php endif; ?>
<?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?>待审核<?php endif; ?></td>
<td><a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;<a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">删除</a></td>
</tr>
<tr class="row" ondblclick="hidden_more('more<?php echo ($vo["id"]); ?>')"><td colspan="12">
<div id="more<?php echo ($vo["id"]); ?>" class="listdiv">
<div style="float:left;padding-right:10px;"><?php if($vo["logo"] == ''): ?><img src="__ROOT__/Public/Uploads/noavatar.gif" width="48" height="48"><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="48" height="48"><?php endif; ?></div>
<div style="float:left;">
<li>优惠券英文：<?php echo ($vo["name"]); ?></li>
<li>商家获得结算金额：<?php echo ($vo["address"]); ?></li>
<li>注意事项：<?php echo ($vo["attention"]); ?></li>
<li>关键字：<?php echo ($vo["keyword"]); ?></li>
<li>介绍：<?php echo ($vo["introduce"]); ?></li>
<li>关键字：<?php echo ($vo["content"]); ?></li>
<li>创建时间：<?php echo (date("Y-m-d",$vo["create_time"])); ?></li>
<li>更新时间：<?php echo (date("Y-m-d",$vo["update_time"])); ?></li>
<li>状态：<?php if(($vo["status"])  ==  "-1"): ?>禁用<?php endif; ?><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>待审核<?php endif; ?></li>
</div></div></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="12" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->