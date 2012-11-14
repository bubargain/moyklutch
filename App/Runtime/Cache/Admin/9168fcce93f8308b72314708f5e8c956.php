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
<div class="title">待审核展位租赁列表</div>
<!--  功能操作区域  -->
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
<tr><td height="5" colspan="11" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="40"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">编号</a></th>
</th><th width="105"><a href="javascript:sortBy('p_id','<?php echo ($sort); ?>','index')">打印机位置</a></th>
<th width="70"><a href="javascript:sortBy('bt_position','<?php echo ($sort); ?>','index')">按钮位置</a></th>
<th width="100"><a href="javascript:sortBy('trade_id','<?php echo ($sort); ?>','index')">商家</a></th>
<th width="100"><a href="javascript:sortBy('start_time','<?php echo ($sort); ?>','index')">开始时间</a></th>
<th width="90"><a href="javascript:sortBy('close_time','<?php echo ($sort); ?>','index')">结束时间</a></th>
<th width="90"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">申请时间</a></th>
<th width="205">备注</th><th width="50"><a href="javascript:sortBy('status','<?php echo ($sort); ?>','index')">状态</th>
<th width="70">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo (get_position($vo["p_id"])); ?></td>
<td><?php echo ($vo["bt_position"]); ?></td>
<td><?php echo (get_trade($vo["trade_id"])); ?></td>
<td><?php echo (date("Y-m-d",$vo["start_time"])); ?></td>
<td><?php echo (date("Y-m-d",$vo["close_time"])); ?></td>
<td><?php echo (date("Y-m-d",$vo["create_time"])); ?></td>
<td><input type="text" id="remark<?php echo ($vo["id"]); ?>" name="remark" size="28" value="<?php echo ($vo["remark"]); ?>" style="border:#FFF 1px solid;" onchange="aupdate('tenancy','<?php echo ($vo["id"]); ?>','remark')"></td>
<td><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?>预订<?php endif; ?>
<?php if(($vo["status"])  ==  "-1"): ?>到期<?php endif; ?>
<?php if(($vo["status"])  ==  "-2"): ?>撤销<?php endif; ?></td>
<td><a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="11" class="bottomTd"></td></tr>
</table>
</div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->