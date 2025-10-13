<?php  session_start();  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
</head>
	<title>Import from a CSV file</title>
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
<body><div id="container">
<?php
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
<?php include("../config/other_config.php"); ?>
<div id="insert_csv" class="form-inline"><form method="post" action="csv_tabla.php" enctype="multipart/form-data">
	<table>
    	<tr><td colspan="2"><h2>Importing from CSV file</h2></td></tr>
        <tr><td>Select Table: </td><td><?php include("../resources/tablas.php")?></td></tr>
        <tr><td>CSV Separator: </td><td><input type="text" name="separator" id="separator" value="<?php echo $csv_char?>"></td></tr>
        <tr id="m_arch"><td>Select File: </td><td><input type="file" id="archivo" name="archivo"></td></tr>
        <tr><td colspan="2" id="subir_archivo" style="text-align:right"><input type="submit" value="next" class="btn btn-success"></td></tr>
    </table>
</form></div>
<?php
	}else{
		echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
	}
?>
</div></body>
</html>