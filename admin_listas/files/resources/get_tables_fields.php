<?php
	include("funciones.php");
	$caracter=caracter();
	$tablas=$_REQUEST['tablas'];
	echo "<h3>Select the fields that you want to show from each table</h3>";
	echo "<table><tr>";
	for($j=1;$j<=count($tablas);$j++){
		$array_campos=obtener_columnas($caracter.$tablas[$j-1].$caracter);
		echo "<td>".$tablas[$j-1]."<br><select id='".$tablas[$j-1]."' name='".$tablas[$j-1]."[]' multiple='multiple' data-original-title='Press Ctrol for select multiple items' data-toggle='tooltip' data-placement='top' title='' style='height:150px;'>\n";
		for($i=0;$i<count($array_campos);$i++){
			echo "<option value='".$array_campos[$i]."' selected='selected'>".$array_campos[$i]."</option>\n";
		}
		echo "</select></td>".((($j%3==0)&&($j!=0))?"</tr><tr>":"");
	}
	if(count($tablas)>2){
		$falta=count($tablas)%3;
		for($i=0;$i<$falta;$i++)	echo "<td></td>";
	}
	echo "</tr><tr><td class='right' colspan='".((count($tablas)>=3)?"3":"2")."'><input type='button' value='Next' class='btn btn-info' id='next2'></td></tr></table>";
?>