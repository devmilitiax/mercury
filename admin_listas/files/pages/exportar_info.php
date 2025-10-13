<?php  session_start();  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Export info</title>
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
		if(isset($_SESSION['user'])){
			$archivo=$_GET['archivo'];
?>
	<?php include("header.php"); ?>
    <div id="export" class="form-inline">
    <div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>This option will export <strong>All</strong> the records in the table</div>
    <h2>Select Information Source</h2>
<?php
    	switch($archivo){
			case 'csv': 
				echo '<form method="post" action="../resources/exportar_csv.php" id="form_exp_tab">';
			break;
			case 'excel': 	
				echo '<form method="post" action="../resources/exportar_excel.php" id="form_exp_tab">';
			break;
			case 'sql': 
				echo '<form method="post" action="../resources/exportar_sql.php" id="form_exp_tab">';
		    break;
			case 'pdf': 
				echo '<form method="post" action="../resources/exportar_pdf.php" id="form_exp_tab">';
			break;
			case 'xml':
				echo '<form method="post" action="../resources/exportar_xml.php" id="form_exp_tab">';
			break;
		}
?>
    <table style="width:100%;"><tr><td style="text-align:right"><span class="label label-info">Select the table</span></td><td style="text-align:left"><?php include("../resources/tablas.php")?></td></tr>
    <tr id="select_c"></tr>
    <tr><td colspan="2" style="text-align:right"><input type="button" value="Export File" name="b_export" id="b_export" class="btn btn-success"></td></tr>
    </table>
    <input type="hidden" value="<?php echo $archivo;?>" id="archivo_ext" name="archivo_ext">
    </form>
    </div>
<?php
	}else{
		echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
	}
?>
</div></body>
</html>