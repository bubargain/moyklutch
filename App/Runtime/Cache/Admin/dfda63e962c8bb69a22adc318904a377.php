<?php if (!defined('THINK_PATH')) exit();?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>『MoyKlutch』By ThinkPHP <?php echo (THINK_VERSION); ?></title>
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
<div id="loader" >Page Loading...</div>
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
  document.getElementById("cate_id").options.length=0;
  //form1.selCity.options.length=0;
  for(var i=0;i<strarr.length;i++)
  {
	  var cate=strarr[i].split(',');
      //document.getElementById("cate_id").options.add(new Option(cate[0],cate[1]));
      var select_cate_id=document.getElementById("cate_id");
     select_cate_id.options.add(new Option(cate[0],cate[1]));

  }
}
function setcate(bid)
{
  oAjax.sendUrl="index.php?g=Admin&m=Cate&s=ajaxcate&id="+bid;
  oAjax.getRequest();
  oAjax.getXMLResponse();
}
function setdate(id){
	if(id=="")
	{
	  document.getElementById("tt1").value="";
	  document.getElementById("tt2").value="";
	}else{
	  var now = new Date();
	  var n=now.getDay();
	  if(id=="11"){  //今天
		  t1=now.getTime();
		  t2=now.getTime();
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
	  }else if(id=="22"){  //昨天
		  t1=now.getTime();
		  t2=t1-24*60*60*1000;
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
	  }else if(id=="33"){  //本周
		  var tdate=new Date();
		  tdate.setDate(now.getDate()-n+7);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setDate(now.getDate()-n+1);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="44"){  //上周
	  	  var ldate=new Date(now-(now.getDay()+6)*86400000);
		  var tdate=new Date((ldate/1000+6*86400)*1000);
		  var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=ldate.getDate();
	  }else if(id=="55"){  //本月
		  t1=now.getTime()+24*60*60*1000;
		  t2=now.getTime();
		  var tdate=new Date();
		  tdate.setTime(t1);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ldate=new Date();
		  ldate.setTime(t2);
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=1;
	  }else if(id=="66"){  //上月
	  	  var ldate=new Date(now.getYear(),now.getMonth()-1,1);
		  var MonthNextFirstDay=new Date(now.getYear(),now.getMonth(),1);
		  var tdate=new Date(MonthNextFirstDay-86400000);
	      var y=tdate.getFullYear();
	      var m=tdate.getMonth()+1;
	      var d=tdate.getDate();
		  var ly=ldate.getFullYear();
		  var lm=ldate.getMonth()+1;
		  var ld=1;
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
	document.getElementById("tt1").value=ly+"-"+lm+"-"+ld;
	document.getElementById("tt2").value=y+"-"+m+"-"+d;
	}
}
</script>
<!-- 主页面开始 -->
<div id="main" class="main" >

<!-- 主体内容  -->
<div class="content" >
<div class="title">优惠券列表 </div>
<!--  功能操作区域  -->
<div class="operate" ><input type="button" name="exp" value="导出" class="small hMargin shadow submit" onclick="formsubmit();">
<form method='post' name="form1" id="form1" action="__URL__">
</div>
<!-- 查询区域 -->
<div id="fRig">
<!-- 高级查询区域 -->
<div id="searchM">
<table width="1173" cellpadding="1" cellspacing="0">
<input type="hidden" name="flag" value="1" />
<tr><td width="118">ID：
  <input type="text" name="id" title="ID" size="5" value="<?php echo ($id); ?>"></td>
<td width="232">优惠券名称：
  <input type="text" name="title" title="按名称查询" size="8" value="<?php echo ($title); ?>" ></td>
<td width="140">商家：<input type="text" name="trade_id" title="按名称查询" size="8" value="<?php echo ($trade_id); ?>" ></td>
<td width="376">优惠券大类：
  <select name="cate_bid" id="cate_bid" onchange="setcate(this.options[this.selectedIndex].value);">
  <option value="">-请选择-</option>
  <?php echo get_cate_option(); ?>
</select>
子类：<select name="cate_id" id="cate_id">
<option value="">-请选择-</option>
</select>
</td>
<td width="305">状态：
  <select name="status">
  <option <?php if(($status)  ==  "1"): ?>selected<?php endif; ?> value="1">已审核</option>
  <option <?php if(($status)  ==  "3"): ?>selected<?php endif; ?> value="">不限</option>
  <option <?php if(($status)  ==  "0"): ?>selected<?php endif; ?> value="0">待审核</option>
  <option <?php if(($status)  ==  "-1"): ?>selected<?php endif; ?> value="-1">禁用</option>
</select></td></tr>
<tr>
<td colspan="5">创建时间：
  <input type="text" name="time1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">~<input type="text" name="time2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($time2); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;标签<input type="text" name="tags" size="5" value="<?php echo ($tags); ?>">&nbsp;&nbsp;&nbsp;&nbsp;券状态：
  <select name="tstatus">
  <option value="">全部</option>
  <option <?php if(($tstatus)  ==  "1"): ?>selected<?php endif; ?> value="1">有效</option>
  <option <?php if(($tstatus)  ==  "-1"): ?>selected<?php endif; ?> value="-1">过期</option>
  <option <?php if(($tstatus)  ==  "0"): ?>selected<?php endif; ?> value="0">未开始</option>
  
</select>&nbsp;&nbsp;&nbsp;&nbsp;统计筛选:<select name="tt" onchange="setdate(this.options[this.selectedIndex].value);">
  <option  value="" selected="selected">全部</option>
  <option <?php if(($tt)  ==  "11"): ?>selected<?php endif; ?> value="11" >今天</option>
  <option <?php if(($tt)  ==  "22"): ?>selected<?php endif; ?> value="22" >昨天</option>
  <option <?php if(($tt)  ==  "33"): ?>selected<?php endif; ?> value="33" >本周</option>
  <option <?php if(($tt)  ==  "44"): ?>selected<?php endif; ?> value="44" >上周</option>
  <option <?php if(($tt)  ==  "55"): ?>selected<?php endif; ?> value="55" >本月</option>
  <option <?php if(($tt)  ==  "66"): ?>selected<?php endif; ?> value="66" >上月</option>
  <option <?php if(($tt)  ==  "7"): ?>selected<?php endif; ?> value="7"  >过去7天</option>
  <option <?php if(($tt)  ==  "30"): ?>selected<?php endif; ?> value="30" >过去30天</option>
  <option <?php if(($tt)  ==  "90"): ?>selected<?php endif; ?> value="90" >过去90天</option>
</select><input type="text" name="tt1" id="tt1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($tt1); ?>">~<input type="text" id="tt2" name="tt2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($tt2); ?>"><input type="submit" value="查询" class="small hMargin shadow submit" onclick="formsubmit2();"></td></tr>
<tr><td colspan="6">结果排序：<select name="resultid">
  <option <?php if(($resultid)  ==  "id"): ?>selected<?php endif; ?> value="id" >ID</option>
  <option <?php if(($resultid)  ==  "cate_id"): ?>selected<?php endif; ?> value="cate_id" >分类</option>
  <option <?php if(($resultid)  ==  "trade_id"): ?>selected<?php endif; ?> value="trade_id" >商家</option>
  <option <?php if(($resultid)  ==  "score"): ?>selected<?php endif; ?> value="score" >打印积分</option>
  <option <?php if(($resultid)  ==  "money"): ?>selected<?php endif; ?> value="money" >结算金额</option>
  <option <?php if(($resultid)  ==  "start_time"): ?>selected<?php endif; ?> value="start_time" >开始时间</option>
  <option <?php if(($resultid)  ==  "close_time"): ?>selected<?php endif; ?> value="close_time" >结束时间</option>
  <option <?php if(($resultid)  ==  "create_time"): ?>selected<?php endif; ?> value="create_time" >创建时间</option>
  <option <?php if(($resultid)  ==  "update_time"): ?>selected<?php endif; ?> value="update_time" >更新时间</option>
  <option <?php if(($resultid)  ==  "point"): ?>selected<?php endif; ?> value="point" >点击</option>
  <option <?php if(($resultid)  ==  "collect"): ?>selected<?php endif; ?> value="collect" >收藏</option>
  <option <?php if(($resultid)  ==  "prints"): ?>selected<?php endif; ?> value="prints" >打印</option>
</select>
<select name="sortid">
  <option <?php if(($sortid)  ==  "desc"): ?>selected<?php endif; ?> value="desc" >递减</option>
  <option <?php if(($sortid)  ==  "asc"): ?>selected<?php endif; ?> value="asc" >递增</option>
</select>
显示字段：
<input name="clist[]" type="checkbox"  value="point"   <?php if(($menupoint)  ==  "1"): ?>checked="checked"<?php endif; ?>/>点击&nbsp;
<input name="clist[]" type="checkbox"  value="prints"  <?php if(($menuprints)  ==  "1"): ?>checked="checked"<?php endif; ?>/>打印&nbsp;
<input name="clist[]" type="checkbox"  value="collect" <?php if(($menucollect)  ==  "1"): ?>checked="checked"<?php endif; ?>/>收藏&nbsp;
<input name="clist[]" type="checkbox"  value="trade_id" <?php if(($menutrade_id)  ==  "1"): ?>checked="checked"<?php endif; ?>/>商家&nbsp;
<input name="clist[]" type="checkbox"  value="cate_id" <?php if(($menucate_id)  ==  "1"): ?>checked="checked"<?php endif; ?>/>分类&nbsp;
<input name="clist[]" type="checkbox"  value="score"  <?php if(($menuscore)  ==  "1"): ?>checked="checked"<?php endif; ?>/>积分&nbsp;
<input name="clist[]" type="checkbox"  value="start_time" <?php if(($menustart_time)  ==  "1"): ?>checked="checked"<?php endif; ?>/>开始时间&nbsp;
<input name="clist[]" type="checkbox"  value="close_time" <?php if(($menuclose_time)  ==  "1"): ?>checked="checked"<?php endif; ?>/>结束时间&nbsp;
<input name="clist[]" type="checkbox"  value="create_time" <?php if(($menucreate_time)  ==  "1"): ?>checked="checked"<?php endif; ?>/>创建时间&nbsp;
<input name="clist[]" type="checkbox"  value="update_time" <?php if(($menuupdate_time)  ==  "1"): ?>checked="checked"<?php endif; ?>/>更新时间&nbsp;
<input name="clist[]" type="checkbox"  value="keyword"  <?php if(($menukeyword)  ==  "1"): ?>checked="checked"<?php endif; ?>/>关键字&nbsp;
<input name="clist[]" type="checkbox"  value="money"  <?php if(($menumoney)  ==  "1"): ?>checked="checked"<?php endif; ?>/>商家结算金额&nbsp;
</td></tr>
</table>
</div>
</div></form>
<!-- 功能操作区域结束 -->

<!-- 列表显示区域  -->
<div class="list">
<table id="checkList" cellpadding="0" cellspacing="0" width="100%" class="list">
<tr><td height="5" colspan="16" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="54" ><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">ID</a></th>
<th width="109">优惠券名称</th>
<?php if(($menucate_id)  ==  "1"): ?><th width="115"><a href="javascript:sortBy('cate_id','<?php echo ($sort); ?>','index')">分类</a></th><?php endif; ?>
<?php if(($menutrade_id)  ==  "1"): ?><th width="115"><a href="javascript:sortBy('trade_id','<?php echo ($sort); ?>','index')">商家</a></th><?php endif; ?>
<?php if(($menuscore)  ==  "1"): ?><th width="85"><a href="javascript:sortBy('score','<?php echo ($sort); ?>','index')">打印所需积分</a></th><?php endif; ?>
<?php if(($menumoney)  ==  "1"): ?><th width="85"><a href="javascript:sortBy('money','<?php echo ($sort); ?>','index')">商家结算金额</a></th><?php endif; ?>
<?php if(($menukeyword)  ==  "1"): ?><th width="115"><a href="javascript:sortBy('keyword','<?php echo ($sort); ?>','index')">关键字</a></th><?php endif; ?>
<?php if(($menustart_time)  ==  "1"): ?><th width="100"><a href="javascript:sortBy('start_time','<?php echo ($sort); ?>','index')">开始时间</a></th><?php endif; ?>
<?php if(($menuclose_time)  ==  "1"): ?><th width="100"><a href="javascript:sortBy('close_time','<?php echo ($sort); ?>','index')">结束时间</a></th><?php endif; ?>
<?php if(($menucreate_time)  ==  "1"): ?><th width="100"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">创建时间</a></th><?php endif; ?>
<?php if(($menuupdate_time)  ==  "1"): ?><th width="100"><a href="javascript:sortBy('update_time','<?php echo ($sort); ?>','index')">更新时间</a></th><?php endif; ?>
<?php if(($menupoint)  ==  "1"): ?><th width="46"><a href="javascript:sortBy('point','<?php echo ($sort); ?>','index')">点击</a></th><?php endif; ?>
<?php if(($menucollect)  ==  "1"): ?><th width="46"><a href="javascript:sortBy('collect','<?php echo ($sort); ?>','index')">收藏</a></th><?php endif; ?>
<?php if(($menuprints)  ==  "1"): ?><th width="46"><a href="javascript:sortBy('prints','<?php echo ($sort); ?>','index')">打印</a></th><?php endif; ?>
<th width="74">状态</th>
<th width="300">操作</th>
</tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" ondblclick="show_more('more<?php echo ($vo["id"]); ?>');" align="center">
<td ><?php echo ($vo["id"]); ?></td>
<td><a title="<?php echo ($vo["title"]); ?>"><?php echo (mb_substr($vo["title"],0,5,'utf-8')); ?></a></td>
<?php if(($menucate_id)  ==  "1"): ?><td><?php echo (mb_substr(get_cate($vo["cate_id"]),0,5,'utf-8')); ?></td><?php endif; ?>
<?php if(($menutrade_id)  ==  "1"): ?><td><a href="__APP__/Admin/Trade/edit/id/<?php echo ($vo["trade_id"]); ?>" title="<?php echo (get_trade($vo["trade_id"])); ?>" target="_blank"><?php echo (mb_substr(get_trade($vo["trade_id"]),0,5,'utf-8')); ?></a></td><?php endif; ?>
<?php if(($menuscore)  ==  "1"): ?><td><?php echo ($vo["score"]); ?></td><?php endif; ?>
<?php if(($menumoney)  ==  "1"): ?><td><?php echo ($vo["money"]); ?></td><?php endif; ?>
<?php if(($menukeyword)  ==  "1"): ?><td><?php echo ($vo["keyword"]); ?></td><?php endif; ?>
<?php if(($menustart_time)  ==  "1"): ?><td><?php echo (date("Y-m-d",$vo["start_time"])); ?></td><?php endif; ?>
<?php if(($menuclose_time)  ==  "1"): ?><td><?php echo (date("Y-m-d",$vo["close_time"])); ?></td><?php endif; ?>
<?php if(($menucreate_time)  ==  "1"): ?><td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td><?php endif; ?>
<?php if(($menuupdate_time)  ==  "1"): ?><td><?php echo (date("Y-m-d H:i:s",$vo["update_time"])); ?></td><?php endif; ?>
<?php if(($menupoint)  ==  "1"): ?><td><a href="javascript:showcount('Ticket',0,'<?php echo ($vo["id"]); ?>')"><?php echo ($vo["point"]); ?></a></td><?php endif; ?>
<?php if(($menucollect)  ==  "1"): ?><td><a href="javascript:showcount('Ticket',2,'<?php echo ($vo["id"]); ?>')"><?php echo ($vo["collect"]); ?></a></td><?php endif; ?>
<?php if(($menuprints)  ==  "1"): ?><td><a href="javascript:showcount('Ticket',1,'<?php echo ($vo["id"]); ?>')"><?php echo ($vo["prints"]); ?></a></td><?php endif; ?>
<td>
<?php if(($vo["status"])  ==  "-1"): ?>禁用<?php endif; ?>
<?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?>待审核<?php endif; ?></td>
<td><a href="__ROOT__/editor/demo.php?id=<?php echo ($vo["id"]); ?>&name=<?php echo ($vo["title"]); ?>">编辑打印图片</a>&nbsp;&nbsp;<a href="javascript:edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;&nbsp;&nbsp;
<?php if(($vo["status"])  ==  "1"): ?><a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">禁用</a><?php endif; ?>
<?php if(($vo["status"])  ==  "-1"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?>
<?php if(($vo["status"])  ==  "0"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?>
&nbsp;<a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a>
</td>
</tr>
<tr class="row" ondblclick="hidden_more('more<?php echo ($vo["id"]); ?>')"><td colspan="16">
<div id="more<?php echo ($vo["id"]); ?>" class="listdiv" style="display:none;">
<div style="float:left;padding-right:10px;"><?php if($vo["logo"] == ''): ?><img src="__ROOT__/Public/Uploads/noavatar.gif" width="48" height="48"><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="140" height="70"><?php endif; ?></div>
<div style="float:left;">
<li>优惠券英文：<?php echo ($vo["name"]); ?></li>
<li>商家获得结算金额：<?php echo ($vo["address"]); ?></li>
<li>注意事项：<?php echo ($vo["attention"]); ?></li>
<li>关键字：<?php echo ($vo["keyword"]); ?></li>
<li>介绍：<?php echo ($vo["introduce"]); ?></li>
<li>标签：<?php echo ($vo["tags"]); ?></li>
<li>创建时间：<?php echo (date("Y-m-d",$vo["create_time"])); ?></li>
<li>更新时间：<?php echo (date("Y-m-d",$vo["update_time"])); ?></li>
<li>状态：<?php if(($vo["status"])  ==  "-1"): ?>禁用<?php endif; ?><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>待审核<?php endif; ?></li>
</div></div></td>
</tr><?php endforeach; endif; ?>
<tr><td height="5" colspan="16" class="bottomTd"></td></tr>
</table></div>
<!--  分页显示区域 -->
<div class="page"><?php echo ($page); ?></div>
<!-- 列表显示区域结束 -->
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->