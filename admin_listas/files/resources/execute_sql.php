<?php
	include("funciones.php");
	include("../config/other_config.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	$archivo=fopen($_REQUEST['archivo'], "r");
	$inserciones=0;
	$errors=0;
	$sql="";
	$conexion=conexion();
	while(!feof($archivo)){
		$sentence=fgets($archivo);
		if(strstr($sentence, ';')){
			$sql.=$sentence." ";
			$tokens = preg_split("/(--.*\s+|\s+|\/\*.*\*\/)/", $sql, null, PREG_SPLIT_NO_EMPTY);
			$length = count($tokens);
			$query = '';
			$inSentence = false;
			$curDelimiter = ";";
			for($i = 0; $i < $length; $i++) {
				if($db=="sqlserver"){
					if(strripos($tokens[$i], "\ngo ")!=false||strripos($tokens[$i]," go ")!=false||strripos($tokens[$i],"\ngo\n")!=false||strripos($tokens[$i]," go\n")!=false)
						$tokens[$i]=str_replace("go", ";", $tokens[$i]);
				}
				$lower = mb_strtolower($tokens[$i]);
				$isStarter = in_array($lower, array(
					 'update', 'delete', 'insert', 'drop', 'create', 'alter', 'if'
				));
				if($inSentence) {
					if($tokens[$i] == $curDelimiter || substr(trim($tokens[$i]), -1*(strlen($curDelimiter))) == $curDelimiter) { 
						$query .= str_replace($curDelimiter, '', $tokens[$i]);
						if(!ejecutar_query_fast($query, $conexion)){
							$errors++;
							if(($errors==10)&&($inserciones==0))
								die();
						}else{
							$inserciones++;
						}
						$query = "";
						$tokens[$i] = '';
						$inSentence = false;
					}
				}else if($isStarter) {
					if($lower == 'delimiter' && isset($tokens[$i+1]))  
						$curDelimiter = $tokens[$i+1]; 
					else
						$inSentence = true;
					$query = "";
				}
				$query .= "{$tokens[$i]} ";
			}
			$sql="";
		}else{
			$sql.=$sentence." ";
		}
	}
	fclose($archivo);
	cerrar_conexion($conexion);
	echo $inserciones." queries executed successfuly";
?>