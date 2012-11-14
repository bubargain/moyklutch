<?php
header("Content-type: application/octet-stream");
header("Content-length: " . strlen($_POST['htmlData']) . "");
header("Content-Disposition: attachment; filename=coupon.html");
echo $_POST['htmlData'];
?>