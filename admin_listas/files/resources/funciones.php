<?php
	@include_once("../config/config.php");
	@include_once("../config/".$file.".php");
	include_once("encriptar.php");
	function caracter(){
		global $db;
		switch($db){
			case "mysql":
				$character="`";
			break;
			case "postgres":
			case "sqlite":
			case "sqlserver":
				$character='"';
			break;
		}
		return $character;
	}
	function escape(){
		global $db;
		switch($db){
			case "mysql":
				$caracter="\'";
			break;
			case "postgres":
			case "sqlserver":
			case "sqlite":
				$caracter="''";
			break;
		}
		return $caracter;
	}
	function conexion(){
		global $host, $dbname, $port, $db, $user, $pass;
		try {
			switch($db){	
				case "mysql":
					$user1=decrypt($user);
					$pass1=decrypt($pass);
					$conexion=new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user1,$pass1);
				break;
				case "sqlserver":
					if((isset($user))&&(isset($pass))){
						$user1=decrypt($user);
						$pass1=decrypt($pass);
					}else{
						$user1="";
						$pass1="";
					}
					$conexion=new PDO('sqlsrv:Server='.$host.';Database='.$dbname,$user1,$pass1);
				break;
				case "postgres":
					$user1=decrypt($user);
					$pass1=decrypt($pass);
					$conexion=new PDO('pgsql:host='.$host.';dbname='.$dbname.';port='.$port,$user1,$pass1);
				break;
				case "sqlite":
					$conexion=new PDO('sqlite:../sqlite/'.$dbname);
				break;
			}
		}catch (PDOException $e) {
			echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Problems with the connexion: aqui".$e->getMessage()."</div>";
			die();
		}
		return $conexion;
	}
	function login($usuario, $contra){
		global $host, $db, $port, $dbname;
		$flag=true;
		try{
			switch($db){
				case "mysql":
					$conexion=new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$usuario,$contra);
				break;
				case "sqlserver":
					$conexion=new PDO('sqlsrv:Server='.$host.';Database='.$dbname,$usuario,$contra);
				break;
				case "postgres":
					$conexion=new PDO('pgsql:host='.$host.';dbname='.$dbname.';port='.$port,$usuario,$contra);
				break;
				case "sqlite":
					$conexion=new PDO('sqlite:'.$dbname);
				break;
			}
		}catch (PDOException $e) {
			echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Problems with the connexion ".$e->getMessage()."</div>";
			$flag=false;
			die();
		}
		cerrar_conexion($conexion);
		return $flag;
	}
	function obtener_create_table($tabla){
		global $db, $dbname;
		$create_table=array();
		$fila=0;
		$conexion=conexion();
		switch($db){
			case "mysql":
				$result = $conexion->prepare("SHOW CREATE TABLE ".$tabla);
				$result->execute();
				$row = $result->fetch(PDO::FETCH_ASSOC);
				$create_table[0]=$row['Table'];
				$create_table[1]=$row['Create Table'];
				$result=null;
			break;
			case "sqlite":
				$result = $conexion->prepare("select sql from sqlite_master where name='".$tabla."'");
				$result->execute();
				$row = $result->fetch(PDO::FETCH_ASSOC);
				$create_table[0]=$tabla;
				$create_table[1]=$row['sql'];
			break;
		}
		cerrar_conexion($conexion);
		return $create_table;
	}
	function obtener_tablas(){
		global $dbname, $db;
		$tablas=0;
		$array_tablas=array();
		$conexion=conexion();
		$query="";
		$field_name="";
		try{
			switch($db){
				case "postgres":
					$query="SELECT relname FROM pg_stat_user_tables ORDER BY relname";
					$field_name="relname";
				break;
				case "sqlite":
					$query="select name from sqlite_master where type = 'table'";
					$field_name="name";
				break;
				case "mysql":
					$query="SHOW TABLE STATUS FROM ".$dbname;
					$field_name="Name";
				break;
				case "sqlserver":
					$query="SELECT NAME FROM sys.objects SO WHERE TYPE = 'U' ORDER BY NAME";
					$field_name="NAME";
				break;
			}
			$result = $conexion->prepare($query);
			$result->execute();
			while ($array = $result->fetch(PDO::FETCH_ASSOC)){
				$array_tablas[$tablas]=$array[$field_name];
				$tablas++;
			}
			$result=null;
		}catch (PDOException $e) {
			echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Problems with the obtener_tables function: ".$e->getMessage()."</div>";
			$flag=false;
			die();
		}
		cerrar_conexion($conexion);
		return $array_tablas;
	}
	function obtener_columnas($tabla){
		$array_columnas=array();
		$n_campos=0;
		$conexion=conexion();
		$result = $conexion->prepare("select * from ".$tabla);
		$result->execute();
		for($i=0;$i<$result->columnCount();$i++){
			$meta=$result->getColumnMeta($i);
			$array_columnas[$i]=$meta['name'];
		}
		$result=null;
		cerrar_conexion($conexion);
		return $array_columnas;
	}
	function obtener_columnas_libre($sql){
		$array_columnas=array();
		$n_campos=0;
		$conexion=conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		for($i=0;$i<$result->columnCount();$i++){
			$meta=$result->getColumnMeta($i);
			$array_columnas[$i]=$meta['name'];
		}
		$result=null;
		cerrar_conexion($conexion);
		return $array_columnas;
	}
	function ejecutar_query($query){
		try{
			$conexion=conexion();
			$conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$result = $conexion->prepare($query);
			if($result->execute()) return true;
			else return false;
		}catch (PDOException $e) {
			echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Problems with the query: ".$e->getMessage()."</div>";
			return false;
		}
		cerrar_conexion($conexion);
	}
	function ejecutar_query_fast($query, $conexion){
		try{
			$result = $conexion->prepare($query);
			$conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			if($result->execute()) return true;
			else return false;
		}catch (PDOException $e) {
			echo "Problems with the query: ".$e->getMessage()."</div>";
			return false;
		}
	}
	
	function obtener_n_filas($query){
		$n_filas=0;
		$conexion=conexion();
		$result = $conexion->prepare($query);
		$result->execute();
		if($result!=false){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$n_filas=$row['total'];
		}
		$result=null;
		cerrar_conexion($conexion);
		return $n_filas;
	}
	
	function obtener_query($query){
		$array_campos=array();
		$arreglo=array();
		$fila=0;
		try{
			$conexion=conexion();
			$result = $conexion->prepare($query);
			$conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			//echo $query."<br>";
			$result->execute();
			if($result!=false){
				for($i=0;$i<$result->columnCount();$i++){
					$meta=$result->getColumnMeta($i);
					$array_campos[$i]=$meta['name'];
				}
				while ($row = $result->fetch(PDO::FETCH_ASSOC)){
					for($i=0;$i<count($array_campos);$i++){
						if($row[$array_campos[$i]]===NULL){
							$arreglo[$fila][$i]="";
						}else
							$arreglo[$fila][$i]=$row[$array_campos[$i]];
					}
					$fila++;
				}
			}
			$result=null;
		}catch (PDOException $e) {
			echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Problems with the query: ".$e->getMessage()."</div>";
			return false;
		}
		cerrar_conexion($conexion);
		if(count($arreglo)==0){
			return false;
		}else{
			if(count($arreglo)==1){
				for($i=0;$i<count($arreglo[0]);$i++)
					if($arreglo[0][$i]!="")	return $arreglo;
				return false;
			}else
				return $arreglo;
		}
	}
	function obtener_query_fetch($query, $conexion){
		$result = $conexion->prepare($query);
		//echo $query."<br>";
		$result->execute();
		if($result!=false){
			return $result;
		}else{
			return false;
		}
	}
	
	function getPrimaryKey($table){
		global $db;
		$keys=array();
		$i=0;
		$conexion=conexion();
		switch($db){
			case "mysql":
				$pk_query=$conexion->query("SHOW KEYS FROM ".$table." WHERE Key_name = 'PRIMARY'");
				if($pk_query!=false){
					while($pk=$pk_query->fetch(PDO::FETCH_ASSOC)){
						$keys[$i]=$pk['Column_name'];
						$i++;
					}
				}
			break;
			case "postgres":
				$pk_query=$conexion->query("SELECT kcu.constraint_name, kcu.column_name FROM INFORMATION_SCHEMA.TABLES t LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc ON tc.table_catalog = t.table_catalog AND tc.table_schema = t.table_schema AND tc.table_name = t.table_name AND tc.constraint_type = 'PRIMARY KEY' LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu ON kcu.table_catalog = tc.table_catalog AND kcu.table_schema = tc.table_schema AND kcu.table_name = tc.table_name AND kcu.constraint_name = tc.constraint_name WHERE   t.table_schema NOT IN ('pg_catalog', 'information_schema') and t.table_name='".$table."'");
				if($pk_query!=false){
					while($pk=$pk_query->fetch(PDO::FETCH_ASSOC)){
						if($pk['column_name']!=""){
							$keys[$i]=$pk['column_name'];
							$i++;
						}
					}
				}
			break;
			case "sqlserver":
				$pk_query=$conexion->query("select I.name as CONSTRAINT_NAME, I.type_desc as TYPE_DESC, AC.name as COLUMN_NAME from sys.tables as T inner join sys.indexes as I on T.[object_id] = I.[object_id] inner join sys.index_columns as IC on IC.[object_id] = I.[object_id] and IC.[index_id] = I.[index_id] inner join sys.all_columns as AC on IC.[object_id] = AC.[object_id] and IC.[column_id] = AC.[column_id] where T.name='".$table."' and I.is_primary_key='1'");
				if($pk_query!=false){
					while($pk=$pk_query->fetch(PDO::FETCH_ASSOC)){
						if($pk['COLUMN_NAME']!=""){
							$keys[$i]=$pk['COLUMN_NAME'];
							$i++;
						}
					}
				}
			break;
			case "sqlite":
				$pk_query=$conexion->query("PRAGMA table_info(".$table.")");
				if($pk_query!=false){
					while($pk=$pk_query->fetch(PDO::FETCH_ASSOC)){
						if($pk['pk']=="1"){
							$keys[$i]=$pk['name'];
							$i++;
						}
					}
				}
			break;
		}
		$pk_query=null;
		cerrar_conexion($conexion);
		return ((count($keys)>0)?$keys:false);
	}

	function cerrar_conexion($conexion){
		$conexion=null;
	}
?>