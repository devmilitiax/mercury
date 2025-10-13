<?php  session_start();  ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consult join tables data</title>
    <link href="../styles/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../styles/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="../styles/style.css" type="text/css">
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
<?php if(isset($_SESSION['user'])){ ?>
<?php include("header.php"); ?>

<div id="ver_tabla">
    <div id="expandir" class='boton'><h4 style="padding:0px; margin:0px; border:0px">Joining Tables</h4></div>
    
    <div id="replegar" style="background:#FFF; color:#000">
        
        <form method="post" id="form_consulta_avanzada" action="../resourcess/exportar_csv.php">
			<div id="save_query_modal" class="modal hide fade">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4>Save your query to consult it later</h4>
				</div>
				<div class="modal-body">
					<div id="problem"></div>
					<table>
						<tr><td>Write a name for the query: </td><td><input type="text" id="query_name" name="query_name" placeholder="name"></td></tr>
                        <tr><td>Write a description: </td><td><textarea name="query_desc" style="width:205px;height:150px"></textarea></td></tr>
					</table>
				</div>
				<div class="modal-footer">
					<input type="button" value="Save" name="save_query" id="save_query" class="btn btn-success">
					<input type="button" value="Cancel" name="cancel_query" id="cancel_query" class="btn btn-warning">
				</div>
			</div>
            <div style='background:#000;'>
                <table style="width:97%;">
				<tr><td style="background:#000; color:#fff; font-weight:bold"><h3>Select the tables that you want to join</h3></td></tr>
                <tr><td class='center' style="background:#000; color:#fff; font-weight:bold">
				<?php 
					require_once("../resources/funciones.php");
					echo "<select id='tablas' name='tablas[]' multiple='multiple' data-original-title='Press Ctrol for select multiple items' data-toggle='tooltip' data-placement='bottom' title=''>";
					$array_tablas=obtener_tablas();
					for($i=0;$i<count($array_tablas);$i++) {
						echo "<option value='".$array_tablas[$i]."'>".$array_tablas[$i]."</option>";
					}
					echo "</select>";
					echo "<script>$('#tablas').tooltip('hover')</script>";
				?></td>
				</tr><tr><td class="right" style="background:#000; color:#fff; font-weight:bold"><input type='button' value='Next' class='btn btn-info' id='next1'></td></tr>
				<tr><td id='tables_fields' style="background:#fff; color:#000; font-weight:bold;"></td></tr>
				<tr><td id="join_conditions" class='center'></td></tr>
				<tr><td id="button_next2" class='right'><input type='button' id='more_c' value='+' class='btn btn-success'> <input type='button' value='Next' class='btn btn-info' id='next3'></td></tr>
				<tr><td id="other_conditions"></td></tr>
				<tr><td id="execute_field" class='right'><input type='button' value='Show Result' id='show_join' class='btn btn-success'> <input type='button' value='Save Query' name="guardar_condicion" id="guardar_condicion" class='btn btn-success'> 
				<div id='export_op' class="btn-group">
                    <a class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-file icon-white"></i> Export <span class="caret"></span></a>
                    <ul class="dropdown-menu" id="b_export_to">
                        <li><a>To a CSV file</a></li>
                        <li><a>To a PDF file</a></li>
						<li><a>To a XML file</a></li>
                        <li><a>To a Excel file</a></li>
                    </ul>
                </div></td></tr>
				</table>
            </div>
        </form>
    </div>
    <div id="resultado" class="resultado"></div>
	<script type="text/javascript">
	 	$('.dropdown-toggle').dropdown();
    </script>
</div>
<?php }else{ echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>"; } ?>
</div></body>
</html>