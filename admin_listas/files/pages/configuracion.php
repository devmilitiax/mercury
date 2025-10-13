<?php  session_start();  ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Database Configuration</title>
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
    <?php include("../config/config.php"); ?>
    <script type="text/javascript">
		function ValName(e) {
			k = (document.all) ? e.keyCode : e.which;
			if (k==8 || k==0) return true;
			patron = /\w/;
			n = String.fromCharCode(k);
			return patron.test(n);
		}
	</script>
</head>
<body><div id="container" class="form-inline">
	<?php include("header.php"); ?>
    
    <div id="insert_csv" class="center">
    	<h3>Select a setup configuration:</h3>
		<span id="c_name"><span class="label label-inverse">Actual Configuration: </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php include("../resources/settings.php");?></span>
    <div>
    <div id="config">
        <form id="formulario_config" method="post" action="../resources/upload_file.php" class="form-inline" enctype="multipart/form-data" target="upload_target">
        	<div id="problema"></div>
            <div id="my_config"><?php include("../resources/db_config.php");?></div>
            <div id="hidden_buttons">
            <input type="button" value="cancel" id="cancel" class="btn"> <input type="button" value="test conection" id="b_test" name="b_test" class="btn btn-info"> <input type="button" value="save configuration" id="save" class="btn btn-success"></div>
         </form>
         <iframe id="upload_target" name="upload_target" src="#" style="display:none"></iframe> 
        </div>
    </div>
    	<div id="buttons">
            <input type="button" value="Use" id="b_u" class="btn btn-success">
            <input type="button" value="New" id="b_n" class="btn btn-info">
            <input type="button" value="Delete" id="b_d" class="btn btn-warning">
        </div>
    </div>
    
    <div id="modal1" class="modal hide fade form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4>Enter your credentials</h4>
        </div>
        <div class="modal-body">
        	<div id="problem2"></div>
        	<table>
                <tr><td>User Name: </td><td><input type="text" id="usua" name="usua" placeholder="User"></td></tr>
                <tr><td>Password: </td><td><input type="password" id="cont" name="cont"></td></tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" value="Ok" name="ok" id="ok" class="btn btn-success">
        </div>
    </div>
    
    <div id="modal2" class="modal hide fade">
    	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4>Confirm</h4>
        </div>
        <div class="modal-body">
        	<div id="problem"></div>
        	<p>Do you really want to delete the selected settings?</p>
            <table>
                <tr><td>User Name: </td><td><input type="text" id="usua2" name="usua2" placeholder="User"></td></tr>
                <tr><td>Password: </td><td><input type="password" id="cont2" name="cont2"></td></tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" value="Ok" name="ok_delete" id="ok_delete" class="btn btn-warning">
            <input type="button" value="Cancel" name="cancel" id="cancel_modal2" class="btn">
        </div>
    </div>

    <script type="text/javascript">
		$("input[data-toggle='popover']").click(function(e) {
			if($(this).attr("name")!="nam"){
				$(this).popover('show');
			}else{
				if(!($(this).attr("readonly"))){
					$(this).popover('show');
				}
			}
		});
		$("input[data-toggle='popover']").focusout(function(e) {
            $(this).popover('destroy');
        });
		$("#b_u").click(function(e){
			if($("#configs").val()!="0"){
				$("#modal1").modal("show");
			}else{
				alert("Select a valid configuration!");
			}
		});
		$("#b_d").click(function(e){
			if($("#configs").val()!='0'){
				$("#modal2").modal("show");
			}
		});
		$("#ok_delete").click(function(e){
			delete_configuration();
		});
		$("#cancel_modal2").click(function(e){
			$("#modal2").modal('hide');	
		});
    </script>
</div></body>
</html>
