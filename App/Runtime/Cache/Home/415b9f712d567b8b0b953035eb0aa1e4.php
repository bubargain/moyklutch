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


<script src="__PUBLIC__/Js/AjaxCls.js" type="text/javascript" ></script>
<script  src="__PUBLIC__/Js/Util/Calendar.js"></script>
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
      document.getElementById("show").innerHTML=str;
}
function checkname()
{
  var str=document.getElementById("account").value;
  oAjax.sendUrl="index.php?g=Home&m=Index&a=checkname&str="+str;
  oAjax.getRequest();
  oAjax.getXMLResponse();
}
</script>

<script>

 //判断注册信息
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
	if(myform.repassword.value != myform.password.value)
	{
		alert("确认密码和密码不一致!");
		myform.repassword.focus();
		return (false);
	}
	if(myform.email.value == "")
	{
		alert("邮箱不能为空!");
		myform.email.focus();
		return (false);
	}
 }
 
//确认信息验证
 function Juge1(myform1)
 {
   if(myform1.realname.value == "")
	{
		alert("请填写真实姓名!");
		myform1.realname.focus();
		return (false);
	}else{
	    var name=/^[\u4e00-\u9fa5_a-zA-Z]+$/
		flag=name.test(myform1.realname.value);
		if(!flag){
		   alert('姓名必须是中文或英文'); return false;
		}
	}
    if(myform1.birthday.value == "")
	{
		alert("请填写你的生日!");
		myform1.birthday.focus();
		return (false);
	}else{
	    var time=/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/
		flag=time.test(myform1.birthday.value);
		if(!flag){
		   alert('请填写正确的日期'); return false;
		}
	}
     if(myform1.mobile.value == "")
	{
		alert("请填写手机号码!");
		myform1.mobile.focus();
		return (false);
	}else{
	     var telphone=/^1\d{10}$/;    //手机号码正则 只是对以一开头的匹配
	     flag=telphone.test(myform1.mobile.value);
		 if(!flag){
		   alert('请填写正确的手机号码');return false;
		}
	}
     if(myform1.zip_code.value == "")
	{
		alert("请填写邮编!");
		myform1.zip_code.focus();
		return (false);
	}else{
	    var zip_code=/^[0-9]\d{5}$/;   
	     flag=zip_code.test(myform1.zip_code.value);
		 if(!flag){
		   alert('请填写正确的邮编'); return false;
		} 
	}
     if(myform1.address.value == "")
	{
		alert("请填写详细地址!");
		myform1.address.focus();
		return (false);
	}
    
 }
 
 //添加的
 function emailclick(){
   var email=document.formemail.email.value;
   if(email==""){
    alert("邮箱不能为空!");
    formemail.email.focus();
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
   var phone=document.formphone.mobile.value;
   if(phone==""){
      alert("手机号码不能为空!");
     formphone.mobile.focus();
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

function cardclick(){
   var card=document.formcard.card_password.value;
   if(card==""){
      alert("请输入会员卡密码!");
     formcard.card_password.focus();
  }else{
       
	 window.location.href="__APP__/Home/User/savebind?card_password="+card;
  }
}

function back(){
	 window.location.href="__APP__/Index";
     }
</script>
<script type="text/javascript">
$(function(){
	$(".uploadaa").click(function(){
	   $("#mask").show().css("opacity","0.6");
		 	document.getElementById("popUp").style.display="block";	
            
		});
  $("#btn22").click(function(){
    $("#mask").hide();
    
  })
})
function closePop(){
	document.getElementById("popUp").style.display="none";	
}
</script>
<div id="mask" style="position: absolute; height:100%; width:100%; background: #666; left:0; top:0; z-index:99; display: none;"></div>
<!---->
<div class="w96 ah">
<div class="fl w71">
<div id="space">当前位置：<a href="__APP__">首页</a>&gt;注册会员</div>
<div class="cr"></div>
<!--MEMBER REGISTER START-->
<?php if($register == 0): ?><div id="reg">
<ul id="nav-reg">
	<li><a href="#" class="reg4">快速注册</a></li>
    <li><a href="#" class="reg2">完善信息</a></li>
    <li><a href="#" class="reg3">验证身份</a></li>
</ul>
<div class="cr"></div>
<div class="reg-txt">
<p>填写简单的注册信息,快速成为爱券会员,享受优惠券带来的实惠与快乐！</p>
<p>现在注册会员可立即获得<span>+10</span>积分</p>
</div>
<div class="cr"></div>
<form id="form1" action="__APP__/Index/useradd" method="post" onSubmit="return Juge(this)" class="regform1" > 
<p><label>用户名</label><input name="account" id="account"  size="16" onchange="checkname()"/><span id="show"></span></p>
<p><label>密&nbsp;&nbsp;码</label><input name="password" size="16" type="password" /></p>
<p><label>确认密码</label><input name="repassword" size="16" type="password"/></p>
<p><label>邮&nbsp;&nbsp;箱</label><input name="email" size="30" /></p>
<div class="rrg"><label></label><input type="submit" value="注&nbsp;册" id="btn1" /></div>
</form>
</div>
<!--MEMBER REGISTER ENDING-->
<div class="cr"></div>
<!--MEMBER REGISTER START-->
<?php elseif($register == 1): ?>
<div class="popUp" id="popUp">
            <div class="pop-middle">
            	<div class="letter1"><?php echo ($avar); ?></div>
            </div>
            <div class="pop-bottom">
                <a href="javascript:void(0)" class="btn2" id="btn22" onclick="closePop();">返回</a>
            </div>
        </div>
<div id="reg" class="hidden1" >
        <ul id="nav-reg">
        	<li><a href="#" class="reg1">快速注册</a></li>
            <li><a href="#" class="reg5">完善信息</a></li>
            <li><a href="#" class="reg3">验证身份</a></li>
        </ul>
        <div class="cr"></div>
        <div class="reg-txt">
        <p>为了能准确快速的将礼品,优惠信息邮寄给您,请您完善以下信息，填写时写保证信息的正确性.</p>
        <p>邮寄时快递公司的工作人员需要核对您的真实<span>姓名</span>,<span>手机</span>号,详细<span>地址</span></a>及<span>邮编</span>以便您能安全无误的收到邮件.</p>
        </div>
        <div class="cr"></div>
        <form id="form1" action="__APP__/Index/update" method="post" name="myform1" onSubmit="return Juge1(this)" class="regform2" > 
       <p><label>设置头像<input type="button" value="上传头像" class="uploadaa" /></label></p>
        <div style=""></div>
        <p>姓&nbsp;&nbsp;名<input  type="text" size="16" name="realname"/>&nbsp;&nbsp;&nbsp;&nbsp;性&nbsp;&nbsp;别<select><option>女士</option><option>男士</option></select></p>
        <p>生&nbsp;&nbsp;日 <input type="text" name="birthday" size="16" onFocus="ShowCalendar(this)" value="<?php echo ($time1); ?>">&nbsp;&nbsp;&nbsp;&nbsp;手&nbsp;&nbsp;机<input  type="text" size="16" name="mobile" /></p>
        <p>邮&nbsp;&nbsp;编<input  type="text" size="16"  name="zip_code"/>&nbsp;&nbsp;&nbsp;&nbsp;地&nbsp;&nbsp;址<input  type="text" size="16" name="address"/></p>
        <p><input type="hidden" name="id" value="<?php echo ($userid); ?>"><input type="hidden" name="userinfo_if" value="1"><input type="submit" value="确认提交"/><input type="button" value="以后再说" onClick="back()"/></p>
        </form>
</div>
<?php else: ?>
<!--MEMBER REGISTER ENDING-->
<div class="cr"></div>
<!--MEMBER REGISTER ENDING-->
<div class="cr"></div>
<!--MEMBER REGISTER START-->
<div id="reg"  class="hidden2">
<ul id="nav-reg">
	<li><a href="#" class="reg1">快速注册</a></li>
    <li><a href="#" class="reg2">完善信息</a></li>
    <li><a href="#" class="reg6">验证身份</a></li>
</ul>
<div class="cr"></div>
<div class="reg-txt">
<p>只要验证了<span>邮箱</span>,<span>手机</span>的用户才能享受更优质的服务.</p>
<p>绑定会员卡，您可以额外得到奖励<span>积分</span>,并获得优惠券收藏的功能,可以在任何一个机器上打印您收藏的优惠券.</p>

</div>
<div class="cr"></div>
<form id="form1" action=""  class="regform2" name="formemail"> 
<p><label>您的邮箱</label><input  type="text" size="16" name="email" value="<?php echo ($email); ?>"/><input type="button" value="验证" onClick="emailclick()"/></p></form>
 <form id="formphone" action=""  method="post" class="regform2" name="formmobile"> 
<p><label>您的手机</label><input  type="text" size="16" name="mobile" value="<?php echo ($mobile); ?>"/><input type="button" value="验证" onClick="phoneclick()"/></p>
</form>
<form id="form1"  action=""  class="regform2" name="formcard"> 
<p><label>请输入会员卡密码</label><input name="card_password" type="password" id="" class="text" /><input type="button" value="绑定"  onClick="cardclick()"/></p>
<p><input type="button" value="以后再说" onClick="back()"/></p>
</form>
</div>
<!--MEMBER REGISTER ENDING--><?php endif; ?>
</div>
 <div class="fr w24">

<div class="box6-title">最新活动</div>
<ul class="list2">
<?php $_result=M('Huodong')->order('id')->where("1=1 and typeid = 1")->limit(3)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/Huodong/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
</ul>
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