<?php
	include_once("funciones.php");
	$caracter=caracter();
	$escape=escape();
	$campos=$_REQUEST['campos'];
	$valores=$_REQUEST['valores'];
	$tabla=$_REQUEST['tabla'];
	$n=count($campos);
	$keys=getPrimaryKey($tabla);
	$sql="delete from ".$caracter.$tabla.$caracter." where ";
	for($i=0;$i<count($valores)-1;$i++){
		$valores[$i]=str_replace("'", $escape, $valores[$i]);
		if($keys!=false){
			for($j=0;$j<count($keys);$j++){
				if($campos[$i]==$keys[$j]){
					$sql.=$caracter.$campos[$i].$caracter."='".$valores[$i]."' and ";
				}
			}
		}else{
			$sql.=$caracter.$campos[$i].$caracter."='".$valores[$i]."' and ";
		}
	}
	$sql=trim($sql, " and ");
	$sql="d".$sql.(($db=="mysql")?" limit 1":"");
	//echo $sql;
	if(ejecutar_query($sql))
		echo "true";
?>