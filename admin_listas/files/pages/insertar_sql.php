<?php  session_start();  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
    <title>Import from SQL file</title>
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
	$dir="../uploads/";
	if(!is_dir($dir)){
		mkdir($dir);
	}
	
	$handle = opendir($dir);
	while ($file = readdir($handle)){
	   if (is_file($dir.$file) && strpos($file,"index.html")===false){
		   unlink($dir.$file);
	   }
	}
	if(isset($_SESSION['user'])){
		
?>
<?php include("header.php"); ?>
<div id="insert_sql" class="form-inline"><form method="post" action="show_sql.php" enctype="multipart/form-data">
	<table>
  		<tr><td colspan="2"><h2>Importing from SQL file</h2></td></tr>
        <tr><td>Select File: </td><td><input type="file" id="archivo_sql" name="archivo_sql"></td></tr>
        <tr><td colspan="2" id="subir_archivo_sql" style="text-align:right"><input type="submit" value="next" class="btn btn-success"></td></tr>
    </table>
</form></div>
<?php
	}else{
		echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
	}
?>
</div></body>
</html>