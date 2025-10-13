<?php
	include("funciones.php");
	$caracter=caracter();
	$tabla=$_REQUEST['tabla'];
	$tabla=$caracter.$tabla.$caracter;
	$array_campos=obtener_columnas($tabla);
	echo "<select id='select_campo_unico' name='select_campo_unico' class='span2'>\n";
	echo "<option value='0' style='background:#000'>Select</option>";
	for($i=0;$i<count($array_campos);$i++){
		echo "<option value='".$array_campos[$i]."'>".$array_campos[$i]."</option>\n";
	}
	echo "</select>";
?>