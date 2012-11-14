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
<script>
function CheckAll(strSection)
	{
		var i;
		var	colInputs = document.getElementById(strSection).getElementsByTagName("input");
		for	(i=1; i < colInputs.length; i++)
		{
			colInputs[i].checked=colInputs[0].checked;
		}
	}
</script>
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
<td width="280">打印机编号：
  <input type="text" name="source" title="打印机编号" size="10" value="<?php echo ($source); ?>"></td>
<td width="236">机器mac编号：
  <input type="text" name="mac_id" title="机器mac编号" size="10" value="<?php echo ($mac_id); ?>"></td>
<td width="233">错误等级：
  <select name="level">
  <option value="">不限</option>
  <option <?php if(($level)  ==  "1"): ?>selected<?php endif; ?> value="1">严重</option>
  <option <?php if(($level)  ==  "2"): ?>selected<?php endif; ?> value="2">一般</option>
</select></td>
<td width="261">状态：
  <select name="status">
  <option <?php if(($status)  ==  "0"): ?>selected<?php endif; ?> value="0">未处理</option>
  <option <?php if(($status)  ==  "-1"): ?>selected<?php endif; ?> value="-1">已处理</option>
  <option value="1">全部</option>
</select></td></tr><tr>
<td colspan="2">记录时间：
  <input type="text" name="time1" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">~<input type="text" name="time2" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($time2); ?>"></td><td colspan="2"><input type="submit" value="查询" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<form action ="__URL__/chuli" method="post">
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="8" class="topTd"></td></tr>
<tr class="row" align="center">
<th><input type="checkbox" id="check" onclick="CheckAll('checkList')"></th><th width="78"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th><th width="192"><a href="javascript:sortBy('source','<?php echo ($sort); ?>','index')">地点名称</a></th><th width="362"><a href="javascript:sortBy('error_content','<?php echo ($sort); ?>','index')">错误内容</a></th><th width="141"><a href="javascript:sortBy('level','<?php echo ($sort); ?>','index')">错误等级</a></th><th width="252"><a href="javascript:sortBy('log_time','<?php echo ($sort); ?>','index')">记录时间</a></th><th width="154">状态</th><th width="129">操作</th></tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td width="6"><input type="checkbox" name="id" value="<?php echo ($vo["id"]); ?>"></td>
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo ($vo["source"]); ?></td>
<td><?php echo ($vo["error_content"]); ?></td>
<td><?php if(($vo["level"])  ==  "2"): ?>一般<?php endif; ?><?php if(($vo["level"])  ==  "1"): ?>严重<?php endif; ?></td>
<td><?php echo (date("Y-m-d H:i:s",$vo["log_time"])); ?></td>
<td><?php if(($vo["status"])  ==  "-1"): ?>已处理<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>未处理<?php endif; ?></td>
<td><?php if(($vo["status"])  ==  "0"): ?><a href="javascript:dealerror('<?php echo ($vo["id"]); ?>')">设为已处理</a><?php endif; ?></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="8" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><select name="status">
                    <option value="">批处理···</option>
                    <option value="0">未处理</option>
                    <option value="-1">已处理</option>
                    </select>
    <input  type="submit" name="del" value="应用" />&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->