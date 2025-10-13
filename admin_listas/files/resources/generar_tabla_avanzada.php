<?php
	include("funciones.php");
	include("../config/other_config.php");
	$caracter=caracter();
	$escape=escape();
	ini_set('memory_limit', $m_limit.'M');
	set_time_limit(0);
	
	if(isset($_POST['s_queries'])){
		$result=obtener_query("select query, description from saved_queries where name='".$_POST['s_queries']."'");
		$sql=$result[0][0];
		$description=$result[0][1];
		$array_campos=obtener_columnas_libre($sql);
	}else{
		$tabla=$_POST['tabla'];
		$tabla=$caracter.$tabla.$caracter;
		$array_campos=$_POST['select_campos'];
		for($i=0;$i<count($array_campos);$i++){
			$array_campos[$i]=$caracter.$array_campos[$i].$caracter;
		}
		$order_field=$_POST['select_campo_unico'];
		$order_field=$caracter.$order_field.$caracter;
		$first=$_POST['first'];
		$last=$_POST['last'];
		$condicion="";
		$orden="";
		$sql="select ".implode(",",$array_campos);
		$and=false;
		if(($_POST['operador']!="none")&&($_POST['condicion']!="")){
			$_POST['condicion']=str_replace("'", $escape, $_POST['condicion']);
			$_POST['campos']=$caracter.$_POST['campos'].$caracter;
			if(($_POST['operador']=="like")||($_POST['operador']=="not like")){
				$_POST['condicion']="%".$_POST['condicion']."%";
			}
			$condicion=$_POST['campos']." ".$_POST['operador']." '".$_POST['condicion']."' ";
			$and=true;
		}
		$y=0;
		while(isset($_POST['operador'.$y])){
			if(($_POST['operador'.$y]!="none")&&($_POST['condicion'.$y]!="")){
				$_POST['condicion'.$y]=str_replace("'", $escape, $_POST['condicion'.$y]);
				$_POST['campos'.$y]=$caracter.$_POST['campos'.$y].$caracter;
				if(($_POST['operador'.$y]=="like")||($_POST['operador'.$y]=="not like")){
					$_POST['condicion'.$y]="%".$_POST['condicion'.$y]."%";
				}
				$condicion.=(($and)?(($_POST['c_option']=='0')?" and ":" or "):$and=true).$_POST['campos'.$y]." ".$_POST['operador'.$y]." '".$_POST['condicion'.$y]."' ";
			}
			$y++;
		}
		
		if($order_field!=$caracter.'0'.$caracter){
			$orden=" order by ".$order_field." ".$_POST['asc-desc'];
		}else{
			if($db=="sqlserver"){
				$orden="order by ".$array_campos[0];
			}
		}
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
			$sql.=" from ".$tabla;
			if($condicion!="")
				$sql.=" where ".$condicion;
			$sql.=$orden.$limites;
		}else{
			$sql.=" from(select *, ROW_NUMBER() over(".$orden.") as row_1ab2 from ".$tabla;
			if($condicion!=""){
				$sql.=" where ".$condicion;
			}
			$sql.=") a where ".$limites;
		}
	}
	$resultado=obtener_query($sql);

	echo "<table class='result_tabla'>\n".(isset($description)?"<caption class='left' style='background:#fff;color:#000;padding:10px;margin-bottom:10px;border:solid 1px #000'><h4>Description:</h4><p>".$description."</p></caption>":"")."<tr class='header'>";
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