<?php  session_start();  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
    <title>Other Options</title>
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
<body><div id="container" class="form-inline">
<?php
	if(isset($_SESSION['user'])){
?>
<?php include("header.php"); 
	include("../config/other_config.php");
?>
<div id="menu">
<h3>Other Configurations</h3>
<form action="../resources/guardar_configuraciones.php" method="post" id="configuraciones">
<table style="background:#FFF; color:#000; text-align:left">

<tr class="header"><th>Property</th><th>Value</th></tr>
<tr><td><span class="label label-info">CSV separator character:</span></td><td class="center"><input type="text" name="csv_char" value="<?php echo $csv_char?>" required ></td></tr>

<tr><td><span class="label label-info">Save excel files in:</span></td><td class="center"><select name='excel' class="span1"><option value='xls' <?php if($excel=='xls') echo 'selected';?>>Excel 2003</option><option value='xlsx' <?php if($excel=='xlsx') echo 'selected';?>>Excel 2007</option></select></td></tr>

<tr><td><span class="label label-info">Number of rows shown in table</span></td><td class="center"><input type="number" name="n_result" value="<?php echo $n_result?>" style="text-align:center; width:55px;"></td></tr>

<tr><td><span class="label label-info">Memory Limit(Mb)</span></td><td class="center"><input type="number" name="m_limit" value="<?php echo $m_limit?>" style="text-align:center; width:55px;"></td></tr>

</table><br/>

<div class="right"><input type="button" id="b_save_config" value="Save Configuration" class="btn btn-success"></div>

</form>
</div>
<?php
	}else{
		echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
	}
?>
</div></body>
</html>
