<?php
	include("funciones.php");
	include("../config/other_config.php");
	$caracter=caracter();
	$escape=escape();
	
	if(isset($_POST['tabla'])){
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
	}else{
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
				$sql.=" and ".implode((($_POST['c_option']==0)?" and ":" or "), $condiciones);
			$sql.=$orden.$limites;
		}else{
			$sql.=" from(select *, ROW_NUMBER() over(".$orden.") as row_1ab2 from ".implode(", ", $tablas)." where ".implode(" and ", $j_condiciones);
			if(count($condiciones)>0){
				$sql.=" and ".implode((($_POST['c_option']==0)?" and ":" or "), $condiciones);
			}
			$sql.=") a where ".$limites;
		}
	}
	$sql=str_replace("'", $escape, $sql);
	if($_REQUEST['query_name']!=""){
		$query="select name from saved_queries where name='".$_POST['query_name']."'";
		if(obtener_query($query)!=false){
			echo "The name is already used. Write another name!";
		}else{
			$query="insert into saved_queries (name, description, query) values('".$_REQUEST['query_name']."','".str_replace("'", $escape,$_REQUEST['query_desc'])."' ,'".$sql."')";
			//echo $query;
			if(ejecutar_query($query)){
				echo "true";
			}
		}
	}else{
		echo "Write a valid name!";
	}
	//echo "<br/><span style='color='#000'>".$sql."</span>";
?>