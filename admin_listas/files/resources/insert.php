<?php
	include("funciones.php");
	include("../config/other_config.php");
	$caracter=caracter();
	$escape=escape();
	set_time_limit(0);
	$archivo=$_REQUEST['archivo'];
	$tabla=$caracter.$_REQUEST['tabla'].$caracter;
	$separator=$_REQUEST['separator'];
	$upd_fields=$_REQUEST['upd_fields'];
	$primera_linea=1;
	$errors=0;
	$cambios=0;
	$ignorer=0;
	$n_campos=$_REQUEST['n_campos'];
	if(isset($_REQUEST['saltar'])){
		$primera_linea=2;
	}
	$handle = fopen($archivo, "r");
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
	while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {
		$exists=false;
		$array_info=Array(count($array_campos));
		$insert_sql=$respaldo;
		$update_fields=Array();
		if($i>=$primera_linea){
			$k=0;
			$o=0;
			for($j=0;$j<count($data);$j++){
				$iskey=false;
				if($_REQUEST['campos'.$j]!="0"){
					$array_info[$k]=str_replace("'",$escape, ($data[$j]));
					if($keys!=false){
						for($l=0;$l<count($keys);$l++){
							if($_REQUEST['campos'.$j]==$keys[$l]){
								$iskey=true;
								$keysValue[$_REQUEST['campos'.$j]]=$array_info[$k];
								$l=count($keys);
							}
						}
						for($s=0;$s<count($upd_fields);$s++){
							if($_REQUEST['campos'.$j]==$upd_fields[$s]){
								$update_fields[$o]=$caracter.$upd_fields[$s].$caracter."='".$array_info[$k]."'";
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
				$insert_sql.="'".implode("','",$array_info)."')";
				if(!ejecutar_query_fast($insert_sql, $conexion)){
					echo "<div class='error'>".$insert_sql." Problems with the insertion ".mysql_error()."</div><br>";
					$errors++;
					if(($errors==10)&&($inserciones==0))	die();
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
					//echo $update_query."<br>";
				}else	$ignorer++;
			}
		}
		$i++;
	}
	cerrar_conexion($conexion);
	echo $inserciones." rows inserted successfully. ".(($_REQUEST['if_exists']!="Ignore")?$cambios." rows updated. ":$ignorer." rows ignored.");
?>