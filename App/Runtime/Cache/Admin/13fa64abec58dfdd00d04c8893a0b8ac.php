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
<div id="fRig">
<!-- 高级查询区域 -->
<div id="searchM">
<table width="1183" cellpadding="1" cellspacing="0">
  <form method='post' action="__URL__"><input type="hidden" name="flag" value="1" />
<tr><td width="205">商家ID：
  <input type="text" name="trade_id" title="标题" size="8" value="" ></td>
<td>充值时间：
  <input type="text" name="time1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">~<input type="text" name="time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($time2); ?>"></td>
  <td><input type="submit" value="查询" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
</div>
<div class="content" >
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="7" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="83"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th><th width="235"><a href="javascript:sortBy('trade_id','<?php echo ($sort); ?>','index')">商家名称</a></th><th width="166"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','index')">操作金额</a></th><th width="144"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','index')">当时余额</a></th><th width="112"><a href="javascript:sortBy('type','<?php echo ($sort); ?>','index')">操作方式</a></th><th width="343"><a href="javascript:sortBy('remark','<?php echo ($sort); ?>','index')">备注</a></th><th width="225"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">记录时间</a></th></tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo (get_trade($vo["trade_id"])); ?></td>
<td><?php echo ($vo["money"]); ?>元</td>
<td><?php echo ($vo["tmoney"]); ?>元</td>
<td><?php if(($vo["type"])  ==  "0"): ?>支付宝<?php endif; ?><?php if(($vo["type"])  ==  "1"): ?>手动<?php endif; ?><?php if(($vo["type"])  ==  "2"): ?>购买展位<?php endif; ?></td>
<td><?php echo ($vo["remark"]); ?></td>
<td><?php echo (date("Y年-m月-d日 H:i:s",$vo["create_time"])); ?></td>
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