<?php
require('../Config/config.php');
require('../card/common.php');
dbconnect();
global $_SGLOBAL, $_SCONFIG;
$query = $_SGLOBAL['db']->query("select html from " . $_SCONFIG['dbDSN']['dbTablePrefix'] . "ticket where id='$_REQUEST[id]'");
$ticket = $_SGLOBAL['db']->fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>KindEditor</title>
		<style>
			form {
				margin: 0;
			}
			textarea {
				display: block;
			}
		</style>
		<script charset="utf-8" src="jquery.js"></script>
		<script charset="utf-8" src="kindeditor.js"></script>
		<script>
			KE.show({
				id : 'content'
			});
		</script>
	</head>
	<body>
		<h3>Coupon Editer——<?php echo $_REQUEST['name'];?></h3>
		<iframe name="saveIframe" id="saveIframe" style="display:none;"></iframe>
		<form id="editorForm" name="editorForm" method="post" action="php/save2.php">
        <input type="hidden" id="tid" name="tid" value="<?php echo $_REQUEST['id'];?>" />
			<input type="hidden" id="htmlData" name="htmlData" value="" />
			<textarea id="content" name="content" style="width:929px;height:<?php echo $_SCONFIG['gif_height']; ?>px;visibility:hidden;"><?php echo $ticket['html']; ?></textarea>
            <input type="submit" value="Save" />
		</form>
	</body>
</html>
