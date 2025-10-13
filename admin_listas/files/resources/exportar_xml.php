<?php
	include("funciones.php");
	include("../config/other_config.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	$caracter=caracter();
	$escape=escape();
	if(isset($_POST['s_queries'])){ //Saved Queries
		$result=obtener_query("select query from saved_queries where name='".$_POST['s_queries']."'");
		$query=$result[0][0];
		$campos=obtener_columnas_libre($query);
		$tabla=$_POST['s_queries'];
		$result = obtener_query($query);
	}else{
		if(!isset($_POST['tablas'])){
			$condicion="";
			$limites="";
			$ordenamiento="";
			if(isset($_POST['tipo'])){ //Advanced Query Table
				if($_POST['tipo']=="easy"){
					$tabla=$_POST['tabla'];
					$tabla=$caracter.$tabla.$caracter;
					$campos=$_POST['select_campos'];
					$campo_c=$_POST['campos'];
					$campo_c=$caracter.$campo_c.$caracter;
					$and=false;
					if(($_POST['operador']!="none")&&($_POST['condicion']!="")){
						$_POST['condicion']=str_replace("'", $escape, $_POST['condicion']);
						if(($_POST['operador']=="like")||($_POST['operador']=="not like"))
							$_POST['condicion']="%".$_POST['condicion']."%";
						$condicion=$campo_c." ".$_POST['operador']." '".$_POST['condicion']."'";
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
					$order_field=$_POST['select_campo_unico'];
					$order_field=$caracter.$order_field.$caracter;
					$order_sence=$_POST['asc-desc'];
					$first=$_POST['first'];
					$last=$_POST['last'];
					if($order_field!=$caracter.'0'.$caracter){
						$ordenamiento=" order by ".$order_field." ".$order_sence;
					}else{
						if($db=="sqlserver"){
							$order_field=$campos[0];
							$order_field=$caracter.$order_field.$caracter;
							$ordenamiento="order by ".$order_field;
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
								$limites=" a.row_1ab2>".$first." and a.row_1ab2<=".($last+$first);
							break;
						}
					}
				}else{
					$sql=$_POST['inst_sql'];
					$campos=obtener_columnas_libre($sql);
					$tabla="consulta";
				}
			}else{ //Easy Query Table
				$tabla=$_POST['tabla'];
				$tabla=$caracter.$tabla.$caracter;
				if(isset($_POST['select_campos'])){
					$campos=$_POST['select_campos'];
				}else{
					$campos=obtener_columnas($tabla);
				}
				if((isset($_POST['select_campo_orden']))&&(isset($_POST['asc-desc']))){
					if(($_POST['select_campo_orden']!="")&&($_POST['asc-desc']!="")){
						$ordenamiento="order by ".$caracter.$_POST['select_campo_orden'].$caracter." ".$_POST['asc-desc'];
					}else{
						if($db=="sqlserver"){
							$ordenamiento="order by ".$caracter.$campos[0].$caracter;
						}
					}
				}else{
					if($db=="sqlserver"){
						$ordenamiento="order by ".$caracter.$campos[0].$caracter;
					}
				}
				if((isset($_POST['search_value']))&&(isset($_POST['select_campo_unico'])))
				if(($_POST['search_value']!="")&&($_POST['select_campo_unico']!="0")){
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
			}
		}else{ //Join Tables
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
			$tabla="join_table";
			$campos=$array_campos;
			$result=obtener_query($sql);
		}
	}
	
	$copy_campos=$campos;

	if(!isset($_POST['s_queries'])){
		for($i=0;$i<count($campos);$i++){
			$campos[$i]=$caracter.$campos[$i].$caracter;
		}
		$string_campos=implode(",", $campos);
		if(isset($sql)){
			$result = obtener_query($sql);
		}else{
			if($db!="sqlserver"){
				$query="SELECT ".$string_campos." FROM ".$tabla;
				if($condicion!="")
					$query.=" WHERE ".$condicion;
				$query.=$ordenamiento.$limites;
			}else{
				if($limites!=""){
					$query="select ".$string_campos." from(select *, ROW_NUMBER() over(".$ordenamiento.") as row_1ab2 from ".$tabla;
					if($condicion!=""){
						$query.=" where ".$condicion;
					}
					$query.=") a where".$limites;
				}else{
					$query="select ".$string_campos." from ".$tabla;
					if($condicion!=""){
						$query.=" where ".$condicion;
					}
				}
			}
			//echo $query;
			$result = obtener_query($query);
		}
	}
	
	header("Content-type: text/xml");
	header('Content-Disposition:attachment;filename="'.str_replace($caracter,"",$tabla).'.xml"');
	header("Content-Type:application/force-download");
	
	$xml = new DOMDocument('1.0', 'UTF-8');
	$root = $xml->appendChild($xml->createElement(str_replace(" ","-",str_replace($caracter,"",$tabla))));
	
	if($result!=false){
		for($i=0;$i<count($result);$i++){
			$row = $root->appendChild($xml->createElement('row'));
			for($j=0;$j<count($copy_campos);$j++){
				$field=$row->appendChild($xml->createElement(str_replace(" ","-",$copy_campos[$j])));
				$field->appendChild($xml->createTextNode($result[$i][$j]));
			}
		}
	}
	$xml->formatOutput = true;

	echo $xml->saveXML();
?>