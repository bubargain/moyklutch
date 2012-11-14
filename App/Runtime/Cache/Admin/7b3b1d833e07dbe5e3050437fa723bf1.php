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
<script src="__PUBLIC__/Js/AjaxCls.js" type="text/javascript" ></script>
<script language="javascript">
function alertContents()
{
  if(Http_Request.readyState==4)
  {
     if(Http_Request.status==200)
     {
        XMLDocument=Http_Request.responseText;
		if(XMLDocument!="None")
        {
		   Fillset(XMLDocument);
	    }
	 }
     else
     {
        XMLDocument=null;
     }
  }
	  else
  {
     XMLDocument=null;
  }
}
function Fillset(str)
{
  var strarr=str.split('|');
  document.getElementById("area").options.length=0;
  //form1.selCity.options.length=0;
  for(var i=0;i<strarr.length;i++)
  {
	  var cate=strarr[i].split(',');
      document.getElementById("area").options.add(new Option(cate[0],cate[1]));
  }
}
function setcate(bid)
{
  oAjax.sendUrl="index.php?g=Admin&m=Area&s=ajaxarea&id="+bid;
  oAjax.getRequest();
  oAjax.getXMLResponse();
}
</script>
<div id="main" class="main" >
<div class="content">
<div class="title">请为<font color="#990000"><?php echo ($title); ?></font>选择展位 [ <a href="__URL__">返回列表</a> ]</div>
<form method='post' name="form1" id="form1" action="">
<table width="1126" cellpadding="1" cellspacing="0" align="left"><input type="hidden" name="id" value="<?php echo ($id); ?>" /><input type="hidden" name="flag" value="1" />
<td width="346">大商圈：
  <select name="areab" onchange="setcate(this.options[this.selectedIndex].value);">
  <option value="">-请选择-</option>
  <?php echo get_area_option(); ?>
</select>
小商圈：<select name="area" onchange="setpid(this.options[this.selectedIndex].value);">
<option value="">-请选择-</option>
</select>
</td>
<td width="193">展位状态：<select name="status">
  <option <?php if(($status)  ==  "1"): ?>selected<?php endif; ?> value="1">可直接购买</option>
  <option <?php if(($status)  ==  "2"): ?>selected<?php endif; ?> value="2">一个月内到期</option>
  <option <?php if(($status)  ==  ""): ?>selected<?php endif; ?> value="">不限</option>
</select></td>
<td width="435">展位价格：<input type="text" name="money1" size="5" value="<?php echo ($money1); ?>">~<input type="text" name="money2" size="5" value="<?php echo ($money2); ?>"></td>
</tr>
<tr>

<td colspan="3">位置：
<input name="tdx[]" type="checkbox" checked="checked" value="DX1" />DX1&nbsp;
<input name="tdx[]" type="checkbox" checked="checked"  value="DX2" />DX2&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX3" />DX3&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX4" />DX4&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX5" />DX5&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX6" />DX6&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX7" />DX7&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX8" />DX8&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX9" />DX9&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX10" />DX10&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX11" />DX11&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX12" />DX12&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX13" />DX13&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX14" />DX14&nbsp;
<input name="tdx[]" type="checkbox" checked="checked" value="DX15" />DX15&nbsp;
<input type="submit" value="查询" class="small hMargin shadow submit" onclick="formsubmit2();">
</td></tr>
</table>
</form>
</div>
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="11" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="41"></th>
<th width="136">编号</th>
<th width="309">打印机位置</th>
<th width="239">按钮位置</th>
<th width="583">状态</th>
</tr><form action="__URL__/add4/" method="post"><input type="submit" value="购买选中" class="small hMargin shadow submit"><input type="hidden" name="id" value="<?php echo ($id); ?>" /><input type="hidden" name="title" value="<?php echo ($title); ?>" />
<?php  if(is_array($exhlist)): foreach($exhlist as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<th width="41"><input name="dxlist[]" type="checkbox" value="<?php echo ($vo["id"]); ?>" /></th>
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo (get_position($vo["p_id"])); ?></td>
<td><?php echo ($vo["bt_position"]); ?></td>
<td align="left"><?php echo (get_rendinfo($vo["id"])); ?></td>
</tr><?php endforeach; endif; ?></form>
<tr><td height="5" colspan="11" class="bottomTd"></td></tr>
</table>
</div>
</div>