<?php
	include_once("funciones.php");
	$caracter=caracter();
	$escape=escape();
	$campos=$_REQUEST['campos'];
	$valores=$_REQUEST['valores'];
	$n_valores=$_REQUEST['n_valores'];
	$tabla=$_REQUEST['tabla'];
	$sql="update ".$caracter.$tabla.$caracter." set ";
	$flag=false;
	for($i=0;$i<count($valores)-1;$i++){
		$valores[$i]=str_replace("'",$escape,$valores[$i]);
		$n_valores[$i]=str_replace("'",$escape,$n_valores[$i]);
		if($valores[$i]!=$n_valores[$i]){
			$sql.=$caracter.$campos[$i].$caracter."='".$n_valores[$i]."',";
			$flag=true;
		}
	}
	if($flag){
		$sql=trim($sql, ",");
		$keys=getPrimaryKey($tabla);
		$sql.=" where ";
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
		$sql=str_replace("<td>", "", trim($sql, " and "));
		if(ejecutar_query($sql))
			echo "true";
		//echo $sql;
	}else{
		echo "nada";
	}
?>