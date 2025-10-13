<?php
	include("funciones.php");
	$caracter=caracter();
	$tabla=$_REQUEST['tabla'];
	$tabla=$caracter.$tabla.$caracter;
	$array_campos=obtener_columnas($tabla);
	echo "<select id='select_campos' name='select_campos[]' multiple='multiple' data-original-title='Press Ctrol for select multiple items' data-toggle='tooltip' data-placement='top' title='' style='height:150px;'>\n";
	for($i=0;$i<count($array_campos);$i++){
		echo "<option value='".$array_campos[$i]."' selected='selected'>".$array_campos[$i]."</option>\n";
	}
	echo "</select>";
	echo "<script>$('#select_campos').tooltip('hover')</script>";
?>