<?php
	include("funciones.php");
	include("../config/other_config.php");
	ini_set('memory_limit', $m_limit.'M');
	set_time_limit(0);
	$escape=escape();
	$option=$_POST['option'];
	$tablas=$_POST['tablas'];
	$tablas_=array();
	for($i=0;$i<count($tablas);$i++){
		$tablas_[$i]='"'.$tablas[$i].'"';
	}
	set_time_limit(0);
	header('Content-Type: text/sql; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$dbname.' '.date("d-m-Y").'.sql"');
	$fp = fopen('php://output', 'w') or die('<div class="error">Error while opening the file<div>');
	
	$string="/*==============================================================*/\n";
	$string.="/*BACK-UP DATABASE ".$dbname." in SQL Server                  */\n";
	$string.="/*DATE: ".date("d-m-Y H:i:s")."                                 */\n";
	$string.="/*==============================================================*/\n\n";
	if(($option=="all")||($option=="structure")){
		for($i=0;$i<count($tablas);$i++){
			$string.="IF OBJECT_ID('".$tablas[$i]."', 'U') IS NOT NULL\n	DROP TABLE ".$tablas_[$i].";\n";
			$string.="/*=================================================*/\n";
			$string.="/*TABLE: ".$tablas_[$i]."                           */";
			$string.="\n/*=================================================*/\n";
			$string.="CREATE TABLE ".$tablas_[$i]." (\n";
			$conexion=conexion();
			$fields=obtener_query_fetch("select * from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$tablas[$i]."'", $conexion);
			while ($row = $fields->fetch(PDO::FETCH_ASSOC)){
				$row['COLUMN_NAME']='"'.$row['COLUMN_NAME'].'"';
				$string.="	".$row['COLUMN_NAME']."	";
				$string.=$row['DATA_TYPE'];
				switch(mb_strtolower($row['DATA_TYPE'])){
					case "nvarchar":
					case "varchar":
					case "nchar":
					case "varbinary":
						if($row['CHARACTER_MAXIMUM_LENGTH']!=NULL){
							if($row['CHARACTER_MAXIMUM_LENGTH']!=-1)
								$string.="(".$row['CHARACTER_MAXIMUM_LENGTH'].")	";
							else
								$string.="(max)	";
						}
					break;
					case "numeric":
					case "decimal":
						$string.="(".$row['NUMERIC_PRECISION'].",".$row['NUMERIC_SCALE'].")	";
					break;
					case "datetime":
					case "datetime2":
					case  "time":
						if($row['DATETIME_PRECISION']!=NULL)
							$string.="(".$row['DATETIME_PRECISION'].")	";
					break;
				}
				if(strcasecmp($row['IS_NULLABLE'],"NO")==0){
					$string.="	not null ";
				}
				if($row['COLUMN_DEFAULT']!=NULL){
					$string.="default ".$row['COLUMN_DEFAULT'];
				}
				$string.=",\n";
			}
			$pk_query=obtener_query_fetch("select I.name as CONSTRAINT_NAME, I.type_desc as TYPE_DESC, AC.name as COLUMN_NAME from sys.tables as T inner join sys.indexes as I on T.[object_id] = I.[object_id] inner join sys.index_columns as IC on IC.[object_id] = I.[object_id] and IC.[index_id] = I.[index_id] inner join sys.all_columns as AC on IC.[object_id] = AC.[object_id] and IC.[column_id] = AC.[column_id] where T.name='".$tablas[$i]."' and I.is_primary_key='1'", $conexion);
			if($pk_query!=false){
				$flag=0;
				while($pk = $pk_query->fetch(PDO::FETCH_ASSOC)){
					if($pk['COLUMN_NAME']!=""){
						if($flag==0){
							$pk['CONSTRAINT_NAME']='"'.$pk['CONSTRAINT_NAME'].'"';
							$string.="	CONSTRAINT ".$pk['CONSTRAINT_NAME']." PRIMARY KEY ".$pk['TYPE_DESC']." (";
							$flag=1;
						}
						$pk['COLUMN_NAME']='"'.$pk['COLUMN_NAME'].'"';
						$string.=$pk['COLUMN_NAME'].",";
					}
				}
				if($flag!=0){
					$string=trim($string, ",");
					$string.="),\n";
				}
			}
			$string=trim($string, ",\n");
			$string.="\n);\n\n";
			$index_query=obtener_query_fetch("select T.name as TABLE_NAME, I.name as CONSTRAINT_NAME, I.type_desc as TYPE_DESC, AC.name as COLUMN_NAME from sys.tables as T inner join sys.indexes as I on T.[object_id] = I.[object_id] inner join sys.index_columns as IC on IC.[object_id] = I.[object_id] and IC.[index_id] = I.[index_id] inner join sys.all_columns as AC on IC.[object_id] = AC.[object_id] and IC.[column_id] = AC.[column_id] where T.name='".$tablas[$i]."' and I.is_primary_key='0'", $conexion);
			if($index_query!=false){
				$flag=0;
				$const="0";
				while($index = $index_query->fetch(PDO::FETCH_ASSOC)){
					if($const!="0"){
						if($const!=$index['CONSTRAINT_NAME']){
							$flag=0;
							$string=trim($string, ",");
							$string.=");\n\n";
						}
					}
					if($index['COLUMN_NAME']!=""){
						if($flag==0){
							$index['CONSTRAINT_NAME']='"'.$index['CONSTRAINT_NAME'].'"';
							$index['TABLE_NAME']='"'.$index['TABLE_NAME'].'"';
							$string.="CREATE INDEX ".$index['CONSTRAINT_NAME']." ON ".$index['TABLE_NAME']." (";
							$flag=1;
						}
						$index['COLUMN_NAME']='"'.$index['COLUMN_NAME'].'"';
						$string.=$index['COLUMN_NAME'].",";
					}
					$const=str_replace('"',"",$index['CONSTRAINT_NAME']);
				}
				if($flag!=0){
					$string=trim($string, ",");
					$string.=");\n\n";
				}
			}
		}
		cerrar_conexion($conexion);
	}
	if(($option=="all")||($option=="data")){
		$string.="\n\n/*===========================TABLES DATA================================*/\n";
		for($i=0;$i<count($tablas);$i++){
			$string.="\n\n/*TABLE: ".$tablas_[$i]."===========================*/\n";
			$filas=obtener_query("SELECT * FROM ".$tablas_[$i]);
			if($filas!=false){
				for($s=0;$s<count($filas);$s++)
					for($r=0;$r<count($filas[0]);$r++)
						$filas[$s][$r]=str_replace("'", $escape, $filas[$s][$r]);
				$campos=obtener_columnas($tablas_[$i]);
				for($m=0;$m<count($campos);$m++){
					$campos[$m]='"'.$campos[$m].'"';
				}
				for($j=0;$j<count($filas);$j++){
					$string.="insert into ". $tablas_[$i]." (". implode(',',$campos).") values('". implode("','",$filas[$j])."');\n";
				}
			}
		}
	}
	if(($option=="all")||($option=="structure")){
		$conexion=conexion();
		$string.="\n\n/*===========================RELATIONSHIPS================================*/\n";
		for($i=0;$i<count($tablas);$i++){
			$resultado=obtener_query_fetch("SELECT object_name(parent_object_id) as 'tabla', object_name(referenced_object_id) as 'columna', name FROM sys.foreign_keys where object_name(parent_object_id)='".$tablas[$i]."'", $conexion);
			if($resultado!=false){
				while($fk = $resultado->fetch(PDO::FETCH_ASSOC)){
					$_table   = $fk['tabla'];
					$_table='"'.$_table.'"';
					$_conname = $fk['columna'];
					$_conname='"'.$_conname.'"';
					$_constraint=$fk['name'];
					$string.="\nALTER TABLE ONLY ".$_table." ADD CONSTRAINT ".$_conname." ".$_constraint.";\n\n";
				}
			}
		}
	}
	fwrite($fp, $string);
	fclose($fp);	
	
?>