<?php
	include("funciones.php");
	include("../config/other_config.php");
	ini_set('memory_limit', $m_limit.'M');
	set_time_limit(0);
	
	$string_sql=$_REQUEST['sql'];
	$habilitar=$_REQUEST['enable'];
	$string_sql=trim($string_sql);
	if(mb_strtolower(mb_substr($string_sql,strlen($string_sql)-1,1))!=";"){
		$string_sql.=";";
	}
	$sql=preg_split("/(--.*\s+|\s+|\/\*.*\*\/)/", $string_sql, null, PREG_SPLIT_NO_EMPTY);
	$query="";
	$inSentence = false;
	$curDelimiter = ";";
	$rest=false;
	$n_queries=0;
	for($i=0;$i<count($sql);$i++){
		$lower = mb_strtolower($sql[$i]);
 		$isStarter = in_array($lower, array(
		 'update', 'delete', 'insert', 'drop', 'create', 'alter', 'select', 'show', 'if'
		));
		if($isStarter){
			$isSelect = in_array($lower, array(
				'select', 'show'
			));
		}
		if($inSentence) {
     		if($sql[$i] == $curDelimiter || substr(trim($sql[$i]), -1*(strlen($curDelimiter))) == $curDelimiter) { 
			  	$query .= str_replace($curDelimiter, '', $sql[$i]);
				if($isSelect){
					$resultado=obtener_query($query);
					if($resultado!=false){
						$array_campos=obtener_columnas_libre($query);
						$rest=true;
						$n_queries++;
					}else
						$array_campos=array();
				}else{
					if($habilitar=="Execute"){
						if(ejecutar_query($query)!=false)
							$n_queries++;
					}else
						echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Only select queries are enabled</div>";
				}
				$query = "";
				$sql[$i] = '';
				$inSentence = false;
     		}
 		}else if($isStarter) {
     		if($lower == 'delimiter' && isset($sql[$i+1]))  
  				$curDelimiter = $sql[$i+1]; 
     		else
  				$inSentence = true;
     		$query = "";
 		}
 		$query .= $sql[$i]." ";
	}
	if($rest){
		echo "<table class='result_tabla'>\n<tr class='header'>";
		for($i=0;$i<count($array_campos);$i++){
			echo "<th>".$array_campos[$i]."</th>";
		}
		echo "</tr>\n";
		for($i=0;$i<count($resultado);$i++){
			echo "<tr>";
			for($j=0;$j<count($array_campos);$j++){
				if(isset($resultado[$i][$j]))
					echo "<td>".$resultado[$i][$j]."</td>";
			}
			echo "</tr>\n";
		}
		echo "</table><br/>";
	}
	echo "<span style='font-weight:bold'>".$n_queries." queries executed successfully </span>";

?>