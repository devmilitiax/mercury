<?php
	if(isset($_REQUEST['file']))
		$files=$_REQUEST['file'];
	$dir="../config/";
	$fp2 = fopen($dir."config.php", "w");
	if(isset($_REQUEST['delete'])){
		include($dir."config.php");
		$file_delete=$_REQUEST['delete'];
		if($file_delete==$file){
			fwrite($fp2,"");
		}
	}else{
		fwrite($fp2,"<?php $"."file='".$files."';?>");
	}
	fclose($fp2);
	
	include("../resources/funciones.php");
	if(!ejecutar_query("select * from saved_queries"))
		ejecutar_query("create table saved_queries(name varchar(30), description varchar(300), query varchar(3000))");
?>