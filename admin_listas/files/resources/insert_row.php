<?php
	include_once("funciones.php");
	$caracter=caracter();
	$escape=escape();
	$campos=$_REQUEST['campos'];
	$valores=$_REQUEST['valores'];
	$tabla=$_REQUEST['tabla'];
	$n=count($campos);
	for($i=0;$i<count($valores);$i++)
		$valores[$i]=str_replace("'", $escape, $valores[$i]);
		
	$valores_reales=array();
	$campos_reales=array();
	$j=0;
	for($i=0;$i<count($valores);$i++){
		if($valores[$i]!=""){
			$valores_reales[$j]=$valores[$i];
			$campos_reales[$j]=$campos[$i];
			$j++;
		}
	}
		
	$sql="insert into ".$caracter.$tabla.$caracter." (".$caracter.implode($caracter.",".$caracter,$campos_reales).$caracter.") values('".implode("','",$valores_reales)."')";
	$sql=str_replace("<td>","", $sql);
	//echo $sql."<br>";
	if(ejecutar_query($sql))
		echo "true";
?>