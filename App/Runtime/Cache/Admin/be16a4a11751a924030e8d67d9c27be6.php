<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>MoyKlutch Coupon</title>



<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css" />
<script type="text/javascript" src="__PUBLIC__/Js/Base.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/prototype.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/mootools.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/Ajax/ThinkAjax.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/Form/CheckForm2.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>

 <script language="JavaScript">
<!--
var PUBLIC	 =	 '__PUBLIC__';
ThinkAjax.image = [	 '__PUBLIC__/Images/loading2.gif', '__PUBLIC__/Images/ok.gif','__PUBLIC__/Images/update.gif' ]
ThinkAjax.updateTip	=	'Loading ～';

function loginHandle(data,status){
if (status==1)
{
$('result').innerHTML	=	'<span style="color:blue"><img SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="" align="absmiddle" > Login succeed！ jump in 3 seconds～</span>';
$('form1').reset();
window.location = '__URL__';
}
}
function keydown(e){
	var e = e || event;
	if (e.keyCode==13)
	{
	ThinkAjax.sendForm('form1','__URL__/checkLogin/',loginHandle,'result');
	}
}
function fleshVerify(type){ 
//重载验证码
var timenow = new Date().getTime();
if (type)
{
$('verifyImg').src= '__URL__/verify/adv/1/'+timenow;
}else{
$('verifyImg').src= '__URL__/verify/'+timenow;
}

}
//-->
</script>
</head>
<body onLoad="document.login.account.focus()" >
<form method='post' name="login" id="form1" >

<div class="tCenter hMargin">
<table id="checkList" class="login shadow" cellpadding=0 cellspacing=0 >
<tr><td height="5" colspan="2" class="topTd" ></td></tr>
<tr class="row" ><th colspan="2" class="tCenter space" ><img src="__PUBLIC__/Images/preview_f2.png" width="32" height="32" border="0" alt="" align="absmiddle" />Moy Klutch Platform</th></tr>
<tr><td height="5" colspan="2" class="topTd" ></td></tr>
<tr class="row" ><td colspan="2" class="tCenter"><div id="result" class="result none"></div></td></tr>
<tr class="row" ><td class="tRight" width="25%">Account：</td><td><input type="text" class="medium bLeftRequire" check="Require" warning="please input your account name" name="account" /></td></tr>
<tr class="row" ><td class="tRight">PASS：</td><td><input type="password" class="medium bLeftRequire" check="Require" warning="please input password" name="password" /></td></tr>
<tr class="row" ><td class="tRight">Verified：</td><td><input type="text" onKeyDown="keydown(event)" class="small bLeftRequire" check="Require" warning="please input the verified number " name="verify"/> <img id="verifyImg" src="__URL__/verify/" onclick="fleshVerify()" border="0" alt="Reflash " style="cursor:pointer"  /></td></tr>
<tr class="row" ><td class="tCenter" align="justify" colspan="2">
<input type="hidden" name="ajax" value="1" />
<input type="button" value="Login" onclick="ThinkAjax.sendForm('form1','__URL__/checkLogin/',loginHandle,'result')" class="submit medium hMargin" />
</td></tr>
<tr><td height="5" colspan="2" class="bottomTd" ></td></tr>
</table>
</div>
</form>
</body>
</html>