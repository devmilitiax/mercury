<?php  session_start();  ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <title>File Content</title>
    <link rel="stylesheet" href="../styles/style.css" type="text/css">
    <link href="../styles/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../styles/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link rel="shortcut icon" href="../images/DB ico.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../images/DB ico.png" type="image/png">
    <script src="../scripts/jquery.js" type="text/javascript"></script>
    <script src="../scripts/script.js" type="text/javascript"></script>
    <script src="../scripts/bootstrap.min.js"></script>
	<script src="../scripts/bootstrap-dropdown.js"></script>
    <script src="../scripts/bootstrap-collapse.js"></script>
    <script src="../scripts/bootstrap-popover.js"></script>
    <script src="../scripts/bootstrap-alert.js"></script>
    <script src="../scripts/bootstrap-modal.js"></script>
</head>
<body><div id="container">
<?php 
	include("../config/other_config.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	if(isset($_SESSION['user'])){
		$titulo="File Content";
?>
<?php include("header.php"); ?>
<div class="loader"></div>
<div class="cover"></div>
<div id="show_sql">
<div id="ocultar" style="width:inherit">
<h2 id="titulo"><?=$titulo?></h2>
<div id="instrucciones">
<?php
		$target_path = "../uploads/";
		
		if(!is_dir($target_path)){
			mkdir($target_path);
		}
		
		if(isset($_FILES['archivo_sql'])){
			$target_path = $target_path . basename( $_FILES['archivo_sql']['name']); 
			if(move_uploaded_file($_FILES['archivo_sql']['tmp_name'], $target_path)) {
				if(filesize($target_path)<=10485760){
					$sql = file_get_contents($target_path);
					$sql = str_replace("\n", "<br/>", $sql);
				}else{
					$sql = "	File is too large to be shown. But you still can execute it";
				}
			}
		}else{
			$sql = "It's possible the file is too large to be uploading with your actual php.ini upload configuration.";
			$sql .= " You upload_max_filesize=".ini_get('upload_max_filesize')." and post_max_size=".ini_get('post_max_size').".\n Both values have to be bigger than your file size.";
			$sql .= " You can change these values in the file php.ini in the PHP installation directory. After you will have to restart your web server and you can try to upload the file again.";
			
		}
		echo $sql;
?>
</div>
<?php
		if(isset($_FILES['archivo_sql'])){
			echo '<div style="padding-top:10px;text-align:right;"><input type="button" value="Execute" id="ejecutar_sql" name="ejecutar_sql" class="btn btn-success"/></div>';
		}
?>
</div>
<div class="result"></div>
<input type="hidden" value="<?=$target_path?>" id="archivo">
<table class="menu_final" style="width:inherit;"><tr><td style="text-align:right; width:100%"><a href="../main.php">Back to the menu</a></td></tr></table>
</div>
<?php
	}else{
		echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
	}
?>
</div></body>
</html>