<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<div id="main" class="main" >
<div class="content">
<div class="title">添加新闻分类 </div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1" action="__URL__/insert/" enctype="multipart/form-data">
<tr>
	<td class="tRight" >类别名称：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="name" value=""></td>
</tr>
<tr>
	<td class="tRight" >父级类别：</td>
	<td class="tLeft" ><select name="pid" >
  <option value="0" selected="selected">顶级分类</option>
  <?php if(is_array($alist)): $i = 0; $__LIST__ = $alist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo['id']); ?>">
				<?php for($i=0;$i<$vo['count']*2;$i++){
								echo '-';
					} ?>
				<?php echo ($vo['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select></td>
	<td >
	<input type="submit" value="添 加"  class="small submit">
	</td>
</tr>
</table>
</form>
</div>
</div>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="9" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="80"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="250"><a href="javascript:sortBy('title','<?php echo ($sort); ?>','index')">分类名称</a></th>

<th width="110">操作</th>
</tr>
<?php  if(is_array($alist)): foreach($alist as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" >
<td><?php echo ($vo["id"]); ?></td>
<td>	<?php for($i=0;$i<$vo['count']*2;$i++){
					echo '-';
			} ?>
        <?php echo ($vo["name"]); ?></td>
<td><a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;
&nbsp;<a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a>
</td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="3" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->