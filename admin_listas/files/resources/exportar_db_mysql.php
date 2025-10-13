<?php
	include("funciones.php");
	include("../config/other_config.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	$escape=escape();
	$option=$_POST['option'];
	$tablas=$_POST['tablas'];
	for($i=0;$i<count($tablas);$i++){
		$tablas[$i]="`".$tablas[$i]."`";
	}
	set_time_limit(0);
	header('Content-Type: text/sql; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$dbname.' '.date("d-m-Y").'.sql"');
	$fp = fopen('php://output', 'w') or die('<div class="error">Error while opening the file<div>');
	
	$string="/*==============================================================*/\n";
	$string.="/*BACK-UP DATABASE ".$dbname." in MySQL                         */\n";
	$string.="/*DATE: ".date("d-m-Y H:i:s")."                                 */\n";
	$string.="/*==============================================================*/\n\n";
	if(($option=="all")||($option=="structure")){
		for($i=0;$i<count($tablas);$i++){
			$string.="drop table if exists ".$tablas[$i].";\n";
		}
		$create_table=array();
		for($i=0;$i<count($tablas);$i++){
			$string.="\n\n/*=================================================*/\n";
			$string.="/*TABLE: '".$tablas[$i]."'                           */";
			$string.="\n/*=================================================*/\n";
			$create_table=obtener_create_table($tablas[$i]);
			$string.=$create_table[1].";";
		}
	}
	if(($option=="all")||($option=="data")){
		$string.="\n\n/*======================DATA===========================*/";
		for($i=0;$i<count($tablas);$i++){
			$string.="\n\n/*TABLE: '".$tablas[$i]."'===========================*/\n";
			$filas=obtener_query("SELECT * FROM ".$tablas[$i]);
			if($filas!=false){
				for($s=0;$s<count($filas);$s++)
					for($r=0;$r<count($filas[0]);$r++)
						$filas[$s][$r]=str_replace("'", $escape, $filas[$s][$r]);
				$campos=obtener_columnas($tablas[$i]);
				for($m=0;$m<count($campos);$m++)
					$campos[$m]="`".$campos[$m]."`";
					
				for($j=0;$j<count($filas);$j++){
					$string.="insert into ".$tablas[$i]." (". implode(',',$campos).") values('". implode("','",$filas[$j])."');\n";
				}
			}
		}
	}
	fwrite($fp, $string);
	fclose($fp);
	
?>