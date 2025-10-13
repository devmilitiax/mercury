<?php
	include("funciones.php");
	include("../config/other_config.php");
	$caracter=caracter();
	$escape=escape();
	ini_set('memory_limit', $m_limit.'M');
	set_time_limit(0);
	$tablas=$_POST['tablas'];
	$campos_mostrar=Array();
	$array_campos=Array();
	$k=0;
	for($i=0;$i<count($tablas);$i++){
		for($j=0;$j<count($_POST[$tablas[$i]]);$j++){
			$campos_tabla=$_POST[$tablas[$i]];
			$array_campos[$k]=$campos_tabla[$j];
			$campos_mostrar[$k]=$caracter.$tablas[$i].$caracter.".".$caracter.$campos_tabla[$j].$caracter;
			$k++;
		}
		$tablas[$i]=$caracter.$tablas[$i].$caracter;
	}
	$i=0;
	$j_condiciones=Array();
	while(isset($_POST['a_tab'.$i])){
		$j_condiciones[$i]=$caracter.$_POST['a_tab'.$i].$caracter.".".$caracter.$_POST['a_field'.$i].$caracter."=".$caracter.$_POST['b_tab'.$i].$caracter.".".$caracter.$_POST['b_field'.$i].$caracter;
		$i++;
	}
	$sql="select ".implode(", ",$campos_mostrar);
	
	$order_tab=$caracter.$_POST['order_tab'].$caracter;
	$order_field=$caracter.$_POST['order_field'].$caracter;
	$first=$_POST['first'];
	$last=$_POST['last'];
	$condiciones=Array();
	$orden="";
	$and=false;
	$y=0;
	while(isset($_POST['operador'.$y])){
		if(($_POST['operador'.$y]!="0")&&($_POST['c_value'.$y]!="")){
			$_POST['c_value'.$y]=str_replace("'", $escape, $_POST['c_value'.$y]);
			$_POST['c_tab'.$y]=$caracter.$_POST['c_tab'.$y].$caracter;
			$_POST['c_fields'.$y]=$caracter.$_POST['c_fields'.$y].$caracter;
			if(($_POST['operador'.$y]=="like")||($_POST['operador'.$y]=="not like")){
				$_POST['c_value'.$y]="%".$_POST['c_value'.$y]."%";
			}
			$condiciones[$y]=$_POST['c_tab'.$y].".".$_POST['c_fields'.$y]." ".$_POST['operador'.$y]." '".$_POST['c_value'.$y]."'";
		}
		$y++;
	}
	$orden=" order by ".$order_tab.".".$order_field." ".$_POST['order_sence'];
		
	if(is_numeric($first)&&(is_numeric($last))){
		$first=round($first);
		$last=round($last);
		if($first<0){
			$first=$first*(-1);
		}
		if($last<0){
			$last=$last*(-1);
		}
		$last=$last-$first;
		switch($db){
			case "mysql":
				$limites=" limit ".$first.",".$last;
			break;
			case "postgres":
				$limites=" limit ".$last." offset ".$first;
			break;
			case "sqlserver":
				$limites=" a.row_1ab2>=".$first." and a.row_1ab2<=".($last+$first)." ";
			break;
		}
	}
	if($db!="sqlserver"){
		$sql.=" from ".implode(", ", $tablas)." where ".implode(" and ", $j_condiciones);
		if(count($condiciones)>0)
			$sql.=" and ".implode(" and ", $condiciones);
		$sql.=$orden.$limites;
	}else{
		$sql.=" from(select *, ROW_NUMBER() over(".$orden.") as row_1ab2 from ".implode(", ", $tablas)." where ".implode(" and ", $j_condiciones);
		if(count($condiciones)>0){
			$sql.=" and ".implode(" and ", $condiciones);
		}
		$sql.=") a where ".$limites;
	}
	//echo $sql;
	$resultado=obtener_query($sql);
	echo "<table class='result_tabla'>\n<tr class='header'>";
	for($i=0;$i<count($array_campos);$i++){
		$array_campos[$i]=str_replace($caracter, "", $array_campos[$i]);
		echo "<th>".$array_campos[$i]."</th>";
	}
	echo "</tr>\n";
	for($i=0;$i<count($resultado);$i++){
		echo "<tr>";
		for($j=0;$j<count($array_campos);$j++){
			if(isset($resultado[$i][$j]))
				echo "<td>".$resultado[$i][$j]."</td>";
		}
		echo "</tr>\n";
	}
	echo "</table>";
	//echo "<br/><span style='color='#000'>".$sql."</span>";
?>