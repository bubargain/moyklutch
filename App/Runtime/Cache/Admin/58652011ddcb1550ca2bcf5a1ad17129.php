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
<div class="title">秒杀<span style="color:red">结束</span>商品列表</div>
<!--  功能操作区域  -->
<div class="operate" >
</div>
<!-- 查询区域 -->
<div id="fRig">
<!-- 高级查询区域 -->
<div id="searchM">
<table width="600" cellpadding="1" cellspacing="0">
  <form method='post' action="__URL__"><input type="hidden" name="flag" value="1" />
<tr><td width="235">商品名：
  <input type="text" name="title" title="商品名" size="30" value="" ></td>
  <td width="200"><input type="submit" value="查询" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
</div>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="9" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="40"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="250">商品名称</th>
<th width="100">商品类型</th>
<th width="100">秒杀时间</th>
<th width="100">秒杀积分</th>
<th width="100">报名扣分</th>
<th width="100">返还扣分</th>
<th width="100">状态</th>
<th width="110">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><a href="__URL__/user/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></td>
<td><?php echo ($vo["classname"]); ?></td>
<td><?php echo (date("Y-m-d",$vo["starttime"])); ?><br><span style="color:red"><?php echo (date("H:i",$vo["starttime"])); ?>-<?php echo (date("H:i",$vo["endtime"])); ?></span></td>
<td><?php echo ($vo["score"]); ?></td>
<td><?php echo (intval($vo[score]*$vo[signupscore]/100)); ?></td>
<td><?php echo (intval($vo[score]*$vo[failscore]/100)); ?></td>
<td>
<?php if(($vo["status"])  ==  "1"): ?><?php if((get_seckilltype($vo["id"]))  ==  "0"): ?><span style="color:blue">秒杀失败</span>
	<?php else: ?>
		<span style="color:red">待取货</span><?php endif; ?><?php endif; ?>
<?php if(($vo["status"])  ==  "3"): ?><?php if((get_seckilltype($vo["id"]))  ==  "0"): ?><span style="color:blue">秒杀失败</span>
	<?php else: ?>
		已完成<?php endif; ?><?php endif; ?>


</td>
<td><a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a>
<?php if(($vo["status"])  ==  "1"): ?><?php if((get_seckilltype($vo["id"]))  ==  "0"): ?><?php else: ?>
		&nbsp;|&nbsp;<a href="__URL__/over/id/<?php echo ($vo["id"]); ?>">结束</a><?php endif; ?><?php endif; ?>
</td>
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