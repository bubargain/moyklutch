<?php

if (empty($_FILES) === false) {
	$tmp_name = $_FILES['htmlFile']['tmp_name'];
	$html = file_get_contents($tmp_name);
	header('Content-type: text/html; charset=UTF-8');
	echo $html;
}

?>