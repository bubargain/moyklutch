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

<style type="text/css">
#map { position:absolute; left:10px; }
</style>
<div id="map"><a href="./Huodong/show/id/21"><img src="/imgs/maps.jpg" /></a></div>
<script type="text/javascript">
     $( document ).ready( function () {
       $("#map").scrollFollow( {
        speed: 500,
        offset: 260,
        killSwitch:"exampleLink",
        onText:"Disable Follow",
        offText:"Enable Follow"
       } );
     } );
</script>



<script>
$("div#nav ul li:nth-child(1)").addClass("cc");
</script>

<div class="w96">
<div id="hot">
<div id="tuan"><a href="/Seckill"><img src="/imgs/tuan.png" /></a></div>

<div id="hota">
	<div id="hotbox">
    <div class="scrollable">
   <div class="items">
<?php $_result=M('Huodong')->order('create_time desc')->where("1=1 and typeid = 1")->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><a href="__GROUP__/Huodong/show/id/<?php echo ($vo["id"]); ?>"><img src="__ROOT__<?php echo ($vo["path"]); ?>"  width="445" height="250"/></a><?php endforeach; endif;?>
	</div>
    </div>
<script> 
$(function() {
	$("div.scrollable").scrollable({size:2}).circular().autoscroll({ 
    steps: 1, 
    interval: 3000         
});// .autoscroll({autoplay:true});
});
</script> 

</div>
	<!--MEMBER LOGIN START-->
<div id="tt">
<?php if (!$_SESSION["auth"]){ ?>
<div id="login1">
<form action="__GROUP__/Index/checkLogin" method="post" id="loginform">
<p>用户名:&nbsp;&nbsp;&nbsp;<input name="account" type="text" style="width:110px;" /></p>
<p>密&nbsp;&nbsp;&nbsp;码:&nbsp;&nbsp;&nbsp;<input name="password" type="password" style="width:110px;" /></p>
<p><a href="__GROUP__/Index/reg">注册会员？</a><input type="image" src="__PUBLIC__/home_image/btn1.gif" /></p>
</form>
</div>
<?php }else{ ?>



<div id="login2">
<div class="loo1">
<?php echo ($_SESSION['uc_ava']); ?>
<div><?php echo (get_user($_SESSION['auth'])); ?></div>
<div><a href="__GROUP__/User/avatar">修改头像</a></div>
</div>


<div class="loo2">
<span>积分：<span class="red"><?php echo (getMyinfo($_SESSION['auth'],'score')); ?></span>分</span>
<span>新消息：<a href="__GROUP__/User/message"><span class="red"><?php echo (getMyinfo($_SESSION['auth'],'msgnum')); ?></span></a>条</span>
</div>

<div class="loo3">
<a href="__GROUP__/User">[我的券(<?php echo (get_collect($_SESSION['auth'])); ?>)]</a>
<a href="__GROUP__/User/info">[会员中心]</a>
<a href="__GROUP__/Index/logout">[退出]</a>
<!--<img src="__PUBLIC__/home_image/sud.gif" /><?php echo ($_SESSION['announce']); ?>-->
</div>

</div>
<?php } ?>


	<div id="nat">
<p class="title">鏈�柊娲诲姩</p>
<ul>
<?php $_result=M('Huodong')->order('create_time desc')->where("1=1 and typeid = 1")->limit(3)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/Huodong/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
</ul>
	</div>
</div>
    <!--MEMBER LOGIN ENDING-->

</div>

</div>
<div class="cr"></div>
<div class="ad"><img src="/imgs/ad1.png" /></div>
<div class="cr"></div>
<div class="fl w71">
	<div class="box1-title"><ul class="tabs3x">
<?php $_result=M('Cate')->order('id')->where("1=1 and pid = 0")->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$cate):++$i;$mod = ($i % 2 );?><li><a href="__APP__/Ticket/index/cate_id/<?php echo ($cate["id"]); ?>"><?php echo ($cate["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>鏈�柊浼樻儬鍒�/div>
    <div class="box1 panes">
<?php $_result=M('Cate')->order('id')->where("1=1 and pid = 0")->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$cate):++$i;$mod = ($i % 2 );?><div>
	<ul class="list1">
<?php $_result=D('Ticket')->getlist("where ticket.status=1 and ticket.close_time > 1352799273 and ticket.cate_id in(SELECT id from think_cate where pid=$cate[id])",'order by ticket.create_time desc','4',''); if ($_result): $i=0;foreach($_result as $key=>$voo):++$i;$mod = ($i % 2 );?><li>
<div class="fl"><label><?php echo ($voo["id"]); ?></label><a href="__APP__/Ticket/show/id/<?php echo ($voo["id"]); ?>"><img src="__ROOT__/img/imagethum/<?php echo ($voo["id"]); ?>.jpg" width="166" height="83"/></a></div>
<div class="fr">
<p class="q-name"><?php echo (get_trade($voo["trade_id"])); ?></p>
<p class="q-inf"><?php echo (t_substr($voo["title"],0,20)); ?></p>
<p class="q-pr">
<?php if($voo["score"] > 0): ?><a href="__GROUP__/User/ticket_col/id/<?php echo ($vo["id"]); ?>">鍔犲叆鏀惰棌</a>
<?php else: ?>
 <a href="__GROUP__/User/ticket_col/id/<?php echo ($vo["id"]); ?>">鍔犲叆鏀惰棌</a>|<a href="javascript:;" class="prt">绔嬪嵆鎵撳嵃</a><?php endif; ?>
</p>
</div>
</li><?php endforeach; endif;?>
    </ul>
</div><?php endforeach; endif;?>
    </div>
<script>
$(function() {
	$("ul.tabs3x").tabs("div.panes > div",{  event: 'click'});
});
</script>
<div class="cr"></div>
<div class="ad"><img src="/imgs/ad2.jpg" /></div>
<div class="cr"></div>
	<div class="box5-title"><ul class="tabs3">
<?php $_result=M('Cate')->order('id')->where("1=1 and pid = 0")->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$cate):++$i;$mod = ($i % 2 );?><li><a href="__APP__/Ticket/index/cate_id/<?php echo ($cate["id"]); ?>"><?php echo ($cate["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>鐑棬浼樻儬鍒�/div>
    <div class="box5 panes3">
<?php $_result=M('Cate')->order('id')->where("1=1 and pid = 0")->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$cate):++$i;$mod = ($i % 2 );?><div>
	<ul class="list1">
<?php $_result=D('Ticket')->getlist("where ticket.status=1 and ticket.close_time > 1352799273 and ticket.keyword like '%鐑棬%' and ticket.cate_id in(SELECT id from think_cate where pid=$cate[id])",'order by ticket.create_time desc','4',''); if ($_result): $i=0;foreach($_result as $key=>$voo):++$i;$mod = ($i % 2 );?><li>
<div class="fl"><label><?php echo ($voo["id"]); ?></label><a href="__APP__/Ticket/show/id/<?php echo ($voo["id"]); ?>"><img src="__ROOT__/img/imagethum/<?php echo ($voo["id"]); ?>.jpg" width="166" height="83"/></a></div>
<div class="fr">
<p class="q-name"><?php echo (get_trade($voo["trade_id"])); ?></p>
<p class="q-inf"><?php echo (t_substr($voo["title"],0,20)); ?></p>
<p class="q-pr">
<?php if($voo["score"] > 0): ?><a href="__GROUP__/User/ticket_col/id/<?php echo ($vo["id"]); ?>">鍔犲叆鏀惰棌</a>
<?php else: ?>
 <a href="__GROUP__/User/ticket_col/id/<?php echo ($vo["id"]); ?>">鍔犲叆鏀惰棌</a>|<a href="javascript:;" class="prt">绔嬪嵆鎵撳嵃</a><?php endif; ?>
</p>
</div>
</li><?php endforeach; endif;?>
    </ul>
</div><?php endforeach; endif;?>

    </div>
<script>
$(function() {
	$("ul.tabs3").tabs("div.panes3 > div",{  event: 'click'});
});
</script>
</div>
<div class="fr w24">
	<ul class="box2-title tabs2">
<li><a href="#">鐑棬鍟嗗</a></li>
<li><a href="#">鏈�柊鍟嗗</a></li>
    </ul>
    <div class="box2 panes2"><ul>
<li id="box2a">
<?php $_result=M('Trade')->order('id')->where("tags like '%澶存潯%'")->limit(1)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>" class="fl"><img src="<?php echo ($vo["logo"]); ?>" width="71" height="56" /></a>
    <div class="fr">
<p><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></p>
<p class="box2b"><?php echo ($vo["status"]); ?>绉嶄紭鎯�/p>
    </div><?php endforeach; endif;?>
</li>
<?php $_result=M('Trade')->order('id')->where("tags like '%鐑棬%'")->limit(6)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>
<ul>
<li id="box2a">
<?php $_result=M('Trade')->order('id')->limit(1)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>" class="fl"><img src="<?php echo ($vo["logo"]); ?>" width="71" height="56" /></a>
    <div class="fr">
<p><a href="__GROUP__/Shop/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></p>
<p class="box2b"><?php echo ($vo["status"]); ?>绉嶄紭鎯�/p>
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
<div class="cr"></div>
<div class="box6-title">缃戝弸鐑瘎</div>
<ul class="list2">
<?php $_result=M('News')->where('cid=42')->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/News/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
</ul>
</div>
<div class="cr"></div>
<div id="card-show">
<a class="nextPage bs1">l</a>
<a class="prevPage bs2">r</a>
<div class="icard">
<ul class="items">
<?php $_result=M('Cards')->order('id')->where("1=1")->limit(10)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/Cards/show/id/<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["title"]); ?>"><img src="<?php echo ($vo["path"]); ?>" /></a></li><?php endforeach; endif;?> 
</ul>
</div>
<script> 
$(function() {
	$("div.icard").scrollable({
	size:5,
	circular:true
	}).circular().autoscroll({
	steps:1,
	autoplay:true,
    interval:3000
	});// .autoscroll({autoplay:true});
});
</script>
</div>
<div class="cr"></div>
<div id="help-title">鍦ㄧ嚎甯姪</div>
<div id="help">
	<p>鎮ㄦ湁浠讳綍闂锛岄兘鍙互閫氳繃鍦ㄧ嚎瀹㈡湇銆佺數璇濄�QQ涓庢垜浠仈绯伙紒</p>
    <ul id="help-ico">
<li><a href="#"><img src="/imgs/kf.jpg" /></a></li>
<li id="help-ico1">03155919006</li>
<li id="help-ico2">980286941</li>
    </ul>
    <div class="cr"></div>
    <div id="help-cu">
<div>
	<p>鍟嗗甯姪锛�/p>
    <ul>
<?php $_result=M('News')->where('cid=40')->limit(3)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/News/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>
</div>
<div>
	<p>鐢ㄦ埛甯姪锛�/p>
    <ul>
<?php $_result=M('News')->where('cid=41')->limit(3)->select(); if ($_result): $i=0;foreach($_result as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="__GROUP__/News/show/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
    </ul>
</div>
    </div>
</div>
<div class="cr"></div>
</div>
<div id="links">
	<div class="w96 trk">
<div class="lkbox">
<div class="lkl1">
<p>鍚堜綔浼欎即 / <span>鍙嬫儏杩炴帴</span></p>
<div class="cr"></div>
<ul class="lkl1">
<?php $_result=M('Partner')->order('id')->where("1=1 and typeid = 1")->limit(5)->select(); if ($_result): $i=0;foreach($_result as $key=>$voo):++$i;$mod = ($i % 2 );?><li><a href="<?php echo ($voo["address"]); ?>"><img src="<?php echo ($voo["picpath"]); ?>" alt="<?php echo ($voo["content"]); ?>" /></a></li><?php endforeach; endif;?>
</ul>
<div class="cr"></div>
<ul class="lkl2">
<?php $_result=M('Partner')->order('id')->where("1=1 and typeid = 0")->limit(20)->select(); if ($_result): $i=0;foreach($_result as $key=>$voo):++$i;$mod = ($i % 2 );?><li><a href="<?php echo ($voo["address"]); ?>" title="<?php echo ($voo["content"]); ?>"><?php echo ($voo["title"]); ?></a></li><?php endforeach; endif;?>
</ul>
<div class="cr"></div>
</div>
</div>
    </div>
</div>










<div class="cr"></div>
<ul class="list1">
<?php $_result=D('Ticket')->getlist("where ticket.status=1 and ticket.close_time > 1352799273 and ticket.keyword like '%澶╃┖%'",'','4',''); if ($_result): $i=0;foreach($_result as $key=>$voo):++$i;$mod = ($i % 2 );?><li>
<div class="fl"><label><?php echo ($voo["id"]); ?></label><a href="__APP__/Ticket/show/id/<?php echo ($voo["id"]); ?>"><img src="__ROOT__/img/imagethum/<?php echo ($voo["id"]); ?>.jpg" width="166" height="83"/></a></div>
<div class="fr">
<p class="q-name"><?php echo (get_trade($voo["trade_id"])); ?></p>
<p class="q-inf"><?php echo (t_substr($voo["title"],0,20)); ?></p>
<p class="q-pr"><a href="__GROUP__/User/ticket_col/id/<?php echo ($voo["id"]); ?>">鍔犲叆鏀惰棌</a>|<a href="javascript:;" class="prt">绔嬪嵆鎵撳嵃</a></p>
</div>
</li><?php endforeach; endif;?>
</ul>













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