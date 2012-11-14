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

 <script type="text/javascript">  
		   function show(s){
			  var meu = document.getElementById("menu");
			  var con = document.getElementById("content");
			  var meuLi = meu.getElementsByTagName("a");
			  var conLi = con.getElementsByTagName("div");
			  for(var i = 0; i<= conLi.length;i++){
				  if(s == meuLi[i] ){
					 // meuLi[i].setAttribute("class","tabFocus");
					  conLi[i].style.display = "block";
                     // alert(i);
					  }else{
						 //meuLi[i].setAttribute("class","tabFocus1");
						 conLi[i].style.display = "none";
						  }
				  }
			   }
$("div#nav ul li:nth-child(6)").addClass("cc");
</script>


<div class="w96">
<div class="cr"></div>
<div class="fl w71">
<!--QUYU START-->
<div id="quanzi">
 <ul >
     <li id="menu">区域:&nbsp;&nbsp;&nbsp;
         <?php $_result=M('area')->order('id')->where("1=1 and pid = 0")->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><a name="showmenu" id="showmenu<?php echo ($i); ?>" <?php if(($search["cur"])  ==  $i): ?>class="cks"<?php endif; ?> onmousemove="show(this);"><?php echo ($vo["title"]); ?>&nbsp;&nbsp;&nbsp;</a><?php endforeach; endif;?>
    </li>
</ul>
 
<ul>
<li id="content"><?php $_result=M('Area')->order('id')->where("1=1 and pid = 0")->select(); if ($_result): $j=0;foreach($_result as $key=>$vo):++$j;$mod = ($j % 2 );?><div id="showarea<?php echo ($j); ?>" name="showarea" <?php if(($search["cur"])  ==  $j): ?>style="display:block"<?php else: ?>style="display:none"<?php endif; ?>><span><?php echo ($vo["title"]); ?>所有地区：</span>
<?php $_result=M('Area')->order('id')->where("1=1 and pid = $$vo[id]")->select(); if ($_result): $i=0;foreach($_result as $key=>$voo):++$i;$mod = ($i % 2 );?><a <?php if(($search["area"])  ==  $voo['id']): ?>style="color:red;"<?php endif; ?> href="__APP__/Shop/index/cur/<?php echo ($j); ?>/area/<?php echo ($voo["id"]); ?>"><?php echo ($voo["title"]); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif;?></div><?php endforeach; endif;?>
</li>
 </ul>
</div>
<!--QUYU ENDING-->
<div class="box5-title"><span><?php echo ($_GET["searchtag"]); ?></span>您搜索的商户共<?php echo ($count); ?>条：</div>
<div class="box1">
<ul class="list9">
<?php  if(is_array($list)): foreach($list as $key=>$vo): ?><li>
<a a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>" class="cover-9"><img src="__ROOT__/img/shop/<?php echo ($vo["id"]); ?>.jpg" width="130" height="90"/></a>
<div class="fr">
<div class="fl">
<p>优惠券<span><?php echo (get_ticketbytrade($vo["id"])); ?></span>张 评论<span><?php echo (get_comment($vo["id"],'trade')); ?></span>条</p>
<p><a href="__GROUP__/User/trade_col/id/<?php echo ($vo["id"]); ?>" class="add">收藏商户</a></p>
</div>
<div class="fr">
<p><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>" class="big1"><?php echo ($vo["title"]); ?></a></p>
<p>评分：<img src="__PUBLIC__/home_image/star<?php echo (get_star($vo["id"],'trade')); ?>.gif" /></p>
<p>电话：<?php echo ($vo["phone"]); ?></p>
<p>属性：<a href="#"><?php echo ($vo["keyword"]); ?></a></p>
</div>
</div>
	</li><?php endforeach; endif; ?>
</ul>
<div id="fpage"><?php echo ($page); ?></div>
</div>
</div>
<div class="fr w24">
<div class="box">
	<p class="box7-title">会员登录</p>
<?php if (!$_SESSION["auth"]){ ?>

<div class="box1">
    <div class="box1-body"><form action="__GROUP__/Index/checkLogin" method="post" id="loginform">
<ul>
	<li>用户名：<input name="account" type="text" style="width:120px;"/></li>
    <li>密&nbsp;&nbsp;&nbsp;码：<input name="password" type="password" style="width:120px;" /></li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="image" src="__PUBLIC__/home_image/btn1.gif" />&nbsp;&nbsp;&nbsp;<a href="__GROUP__/Index/reg">注册会员？</a></li>
</ul></form>
    </div>
    <p class="bottom"><span class="fl"></span><span class="fr"></span></p>
</div>
<?php }else{ ?>
<div class="box1" style="padding:10px;">
<div>&nbsp;&nbsp;<?php echo ($_SESSION['uc_ava']); ?><a href="__GROUP__/User/avatar">修改头像</a></div>
<div>
	<p class="loginin1" style="padding-bottom:3px;"><?php echo (get_user($_SESSION['auth'])); ?></p>
	<p class="loginin2" style="padding-bottom:3px;">积分：<span class="red"><?php echo (getMyinfo($_SESSION['authid'],'score')); ?></span>分</span></p>
	<p class="loginin4" style="padding-bottom:3px;">新消息：<a href="__GROUP__/User/message"><span class="red"><?php echo (getMyinfo($_SESSION['authid'],'msgnum')); ?></span></a>条</p>
	<p class="loginin5" style="padding-bottom:3px;"><a href="__GROUP__/User">[我的券(<?php echo (get_collect($_SESSION['auth'])); ?>)]</a><a href="__GROUP__/User/info">[会员中心]</a><a href="__GROUP__/Index/logout">[退出]</a></p>
</div>
<!--<div class="loginin6"><img src="__PUBLIC__/home_image/sud.gif" /><?php echo ($_SESSION['announce']['data']); ?></div>-->
</div>
<?php } ?>

</div>
<div class="cr"></div>
	<ul class="box2-title tabs2">
<li><a href="#">热门商家</a></li>
<li><a href="#">最新商家</a></li>
    </ul>
    <div class="box2 panes2"><ul>
<li id="box2a">
<?php $_result=M('Trade')->order('id')->where("tags like '%头条%'")->limit(1)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>" class="fl"><img src="<?php echo ($vo["logo"]); ?>" width="71" height="56" /></a>
    <div class="fr">
<p><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></p>
<p class="box2b"><?php echo ($vo["status"]); ?>种优惠</p>
    </div><?php endforeach; endif;?>
</li>
<?php $_result=M('Trade')->order('id')->where("tags like '%热门%'")->limit(6)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>
<ul>
<li id="box2a">
<?php $_result=M('Trade')->order('id')->limit(1)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>" class="fl"><img src="<?php echo ($vo["logo"]); ?>" width="71" height="56" /></a>
    <div class="fr">
<p><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></p>
<p class="box2b"><?php echo ($vo["status"]); ?>种优惠</p>
    </div><?php endforeach; endif;?>
</li>
<?php $_result=M('Trade')->order('id')->limit(6)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>    </div>
<script>
$(function() {
	$("ul.tabs2").tabs("div.panes2 > ul",{  event: 'click'});
});
</script>
<div class="cr"></div>
<div class="ad"><img src="/imgs/q4.jpg" /></div>
</div>
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