<?php  session_start();  ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
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
</head>
<body><div id="container" class="form-inline">
<?php
	if(isset($_SESSION['user'])){
?>
<?php include("header.php"); ?>
<div class="loader"></div>
<div class="cover"></div>
<div id="comparar">
<div id="match">
<?php
	include("../config/other_config.php");
	include("../resources/funciones.php");
	set_time_limit(0);
	ini_set('memory_limit', $m_limit.'M');
	$separator=$_POST['separator'];
	$caracter=caracter();
	$target_path = "../uploads/";
	if(!is_dir($target_path)){
		mkdir($target_path);
	}
	if(isset($_FILES['archivo'])){
		$target_path = $target_path . basename( $_FILES['archivo']['name']); 
		if(move_uploaded_file($_FILES['archivo']['tmp_name'], $target_path)) {
			$handle = fopen($target_path, "r");
			$array_campos=obtener_columnas($caracter.$_POST['tabla'].$caracter);
			echo "<form id='form_csv_table'><table style='text-align:center;border:solid 3px #fff;'>";
			echo "<tr><td colspan='2' style='background-color:#000'><h2>Match each CSV file column with a DB Table Field</h2></td></tr>";
			echo "<tr style='background:#fff; color:#000; font-weight:bold'><td><span class='label label-info'>CSV file</span> Columns</td><td><span class='label label-info'>".$_POST['tabla']." table</span> Columns</td></tr>";
			
			$keys=getPrimaryKey($caracter.$_POST['tabla'].$caracter);
			$data = fgetcsv($handle, 1000, $separator);
			if(mb_substr($data[0],0,1)==" "){
				$data[0]=substr($data[0],1);
			}
			for($j=0;$j<count($data);$j++){
				echo "<tr><td style='background:#000; border:solid #fff 1px'>".$data[$j]."</td><td>";
				echo "<select id='campos".$j."' name='campos".$j."' class='span2'><option value='0' style='background:#999'>Don't use</option>";
				for($n_campos=0;$n_campos<count($array_campos);$n_campos++) {
					if(mb_strtolower($data[$j])==mb_strtolower($array_campos[$n_campos])){
						echo "<option value='".$array_campos[$n_campos]."' selected>".$array_campos[$n_campos]."</option>";
					}else{
						echo "<option value='".$array_campos[$n_campos]."'>".$array_campos[$n_campos]."</option>";
					}
				}
				echo "</select></td></tr>";
			}
			echo "<tr style='background:#fff;color:#000'><td>Skip the first row</td><td style='text-align:center'><input type='checkbox' id='saltar' name='saltar' checked></td></tr>";
			echo "<tr style='background:#fff;color:#000'><td>Delete all the data in the table first</td><td style='text-align:center'><input type='checkbox' id='eliminar_data' name='eliminar_data'></td></tr>";
			echo "<tr style='background:#fff;color:#000'><td>If record exists</td><td style='text-align:center'><select name='if_exists' id='if_exists' class='span2'><option value='Ignore'>Ignore</option><option value='update'>Update</option></select></td></tr>";
			echo "<tr style='background:#fff;color:#000' id='update_fields'><td>Select fields to be updated</td><td style='text-align:center'><select id='upd_fields' name='upd_fields[]' class='span2' multiple='multiple' style='height:150px;'>";
			for($n_campos=0;$n_campos<count($array_campos);$n_campos++){
				$iskey=false;
				for($j=0;$j<count($keys);$j++){
					$iskey=(($keys[$j]==$array_campos[$n_campos])?true:false);	
				}
				if(!$iskey)
					echo "<option value='".$array_campos[$n_campos]."' selected>".$array_campos[$n_campos]."</option>";
			}
			echo "</select></td></tr>";
			echo "<tr style='background:#fff;color:#000' id='pk_field_space'><td>Set index field</td><td style='text-align:center'>automatically <input type='checkbox' id='set_pk_cb' name='set_pk_cb' checked title='Depend from your table structure'><div id='select_pk' style='padding:10px;'><select id='pk_field' name='pk_field' class='span2'>";
			for($n_campos=0;$n_campos<count($array_campos);$n_campos++){
				echo "<option value='".$array_campos[$n_campos]."'>".$array_campos[$n_campos]."</option>";
			}
			echo "</select></div></td></tr>";
			echo "</table>";
			echo "<input type='hidden' value='".$target_path."' name='archivo'>";
			echo "<input type='hidden' value='".$_POST['tabla']."' name='tabla'>";
			echo "<input type='hidden' value='".count($data)."' name='n_campos'>";
			echo "<input type='hidden' value='".$separator."' name='separator'>";
			echo "<div style='text-align:right; padding-top:15px'><input type='button' value='Insert data' id='b_insertar_data' name='b_insertar_data' class='btn btn-success'></div>";
			echo "</form>";
		} else{
			echo "There was an error uploading the file, please try again!";
		}
	}else{
		$sql = "It's possible the file is too large to be uploading with your actual php.ini upload configuration.";
			$sql .= " You upload_max_filesize=".ini_get('upload_max_filesize')." and post_max_size=".ini_get('post_max_size').".\n Both values have to be bigger than your file size.";
			$sql .= " You can change these values in the file php.ini in the PHP installation directory. After you will have to restart your web server and you can try to upload the file again.";
		echo $sql;
	}
?>
</div>
<div id="respuesta" class="result"></div>
</div>
<?php
	}else{
		echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
	}
?>
</div></body>
</html>