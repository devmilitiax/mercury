<?php
	$csv_char=$_POST['csv_char'];
	$excel=$_POST['excel'];
	$n_result=$_POST['n_result'];
	$m_limit=$_POST['m_limit'];
	if($csv_char=="")
		$csv_char=";";
	$fp = fopen("../config/other_config.php","w");
	$write="<?php\n$"."csv_char='".$csv_char."';\n$"."excel='".$excel."';\n$"."n_result=".$n_result.";\n$"."m_limit='".$m_limit."';\n?>" . PHP_EOL;
	fwrite($fp, $write);
	fclose($fp);
?>
