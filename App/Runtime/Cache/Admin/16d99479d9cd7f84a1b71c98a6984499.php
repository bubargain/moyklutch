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
<div class="main" >
<div class="content">
<TABLE width="458" cellpadding=0 cellspacing=0 class="list" id="error" >
<TR class="row" ><th colspan="2" class="space"><font color="#FF0000">下列机器无法正常连接</font><img onclick="document.getElementById('error').style.display='none';" SRC="__PUBLIC__/Images/error.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="" align="absmiddle"></th></tr>
<?php  if(is_array($overlist)): foreach($overlist as $key=>$v): ?><TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="16%"><?php echo ($v["macno"]); ?></TD><TD width="79%">最后连接时间：<?php echo (date("Y-m-d:H时-i分-s秒",$v["link_time"])); ?></TD></TR><?php endforeach; endif; ?>
<TR class="row" ><th colspan="2" class="space"><font color="#FF0000">下列机器有严重错误</font></th></tr>
<?php  if(is_array($overlist2)): foreach($overlist2 as $key=>$v): ?><TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="16%"><?php echo ($v["mac_id"]); ?></TD><TD width="79%"><?php echo ($v["error_content"]); ?></TD></TR><?php endforeach; endif; ?>
</TABLE>
<TABLE id="checkList" class="list" cellpadding=0 cellspacing=0 >
<tr><td height="5" colspan="4" class="topTd" ></td></tr>
<TR class="row" ><th colspan="4" class="space">系统提示</th></tr>

<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%">用户数量：</TD><TD><?php echo ($usercount); ?></TD><TD width="15%">商家数量：</TD><TD><?php echo ($tradecount); ?></TD></TR>
<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%"><font color="#FF0000">待审核用户：</font></TD><TD><font color="#FF0000"><?php echo ($usercount_s); ?></font></TD><TD width="15%"><font color="#FF0000">今日打印数量：</font></TD><TD><font color="#FF0000"><?php echo ($printcount); ?></font></TD></TR>
<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%"><font color="#FF0000">今日收藏数量：</font></TD><TD><font color="#FF0000"><?php echo ($collectcount); ?></font></TD><TD width="15%"><font color="#FF0000">今日点击数量：</font></TD><TD><font color="#FF0000"><?php echo ($pointcount); ?></font></TD></TR>
<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%">会员卡数量：</TD><TD><?php echo ($cardcount); ?></TD><TD width="15%">优惠券数量：</TD><TD><?php echo ($ticketcount); ?></TD></TR>
<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%">展位租赁数量：</TD><TD><?php echo ($tenancycount); ?></TD><TD width="15%"><font color="#FF0000">待审核优惠券：</font></TD><TD><font color="#FF0000"><?php echo ($ticketwait); ?></font></TD></TR>
<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%"><font color="#FF0000">展位即将到期（一周内）：</font></TD><TD><font color="#FF0000"><?php echo ($tenancyover); ?></font></TD><TD width="15%"><font color="#FF0000">展位租赁预约：</font></TD><TD><font color="#FF0000"><?php echo ($tenancywait); ?></font></TD></TR>
<TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%"><font color="#FF0000">今日打印机错误报告：</font></TD><TD><font color="#FF0000"><?php echo ($errorcount); ?></font></TD><TD width="15%"><font color="#FF0000">今日充值记录：</font></TD><TD><font color="#FF0000"><?php echo ($paycount); ?></font></TD></TR>

<tr><td height="5" colspan="4" class="bottomTd"></td></tr>
</TABLE>
<TABLE id="checkList" class="list" cellpadding=0 cellspacing=0 >
<tr><td height="5" colspan="5" class="topTd" ></td></tr>
<TR class="row" ><th colspan="3" class="space">系统信息</th></tr>
<?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): ++$i;$mod = ($i % 2 )?><TR class="row" onmouseover="over()" onmouseout="out()" onclick="change()" ><TD width="15%"><?php echo ($key); ?></TD><TD><?php echo ($v); ?></TD></TR><?php endforeach; endif; else: echo "" ;endif; ?>
<tr><td height="5" colspan="5" class="bottomTd"></td></tr>
</TABLE>
</div>
</div>
<!-- 主页面结束 -->