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
  oAjax.sendUrl=APP+"/Admin/Area/ajaxarea/id/"+bid;
  oAjax.getRequest();
  oAjax.getXMLResponse();
}
</script>
<!-- 主页面开始 -->
<div id="main" class="main" >

<!-- 主体内容  -->
<div class="content" >
<div class="title">地点列表</div>
<!--  功能操作区域  -->
<div class="operate" >
<input type="button" name="add" value="新增" class="small hMargin shadow submit" onclick="add();">
</div>
<!-- 查询区域 -->
<div id="fRig">
<!-- 高级查询区域 -->
<div id="searchM">
<table width="1170" cellpadding="1" cellspacing="0">
  <form method='post' action="__URL__"><input type="hidden" name="flag" value="1" />
<tr><td width="317">地点名称：
  <input type="text" name="name" title="地点名称" size="6" value="<?php echo ($name); ?>"></td>
  <td colspan="2">机器编码：
  <input type="text" name="macno" title="机器编码" size="6" value="<?php echo ($macno); ?>">&nbsp;&nbsp;
归属商圈：
  <select name="areab" onchange="setcate(this.options[this.selectedIndex].value);">
  <option value="">-请选择-</option>
  <?php echo get_area_option(); ?>
</select>
小商圈：<select name="area" id="area">
<option value="">-请选择-</option>
</select>
</td>
<td width="353">状态：
  <select name="status">
  <option value="">不限</option>
  <option <?php if(($status)  ==  "-1"): ?>selected<?php endif; ?> value="-1">过期</option>
  <option <?php if(($status)  ==  "0"): ?>selected<?php endif; ?> value="0">预约</option>
  <option <?php if(($status)  ==  "1"): ?>selected<?php endif; ?> value="1">正常</option>
</select></td>
</tr>
<tr>
<td>租赁开始时间：
  <input type="text" name="s_time1" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($s_time1); ?>">~<input type="text" name="s_time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($s_time2); ?>"></td><td width="460">租赁结束时间：
    <input type="text" name="c_time1" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($c_time1); ?>">~<input type="text" name="c_time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($c_time2); ?>"></td><td  colspan="2">地点到期时间：
  <select name="tstatus">
  <option value="">全部</option>
  <option <?php if(($tstatus)  ==  "1"): ?>selected<?php endif; ?> value="1">一个月</option>
  <option <?php if(($tstatus)  ==  "2"): ?>selected<?php endif; ?> value="2">两个月</option>
  <option <?php if(($tstatus)  ==  "3"): ?>selected<?php endif; ?> value="3">三个月</option>
</select><input type="submit" value="查询" class="small hMargin shadow submit"></td></tr></form>
</table>
</div>
</div>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="12" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="53"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="154">地点名称</th>
<th width="138"><a href="javascript:sortBy('area','<?php echo ($sort); ?>','index')">归属商圈</a></th>
<th width="82">打印机编码</th>
<th width="80">已租赁（预约）</th>
<th width="80">打印数量</th>
<th width="121"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','index')">租金</a></th>
<th width="90"><a href="javascript:sortBy('open','<?php echo ($sort); ?>','index')">开机时间</a></th>
<th width="90"><a href="javascript:sortBy('close','<?php echo ($sort); ?>','index')">关机时间</a></th>
<th width="115"><a href="javascript:sortBy('link_time','<?php echo ($sort); ?>','index')">最后连接</th>
<th width="100">备注</th>
<th width="120">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" ondblclick="show_more('more<?php echo ($vo["id"]); ?>');" align="center">
<td onclick="show_more('more<?php echo ($vo["id"]); ?>');"><?php echo ($vo["id"]); ?></td>
<td><a style="cursor:pointer;" onclick="show_more('more<?php echo ($vo["id"]); ?>');"><?php echo ($vo["name"]); ?></a></td>
<td><?php echo (get_p_cate($vo["area"])); ?></td>
<td><?php echo ($vo["macno"]); ?></td>
<td><a href="javascript:showten('<?php echo ($vo["id"]); ?>')"><?php echo (get_rendnumbyloc($vo["id"])); ?></a></td>
<td><?php echo (get_countbyloc($vo["id"])); ?></td>
<td><?php echo ($vo["money"]); ?></td>
<td><?php echo ($vo["open"]); ?>时</td>
<td><?php echo ($vo["close"]); ?>时</td>
<td><?php echo (date("m-d:H-i",$vo["link_time"])); ?></td>
<td><input type="text" id="remark<?php echo ($vo["id"]); ?>" name="remark" size="15" value="<?php echo ($vo["remark"]); ?>" style="border:#FFF 1px solid;" onchange="aupdate('Location','<?php echo ($vo["id"]); ?>','remark')"></td>
<td><a href="javascript:showexh('<?php echo ($vo["id"]); ?>')">查看展位</a>&nbsp;
<a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;<br >
<a href="javascript:showprinten('<?php echo ($vo["id"]); ?>')">打印展位</a>&nbsp;
<?php if(($vo["status"])  ==  "1"): ?><a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">禁用</a><?php endif; ?>
<?php if(($vo["status"])  ==  "-1"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?>
</td>
</tr>
</tr>
<tr class="row" ondblclick="hidden_more('more<?php echo ($vo["id"]); ?>')"><td colspan="12">
<div id="more<?php echo ($vo["id"]); ?>" class="listdiv">
<li>联系人：<?php echo ($vo["contact"]); ?></li>
<li>联系电话：<?php echo ($vo["phone"]); ?></li>
<li>开始时间：<?php echo (date("Y-m-d",$vo["start_time"])); ?></li>
<li>结束时间：<?php echo (date("Y-m-d",$vo["close_time"])); ?></li>
<li>地址：<?php echo ($vo["address"]); ?></li>
<li>添加时间：<?php echo (date("Y-m-d",$vo["create_time"])); ?></li>
<li>更新时间：<?php echo (date("Y-m-d",$vo["update_time"])); ?></li>
<li>状态：<?php if(($vo["status"])  ==  "-1"): ?>已删除<?php endif; ?><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>预约<?php endif; ?></li>
</div></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="12" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->