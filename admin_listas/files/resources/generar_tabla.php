<?php
	include("funciones.php");
	include("../config/other_config.php");
	$caracter=caracter();
	$escape=escape();
	set_time_limit(0);
	$tabla=$_REQUEST['tabla'];
	$tabla=$caracter.$tabla.$caracter;
	$order_field=$_REQUEST['order_field'];
	$sence=$_REQUEST['sence'];
	$condicion="";
	if((isset($_REQUEST['search']))&&(isset($_REQUEST['search_field'])))
	if(($_REQUEST['search']!="")&&($_REQUEST['search_field']!="0")){
		$search=str_replace("'", $escape, $_REQUEST['search']);
		$field=$caracter.$_REQUEST['search_field'].$caracter;
		$condicion=$field." like '%".$search."%'";
	}
	$query="select count(*) as total from ".$tabla;
	if($condicion!="")
		$query.=" where ".$condicion;
	$n_filas=obtener_n_filas($query);
	$n_paginas=ceil($n_filas/$n_result);
	$init=0;
	if(isset($_REQUEST['actual'])){
		$init=$_REQUEST['actual'];
		switch($init){
			case "First":
				$init=0;
				$actual=1;
			break;
			case "Last":
				$init=(($n_paginas-1)*$n_result);
				$actual=$n_paginas;
			break;
			case ">>":
				$init=($_REQUEST['anterior'])*$n_result;
				$actual=$_REQUEST['anterior']+1;
			break;
			case "<<":
				$init=($_REQUEST['anterior']-2)*$n_result;
				$actual=$_REQUEST['anterior']-1;
			break;
			default:
				$actual=$init;
				$init=($init-1)*$n_result;
			break;
		}
	}else	$actual=1;
	$campos=obtener_columnas($tabla);
	for($l=0;$l<count($campos);$l++){
		$campos[$l]=$caracter.$campos[$l].$caracter;
	}
	$limites="";
	switch($db){
		case "mysql":
			$limites=" limit ".$init.",".$n_result;
		break;
		case "postgres":
			$limites=" limit ".$n_result." offset ".$init;
		break;
		case "sqlserver":
			$limites=" a.row_1ab2>".$init." and a.row_1ab2<=".($init+$n_result)." ";
		break;
	}
	if($db!="sqlserver"){
		$query="select * from ".$tabla;
		if($condicion!="")
			$query.=" where ".$condicion;
		if(($order_field!="")&&($sence!=""))
			$query.=" order by ".$caracter.$order_field.$caracter." ".$sence;
		$query.=$limites;
	}else{
		$ord="";
		if(($order_field!="")&&($sence!="")){
			$ord=$caracter.$order_field.$caracter." ".$sence;
		}else{
			$ord=$campos[0];
		}
		$query="select ".implode(',',$campos)." from(select *, ROW_NUMBER() over(order by ".$ord.") as row_1ab2 from ".$tabla;
		if($condicion!="")
			$query.=" where ".$condicion;
		$query.=") a where ".$limites;
	}
	//echo $query;
	$resultado=obtener_query($query);
	echo "<table class='result_tabla'>\n<tr class='header'><th class='control'><input type='checkbox'></th>";
	$orden_number=-1;
	for($i=0;$i<count($campos);$i++){
		$campos[$i]=str_replace($caracter, "", $campos[$i]);
		if($campos[$i]==$order_field){
			if($sence=="asc"){
				echo "<th class='ord' name='".$campos[$i]."'>".$campos[$i]." <i class='icon-chevron-up icon-white'></i></th>";
			}else{
				echo "<th class='ord' name='".$campos[$i]."'>".$campos[$i]." <i class='icon-chevron-down icon-white'></i></th>";
			}
			$orden_number=$i;
		}else{
			echo "<th class='ord' name='".$campos[$i]."'>".$campos[$i]."</th>";
		}
	}
	echo "<th class='control2'></th></tr>\n";
	$background="";
	if($resultado!=false){
		for($i=0;$i<count($resultado);$i++){
			if(($i%2)==0)
				$bgorder="#AAA";
			else	
				$bgorder="#888";
			echo "<tr class='".($i+1)."'>";
			echo "<td class='control'><input type='checkbox' class='".($i+1)."'></td>";
			for($j=0;$j<count($campos);$j++){
				if(isset($resultado[$i][$j])){
					if($j!=$orden_number)
						echo "<td>".$resultado[$i][$j]."</td>";
					else
						echo "<td style='background:".$bgorder.";color:#fff'>".$resultado[$i][$j]."</td>";
				}
			}
			echo "<td class='control2'><span title='see' class='".($i+1)."'><i class='icon-eye-open'></i></span> <span title='edit' class='".($i+1)."'><i class='icon-pencil'></i></span></td>";
			echo "</tr>\n";
		}
	}
	echo "</table>";
	//echo $query;
	
	//Generating the pager for the table
	echo "<div class='pager' id='paginador'>";
	echo "<span class='total'>Total: ".$n_filas." results.</span> ";
	if($n_filas==0) $actual=0;
	echo "Page ".$actual." of ".$n_paginas."  ";
	if($n_paginas>5){
		if($actual<=3){
			if($actual!=1)
				echo '<input type="button" value="<<" class="pag">  ';
			for($i=1;$i<=5;$i++){
				if($i!=$actual)
					echo "<input type='button' value='".$i."' class='pag'> ";
				else
					echo "<input type='button' value='".$i."' class='actual'>  ";
			}
			echo "<input type='button' value='>>' class='pag'>  ";
			echo "<input type='button' value='Last' class='pag'>";
		}elseif($actual<=$n_paginas-2){
			echo "<input type='button' value='First' class='pag'>  ";
			echo '<input type="button" value="<<" class="pag">  ';
			for($i=$actual-2;$i<=$actual+2;$i++){
				if($i!=$actual)
					echo "<input type='button' value='".$i."' class='pag'>  ";
				else
					echo "<input type='button' value='".$i."' class='actual'>  ";
			}
			echo "<input type='button' value='>>' class='pag'>  ";
			echo "<input type='button' value='Last' class='pag'>";
		}else{
			echo "<input type='button' value='First' class='pag'>  ";
			echo '<input type="button" value="<<" class="pag"> ';
			for($i=$n_paginas-4;$i<=$n_paginas;$i++){
				if($i!=$actual)
					echo "<input type='button' value='".$i."' class='pag'>  ";
				else
					echo "<input type='button' value='".$i."' class='actual'>  ";
			}
			if($actual!=$n_paginas){
				echo "<input type='button' value='>>' class='pag'>  ";
			}
		}
	}else{
		for($i=1;$i<=$n_paginas;$i++){
			if($i!=$actual) 
				echo "<input type='button' value='".$i."' class='pag'>  ";
			else
				echo "<input type='button' value='".$i."' class='actual'>  ";
		}
	}
	echo "</div>";
?>