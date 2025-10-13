<?php
session_start();

if(!session_id()) session_start();
if(!session_id()) die("Fallo iniciando sesion");
if( !isset($_SESSION["admin"]) ){
	die("Invalid session variables.");
}

if( trim($_SESSION["admin"])=="" ){
	die("Invalid session variables.");
}
?>
<?php
require_once('../mysqli.inc.php');

$conexion = mysqli_connect($cfg_servidor,$cfg_usuario,$cfg_password,$cfg_basephp);
mysqli_set_charset($conexion,"utf8");

$id = $_POST['id_editor'];
$valor = base64_encode($_POST['campa']);
$valor2 = base64_encode($_POST['email']);

$sql = "UPDATE newsletters SET content_grapesjs='".$valor."', content_html='".$valor2."' WHERE id=".$id;

if (mysqli_query($conexion,$sql)) {	
	$datos["id"]=$conexion->insert_id;
	echo "<h1>Data Saved Successfully.</h1>";
}
else{  
	echo "<h1>Error saving data.</h1>".mysqli_error($conexion);   
    exit();
	}
$conexion->close();
?>