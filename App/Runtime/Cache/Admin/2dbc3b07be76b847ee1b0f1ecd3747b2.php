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
  document.getElementById("cate_id").options.length=0;
  //form1.selCity.options.length=0;
  for(var i=0;i<strarr.length;i++)
  {
	  var cate=strarr[i].split(',');
      document.getElementById("cate_id").options.add(new Option(cate[0],cate[1]));
  }
}
function setcate(bid)
{
  oAjax.sendUrl="index.php?g=Admin&m=Cate&s=ajaxcate&id="+bid;
  oAjax.getRequest();
  oAjax.getXMLResponse();
}
</script>
<div id="main" class="main" >
<div class="content">
<div class="title">编辑优惠券 [ <a href="__URL__">返回列表</a> ]</div>
<table cellpadding=3 cellspacing=3>
<form method='post' id="form1" action="__URL__/update/"  enctype="multipart/form-data">
<tr>
	<td class="tRight" >名称：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="title" value="<?php echo ($vo["title"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >商品类别：</td>
	<td class="tLeft" ><select name="cate_bid" onchange="setcate(this.options[this.selectedIndex].value,'cate_id');">
  <option value="">-请选择-</option>
  <?php echo get_cate_option(); ?>
</select>
子类：<select id="cate_id" name="cate_id">
    <?php  if(is_array($cate)): foreach($cate as $key=>$voo): ?><option value="<?php echo ($voo["id"]); ?>" <?php if(($voo["id"])  ==  $vo["cate_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($voo["title"]); ?></option><?php endforeach; endif; ?>
    </select></td>
</tr>
<tr>
	<td class="tRight" >状态：</td>
	<td class="tLeft" ><select name="status">
<option value="1" <?php if(($vo["status"])  ==  "1"): ?>selected="selected"<?php endif; ?> >已审核</option>
<option value="0" <?php if(($vo["status"])  ==  "0"): ?>selected="selected"<?php endif; ?> >待审核</option>
<option value="-1" <?php if(($vo["status"])  ==  "-1"): ?>selected="selected"<?php endif; ?> >禁用</option>
</select></td>
</tr>
<tr>
	<td class="tRight" >数量：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="num" value="<?php echo ($vo["num"]); ?>">(-1代表无限制)</td>
</tr>
<tr>
	<td class="tRight" >优惠券开始时间：</td>
	<td class="tLeft" ><input type="text" name="start_time" size="10" onFocus="ShowCalendar(this)" value="<?php echo (toDate($vo["start_time"])); ?>">结束时间：<input type="text" name="close_time" size="10" onFocus="ShowCalendar(this)" value="<?php echo (toDate($vo["close_time"])); ?>"></td>
</tr>
<tr>
	<td class="tRight" >商家获得结算额：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="money" value="<?php echo ($vo["money"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >下载需扣积分：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="score" value="<?php echo ($vo["score"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >上传logo：</td>
	<td class="tLeft" ><input name="Img" type="file" id="Img"/><?php if($vo["logo"] == ''): ?><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="200" height="100"><?php endif; ?></td>
</tr>
<tr>
	<td class="tRight" >排序：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="sortnum" value="<?php echo ($vo["sortnum"]); ?>">数值大的排前</td>
</tr>
<tr>
	<td class="tRight" >关键字：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="keyword" value="<?php echo ($vo["keyword"]); ?>"></td>
</tr>
<tr>
	<td class="tRight" >标签：</td>
	<td class="tLeft" ><input type="text" class="medium bLeft" name="tags" value="<?php echo ($vo["tags"]); ?>"></td>
</tr>
<tr>
	<td class="tRight tTop">注意事项：</td>
	<td class="tLeft"><textarea class="large bLeft"  name="remark" rows="5" cols="38"><?php echo ($vo["attention"]); ?></textarea></td>
</tr>
<tr>
	<td class="tRight tTop">商品摘要：</td>
	<td class="tLeft"><textarea class="large bLeft"  name="remark" rows="5" cols="38"><?php echo ($vo["introduce"]); ?></textarea></td>
</tr>
<tr>
	<td class="tRight tTop">详细内容：</td>
	<td class="tLeft"><script type="text/javascript" src="__ROOT__/Public/Js/KindEditor/kindeditor.js"></script><script type="text/javascript"> KE.show({ id : 'content'  ,urlType : "absolute"});</script><textarea id="content" style="width:550px;height:220px" name="content" ><?php echo ($vo["content"]); ?></textarea></td>
</tr>
<tr>
	<td></td>
	<td class="center"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" >
	<input type="submit" value="编辑"  class="small submit">
	</td>
</tr>
</table>
</form>
</div>
</div>