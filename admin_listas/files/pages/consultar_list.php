<?php  session_start();  ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consult data</title>
    <link href="../styles/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../styles/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="../styles/style.css" type="text/css">
    <link rel="shortcut icon" href="../images/DB ico.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../images/DB ico.ico" type="image/png">
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
<?php if(isset($_SESSION['user'])){ ?>
<?php include("header.php"); ?>

<div id="ver_tabla">
    <div id="expandir" class='boton'><h4 style="padding:0px; margin:0px; border:0px">Select a saved query from the list</h4></div>
    
    <div id="replegar" style="background:#FFF; color:#000">
        
        <form method="post" id="form_consulta_avanzada" action="../resourcess/exportar_csv.php">
            <div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>You can consult all the queries that you have <strong>previously</strong> saved</div>
			<div class='center'>
            	<table style="width:95%;">
                <tr><td id="export_option" class="right">
                <div class="btn-group">
                    <a class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-file icon-white"></i> Export <span class="caret"></span></a>
                    <ul class="dropdown-menu" id="b_export_to">
                        <li><a>To a CSV file</a></li>
                        <li><a>To a PDF file</a></li>
						<li><a>To a XML file</a></li>
                        <li><a>To a Excel file</a></li>
                    </ul>
                </div>
                </td></td>
                <tr><td>
				<?php
					include("../resources/funciones.php");
					$result=obtener_query("select * from saved_queries");
					echo "<br>Saved Queries: <select id='s_queries' name='s_queries' class='span2'>";
					for($i=0;$i<count($result);$i++){
						echo "<option value='".$result[$i][0]."'>".$result[$i][0]."</option>";
					}
					echo "</select>";
				?>
                </td></tr>
                <tr><td class="right"><input type='button' value='show' id='ejecutar_condicion' class='btn btn-success'></td></tr>
                </table>
			</div>
			<br/>
        </form>
        
    </div>
    <div id="resultado" class="resultado"></div>
</div>
	 <script type="text/javascript">
	 	$('.dropdown-toggle').dropdown();
		$('#inst_sql').tooltip('hover');
     </script>
<?php }else{ echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>"; } ?>
</div></body>
</html>