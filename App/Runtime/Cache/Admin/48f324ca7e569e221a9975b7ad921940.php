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
<script language="javascript">
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
<div class="title">用户列表</div>
<!--  功能操作区域  -->
<div class="operate" >
<input type="button" name="add" value="新增" class="small hMargin shadow submit" onclick="add();"><input type="button" name="email" value="邮件群发" class="small hMargin shadow submit" onclick="sendmail();"><input type="button" name="exp" value="导出" class="small hMargin shadow submit" onclick="formsubmit();"><form method='post' name="form1" id="form1" action="__URL__">
</div>
<!-- 高级查询区域 -->
<div id="searchM">
<table width="1105" cellpadding="1" cellspacing="0"><input type="hidden" name="flag" value="1" />
<tr><td width="125">编号：</td><td width="118"><input type="text" name="id" title="用户ID" size="10" value="<?php echo ($id); ?>"></td>
<td width="101">用户名：</td><td width="222"><input type="text" name="account" title="帐号查询" size="10" value="<?php echo ($account); ?>" ></td>
<td width="106">真实姓名：</td><td colspan="2"><input type="text" name="realname" title="姓名查询" size="10" value="<?php echo ($realname); ?>"></td>
</tr>
<tr>
<td width="125">个人信息：</td><td width="118"><select name="userinfo_if">
  <option value="">不限</option>
  <option <?php if(($userinfo_if)  ==  "1"): ?>selected<?php endif; ?> value="1">已填写</option>
  <option <?php if(($userinfo_if)  ==  "0"): ?>selected<?php endif; ?> value="0">未填写</option>
</select></td><td width="101">
会员卡：</td><td width="222"><select name="card_if">
  <option value="-1">不限</option>
  <option <?php if(($card_if)  ==  "1"): ?>selected<?php endif; ?> value="1">已绑定</option>
  <option <?php if(($card_if)  ==  "0"): ?>selected<?php endif; ?> value="0">未绑定</option>
</select></td><td>
用户组：</td><td width="58"><select name="type_id">
  <option  value="">不限</option>
  <option <?php if(($type_id)  ==  "0"): ?>selected<?php endif; ?> value="0">管理员</option>
  <option <?php if(($type_id)  ==  "1"): ?>selected<?php endif; ?> value="1">商家</option>
  <option <?php if(($type_id)  ==  "2"): ?>selected<?php endif; ?> value="2">普通会员</option>
</select></td><td width="359" align="center"><input type="submit" value="查询" class="small hMargin shadow submit" onclick="formsubmit2();"></td></tr>
<tr>
<td>验证邮箱：</td><td><select name="email_if">
  <option value="">不限</option>
  <option <?php if(($email_if)  ==  "0"): ?>selected<?php endif; ?> value="0">未验证</option>
  <option <?php if(($email_if)  ==  "1"): ?>selected<?php endif; ?> value="1">已验证</option>
</select></td><td>
验证手机：</td><td><select name="mobile_if">
  <option value="">不限</option>
  <option <?php if(($mobile_if)  ==  "0"): ?>selected<?php endif; ?> value="0">未验证</option>
  <option <?php if(($mobile_if)  ==  "1"): ?>selected<?php endif; ?> value="1">已验证</option>
</select></td><td>WAP网站：</td><td><select name="wap_if">
  <option value="">不限</option>
  <option <?php if(($wap_if)  ==  "0"): ?>selected<?php endif; ?> value="0">未访问</option>
  <option <?php if(($wap_if)  ==  "1"): ?>selected<?php endif; ?> value="1">已访问</option>
</select></td><td width="359" align="center"></td></tr>
<tr>
<td>积分：</td><td><input type="text" name="money1" size="3" value="<?php echo ($money1); ?>">~<input type="text" name="money2" size="3" value="<?php echo ($money2); ?>"></td>
<td>创建时间：</td><td><input type="text" name="time1" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">~<input type="text" name="time2" size="8" onFocus="ShowCalendar(this)" value="<?php echo ($time2); ?>"></td><td>用户状态：</td><td><select name="status">
  <option <?php if(($status)  ==  "1"): ?>selected<?php endif; ?> value="1">正常</option>
  <option <?php if(($status)  ==  "3"): ?>selected<?php endif; ?> value="3">不限</option>
  <option <?php if(($status)  ==  "-1"): ?>selected<?php endif; ?> value="-1">已删除</option>
  <option <?php if(($status)  ==  "0"): ?>selected<?php endif; ?> value="0">禁用</option>
</select></td><td></td></tr><tr>
<td colspan="3">统计筛选:<select name="tt" onchange="setdate(this.options[this.selectedIndex].value);">
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
</select><input type="text" name="tt1" id="tt1" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($tt1); ?>">~<input type="text" id="tt2" name="tt2" size="10" onFocus="ShowCalendar(this)" value="<?php echo ($tt2); ?>"></td>
<td colspan="4">结果排序：<select name="resultid">
  <option <?php if(($resultid)  ==  "id"): ?>selected<?php endif; ?> value="id" >ID</option>
  <option <?php if(($resultid)  ==  "card_id"): ?>selected<?php endif; ?> value="card_id" >会员卡</option>
  <option <?php if(($resultid)  ==  "account"): ?>selected<?php endif; ?> value="account" >用户名</option>
  <option <?php if(($resultid)  ==  "last_login_time"): ?>selected<?php endif; ?> value="last_login_time" >登录时间</option>
  <option <?php if(($resultid)  ==  "create_time"): ?>selected<?php endif; ?> value="create_time" >注册时间</option>
  <option <?php if(($resultid)  ==  "point"): ?>selected<?php endif; ?> value="point" >点击</option>
  <option <?php if(($resultid)  ==  "collect"): ?>selected<?php endif; ?> value="collect" >收藏</option>
  <option <?php if(($resultid)  ==  "prints"): ?>selected<?php endif; ?> value="prints" >打印</option>
  <option <?php if(($resultid)  ==  "realname"): ?>selected<?php endif; ?> value="realname" >真实姓名</option>
  <option <?php if(($resultid)  ==  "mobile"): ?>selected<?php endif; ?> value="mobile" >手机号</option>
  <option <?php if(($resultid)  ==  "address"): ?>selected<?php endif; ?> value="address" >地址</option>
  <option <?php if(($resultid)  ==  "money"): ?>selected<?php endif; ?> value="money" >积分</option>
</select>
<select name="sortid">
  <option <?php if(($sortid)  ==  "desc"): ?>selected<?php endif; ?> value="desc" >递减</option>
  <option <?php if(($sortid)  ==  "asc"): ?>selected<?php endif; ?> value="asc" >递增</option>
</select>
</td>
</tr><tr><td colspan="7">显示字段：
<input name="clist[]" type="checkbox"  value="card_id" <?php if(($menucard_id)  ==  "1"): ?>checked="checked"<?php endif; ?> />会员卡&nbsp;
<input name="clist[]" type="checkbox"  value="address" <?php if(($menuaddress)  ==  "1"): ?>checked="checked"<?php endif; ?> />地址&nbsp;
<input name="clist[]" type="checkbox"  value="point"   <?php if(($menupoint)  ==  "1"): ?>checked="checked"<?php endif; ?>/>点击&nbsp;
<input name="clist[]" type="checkbox"  value="prints"  <?php if(($menuprints)  ==  "1"): ?>checked="checked"<?php endif; ?>/>打印&nbsp;
<input name="clist[]" type="checkbox"  value="collect" <?php if(($menucollect)  ==  "1"): ?>checked="checked"<?php endif; ?>/>收藏&nbsp;
<input name="clist[]" type="checkbox"  value="type_id" <?php if(($menutype_id)  ==  "1"): ?>checked="checked"<?php endif; ?>/>用户组&nbsp;
<input name="clist[]" type="checkbox"  value="money"  <?php if(($menumoney)  ==  "1"): ?>checked="checked"<?php endif; ?>/>金币&nbsp;
<input name="clist[]" type="checkbox"  value="create_time" <?php if(($menucreate_time)  ==  "1"): ?>checked="checked"<?php endif; ?>/>注册时间&nbsp;
<input name="clist[]" type="checkbox"  value="remark"  <?php if(($menuremark)  ==  "1"): ?>checked="checked"<?php endif; ?>/>备注&nbsp;
<input name="clist[]" type="checkbox"  value="mobile"  <?php if(($menumobile)  ==  "1"): ?>checked="checked"<?php endif; ?>/>手机&nbsp;
<input name="clist[]" type="checkbox"  value="email"  <?php if(($menuemail)  ==  "1"): ?>checked="checked"<?php endif; ?>/>邮箱&nbsp;
<input name="clist[]" type="checkbox"  value="phone"  <?php if(($menuphone)  ==  "1"): ?>checked="checked"<?php endif; ?>/>电话&nbsp;
<input name="clist[]" type="checkbox"  value="sex"    <?php if(($menusex)  ==  "1"): ?>checked="checked"<?php endif; ?>/>性别&nbsp;
</form></td></tr>
</table>
</div>
<!-- 功能操作区域结束 -->
<!-- 列表显示区域  -->
<div class="list">
<table cellpadding="0" cellspacing="0" class="list">
<tr><td height="5" colspan="16" class="topTd"></td></tr>
<tr class="row" align="center">
<th width="50"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')">编号</a></th>
<th width="130"><a href="javascript:sortBy('account','<?php echo ($sort); ?>','index')">用户名</a></th>
<?php if(($menucard_id)  ==  "1"): ?><th width="90"><a href="javascript:sortBy('card_id','<?php echo ($sort); ?>','index')">会员卡</a></th><?php endif; ?>
<?php if(($menuaddress)  ==  "1"): ?><th width="204">地址</th><?php endif; ?>
<?php if(($menuemail)  ==  "1"): ?><th width="130">邮箱</th><?php endif; ?>
<?php if(($menusex)  ==  "1"): ?><th width="40">性别</th><?php endif; ?>
<?php if(($menumobile)  ==  "1"): ?><th width="120">手机</th><?php endif; ?>
<?php if(($menuphone)  ==  "1"): ?><th width="140">电话</th><?php endif; ?>
<?php if(($menupoint)  ==  "1"): ?><th width="55"><a href="javascript:sortBy('point','<?php echo ($sort); ?>','index')">点击</a></th><?php endif; ?>
<?php if(($menuprints)  ==  "1"): ?><th width="55"><a href="javascript:sortBy('prints','<?php echo ($sort); ?>','index')">打印</a></th><?php endif; ?>
<?php if(($menucollect)  ==  "1"): ?><th width="53"><a href="javascript:sortBy('collect','<?php echo ($sort); ?>','index')">收藏</a></th><?php endif; ?>
<?php if(($menutype_id)  ==  "1"): ?><th width="75">用户组</th><?php endif; ?>
<?php if(($menumoney)  ==  "1"): ?><th width="65"><a href="javascript:sortBy('score','<?php echo ($sort); ?>','index')">积分</a></th><?php endif; ?>
<?php if(($menucreate_time)  ==  "1"): ?><th width="90"><a href="javascript:sortBy('create_time','<?php echo ($sort); ?>','index')">注册时间</a></th><?php endif; ?>
<?php if(($menuremark)  ==  "1"): ?><th width="215">备注</th><?php endif; ?>
<th width="137">操作</th></tr>
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><!--ondblclick="show_more('more<?php echo ($vo["id"]); ?>');"-->
<tr id="row<?php echo ($vo["id"]); ?>" class="row" onmouseover="over(event)" onmouseout="out(event)" ondblclick="show_more('more<?php echo ($vo["id"]); ?>');" align="center">
<td><?php echo ($vo["id"]); ?></td>
<td><?php echo ($vo["account"]); ?></td>
<?php if(($menucard_id)  ==  "1"): ?><td><?php echo ($vo["card_id"]); ?></td><?php endif; ?>
<?php if(($menuaddress)  ==  "1"): ?><td><?php echo ($vo["address"]); ?></td><?php endif; ?>
<?php if(($menuemail)  ==  "1"): ?><td width="130"><?php echo ($vo["email"]); ?></td><?php endif; ?>
<?php if(($menusex)  ==  "1"): ?><td width="40"><?php if(($vo["sex"])  ==  "0"): ?>男<?php endif; ?><?php if(($vo["sex"])  ==  "1"): ?>女<?php endif; ?></td><?php endif; ?>
<?php if(($menumobile)  ==  "1"): ?><td width="120"><?php echo ($vo["mobile"]); ?></td><?php endif; ?>
<?php if(($menuphone)  ==  "1"): ?><td width="140"><?php echo ($vo["phone"]); ?></td><?php endif; ?>
<?php if(($menupoint)  ==  "1"): ?><td><a href="javascript:showcount('User',0,'<?php echo ($vo["id"]); ?>')"><?php echo ($vo["point"]); ?></a></td><?php endif; ?>
<?php if(($menuprints)  ==  "1"): ?><td><a href="javascript:showcount('User',1,'<?php echo ($vo["id"]); ?>')"><?php echo ($vo["prints"]); ?></a></td><?php endif; ?>
<?php if(($menucollect)  ==  "1"): ?><td><a href="javascript:showcount('User',2,'<?php echo ($vo["id"]); ?>')"><?php echo ($vo["collect"]); ?></a></td><?php endif; ?>
<?php if(($menutype_id)  ==  "1"): ?><td><?php if(($vo["type_id"])  ==  "0"): ?>管理员<?php endif; ?><?php if(($vo["type_id"])  ==  "1"): ?>商家<?php endif; ?><?php if(($vo["type_id"])  ==  "2"): ?>普通会员<?php endif; ?></td><?php endif; ?>
<?php if(($menumoney)  ==  "1"): ?><td><a href="javascript:showscore('User','<?php echo ($vo["id"]); ?>')"><?php echo ($vo["score"]); ?></a></td><?php endif; ?>
<?php if(($menucreate_time)  ==  "1"): ?><td><?php echo (date("Y-m-d",$vo["create_time"])); ?></td><?php endif; ?>
<?php if(($menuremark)  ==  "1"): ?><td><input type="text" id="remark<?php echo ($vo["id"]); ?>" name="remark" size="30" value="<?php echo ($vo["remark"]); ?>" style="border:#FFF 1px solid;" onchange="aupdate('User','<?php echo ($vo["id"]); ?>','remark')"></td><?php endif; ?>
<td><a href="__APP__/Admin/User/addscore/id/<?php echo ($vo["id"]); ?>">充值</a>&nbsp;<a href="javascript:;" onclick="edit('<?php echo ($vo["id"]); ?>')">编辑</a>&nbsp;<?php if(($vo["id"])  !=  "1"): ?><?php if(($vo["status"])  ==  "1"): ?><a href="javascript:forbidden('<?php echo ($vo["id"]); ?>')">禁用</a><?php endif; ?><?php if(($vo["status"])  ==  "-1"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?><?php if(($vo["status"])  ==  "0"): ?><a href="javascript:recycle('<?php echo ($vo["id"]); ?>')">恢复</a><?php endif; ?><?php endif; ?>&nbsp;<a href="javascript:adel('<?php echo ($vo["id"]); ?>')">删除</a></td>
</tr>
<tr ondblclick="hidden_more('more<?php echo ($vo["id"]); ?>')"><td colspan="16">
<div id="more<?php echo ($vo["id"]); ?>" class="listdiv" style="display:none;">
<div style="float:left;padding-right:10px;"><?php if($vo["logo"] == ''): ?><img src="__ROOT__/Public/Uploads/noavatar.gif" width="48" height="48"><?php else: ?><img src="__ROOT__<?php echo ($vo["logo"]); ?>" width="48" height="48"><?php endif; ?></div>
<div style="float:left;">
<li>真实姓名：<?php echo ($vo["realname"]); ?></li>
<li>手机：<?php echo ($vo["mobile"]); ?></li>
<li>性别：<?php echo ($vo["sex"]); ?></li>
<li>生日：<?php echo (date("Y-m-d",$vo["birthday"])); ?></li>
<li>邮箱：<?php echo ($vo["email"]); ?></li>
<li>联系电话：<?php echo ($vo["phone"]); ?></li>
<li>地址：<?php echo ($vo["address"]); ?></li>
<li>管理的商家：<a href="__GROUP__/Trade/index/id/<?php echo ($vo["trade_id"]); ?>"><?php echo (get_trade($vo["trade_id"])); ?></a></li>
<li>邮箱是否验证：<?php if(($vo["email_if"])  ==  "0"): ?>否<?php endif; ?><?php if(($vo["email_if"])  ==  "1"): ?>是<?php endif; ?></li>
<li>手机是否验证：<?php if(($vo["mobile_if"])  ==  "0"): ?>否<?php endif; ?><?php if(($vo["mobile_if"])  ==  "1"): ?>是<?php endif; ?></li>
<li>是否访问过wap网站：<?php if(($vo["wap_if"])  ==  "0"): ?>否<?php endif; ?><?php if(($vo["wap_if"])  ==  "1"): ?>是<?php endif; ?></li>
<li>用户状态：<?php if(($vo["status"])  ==  "-1"): ?>已删除<?php endif; ?><?php if(($vo["status"])  ==  "1"): ?>正常<?php endif; ?><?php if(($vo["status"])  ==  "0"): ?>禁用<?php endif; ?></li>
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