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
<tr><td height="5" colspan="3" class="topTd"></td></tr>
<form method=post id="form1"  action="__URL__/updatecardconf">
<tr class="row">
<td height="30" colspan="3"><strong>机器相关设置</strong></td>
</tr>
<tr class="row">
<td width="29%">网络传输校验码：<input size="30" name="key" value="<?php echo ($vo["key"]); ?>"></td>
<td width="26%">密码过期时间：<input size="5" name="password_expire" value="<?php echo ($vo["password_expire"]); ?>">(天)</td>
<td width="45%">最大上传图片大小限制：<input size="15" name="maxSize" value="<?php echo ($vo["maxSize"]); ?>">(B)</td>
</tr>
<tr class="row">
<td>网站名：<input size="25" name="sitename" value="<?php echo ($vo["sitename"]); ?>"></td>
<td>积分名：<input size="15" name="point_name" value="<?php echo ($vo["point_name"]); ?>"></td>
<td>会员卡模式：<input size="15" name="card_mode" value="<?php echo ($vo["card_mode"]); ?>">（1支持所有M1,2只支持自己的会员卡）</td>
</tr>
<tr class="row">
<td colspan="3">生成gif图片高度：<input size="25" name="gif_height" value="<?php echo ($vo["gif_height"]); ?>">(px)</td>
</tr>
<tr class="row">
<td width="10%" height="30" colspan="3"><strong>打印数量限制</strong>
    <input type="radio" name="limit_time" <?php if(($vo["limit_time"])  ==  "1"): ?>checked="checked"<?php endif; ?> value="1"/> 日 
    <input type="radio" name="limit_time" <?php if(($vo["limit_time"])  ==  "7"): ?>checked="checked"<?php endif; ?> value="7"/> 周
    <input type="radio" name="limit_time" <?php if(($vo["limit_time"])  ==  "30"): ?>checked="checked"<?php endif; ?> value="30"/>月
    <input type="radio" name="limit_time" <?php if(($vo["limit_time"])  ==  "365"): ?>checked="checked"<?php endif; ?> value="365"/>年
  <input size="15" name="limit_count" value="<?php echo ($vo["limit_count"]); ?>" />
  （张）</td></tr>
<tr class="row">
<td colspan="3"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>"/><input type="submit" value="保存设置" class="small hMargin shadow submit"></tr>
<tr class="row">
<tr><td height="5" colspan="3" class="bottomTd"></td></tr></form>
</table></div>
</div>
<!-- 主体内容结束 -->
</div>
<!-- 主页面结束 -->