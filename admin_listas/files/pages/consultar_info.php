<?php  session_start();  ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consult data</title>
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
          include("header.php"); 
    ?>
    <div id="update_modal" class="modal hide fade"></div>
    <div id="ver_tabla">
    <form method="post" id="form_consulta_avanzada" action="../resources/exportar_csv.php">
    <table style="width:100%"><tr><td class="left"><h2>Select Information Source</h2></td><td class="right" id="export_option">
    <div class="btn-group">
        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-file icon-white"></i> Export <span class="caret"></span></a>
        <ul class="dropdown-menu" id="b_export_to">
        	<li><a>To a CSV file</a></li>
            <li><a>To a SQL file</a></li>
            <li><a>To a PDF file</a></li>
			<li><a>To a XML file</a></li>
            <li><a>To a Excel file</a></li>
        </ul>
    </div>
   	</td></tr></table>
    <table><tr><td>Select the table</td><td><?php include("../resources/tablas.php")?></td><td><input type="button" value="Consult" name="b_consult" id="b_consult" class="btn btn-info"></td></tr>
    </table>
    <br/>
    <div id="search"></div>
    <div id="result" class="resultado"></div>
    <input type="hidden" value="" name="n_pagina">
    <input type="hidden" value="" name="select_campo_orden">
    <input type="hidden" value="" name="asc-desc">
    </form>
    </div>
    <script type="text/javascript">	$('.dropdown-toggle').dropdown(); </script>
    <?php }else{ echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>"; } ?>
</div></body>
</html>