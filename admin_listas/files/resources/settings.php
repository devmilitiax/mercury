<?php
	$dir="../config/";
	include($dir."config.php");
	$handle = opendir($dir);
	$empty=true;
	$selected="";
	echo "<select id='configs'>";
	echo "<option value='0' style='background:#000'>Select</option>";
	while ($arc = readdir($handle)){
	   if (is_file($dir.$arc)){
		   if(($arc!="config.php")&&($arc!="other_config.php")&&($arc!="index.html")&&($arc!="users.php")){
			    if(isset($file))
					if($file==str_replace(".php","",$arc)){
						$selected=" selected";
					}
				echo "<option value='".str_replace(".php","",$arc)."'".$selected.">".str_replace(".php","",$arc)."</option>";
				$empty=false;
				$selected="";
		   }
	   }
	}
	if($empty){
		echo "<option value='0'>There aren't settings saved</option>";
	}
	echo "</select>";
?>