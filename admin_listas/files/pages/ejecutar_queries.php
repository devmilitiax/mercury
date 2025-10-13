<?php  session_start();  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Execute SQL Queries</title>
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
<?php if(isset($_SESSION['user'])){ ?>
<?php include("header.php"); ?>
<label for select_campos></label>
<div id="ver_tabla">
    <div class='boton'><h4 style="padding:0px; margin:0px; border:0px">Execute your custom sql queries</h4></div>
    
    <div style="background:#FFF; color:#000">
        
        <form method="post" id="form_consulta_avanzada" action="../resourcess/exportar_csv.php">
        <div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>You can use almost all the SQL commands then you have to be <strong>careful</strong> with your queries. At this option you can not export your results. For export a customized SQL query result you must use the option "Advanced" in the menu "Queries"</div>
        <br/>
            <div>
                <table><tr><td style="padding:10px">Instruction slq:</td><td><textarea name="inst_sql" id="inst_sql" style="resize:both; width:400px; height:100px;" title="Be careful with your queries"></textarea></td><td style="padding:10px"><input type="button" id="ejecutar_inst" name="ejecutar_inst" value="Execute" class="btn btn-info"></td></tr></table>
            </div>
        <br/>
        </form>
        
    </div>
    <div id="resultado" class="resultado"></div>
</div>
<?php }else{ echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>"; } ?>
</div></body>
</html>