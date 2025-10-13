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
    <div id="expandir" class='boton'><h4 style="padding:0px; margin:0px; border:0px">Select source of information</h4></div>
    
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
					</table>
				</div>
				<div class="modal-footer">
					<input type="button" value="Save" name="save_query" id="save_query" class="btn btn-success">
					<input type="button" value="Cancel" name="cancel_query" id="cancel_query" class="btn btn-warning">
				</div>
			</div>
            <div style="padding:10px; background:#000; color:#fff; font-weight:bold">
                <table style="width:100%;">
                <tr><td class="left" style="padding:0px;">Easy <input type="radio" value="easy" name="tipo" class="tipo"  checked> Advanced <input type="radio" value="advanced" name="tipo" class="tipo"></td><td id="export_option" class="right">
                <div class="btn-group">
                    <a class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-file icon-white"></i> Export <span class="caret"></span></a>
                    <ul class="dropdown-menu" id="b_export_to">
                        <li><a>To a CSV file</a></li>
                        <li><a>To a PDF file</a></li>
						<li><a>To a XML file</a></li>
                        <li><a>To a Excel file</a></li>
                    </ul>
                </div>
                </td></tr>
                </table>
            </div>
			<br/>
            <div id="consulta_mas_avanzada">
                <table><tr><td style="padding:10px">Instruction slq:</td><td><textarea name="inst_sql" id="inst_sql" data-original-title='Only select and Show queries are avalaibles' data-toggle='tooltip' data-placement='right' title=''></textarea></td><td style="padding:10px"><input type="button" id="ejecutar_inst" name="ejecutar_inst" value="Consult" class="btn btn-info"></td></tr></table>
            </div>
        
            <div id="custom">
            
            <table id="config_query">
            
            <tr><td class="right">Select table</td><td colspan="2"><?php include("../resources/tablas.php")?> <input type="button" value="Select" id="mostrar_av" name="mostrar_av" class="btn btn-info"></td></tr>
            <tr class="mostrar_avanzado"><td class="right">Conditions <input type="button" value="+" id="m_conditions" class="btn btn-small btn-success"></td><td id="conditions"><span id="campos"></span> <select id='operador' name='operador' class='span1'>
            		<option value="none" style='background:#000'>none</option>
                    <option value="=">=</option>
                    <option value=">">&gt;</option>
                    <option value="<">&lt;</option>
                    <option value=">=">&gt;=</option>
                    <option value="<=">&lt;=</option>
                    <option value="<>">Different To</option>
                    <option value="like">Contains</option>
                    <option value="not like">Doesn't Contain</option>
            </select> <input type="text" name="condicion" id="condicion" placeholder="Search" class="input-medium"></td><td rowspan="3" id="seleccionar_campos" class="center"></td></tr>
            <tr class="mostrar_avanzado"><td class="right">Order By</td><td id="order_field"></td></tr>
            <tr class="mostrar_avanzado"><td class="right">Show</td><td>From <input type="number" name="first" id="first" value="0" class="input-mini"> to <input type="number" name="last" id="last" value="20" class="input-mini"></td></tr>
            <tr class="mostrar_avanzado"><td colspan="3" class="right"><input type="button" name="ejecutar_condicion" id="ejecutar_condicion" value="Execute query" class="btn btn-info"> <input type="button" name="guardar_condicion" id="guardar_condicion" value="Save Query" class="btn btn-info"></td></tr>
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