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
<div class="title">会员卡列表</div>
<!--  功能操作区域  -->
<div class="operate">
<div style="float:left;"><input type="button" name="add" value="新增" class="small hMargin shadow submit" onclick="add();"><input type="button" name="add" value="批量导入" class="small hMargin shadow submit" onclick="imports();"></div><form method='post' action="__URL__"><input type="submit" name="submit" value="删除过期卡号" class="hMargin shadow submit">
</div>
<!-- 查询区域 -->
<div id="fRig">
<!-- 高级查询区域 -->
<div id="searchM">
<table width="1097" cellpadding="1" cellspacing="0">
  <input type="hidden" name="flag" value="1" />
<tr><td width="102">ID：
  <input type="text" name="id" title="ID" size="5" value="<?php echo ($id); ?>"></td>
<td width="188">会员卡内码：
  <input type="text" name="card_id" title="ID" size="10" value="<?php echo ($card_id); ?>"></td>
<td width="181">是否绑定用户：
  <select name="userif">
  <option value="">不限</option>
  <option <?php if(($userif)  ==  "2"): ?>selected<?php endif; ?> value="2">已绑定</option>
  <option <?php if(($userif)  ==  "1"): ?>selected<?php endif; ?> value="1">未绑定</option>

</select></td>

<td width="334">创建时间：
  <input type="text" name="time1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">~<input type="text" name="time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($time2); ?>"></td><td width="117"><input type="submit" value="查询" class="small hMargin shadow submit"></td></tr>
</table>
</div>
</div></form>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="8" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="86"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="161"><a href="javascript:sortBy('card_id','<?php echo ($sort); ?>','index')">会员卡内码</a></th>
<th width="193">绑定用户</th>
<th width="133"><a href="javascript:sortBy('type_id','<?php echo ($sort); ?>','index')">卡类型</a></th>
<th width="216"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">创建时间</a></th>
<th width="180"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">最后使用时间</a></th>
<th width="75"><a href="javascript:sortBy('status','<?php echo ($sort); ?>','index')">状态</a></th>
<th width="163">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo ($vo["card_id"]); ?></td>
<td><?php echo (getuserbycard($vo["card_id"])); ?></td>
<td><?php if(($vo["type_id"])  ==  "0"): ?>本机卡<?php endif; ?>
<?php if(($vo["type_id"])  ==  "1"): ?>外部卡<?php endif; ?></td>
<td><?php echo (date("Y-m-d",$vo["create_time"])); ?></td>
<td><?php if(($vo["use_time"])  <  "10"): ?>--<?php else: ?><?php echo (date("Y-m-d",$vo["use_time"])); ?><?php endif; ?></td>
<td>
<?php switch($vo["status"]): ?><?php case "-1":  ?>禁用<?php break;?>
    <?php case "0":  ?>未激活<?php break;?>
    <?php case "1":  ?>未使用<?php break;?>
    <?php case "2":  ?>使用中<?php break;?><?php endswitch;?>
</td>
<td><a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;
<?php if(($vo["status"])  ==  "1"): ?><a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">禁用&nbsp;</a><?php endif; ?>
<?php if(($vo["status"])  ==  "-1"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?>
&nbsp;<a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a>
</td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="8" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->