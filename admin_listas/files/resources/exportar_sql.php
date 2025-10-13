<?php
	include("funciones.php");
	include("../config/other_config.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	$caracter=caracter();
	$escape=escape();
	$tabla=$_POST['tabla'];
	$tabla=$caracter.$tabla.$caracter;
	$condicion="";
	$ordenamiento="";
	
	if(isset($_POST['select_campos'])){
		$columnas=$_POST['select_campos'];
	}else{
		$columnas=obtener_columnas($tabla);
	}
	if((isset($_POST['select_campo_orden']))&&(isset($_POST['asc-desc']))){
			if(($_POST['select_campo_orden']!="")&&($_POST['asc-desc']!="")){
				$ordenamiento="order by ".$caracter.$_POST['select_campo_orden'].$caracter." ".$_POST['asc-desc'];
			}else{
				if($db=="sqlserver"){
					$ordenamiento="order by ".$caracter.$columnas[0].$caracter;
				}
			}
		}else{
			if($db=="sqlserver"){
				$ordenamiento="order by ".$caracter.$columnas[0].$caracter;
			}
		}
	for($i=0;$i<count($columnas);$i++){
		$columnas[$i]=$caracter.$columnas[$i].$caracter;
	}
	$limites='';
	if((isset($_POST['search_value']))&&(isset($_POST['select_campo_unico'])))
		if(($_REQUEST['search_value']!="")&&($_POST['select_campo_unico']!="0")){
			$search=str_replace("'", $escape, $_POST['search_value']);
			$field=$caracter.$_POST['select_campo_unico'].$caracter;
			$condicion=$field." like '%".$search."%'";
		}
	if(isset($_POST['n_pagina'])){
		$n_pagina=$_POST['n_pagina'];
		$first=($n_pagina-1)*$n_result;
		if($first<0)
				$first=0;
		switch($db){
			case "mysql":
					$limites=" limit ".$first.",".$n_result;
			break;
			case "postgres":
				$limites=" limit ".$n_result." offset ".$first;
			break;
			case "sqlserver":
				$limites=" a.row_1ab2>".$first." and a.row_1ab2<=".($n_result+$first);
			break;
		}
	}
	
	header('Content-Type: text/sql; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.str_replace($caracter,"",$tabla).'.sql"');
	header("Content-Type: application/force-download");
	if($db!="sqlserver"){
		$query="SELECT ".implode(',',$columnas)." FROM ".$tabla;
		if($condicion!="")
			$query.=" WHERE ".$condicion;
		if($ordenamiento!=""){
			$query.=" ".$ordenamiento;
		}
		$query.=" ".$limites;
	}else{
		if($limites!=""){
			$query="select ".implode(',',$columnas)." from(select *, ROW_NUMBER() over(".$ordenamiento.") as row_1ab2 from ".$tabla;
			if($condicion!="")
				$query.=" WHERE ".$condicion;
			$query.=") a where".$limites;
		}else{
			$query="select ".implode(',',$columnas)." from ".$tabla;
		}
	}
	$result=obtener_query($query);
	if($result!=false){
		$sql_query="insert into ".$tabla." (".implode(',',$columnas).") values(";
		$fp = fopen('php://output', 'w') or die('<div class="error">Error while opening the file<div>');
		$respaldo=$sql_query;
		for($i=0;$i<count($result);$i++){
			$sql_query=$respaldo;
			for($j=0;$j<count($columnas);$j++){
				if($j!=count($columnas)-1){
					$sql_query.="'".str_replace("'", $escape, $result[$i][$j])."',";
				}else{
					$sql_query.="'".str_replace("'", $escape, $result[$i][$j])."');";
				}
			}
			fwrite($fp, $sql_query.PHP_EOL);
		}
		fclose($fp);
	}
?>