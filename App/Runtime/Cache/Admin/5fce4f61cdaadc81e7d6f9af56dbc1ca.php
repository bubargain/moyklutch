<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/style.css'>
<style>
html{overflow-x : hidden;}
</style>
<base target="main" />
</head>

<body >
<div id="menu" class="menu">
<table class="list shadow" cellpadding=0 cellspacing=0 >
<tr>
	<td height='5' colspan=7 class="topTd" ></td>
</tr>
<tr class="row" >
	<th class="tCenter space"><img SRC="__PUBLIC__/Images/home.gif" WIDTH="16" HEIGHT="16" BORDER="0" ALT="" align="absmiddle">后台管理</th>
</tr>
<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): ++$i;$mod = ($i % 2 )?><tr class="row " >
	<td><div style="margin:0px 5px"><img SRC="__PUBLIC__/Images/comment.gif" WIDTH="9" HEIGHT="9" BORDER="0" align="absmiddle" ALT="">
    <a href="__ROOT__/Admin/<?php echo ($item['module']); ?>/<?php echo ($item['action']); ?>/">
        <?php echo ($item['title']); ?>
    </a>
    </div></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
	<td height='5' colspan=7 class="bottomTd"></td>
</tr>
</table>
</div>
<script language="JavaScript">
<!--
	function refreshMainFrame(url)
	{
		parent.main.document.location = url;
	}
	if (document.anchors[0])
	{
		refreshMainFrame(document.anchors[0].href);
	}
//-->
</script>
</body>
</html>