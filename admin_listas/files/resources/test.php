<?php
	$db=$_REQUEST['dbselect'];
	$host=$_REQUEST['host'];
	$dbname=$_REQUEST['dbname'];
	$port=$_REQUEST['port'];
	if(($_REQUEST['autenticacion']!="windows")||($db!="sqlserver")){
		$usuario=$_REQUEST['usuario'];
		$contra=$_REQUEST['contra'];	
	}else{
		$usuario="";
		$contra="";
	}
	try {
		switch($db){
			case "mysql":
				$conexion=new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$usuario,$contra);
			break;
			case "sqlserver":
				$conexion=new PDO('sqlsrv:Server='.$host.';Database='.$dbname,$usuario,$contra);
			break;
			case "postgres":
				$conexion=new PDO('pgsql:host='.$host.';dbname='.$dbname.';port='.$port,$usuario,$contra);
			break;
			case "sqlite":
				$conexion=new PDO('sqlite:../sqlite/'.$dbname);
			break;
		}
	}catch (PDOException $e) {
		echo "Problems with the connexion <br>".$e->getMessage();
		die();
	}
	echo "true";
	$conexion=null;
?>