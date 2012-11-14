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

<script type="text/javascript" src="__PUBLIC__/Js/Util/Calendar.js"></script>
<script>
$("div#nav ul li:nth-child(3)").addClass("cc");
$("#jf-gp > a").click(function(){
	$(this).addClass("jfc");
});
</script>


<div class="w96">
<div class="cr"></div>
<div id="jf-fl">
	<div id="jf-title">
<span class="fl">秒杀商品分类</span>
<span class="fr">您当前选的分类是：<?php if($classname == ''): ?>全部<?php else: ?><?php echo ($classname); ?><?php endif; ?></span>
    </div>
    <div id="jf-gp">
<a href="__URL__/slist">全部</a>
<?php if(is_array($classlist)): $i = 0; $__LIST__ = $classlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="__URL__/index/cid/<?php echo ($vo["id"]); ?>"
<?php if(($vo["id"])  ==  $_GET['cid']): ?>class="jfc"<?php endif; ?>
><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<div class="cr"></div>
<div class="msha-now">
	<form action="__URL__/slist" method="POST">
	<p class="title"><span>根据时间排列：
	<input type="text" name='stime' size=10 onFocus="ShowCalendar(this)">&nbsp;至&nbsp;<input type="text" name='etime' size=10 onFocus="ShowCalendar(this)"><input name="search" type="submit" value="查询">
	</span>最新秒杀成功商品</p></form>
    <div class="cr"></div>
    <ul class="listms2 ah">
	<?php if(is_array($slist)): $i = 0; $__LIST__ = $slist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): ++$i;$mod = ($i % 2 )?><li>
	<p class="msha-ct"><a href="#"><?php echo ($s["title"]); ?></a></p>
    <p><span class="fr ms1">￥<?php echo ($s["price"]); ?></span>市场价格：</p>
    <p class="msha-ct"><img src="<?php echo ($s["thumpath"]); ?>" width="168" height="118" /></p>
    <p><span class="fr ms2">-<?php echo ($s["score"]); ?></span>所需积分：</p>
	<p><span class="fr ms4"><?php echo (date("m-d H:i:s",$s["starttime"])); ?></span>开始时间：</p>
	<p class="msha-ct"><a href="__URL__/content/id/<?php echo ($s["id"]); ?>#c">立 刻 报 名</a></p>
	</form>
</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
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