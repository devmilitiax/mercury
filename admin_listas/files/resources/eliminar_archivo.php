<?php
	$file=$_REQUEST['file'];
	$dir=$_REQUEST['dir'];
	unlink($dir.$file);
?>