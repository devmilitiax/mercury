<?php
	include("funciones.php");
	$caracter=caracter();
	$tabla=$_REQUEST['tabla'];
	$tabla=$caracter.$tabla.$caracter;
	$array_campos=obtener_columnas($tabla);
	echo "<select id='campos' name='campos' class='span1'>\n";
	for($i=0;$i<count($array_campos);$i++){
		echo "<option value='".$array_campos[$i]."'>".$array_campos[$i]."</option>\n";
	}
	echo "</select>";
?>