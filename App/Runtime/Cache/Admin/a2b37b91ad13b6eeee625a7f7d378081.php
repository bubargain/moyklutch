<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>『唐山优惠券管理平台』By ThinkPHP <?php echo (THINK_VERSION); ?></title>
<FRAMESET FRAMEBORDER=0 framespacing=0 border=0 rows="50, *,32">
<FRAME SRC="__APP__/Admin/Public/top" name="top" FRAMEBORDER=0 NORESIZE SCROLLING='no' marginwidth=0 marginheight=0>
<FRAMESET FRAMEBORDER=0  framespacing=0 border=0 cols="200,7, *" id="frame-body">
	<FRAME SRC="__APP__/Admin/Public/menu" FRAMEBORDER=0 id="menu-frame" name="menu">
    <frame src="__APP__/Admin/Public/drag" id="drag-frame" name="drag-frame" frameborder="no" scrolling="no">
	<?php if(($_SESSION['type_id'])  ==  "1"): ?><FRAME SRC="__APP__/Admin/Public/trade" FRAMEBORDER=0 id="main-frame" name="main"><?php endif; ?>
    <?php if(($_SESSION['type_id'])  ==  "0"): ?><FRAME SRC="__APP__/Admin/Public/main" FRAMEBORDER=0 id="main-frame" name="main"><?php endif; ?>
</FRAMESET>
<FRAME SRC="__APP__/Admin/Public/footer" name="footer" FRAMEBORDER=0 NORESIZE SCROLLING='no' marginwidth=0 marginheight=0>
</FRAMESET><noframes></noframes>
</html>