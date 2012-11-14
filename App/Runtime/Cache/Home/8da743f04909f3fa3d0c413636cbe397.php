<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo get_config(); ?></title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/xtyle.css" />

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/popUp.css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery.tools.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.cookie.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.scrollfollow.js"></script>


<?php if(isset($synlogin)): ?><?php echo ($synlogin); ?><?php endif; ?>
</head>
<body>
<div class="w96" id="fw"><img src="/imgs/header.png" /></div>
<div id="nav">
	<div class="w96"><a id="jf" href="/jifen"></a><ul>
<li><a href="__GROUP__" class="ico1"><span>首页</span></a></li>
<li><a href="__GROUP__/Ticket"><span>优惠券</span></a></li>
<li><a href="__GROUP__/Seckill"><span>秒杀</span></a></li>
<li><a href="__GROUP__/Huodong"><span>缤纷活动</span></a></li>
<li><a href="__GROUP__/News"><span>最新动态</span></a></li>
<li><a href="__GROUP__/Shop/index/cur/1"><span>优惠商家</span></a></li>
<li><a href="__GROUP__/Info/show/id/2"><span>新手引导</span></a></li>
    </ul></div>
</div>
<div class="cr"></div>
<div id="search" class="w96">
<div id="time">今天是 <SCRIPT language=JavaScript src="__PUBLIC__/Js/riqi.js" type=text/JavaScript></SCRIPT></div>
<form action="__GROUP__/Search" method="post" id="sec1">
    <select class="ipt1" name="mod">
    <option value="Trade">优惠商家</option>
    <option value="Ticket">优惠券</option>
    <option value="Huodong">活动派发</option>
    <option value="News">本站新闻</option>
    </select>
    <input type="text" name="title" class="ipt2" />
    <input type="submit" value="" class="ipt3" />
</form>
</div>
<!--PRINT START-->

<script type="text/javascript">
     $( document ).ready( function () {
       $("#fldiv").scrollFollow( {
        speed: 500,
        offset: 260,
        killSwitch:"exampleLink",
        onText:"Disable Follow",
        offText:"Enable Follow"
       } );
     } );
</script>


<script>
$(document).ready(function() {
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
		for(i=0;i<myArray.length;i++){
			$("#prt2 ul").append(
				"<li><label style='display:none'>" + myArray[i][0] + "</label><span>x</span>" +myArray[i][1] + "<input id='input" + myArray[i][0] +"' type='text' value='"+myArray[i][2]+"' onchange='inputChange(this)' /></li>"
			);
		}
	}
});
</script>


<div id="fldiv">
<a href="#" id="prt1">&lt;-展开打印队列</a>
<div id="prt2"><ul></ul><div><a href="/p.html" target="_blank"><img src="/imgs/prts.gif" /></a> <span>清空</span></div></div>
<script>
$("#prt2 span").click(function(){
$("#prt2 ul li").remove();
$.cookie('name', null,{path:'/'});
});


$("#prt1").toggle(
function() {
$("#prt2").show();
$(this).text("->收起打印队列");
$("#fldiv").css("width","211px");
},
function() {
$("#prt2").hide();
$(this).text("<-展开打印队列");
$("#fldiv").css("width","20px");
}
);

$("#prt2 li span").live("click",function(){
	$(this).parent().remove();
	var myArray = new Array();
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
	}
	for(i=0;i<myArray.length;i++){
		if(myArray[i][0]== $(this).parent().children("label").text()){
			myArray.splice(i,1);
			break;
		}
	}
	for(i=0;i<myArray.length;i++){
		myArray[i]= myArray[i].join('=');
	}
	$.cookie('name', myArray.join('@'),{path:'/'});
});


$("a.prt").click(function(){
$("#fldiv").css("width","211px");

	$("#prt2").show();
	$("#prt1").text("->收起打印队列");
	var myArray = new Array();
	var isIN = false;
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
	}
	for(i=0;i<myArray.length;i++){
		if(myArray[i][0]== $(this).parent().parent().prev().children("label").text()){
			myArray[i][2] = new Number(myArray[i][2])+1;
			//+1
			document.getElementById("input"+myArray[i][0]).value=myArray[i][2];
			isIN =true;
			break;
		}
	}
	if(!isIN){
		myArray[myArray.length]=new Array($(this).parent().parent().prev().children("label").text(),$(this).parent().prev().text(),1);
		$("#prt2 ul").append(
			"<li><label style='display:none;'>" + myArray[i][0] + "</label><span>x</span>" + $(this).parent().prev().text() + "<input id='input"+$(this).parent().parent().prev().children("label").text()+"' type='text' value='1' onchange='inputChange(this)' /></li>"
		);
	}
	for(i=0;i<myArray.length;i++){
		myArray[i]= myArray[i].join('=');
	}
	$.cookie('name', myArray.join('@'),{path:'/'});
});
function inputChange(obj){
	var myArray = new Array();
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
	}
	for(i=0;i<myArray.length;i++){
		if('input'+myArray[i][0]== obj.id){
			myArray[i][2] = obj.value;
			break;
		}
	}
	for(i=0;i<myArray.length;i++){
		myArray[i]= myArray[i].join('=');
	}
	$.cookie('name', myArray.join('@'),{path:'/'});
}
</script>
</div>
<!--PRINT ENDING-->

<script  src="__PUBLIC__/Js/Util/Calendar.js"></script>

<script>
 //添加的
 function emailclick(){
   var email=document.getElementById("email").value;
   if(email==""){
    alert("邮箱不能为空!");
    return false;
  }else{
	 var telphone=/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
	     flag=telphone.test(email);
		 if(!flag){
		   alert('邮箱格式有误');return false;
		}
        else{
             window.location.href="__GROUP__/User/check/type/email?email="+email;
        }
  }
}

function phoneclick(){
   var phone=document.getElementById("mobile").value;
   if(phone==""){
      alert("手机号码不能为空!");
     return false;
  }else if(phone!=""){
         var telphone=/^1\d{10}$/;    //手机号码正则 只是对以一开头的匹配
	     flag=telphone.test(phone);
		 if(!flag){
		   alert('请填写正确的手机号码');return false;
		}else{
		   window.location.href="__GROUP__/User/check/type/mobile?mobile="+phone;
		}
  }
}

function Juge(myform)
{
    if(myform.account.value == "")
	{
		alert("用户不能为空!");
		myform.account.focus();
		return (false);
	}
         if(myform.password.value == "")
	{
		alert("密码不能为空!");
		myform.password.focus();
		return (false);
	}
	if(myform.email.value == "")
	{
		alert("邮箱不能为空!");
		myform.email.focus();
		return (false);
	}else{
	    var telphone=/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
	      flag=telphone.test(myform.email.value);
		  if(!flag){
		   alert('邮箱格式有误');return false;
		}
	}
	if(myform.birthday.value == "")
	{
		alert("生日不能为空!");
		myform.birthday.focus();
		return (false);
	}else{
	   var time=/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/
		flag=time.test(myform.birthday.value);
		if(!flag){
		   alert('请填写正确的日期'); return false;
		}
	}
	if(myform.realname.value == "")
	{
		alert("真实姓名不能为空!");
		myform.realname.focus();
		return (false);
	}else{
	    var name=/^[\u4e00-\u9fa5_a-zA-Z]+$/
		flag=name.test(myform.realname.value);
		if(!flag){
		   alert('姓名必须是中文或英文'); return false;
		}
	}
	if(myform.phone.value == "")
	{
		alert("请填写电话!");
		myform.phone.focus();
		return (false);
	}else{
	    var phone=/\d{7,14}$/;    //手机号码正则 只是对以一开头的匹配
	     flag=phone.test(myform.phone.value);
		 if(!flag){
		   alert('请填写正确的电话号码'); return false;
		} 
	}
	if(myform.mobile.value == "")
	{
		alert("请填写手机号码!");
		myform.mobile.focus();
		return (false);
	}else{
	 var telphone=/^1\d{10}$/;    //手机号码正则 只是对以一开头的匹配
	     flag=telphone.test(myform.mobile.value);
		 if(!flag){
		   alert('请填写正确的手机号码');return false;
		}
	}
	if(myform.zip_code.value == "")
	{
		alert("邮编不能为空!");
		myform.zip_code.focus();
		return (false);
	}
    else{
	    var zip_code=/^[0-9]\d{5}$/; 
	     flag=zip_code.test(myform.zip_code.value);
		 if(!flag){
		   alert('请填写正确的邮编'); return false;
		} 
	}
	if(myform.address.value == "")
	{
		alert("地址不能为空!");
		myform.address.focus();
		return (false);
	}
}
</script>
<div class="w96 ah">
	<div class="fl w71">
	<div class="box5-title">我的资料</div>
<div class="ad1">
<div id="uinfoleft" class="box1">
<div class="box1-body" style="padding-left:20px;">
<p class="myusage">欢迎您：<?php echo ($_SESSION['account']); ?></p>
<ul class="uilist">
<li><a href="__APP__/User/info"><span>我的资料</span></a></li>
<li><a href="__APP__/User"><span>优惠券收藏</span></a></li>
<li><a href="__APP__/User/score"><span>我的积分</span></a></li>
<!--li><a href="__APP__/User/suggest"><span>投诉和建议</span></a></li-->
<li><a href="__APP__/User/bindcard"><span>绑定会员卡</span></a></li>
<li><a href="__APP__/User/message"><span>消息管理</span></a></li>
<li><a href="__APP__/User/seckill"><span>我的秒杀</span></a></li>
<li><a href="__APP__/User/charge"><span>充值</span></a></li>
</ul>
</div>
<p class="bottom"><span class="fl"></span><span class="fr"></span></p>
</div>
<script>
$("ul.uilist li:first-child a").addClass("uc");
</script>
<div id="uinforight" class="box1">
<p class="titlen"><span></span></p>
<div class="box1-body">
<form id="form1" action="__APP__/User/update" method="post" onSubmit="return Juge(this)"> 
<table class="infotable" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td width="82">头&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;像</td><td width="322" valign="bottom"><?php echo ($_SESSION['uc_ava']); ?><br /><a href="__GROUP__/User/avatar">修改头像</a></td></tr>
<tr><td>用&nbsp;户&nbsp;名</td><td><input name="account"  size="15" value="<?php echo ($vo["account"]); ?>" readonly="readonly"/></td></tr>
<tr><td>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</td><td><input name="password" size="16" type="password"/><font color="red">（不修改填写原密码）</font></td></tr>
<tr><td>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱</td><td><input name="email" size="15" id="email"  value="<?php echo ($vo["email"]); ?>" title="输入邮箱是为了方便我们联系您"/><?php if(($vo["email_if"])  ==  "1"): ?>已验证<?php else: ?><input type="button" value="验证邮箱" onClick="emailclick()"/><?php endif; ?></td></tr>
<tr><td>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别</td><td><select name="sex">
<option value="男">男</option>
<option value="女">女</option>
</select></td></tr>
<tr><td>生&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日</td><td><input name="birthday" size="15" onFocus="ShowCalendar(this)" value="<?php echo (date('Y-m-d',$vo["birthday"])); ?>"/></td></tr>
<tr><td>真实姓名</td><td><input name="realname" size="15" value="<?php echo ($vo["realname"]); ?>"/></td></tr>
<tr><td>联系电话</td><td><input name="phone" size="15" value="<?php echo ($vo["phone"]); ?>"/></td></tr>
<tr><td>手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机</td><td><input name="mobile"  id="mobile"  size="15" value="<?php echo ($vo["mobile"]); ?>" title="输入手机号向您发送即时消息"/><?php if(($vo["mobile_if"])  ==  "1"): ?>已验证<?php else: ?><input type="button" value="验证手机" onClick="phoneclick()"/><?php endif; ?></td></tr>
<tr><td>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;编</td><td><input name="zip_code" size="15" value="<?php echo ($vo["zip_code"]); ?>"/></td></tr>
<tr><td>地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址</td><td><input name="address"  size="25" value="<?php echo ($vo["address"]); ?>"/></td></tr>
<tr><td></td><td>
<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
<input type="submit" value="修&nbsp;改" /></td></tr>

</table></form></div>
<p class="bottom"><span class="fl"></span><span class="fr"></span></p>
</div>
</div>
    </div>
    <div class="fr w24">

<div class="ad1"><a href="#"><img src="__PUBLIC__/home_image/ad.jpg" /></a></div>
    </div>
</div>
<div class="cr"></div>
<div id="footer">冀ICP备09000223号 新月互联 版权所有联系电话：0315-5919005（唐山优惠券网）站长统计</div>
<!--PRINT START-->
<script>
$("#prt2 span").click(function(){
$("#prt2 ul li").remove();
$.cookie('name', null,{path:'/'});
});



$("#prt2 li span").live("click",function(){
	$(this).parent().remove();
	var myArray = new Array();
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
	}
	for(i=0;i<myArray.length;i++){
		if(myArray[i][0]== $(this).parent().children("label").text()){
			myArray.splice(i,1);
			break;
		}
	}
	for(i=0;i<myArray.length;i++){
		myArray[i]= myArray[i].join('=');
	}
	$.cookie('name', myArray.join('@'),{path:'/'});
});


$("a.prt").click(function(){
$("#fldiv").css("width","211px");

	$("#prt2").show();
	$("#prt1").text("->收起打印队列");
	var myArray = new Array();
	var isIN = false;
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
	}
	for(i=0;i<myArray.length;i++){
		if(myArray[i][0]== $(this).parent().parent().prev().children("label").text()){
			myArray[i][2] = new Number(myArray[i][2])+1;
			//+1
			document.getElementById("input"+myArray[i][0]).value=myArray[i][2];
			isIN =true;
			break;
		}
	}
	if(!isIN){
		myArray[myArray.length]=new Array($(this).parent().parent().prev().children("label").text(),$(this).parent().prev().text(),1);
		$("#prt2 ul").append(
			"<li><label style='display:none;'>" + myArray[i][0] + "</label><span>x</span>" + $(this).parent().prev().text() + "<input id='input"+$(this).parent().parent().prev().children("label").text()+"' type='text' value='1' onchange='inputChange(this)' /></li>"
		);
	}
	for(i=0;i<myArray.length;i++){
		myArray[i]= myArray[i].join('=');
	}
	$.cookie('name', myArray.join('@'),{path:'/'});
});
function inputChange(obj){
	var myArray = new Array();
	if($.cookie('name')){
		var myArray = $.cookie('name').split('@');
		for(i=0;i<myArray.length;i++){
			myArray[i] = myArray[i].split('=');
		}
	}
	for(i=0;i<myArray.length;i++){
		if('input'+myArray[i][0]== obj.id){
			myArray[i][2] = obj.value;
			break;
		}
	}
	for(i=0;i<myArray.length;i++){
		myArray[i]= myArray[i].join('=');
	}
	$.cookie('name', myArray.join('@'),{path:'/'});
}
</script>
</div>
<!--PRINT ENDING-->

</body>
</html>