<?php
	include("funciones.php");
	include("../config/other_config.php");
	require_once("PHPExcel/IOFactory.php");
	$caracter=caracter();
	$escape=escape();
	set_time_limit(0);
	$archivo=$_REQUEST['archivo'];
	$tabla=$caracter.$_REQUEST['tabla'].$caracter;
	$upd_fields=$_REQUEST['upd_fields'];
	$n_campos=$_REQUEST['n_campos'];
	$n_filas=$_REQUEST['n_filas'];
	$errors=0;
	$cambios=0;
	$ignorer=0;
	$primera_linea=1;
	if(isset($_REQUEST['saltar'])){
		$primera_linea=2;
	}
	$objPHPExcel = PHPExcel_IOFactory::load($archivo);
	$flag=1;
	if(isset($_REQUEST['eliminar_data'])){
		ejecutar_query("delete from ".$tabla);
	}
	$insert_sql="insert into ".$tabla." (";
	$array_campos=array();
	$j=0;
	for($i=0;$i<$n_campos;$i++){
		if($_REQUEST['campos'.$i]!="0"){
			$array_campos[$j]=$_REQUEST['campos'.$i];
			$array_campos[$j]=$caracter.$array_campos[$j].$caracter;
			$j++;
		}
	}
	$insert_sql.=implode(',',$array_campos).") values(";
	$respaldo=$insert_sql;
	$i=1;
	$inserciones=0;
	$conexion=conexion();
	if(!isset($_REQUEST['set_pk_cb'])){
		$keys[0]=$_REQUEST['pk_field'];
	}else{
		$keys=getPrimaryKey($tabla);
	}
	$keysValue=array();
	foreach($objPHPExcel->getWorksheetIterator() as $worksheet){
		if($flag==1){
			$array_values=Array(count($array_campos));
			$update_fields=Array();
			for ($row = $primera_linea; $row <= $n_filas; $row++) {
				$exists=false;
				$insert_sql=$respaldo;
				$k=0;
				$o=0;
				for ($col = 0; $col <$n_campos; $col++) {
					if($_REQUEST['campos'.$col]!="0"){
						$cell = $worksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
						$val = $cell;
						if (date('d-m-Y', strtotime($val)) == $val){
							$val=date("Y-m-d", strtotime($val));
						}
						$array_values[$k]=str_replace("'", $escape, $val);
						if($keys!=false){
							for($l=0;$l<count($keys);$l++){
								if($_REQUEST['campos'.$col]==$keys[$l]){
									$iskey=true;
									$keysValue[$_REQUEST['campos'.$col]]=$array_values[$k];
									$l=count($keys);
								}
							}
							for($s=0;$s<count($upd_fields);$s++){
								if($_REQUEST['campos'.$col]==$upd_fields[$s]){
									$update_fields[$o]=$caracter.$upd_fields[$s].$caracter."='".$array_values[$k]."'";
									$o++;
									$s=count($upd_fields);
								}							
							}
						}
						$k++;
					}
				}
				if(count($keysValue)>0){
					$select_query="select * from ".$tabla." where ";
					$keys_query="";
					$n=0;
					for($m=0;$m<count($keys);$m++){
						if(isset($keysValue[$keys[$m]])){
							$keys_query.=(($n>=1)?" and ":"").$caracter.$keys[$m].$caracter."='".$keysValue[$keys[$m]]."'";
							$n++;
						}
					}
					//echo $select_query.$keys_query."<br>";
					if(obtener_query($select_query.$keys_query)!=false) $exists=true;
					$conexion=conexion();
				}
				if(!$exists){
					$insert_sql.="'".implode("','",$array_values)."')";
					if(!ejecutar_query_fast($insert_sql, $conexion)){
						echo "<div class='error'>".$insert_sql." Problems with the insertion ".mysql_error()."</div><br>";
						$errors++;
						if(($errors==10)&&($inserciones==0))
							die();
					}else{
						$inserciones++;
					}
				}else{
					if($_REQUEST['if_exists']!="Ignore"){
						$update_query="update ".$tabla." set ".implode(", ",$update_fields)." where ".$keys_query;
						if(!ejecutar_query_fast($update_query, $conexion)){
							echo "<div class='error'>".$update_query." Problems with the update ".mysql_error()."</div><br>";
							$errors++;
							if(($errors==10)&&($inserciones==0))	die();
						}else{
							$cambios++;
						}
						echo $update_query."<br>";
					}else	$ignorer++;
				}
			}
		}
	}
	cerrar_conexion($conexion);
	echo $inserciones." rows inserted successfully. ".(($_REQUEST['if_exists']!="Ignore")?$cambios." rows updated. ":$ignorer." rows ignored.");
?>