<?php
	include("funciones.php");
	include("../config/other_config.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
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
	$string.="/*BACK-UP DATABASE ".$dbname." in PostgreSQL                   */\n";
	$string.="/*DATE: ".date("d-m-Y H:i:s")."                                 */\n";
	$string.="/*==============================================================*/\n\n";
	if(($option=="all")||($option=="structure")){
		$string.="\n/*===========================SEQUENCES================================*/\n";
		$s_names=array();
		$s_names=obtener_query("SELECT c.relname FROM pg_class c WHERE c.relkind = 'S'");
		if($s_names!=false){
			$conexion=conexion();
			for($i=0;$i<count($s_names);$i++){
				$s_names[$i][0]='"'.$s_names[$i][0].'"';
				$resultado=obtener_query_fetch("SELECT * FROM ".$s_names[$i][0], $conexion);
				$sequence = $resultado->fetch(PDO::FETCH_ASSOC);
				$string.="DROP SEQUENCE IF EXISTS ".$s_names[$i][0]." CASCADE;\n";
				$string.="CREATE SEQUENCE ".$s_names[$i][0]." INCREMENT BY ".$sequence['increment_by']." MINVALUE ".$sequence['min_value']." MAXVALUE ".$sequence['max_value']." START WITH ".$sequence['last_value']." CACHE ".$sequence['cache_value'].";\n\n";
			}
			cerrar_conexion($conexion);
		}
		$string.="\n/*========================================TABLES==========================================*/\n\n";
		for($i=0;$i<count($tablas);$i++){
			$string.="DROP TABLE IF EXISTS ".$tablas_[$i]." CASCADE;\n";
			$string.="/*=================================================*/\n";
			$string.="/*TABLE: ".$tablas_[$i]."                           */";
			$string.="\n/*=================================================*/\n";
			$string.="CREATE TABLE ".$tablas_[$i]." (\n";
			$conexion=conexion();
			$fields=obtener_query_fetch("select datetime_precision, udt_name, data_type, column_name, column_default, numeric_precision, numeric_scale, is_nullable, column_default, character_maximum_length from information_schema.columns where table_name ='".$tablas[$i]."'  order by ordinal_position asc", $conexion);
			while ($row = $fields->fetch(PDO::FETCH_ASSOC)){
				$row['column_name']='"'.$row['column_name'].'"';

				$string.='	'.$row['column_name'].'	';
				if($row['data_type']!="ARRAY"){
					$type=$row['data_type'];
				}else{
					if(mb_substr($row['udt_name'],0,1)=="_")
						$row['udt_name']=substr($row['udt_name'],1);
					$type=$row['udt_name'];
				}
				switch(mb_strtolower($row['udt_name'])){
					case "bit":
					case "bpchar":
					case "varchar":
						if($row['character_maximum_length']!=NULL)
							$type.="(".$row['character_maximum_length'].")";
					break;
					case "numeric":
						if(($row['numeric_precision']!=NULL)&&($row['numeric_scale']!=NULL))
							$type.="(".$row['numeric_precision'].",".$row['numeric_scale'].")";
					break;
					case "interval":
					case "time":
					case "timetz":
					case "timestamptz";
					case "timestamp";			
						if($row['datetime_precision']!=NULL){
							$replace1='time'."(".$row['datetime_precision'].") with";
							$replace2='timestamp'."(".$row['datetime_precision'].") with";
							$type=str_replace('time with',$replace1,$type);
							$type=str_replace('timestamp with',$replace2,$type);
						}
					break;
				}
				$string.=$type;
				if($row['data_type']=="ARRAY")
					$string.="[]";

				if(strcasecmp($row['is_nullable'],"NO")==0){
					$string.="	not null";
				}
				if($row['column_default']!=NULL/*&&(strncasecmp($row['column_default'],"nextval", 7)!=0)*/){
					$string.="	default ".$row['column_default'];
				}
				$string.=",\n";
			}
			$pk_query=obtener_query_fetch("SELECT kcu.constraint_name, kcu.column_name FROM INFORMATION_SCHEMA.TABLES t LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc ON tc.table_catalog = t.table_catalog AND tc.table_schema = t.table_schema AND tc.table_name = t.table_name AND tc.constraint_type = 'PRIMARY KEY' LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu ON kcu.table_catalog = tc.table_catalog AND kcu.table_schema = tc.table_schema AND kcu.table_name = tc.table_name AND kcu.constraint_name = tc.constraint_name WHERE   t.table_schema NOT IN ('pg_catalog', 'information_schema') and t.table_name='".$tablas[$i]."'", $conexion);
			if($pk_query!=false){
				$flag=0;
				while($pk = $pk_query->fetch(PDO::FETCH_ASSOC)){
					if($pk['column_name']!=""){
						if($flag==0){
							$string.="	CONSTRAINT ".$pk['constraint_name']." PRIMARY KEY (";
							$flag=1;
						}
						$pk['column_name']='"'.$pk['column_name'].'"';
						$string.=$pk['column_name'].",";
					}
				}
				if($flag!=0){
					$string=trim($string, ",");
					$string.="),\n";
				}
			}
			$string=trim($string, ",\n");
			$string.="\n);\n\n";
			//Obtener Indices
			
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
				if($filas!=false){
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
	}
	if(($option=="all")||($option=="structure")){
		$conexion=conexion();
		$string.="\n\n/*===========================INDEX================================*/\n\n";	
		for($i=0;$i<count($tablas);$i++){
			$resultado = obtener_query_fetch("SELECT pg_index.indisprimary, pg_catalog.pg_get_indexdef(pg_index.indexrelid) FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index AS pg_index WHERE c.relname = '".$tablas[$i]."' AND c.oid = pg_index.indrelid AND pg_index.indexrelid = c2.oid AND pg_index.indisprimary<>'t'", $conexion);
			if($resultado!=false){
				while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
					   if (preg_match("/^CREATE UNIQUE INDEX/i", $row['pg_get_indexdef'])){
						  $_keyword = 'UNIQUE';
						  $strSQL = str_replace("CREATE UNIQUE INDEX", "" , $row['pg_get_indexdef']);
						  $strSQL = str_replace("USING btree", "|", $strSQL);
						  $strSQL = str_replace("ON", "|", $strSQL);
						  $strSQL = str_replace("\x20","", $strSQL);
						  list($key, $table, $field) = explode("|", $strSQL);
						  if(in_array($table, $tablas)){
							$table='"'.$table.'"';
							$field='"'.$field.'"';
							$key='"'.$key.'"';
							$string.="ALTER TABLE ONLY ".$table." ADD CONSTRAINT ".$key." ".$_keyword." ".$field.";\n\n";
						  }
						  unset($strSQL);
					   } 
					   else{
						 $string.=$row['pg_get_indexdef'].";\n\n";
					   }
				}
			}
		}
	
		$string.="\n\n/*===========================RELATIONSHIPS================================*/\n";
		
		$resultado=obtener_query_fetch("SELECT cl.relname AS table, ct.conname, pg_get_constraintdef(ct.oid) FROM pg_catalog.pg_attribute a JOIN pg_catalog.pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r') JOIN pg_catalog.pg_namespace n ON (n.oid = cl.relnamespace) JOIN pg_catalog.pg_constraint ct ON (a.attrelid = ct.conrelid AND ct.confrelid != 0 AND ct.conkey[1] = a.attnum) JOIN pg_catalog.pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r') JOIN pg_catalog.pg_namespace nf ON (nf.oid = clf.relnamespace) JOIN pg_catalog.pg_attribute af ON (af.attrelid = ct.confrelid AND af.attnum = ct.confkey[1]) order by cl.relname", $conexion);
		if($resultado!=false){
			while($fk = $resultado->fetch(PDO::FETCH_ASSOC)){
				if(in_array(($fk['table']), $tablas)){
					$_table   = $fk['table'];
					$_table='"'.$_table.'"';
					$_conname = $fk['conname'];
					$_conname='"'.$_conname.'"';
					$_constraint=$fk['pg_get_constraintdef'];
					$string.="\nALTER TABLE ".$_table." ADD CONSTRAINT ".$_conname." ".$_constraint.";\n\n";
				}
			}
		}
	}
	
	fwrite($fp, $string);
	fclose($fp);	
	
?>