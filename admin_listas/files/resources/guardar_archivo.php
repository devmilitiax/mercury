<?php
	include_once("encriptar.php");
	$name=$_GET['nam'];
	$db=$_GET['dbselect'];
	$host=$_GET['host'];
	$dbname=$_GET['dbname'];
	$port=$_GET['port'];
	if(($_GET['autenticacion']!="windows")||($db!="sqlserver")){
		if($_GET['usuario']!=""){
			$user=encrypt($_GET['usuario']);
			$pass=encrypt($_GET['contra']);
		}
	}
	$fp = fopen("../config/".$name.".php","w+");
	$string_aut="";
	if($db=="sqlserver"){
		$string_aut="$"."autenticacion='".$_GET['autenticacion']."';\n";
	}
	$write="<?php\n$"."db='".$db."';\n$"."host='".$host."';\n$"."port='".$port."';\n$"."dbname='".$dbname."';\n".$string_aut;
	if(isset($user))
		$write.="$"."user='".$user."';\n$"."pass='".$pass."';\n";
	$write.="?>" . PHP_EOL;
	fwrite($fp, $write);
	fclose($fp);
?>