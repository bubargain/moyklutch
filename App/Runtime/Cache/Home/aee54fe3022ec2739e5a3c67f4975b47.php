<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>积分换礼品 VIP会员专享</title>
</head>

<body>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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


<div class="w96">
<!--JIFEN START-->
<div class="cr"></div>
<div id="jf-fl">
	<div id="jf-title">
<span class="fl">选择分类</span>
<span class="fr">您当前选的分类是：<a href="#">数码产品</a></span>
    </div>
    <div id="jf-gp">
<a href="#" class="jfc">数码产品</a>
<a href="#">美容美妆</a>
<a href="#">居家生活</a>
<a href="#">母婴用品</a>
<a href="#">珠宝饰品</a>
<a href="#">文化玩乐</a>
<a href="#">会员卡</a>
    </div>
</div>
<div class="cr"></div>
<div id="jf-show"><ul class="jf-list">
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf1.jpg" /></a></p>
    <p class="jfs-2"><a href="#">国行ipad 16G 大屏mp4</a></p>
    <p class="jfs-3">市场价格：<span>3900</span>元</span></p>
    <p class="jfs-4">兑换积分：<span>390000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf2.jpg" /></a></p>
    <p class="jfs-2"><a href="#">PS3 slim主机套装160G</a></p>
    <p class="jfs-3">市场价格：<span>2300</span>元</p>
    <p class="jfs-4">兑换积分：<span>230000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf3.jpg" /></a></p>
    <p class="jfs-2"><a href="#">Supor/苏泊尔 电压力锅</a></p>
    <p class="jfs-3">市场价格：<span>360</span>元</p>
    <p class="jfs-4">兑换积分：<span>36000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf4.jpg" /></a></p>
    <p class="jfs-2"><a href="#">国行iphone4 黑/白 16G</a></p>
    <p class="jfs-3">市场价格：<span>4990</span>元</p>
    <p class="jfs-4">兑换积分：<span>499000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf5.jpg" /></a></p>
    <p class="jfs-2"><a href="#">佳能单反600D/18-55套机</a></p>
    <p class="jfs-3">市场价格：<span>7800</span>元</p>
    <p class="jfs-4">兑换积分：<span>780000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf1.jpg" /></a></p>
    <p class="jfs-2"><a href="#">国行ipad 16G 大屏mp4</a></p>
    <p class="jfs-3">市场价格：<span>3900</span>元</p>
    <p class="jfs-4">兑换积分：<span>390000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf2.jpg" /></a></p>
    <p class="jfs-2"><a href="#">PS3 slim主机套装160G</a></p>
    <p class="jfs-3">市场价格：<span>2300</span>元</p>
    <p class="jfs-4">兑换积分：<span>230000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf3.jpg" /></a></p>
    <p class="jfs-2"><a href="#">Supor/苏泊尔 电压力锅</a></p>
    <p class="jfs-3">市场价格：<span>360</span>元</p>
    <p class="jfs-4">兑换积分：<span>36000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf4.jpg" /></a></p>
    <p class="jfs-2"><a href="#">国行iphone4 黑/白 16G</a></p>
    <p class="jfs-3">市场价格：<span>4990</span>元</p>
    <p class="jfs-4">兑换积分：<span>499000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
<li>
	<p class="jfs-1"><a href="#"><img src="/imgs/jf5.jpg" /></a></p>
    <p class="jfs-2"><a href="#">佳能单反600D/18-55套机</a></p>
    <p class="jfs-3">市场价格：<span>7800</span>元</p>
    <p class="jfs-4">兑换积分：<span>780000</span>分</p>
    <p class="jfs-5"><a href="#"><img src="/imgs/jf-dh.gif" /></a><a href="#"><img src="/imgs/jf-ck.gif" /></a></p>
</li>
</ul>
<div class="cr"></div>
<div id="jf-page"><ul>
	<li>共10条记录</li>
    <li>|</li>
    <li>每页10条</li>
    <li>|</li>
    <li>共1页</li>
    <li></li>
	<li><a href="#">上一页</a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
	<li><a href="#">3</a></li>
    <li><a href="#">下一页</a></li>
</ul></div>
</div>
<!--JIFEN ENDING-->
</div>
<div class="cr"></div>
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
</body>
</html>