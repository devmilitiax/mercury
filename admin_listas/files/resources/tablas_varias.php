<?php
	require_once("funciones.php");
	echo "<select id='tablas' name='tablas[]' multiple='multiple' data-original-title='Press Ctrol for select multiple items' data-toggle='tooltip' data-placement='bottom' title=''>";
	$array_tablas=obtener_tablas();
	for($i=0;$i<count($array_tablas);$i++) {
		echo "<option value='".$array_tablas[$i]."' selected='selected'>".$array_tablas[$i]."</option>";
	}
	echo "</select>";
	echo "<script>$('#tablas').tooltip('hover')</script>";
?>