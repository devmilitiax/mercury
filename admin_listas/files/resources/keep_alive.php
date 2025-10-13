<?php
	session_start();
	$usuario=$_REQUEST['usuario'];
	if(isset($_REQUEST['abierta']))
		setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], time()+60*60*24*30, '/');
	$_SESSION["user"]=$usuario;
?>