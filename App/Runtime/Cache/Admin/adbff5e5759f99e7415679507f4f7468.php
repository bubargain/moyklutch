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
<!-- 菜单区域  -->
<!-- 主页面开始 -->
<div id="main" class="main" >

<!-- 主体内容  -->
<div class="content" >
<div class="title">配置列表</div>
<!-- 列表显示区域  -->
<div class="list">
<!-- 查询区域 -->
<table id="checkList" cellpadding="0" cellspacing="0" width="99%" style="text-align:left;" class="list">
<tr><td height="5" colspan="6" class="topTd"></td></tr>
<form method=post id="form1"  action="__URL__/update">
<tr class="row">
<td width="10%" height="30"><strong>网站名称：</strong></td
><td><input size="20" name="webname" value="<?php echo ($vo["webname"]); ?>"></td>
<td colspan="3"></td></tr>
<tr class="row">
<td width="10%" height="30"><strong>支付宝账号</strong></td
><td>商户ID号：<input size="20" name="zf_username" value="<?php echo ($vo["zf_username"]); ?>"></td>
<td colspan="3">交易密匙：<input size="20" name="zf_password" type="password" value="<?php echo ($vo["zf_password"]); ?>"><input size="15" name="zf_password2" type="hidden" value="<?php echo ($vo["zf_password"]); ?>"></td></tr>
<tr class="row">
<td height="30"><strong>邮件设置</strong></td>
<td width="24%">SMTP主机：<input size="15" name="stmp_server" value="<?php echo ($vo["stmp_server"]); ?>"></td>
<td width="22%">SMTP端口：<input size="15" name="stmp_port" value="<?php echo ($vo["stmp_port"]); ?>"></td>
<td width="24%">验证用户名：<input size="15" name="stmp_username" value="<?php echo ($vo["stmp_username"]); ?>"></td>
<td width="20%">验证密码：<input size="15" name="stmp_password" type="password" value="<?php echo ($vo["stmp_password"]); ?>"></td>
</tr>
<tr class="row">
<td height="30"></td><td>发信地址：<input size="15" name="stmp_send" value="<?php echo ($vo["stmp_send"]); ?>"></td>
<td>回信地址：<input size="15" name="stmp_back" value="<?php echo ($vo["stmp_back"]); ?>"></td>
<td>发信频率：<input size="15" name="stmp_rate" value="<?php echo ($vo["stmp_rate"]); ?>">秒</td>
<td></td></tr>
<tr class="row">
<td height="30"><strong>手机短信设置</strong></td><td>手机短信帐号：<input name="sms_user" value="<?php echo ($vo["sms_user"]); ?>"></td>
<td>手机短信密码：<input name="sms_password" value="<?php echo ($vo["sms_password"]); ?>"></td>
<td></td>
<td></td></tr>
<tr class="row" style="display:none;">
<td width="10%" height="30"><strong>系统错误邮箱</strong></td><td colspan="4"><input size="30" name="error_email" value="<?php echo ($vo["error_email"]); ?>">（用于打印机产生错误时向管理员发送提示信息）</td></tr>
<tr class="row">
<td width="10%" height="30"><strong>金币兑换规则</strong></td><td><input size="15" name="money_rate" value="<?php echo ($vo["money_rate"]); ?>" />
  积分（/元）</td>
  <td width="10%" height="30"  colspan="3"><strong>积分赠送规则:</strong>每充<input size="5" name="score_rate" value="<?php echo ($vo["score_rate"]); ?>">分，赠送<input size="5" name="score_give" value="<?php echo ($vo["score_give"]); ?>">分,绑定会员卡增送 <input name="score_card" type="text" class="text" value="<?php echo ($vo["score_card"]); ?>"/></td></tr>
<tr class="row">
<td width="10%" height="30"><strong>是否开启评论</strong></td><td colspan="4">
<select name="comment_if">
<option <?php if(($vo["comment_if"])  ==  "0"): ?>selected<?php endif; ?> value="0">关闭</option>
<option <?php if(($vo["comment_if"])  ==  "1"): ?>selected<?php endif; ?> value="1">开启</option>
</select></td></tr>
<tr class="row">
<td width="10%" height="30"><strong>注册加积分</strong></td><td><input size="5" name="reg_if" value="<?php echo ($vo["reg_if"]); ?>">分</td>
<td width="10%" height="30"><strong>完善信息加积分</strong><input size="5" name="info_if" value="<?php echo ($vo["info_if"]); ?>">分</td>
<td width="10%" height="30"><strong>邮箱验证加积分</strong><input size="5" name="email_if" value="<?php echo ($vo["email_if"]); ?>">分</td>
<td width="10%" height="30"><strong>手机验证加积分</strong><input size="5" name="mobile_if" value="<?php echo ($vo["mobile_if"]); ?>">分</td>
</tr>
<tr class="row">
</tr>
<tr class="row">
<td width="10%" height="30"><strong>过滤的字符</strong></td><td colspan="4"><textarea cols="120" rows="4" name="word_filter"><?php echo ($vo["word_filter"]); ?></textarea>(请用,分割)</td>
</tr>
<tr class="row">
<td width="10%" height="30"></td><td colspan="4"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>"/><input type="submit" value="保存设置" class="small hMargin shadow submit"></tr>
<tr class="row">
<tr><td height="5" colspan="6" class="bottomTd"></td></tr></form>
</table></div>
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->