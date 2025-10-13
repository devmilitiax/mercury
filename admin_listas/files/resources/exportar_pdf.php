<?php
	include("funciones.php");
	include("../config/other_config.php");
	require("fpdf.php");
	class PDF extends FPDF{
		function Footer(){
			$this->SetY(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		function FancyTable($header, $data){
			$this->SetFillColor(0,120,41);
			$this->SetTextColor(255);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			$fontSize=10;
			$decrementStep=0.1;
			$lineWidth = 192;
			$w=array();
			$lineWidthRow=5000;
			while($lineWidthRow>$lineWidth){
				$lineWidthRow=0;
				for($i=0;$i<count($header);$i++){
					$w[$i]=$this->GetStringWidth($header[$i])+2;
					$lineWidthRow+=$w[$i];
				}
				for($i=0;$i<count($data);$i++){
					$lineWidthRow=0;
					for($j=0;$j<count($header);$j++){
						$calculo=$this->GetStringWidth($data[$i][$j])+2;
						if($w[$j]<$calculo){
							$w[$j]=$calculo;
						}
						$lineWidthRow+=$w[$j];
					}
					if($lineWidthRow>$lineWidth){
						$i=count($data)+1;
						//echo "<br>font-size: ".$fontSize." line-".$lineWidthRow;
						$this->SetFontSize($fontSize -= $decrementStep);
					}
				}
			}
			for($i=0;$i<count($header);$i++)
				$this->Cell($w[$i],6,$header[$i],1,0,'C',true);
			$this->Ln();
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('');
			$fill = false;
			foreach($data as $row)
			{
				for($i=0;$i<count($row);$i++)
					$this->Cell($w[$i],5,$row[$i],'LR',0,'L',$fill);
				$this->Ln();
				$fill = !$fill;
			}
			$this->Cell(array_sum($w),0,'','T');
		}
	}
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	$caracter=caracter();
	$escape=escape();
	if(isset($_POST['s_queries'])){
		$result=obtener_query("select query from saved_queries where name='".$_POST['s_queries']."'");
		$sql=$result[0][0];
		$campos=obtener_columnas_libre($sql);
		$data=obtener_query($sql);
		$tabla=$_POST['s_queries'];
	}else{
		if(!isset($_POST['tablas'])){
			$condicion="";
			$limites="";
			$ordenamiento="";
			if(isset($_POST['tipo'])){
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
							$ordenamiento="order by ".$caracter.$campos[0].$caracter;
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
								$limites=" a.row_1ab2>=".$first." and a.row_1ab2<=".($last+$first);
							break;
						}
					}
				}else{
					$sql=$_POST['inst_sql'];
					$campos=obtener_columnas_libre($sql);
					$tabla="consulta";
				}
			}else{
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
			}

			for($i=0;$i<count($campos);$i++){
				$campos[$i]=$caracter.$campos[$i].$caracter;
			}
			$string_campos=implode(",", $campos);
			if(isset($sql)){
				$data = obtener_query($sql);
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
				$data = obtener_query($query);
			}
			for($i=0;$i<count($campos);$i++){
				$campos[$i]=str_replace($caracter,"",$campos[$i]);
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
			$data=obtener_query($sql);
		}
	}
	
	for($i=0;$i<count($data);$i++){
		for($j=0;$j<count($data[0]);$j++){
			$data[$i][$j]=utf8_decode($data[$i][$j]);
		}
	}
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	if($data!=false){
		$pdf->FancyTable($campos,$data);
	}
	$pdf->Output(str_replace($caracter,"",$tabla).".pdf", 'I');
?>