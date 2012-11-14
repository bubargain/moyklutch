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

function setdate(id){
	if(id=="")
	{
	  document.getElementById("s_time1").value="";
	  document.getElementById("s_time2").value="";
	}else{
	  var now = new Date();
	  var n=now.getDay();
	  if(id=="11"){  //明日
		  t1=now.getTime()+24*60*60*1000;
		  t2=now.getTime()+24*60*60*1000;
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="22"){  //3日内
		  t1=now.getTime()+24*60*60*1000;
		  t2=t1+24*60*60*1000*3;
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="33"){  //5日内
		  t1=now.getTime()+24*60*60*1000;
		  t2=t1+24*60*60*1000*5;
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="44"){  //下周
	  	  var ldate=new Date(now-(now.getDay()-8)*86400000);
		  var tdate=new Date((ldate/1000+6*86400)*1000);
		  var y=ldate.getFullYear();
	      var m=ldate.getMonth()+1;
	      var d=ldate.getDate();
		  var ly=tdate.getFullYear();
		  var lm=tdate.getMonth()+1;
		  var ld=tdate.getDate();
	  }else if(id=="66"){  //下月
	  	  var ldate=new Date(now.getYear(),now.getMonth()+1,1);
		  var MonthNextFirstDay=new Date(now.getYear(),now.getMonth()+2,1);
		  var tdate=new Date(MonthNextFirstDay-86400000);
	      var y=ldate.getFullYear();
	      var m=ldate.getMonth()+1;
	      var d=ldate.getDate();
		  var ly=tdate.getFullYear();
		  var lm=tdate.getMonth()+1;
		  var ld=tdate.getDate();
	  }else{
		  var ldate2=new Date();
		  t1=now.getTime()-24*60*60*1000;
		  ldate2.setTime(t1);
		  var y=ldate2.getFullYear();
		  var m=ldate2.getMonth()+1;
		  var d=ldate2.getDate();
		  var ldate=new Date();
		  t2=t1-24*60*60*1000*id;
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }
	document.getElementById("s_time2").value=ly+"-"+lm+"-"+ld;
	document.getElementById("s_time1").value=y+"-"+m+"-"+d;
	}
}
function setdate2(id){
	if(id=="")
	{
	  document.getElementById("c_time1").value="";
	  document.getElementById("c_time2").value="";
	}else{
	  var now = new Date();
	  var n=now.getDay();
	  if(id=="11"){  //明日
		  t1=now.getTime()+24*60*60*1000;
		  t2=now.getTime()+24*60*60*1000;
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="22"){  //3日内
		  t1=now.getTime()+24*60*60*1000;
		  t2=t1+24*60*60*1000*3;
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="33"){  //5日内
		  t1=now.getTime()+24*60*60*1000;
		  t2=t1+24*60*60*1000*5;
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="44"){  //下周
	  	  var ldate=new Date(now-(now.getDay()-8)*86400000);
		  var tdate=new Date((ldate/1000+6*86400)*1000);
		  var y=ldate.getFullYear();
	      var m=ldate.getMonth()+1;
	      var d=ldate.getDate();
		  var ly=tdate.getFullYear();
		  var lm=tdate.getMonth()+1;
		  var ld=tdate.getDate();
	  }else if(id=="66"){  //下月
	  	  var ldate=new Date(now.getYear(),now.getMonth()+1,1);
		  var MonthNextFirstDay=new Date(now.getYear(),now.getMonth()+2,1);
		  var tdate=new Date(MonthNextFirstDay-86400000);
	      var y=ldate.getFullYear();
	      var m=ldate.getMonth()+1;
	      var d=ldate.getDate();
		  var ly=tdate.getFullYear();
		  var lm=tdate.getMonth()+1;
		  var ld=tdate.getDate();
	  }
	document.getElementById("c_time2").value=ly+"-"+lm+"-"+ld;
	document.getElementById("c_time1").value=y+"-"+m+"-"+d;
	}
}
</script>
<!-- 主页面开始 -->
<div id="main" class="main" >

<!-- 主体内容  -->
<div class="content" >
<div class="title">展位租赁列表</div>
<!--  功能操作区域  -->
<div class="operate" >
<input type="button" name="add" value="新增" class="small hMargin shadow submit" onclick="add();"><input type="button" name="exp" value="导出" class="small hMargin shadow submit" onclick="formsubmit();"><input type="button" name="exp" value="导出明天生效的展位" class="hMargin shadow submit" onclick="tomorrow();">
<form method='post' name="form1" id="form1" action="__URL__">
</div>
<!-- 查询区域 -->
<div id="fRig">
<div id="searchM">
<table width="982" cellpadding="1" cellspacing="0" align="left"><input type="hidden" name="flag" value="1" />
<td width="185">商家ID<input type="text" name="trade_id" title="商家ID" size="10" value="<?php echo ($trade_id); ?>"></td>
<td width="371">机器放置地点ID<input type="text" name="p_id" title="放置地点ID" size="10" value="<?php echo ($p_id); ?>"></td>
<td width="150">状态：<select name="status">
  <option <?php if(($status)  ==  "1"): ?>selected<?php endif; ?> value="1">正常</option>
  <option <?php if(($status)  ==  ""): ?>selected<?php endif; ?> value="">不限</option>
  <option <?php if(($status)  ==  "0"): ?>selected<?php endif; ?> value="0">预约</option>
  <option <?php if(($status)  ==  "-1"): ?>selected<?php endif; ?> value="-1">删除或到期</option>
</select></td>
</tr>
<tr>
<td colspan="2">租赁起始时间:<select name="tt" onchange="setdate(this.options[this.selectedIndex].value);">
  <option  value="" selected="selected">全部</option>
  <option <?php if(($tt)  ==  "11"): ?>selected<?php endif; ?> value="11" >明日</option>
  <option <?php if(($tt)  ==  "22"): ?>selected<?php endif; ?> value="22" >3日内</option>
  <option <?php if(($tt)  ==  "33"): ?>selected<?php endif; ?> value="33" >5日内</option>
  <option <?php if(($tt)  ==  "44"): ?>selected<?php endif; ?> value="44" >下周</option>
  <option <?php if(($tt)  ==  "66"): ?>selected<?php endif; ?> value="66" >下月</option>
</select><input type="text" id="s_time1" name="s_time1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($s_time1); ?>">~<input type="text" id="s_time2" name="s_time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($s_time2); ?>"></td>
<td colspan="2">租赁到期时间：<select name="ttt" onchange="setdate2(this.options[this.selectedIndex].value);">
  <option  value="" selected="selected">全部</option>
  <option <?php if(($tt)  ==  "11"): ?>selected<?php endif; ?> value="11" >明日</option>
  <option <?php if(($tt)  ==  "22"): ?>selected<?php endif; ?> value="22" >3日内</option>
  <option <?php if(($tt)  ==  "33"): ?>selected<?php endif; ?> value="33" >5日内</option>
  <option <?php if(($tt)  ==  "44"): ?>selected<?php endif; ?> value="44" >下周</option>
  <option <?php if(($tt)  ==  "66"): ?>selected<?php endif; ?> value="66" >下月</option>
</select><input type="text" id="c_time1" name="c_time1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($c_time1); ?>">~<input type="text" id="c_time2" name="c_time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($c_time2); ?>">
</td></tr>
<tr><td colspan="4">结果排序：<select name="resultid">
  <option <?php if(($resultid)  ==  "p_id"): ?>selected<?php endif; ?> value="id" >地点ID</option>
  <option <?php if(($resultid)  ==  "trade_id"): ?>selected<?php endif; ?> value="trade_id" >商家</option>
  <option <?php if(($resultid)  ==  "start_time"): ?>selected<?php endif; ?> value="start_time" >开始时间</option>
  <option <?php if(($resultid)  ==  "close_time"): ?>selected<?php endif; ?> value="close_time" >结束时间</option>
  <option <?php if(($resultid)  ==  "prints"): ?>selected<?php endif; ?> value="prints" >打印</option>
</select>
<select name="sortid">
  <option <?php if(($sortid)  ==  "desc"): ?>selected<?php endif; ?> value="desc" >递减</option>
  <option <?php if(($sortid)  ==  "asc"): ?>selected<?php endif; ?> value="asc" >递增</option>
</select><input type="submit" value="查询" class="small hMargin shadow submit" onclick="formsubmit2();"></td></tr>
</table>
</div>
</div></form>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="12" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="40"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">编号</a></th>
</th><th width="105"><a href="javascript:sortBy('p_id','<?php echo ($sort); ?>','index')">打印机位置</a></th>
<th width="70"><a href="javascript:sortBy('bt_position','<?php echo ($sort); ?>','index')">按钮位置</a></th>
<th width="100"><a href="javascript:sortBy('trade_id','<?php echo ($sort); ?>','index')">商家</a></th>
<th width="40">打印</th>
<th width="100"><a href="javascript:sortBy('start_time','<?php echo ($sort); ?>','index')">开始时间</a></th>
<th width="90"><a href="javascript:sortBy('close_time','<?php echo ($sort); ?>','index')">结束时间</a></th>
<th width="90"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">申请时间</a></th>
<th width="205">备注</th><th width="50"><a href="javascript:sortBy('status','<?php echo ($sort); ?>','index')">状态</th>
<th width="70">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><a href="__APP__/Admin/Location/showexh/id/<?php echo ($vo["p_id"]); ?>" title="" target='_blank'><?php echo (get_position($vo["p_id"])); ?></a></td>
<td><?php echo ($vo["bt_position"]); ?></td>
<td><a href="__APP__/Admin/Trade/edit/id/<?php echo ($vo["trade_id"]); ?>" target="_blank"><?php echo (get_trade($vo["trade_id"])); ?></a></td>
<td><?php echo ($vo["prints"]); ?></td>
<td><?php echo (date("Y-m-d",$vo["start_time"])); ?></td>
<td><?php echo (date("Y-m-d",$vo["close_time"])); ?></td>
<td><?php echo (date("Y-m-d",$vo["create_time"])); ?></td>
<td><input type="text" id="remark<?php echo ($vo["id"]); ?>" name="remark" size="28" value="<?php echo ($vo["remark"]); ?>" style="border:#FFF 1px solid;" onchange="aupdate('tenancy','<?php echo ($vo["id"]); ?>','remark')"></td>
<td><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?>预订<?php endif; ?>
<?php if(($vo["status"])  ==  "-1"): ?>到期<?php endif; ?>
<?php if(($vo["status"])  ==  "-2"): ?>撤销<?php endif; ?></td>
<td><a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;<?php if(($vo["status"])  ==  "1"): ?><a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">禁用</a><?php endif; ?><?php if(($vo["status"])  ==  "-1"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?><?php if(($vo["status"])  ==  "0"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?>&nbsp;<a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="12" class="bottomTd"></td></tr>
</table>
</div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->