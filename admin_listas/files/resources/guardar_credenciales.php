<?php
	$user=crypt(md5(sha1($_REQUEST['user'])), "mhfsnucg");
	$npass=crypt(md5(sha1($_REQUEST['pass'])), "mhfsnucg");
	$fp = fopen("../resources/program_file.php","w+");
	fwrite($fp, "<?php\n$"."a='".$user."';\n$"."b='".$npass."';\n?>" . PHP_EOL);
	fclose($fp);
?>