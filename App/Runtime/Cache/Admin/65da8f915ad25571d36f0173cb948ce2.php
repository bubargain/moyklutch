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
  oAjax.sendUrl="/Admin/Area/ajaxarea/id/"+bid;
  oAjax.getRequest();
  oAjax.getXMLResponse();
}
function checkval(myform,car){
	var name=document.getElementById(car).value;
	if(name=="")
	{
		alert("请选择!");
		return (false);
	}
	}
	
function Juge(myform)
{
    if
	 (myform.name.value == "")
	{
		alert("名称不能为空!");
		myform.name.focus();
		return (false);
	}
	else if (myform.area.value == "")
	{
		alert("请选择商圈!");
		myform.area.focus();
		return (false);
	}
	else if (myform.macno.value == "")
	{
		alert("请填写机器码!");
		myform.macno.focus();
		return (false);
	}else if (myform.macno.value.length >8)
         {
                alert("机器码不能超过8位!");
		myform.macno.focus();
		return (false);
         }
	else if (myform.start_time.value == "")
	{
		alert("请填写地点租赁开始时间！");
		myform.start_time.focus();
		return (false);
	}
	else if (myform.close_time.value == "")
	{
		alert("请填写地点租赁结束时间！");
		myform.close_time.focus();
		return (false);
	}
}
</script>
<div id="main" class="main" >
<div class="content">
<div class="title">新增机器存放地点 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1"  name="myform" action="__URL__/insert/" onSubmit="return Juge(this)" >
<tr>
	<td class="tRight" >地点名称：</td>
	<td class="tLeft" ><input type="text" class="medium bLeftRequire"  check='^\S+$' warning="名称不能为空,且不能含有空格" name="name" value=""></td>
</tr>
<tr>
	<td class="tRight" >归属地区：</td>
	<td class="tLeft" >
    <select name="areab" onchange="setcate(this.options[this.selectedIndex].value);">
  <option value="">-请选择-</option>
  <?php echo get_area_option(); ?>
</select>
小商圈：<select name="area"  id="area">
<option value="">-请选择-</option>
</select>
    </td>
</tr>
<tr>
	<td class="tRight" >详细地址：</td>
	<td class="tLeft" ><input type="text" name="address" size="25" value=""></td>
</tr>
<tr>
	<td class="tRight" >联系人：</td>
	<td class="tLeft" ><input type="text" name="contact" size="25" value=""></td>
</tr>
<tr>
	<td class="tRight" >联系电话：</td>
	<td class="tLeft" ><input type="text" name="phone" size="25" value=""></td>
</tr>
<tr>
	<td class="tRight" >机器码：</td>
	<td class="tLeft" ><input type="text" name="macno" size="10" value=""></td>
</tr>
<tr>
	<td class="tRight" >展位租金：</td>
	<td class="tLeft" ><input type="text" name="money" size="10" value="0.00">元/周</td>
</tr>
<tr>
	<td class="tRight" >开机时间：</td>
	<td class="tLeft" ><input type="text" name="open" size="10" value="6">时(24小时制)</td>
</tr>
<tr>
	<td class="tRight" >关机时间：</td>
	<td class="tLeft" ><input type="text" name="close" size="10" value="23">时(24小时制)</td>
</tr>
<tr>
	<td class="tRight" >开始时间：</td>
	<td class="tLeft" ><input type="text" name="start_time" size="10" onFocus="ShowCalendar(this)" value=""></td>
</tr>
<tr>
	<td class="tRight" >到期时间：</td>
	<td class="tLeft" ><input type="text" name="close_time" size="10" onFocus="ShowCalendar(this)" value=""></td>
</tr>
<tr>
	<td class="tRight">状态：</td>
	<td class="tLeft"><SELECT class="small bLeft"  name="status">
	<option value="0">预约</option>
    <option value="1">正常</option>
	<option value="-1">过期</option>
	</SELECT></td>
</tr>
<tr>
	<td class="tRight tTop">备  注：</td>
	<td class="tLeft"><TEXTAREA class="large bLeft"  name="remark" rows="5" cols="57"></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center">
	<input type="submit" value="保 存" class="small submit">
	<input type="reset" class="submit  small" value="清 空" >
	</td>
</tr>
</table>
</form>
</div>
</div>