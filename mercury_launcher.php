<?php
session_start();

if(!session_id()) session_start();
if(!session_id()) die("Fallo iniciando sesion");
if( !isset($_SESSION["admin"]) ){
	die("Variables de sesion invalidas.");
}

if( trim($_SESSION["admin"])=="" ){
	die("Variables de sesion invalidas.");
}
?>
<?php 
require_once('mysqli.inc.php');
$id_editor = $_GET["id_editor"];

$sqlSelect ="SELECT * from editores where id='".$id_editor."'";
$conexion=new mysqli($cfg_servidor,$cfg_usuario,$cfg_password,$cfg_basephp1);
mysqli_set_charset($conexion,"utf8");
if ($resultado = $conexion->query($sqlSelect)) {
  $row =$resultado->fetch_assoc();  
}

$body = $row['contenido_grapesjs'];
$email = $row['email'];
$asunto = $row['asunto'];
$indice = $row['indice'];
$filtro = $row['filtro'];
?>
<?php
/**
 * This example shows how to send a message to a whole list of recipients efficiently.
 */
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
error_reporting(E_STRICT | E_ALL);
date_default_timezone_set('Etc/UTC');

require '../PHPMailer-6.0.5/src/PHPMailer.php';
require '../PHPMailer-6.0.5/src/Exception.php';
require '../PHPMailer-6.0.5/src/SMTP.php';

$mail = new PHPMailer;
$body = file_get_contents('contents.html');
$mail->isSMTP();
$mail->Host = 'smtp.mandrillapp.com';
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
$mail->Port = 587;
$mail->CharSet = 'UTF-8';

$mail->Username = 'graphixx';
$mail->Password = 'UAPGLYrfJI3Jrc1qRd8BMw';
$mail->setFrom('masivo@mercurylauncher.com', 'Mercury Launcher');

$mail->addReplyTo($email);
$mail->Subject = $asunto;

//Same body for all messages, so set this before the sending loop
//If you generate a different body for each recipient (e.g. you're using a templating system),
//set it inside the loop
$mail->msgHTML($body);
//msgHTML also sets AltBody, but if you want a custom one, set it afterwards
$mail->AltBody = 'Para ver este mensaje, por favor use un lector de emails compatible con HTML!';
//Connect to the database and select the recipients from your mailing list that have not yet been sent to
//You'll need to alter this to match your database

$conexion2 = new mysqli($cfg_servidor,$cfg_usuario,$cfg_password,$cfg_basephp2);
$indices = $indice + 1800; //Seleccionamos solo 1800 emails de la lista total cada hora
$result = mysqli_query($conexion2, 'SELECT email FROM listas WHERE filtro='.$filtro.' AND id >= '.$indice.' AND id <= '.$indices);

$lista=1; //contador de 30 cuentas de correo
$bandera=1; //contador de 60 mensajes por cuenta de correo

foreach ($result as $row) {
    $mail->addAddress($row['email']);

	if($bandera<=60){}else{
		//$mail->Username = 'masivo'.$lista.'@sistemasycontroles.net'; //cambia de correo cada 60 mensajes
		//$mail->setFrom('masivo'.$lista.'@sistemasycontroles.net', 'Mercury Launcher');
		$mail->setFrom('masivo@mercurylauncher','Mercury Launcher');
		//$lista=$lista+1;
		$bandera=1;
	}
    if (!$mail->send()) {
        echo "Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '<br />';
        break; //Abandon sending
    } else {
        echo "Message sent to :" . ' (' . str_replace("@", "&#64;", $row['email']) . ')<br />';
		$bandera = $bandera + 1;
    }
    // Clear all addresses and attachments for next loop
    $mail->clearAddresses();
}

$result = mysqli_query($conexion2, 'UPDATE editores SET indice='.$indices.' WHERE id='.$id_editor);
?>