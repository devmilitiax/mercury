<?php
	require_once("funciones.php");
	echo "<select id='tabla' name='tabla' class='span2'><option value='0' style='background:#000'>Select</option>";
	$array_tablas=obtener_tablas();
	for($i=0;$i<count($array_tablas);$i++) {
		echo "<option value='".$array_tablas[$i]."'>".$array_tablas[$i]."</option>";
	}
	echo "</select>";
?>