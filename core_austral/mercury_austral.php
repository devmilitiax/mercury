<?php
//tiempo de ejecucion infinito
ini_set('max_execution_time', 0);
set_time_limit(0);

include('../mysqli.inc.php');
//require_once 'vendor/autoload.php';

$asunto  = $_POST["subject"];
$lista = $_POST["SearchRecipients"];
$body  = $_POST["SearchAdvertising2"];

//PHP Mailer

/**
 * This example shows making an SMTP connection with authentication.
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//Load Composer's autoloader
//require 'vendor/autoload.php';
require 'PHPMailer-6.2.0/src/PHPMailer.php';
require 'PHPMailer-6.2.0/src/Exception.php';
require 'PHPMailer-6.2.0/src/SMTP.php';

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
//date_default_timezone_set('Etc/UTC');
date_default_timezone_set('America/Bogota');

//OBTENER DATOS DEL SMTP DESDE LA BD
$consulta = "SELECT * FROM smtp ORDER BY id LIMIT 1";
$resultado = $conexion->query($consulta);
if($consulta->errno) die($consulta->error);
//Guardamos el registro en la variable $fila
$fila = $resultado->fetch_assoc();
//El resultado de la consulta estarán en nombre y apellido, entonces:
$hostname = $fila['hostname'];
$username = $fila['username'];
$password = $fila['password'];
$port = $fila['port'];
$from_email = $fila['from_email'];
$from_name = $fila['from_name'];
$altbody = $fila['altbody'];
//OBTENER DATOS DEL SMTP DESDE LA BD

//OBTENER DATOS DEL BODY DEL MENSAJE (NEWSLETTER)
$consulta = "SELECT * FROM newsletters WHERE id='$body' ORDER BY id LIMIT 1";
$resultado = $conexion->query($consulta);
if($consulta->errno) die($consulta->error);
//Guardamos el registro en la variable $fila
$fila = $resultado->fetch_assoc();
//El resultado de la consulta estarán en nombre y apellido, entonces:
//$body_full = $fila['contenido_grapesjs'];
$body_full = base64_decode($fila['contenido_html']);

//MJML
/* $apiId = 'c889a7b3-2a9e-4eb6-ad08-6f4b74c1ef73';
$secretKey = 'fbab8c3f-88bc-48bb-a9b1-0662e85e4926';

$api = new \Qferrer\Mjml\Http\CurlApi($apiId, $secretKey);
$renderer = new \Qferrer\Mjml\Renderer\ApiRenderer($api);

$html = $renderer->render($body_full2);
$body_full = $html; */
//MJML

//echo $body_full;
//OBTENER DATOS DEL BODY DEL MENSAJE (NEWSLETTER)

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_OFF;
//Set the hostname of the mail server
$mail->Host = $hostname;
//$mail->Host = 'smtp25.elasticemail.com';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//$mail->SMTPSecure = 'tls';  // Enable TLS encryption, `ssl` also accepted
//Username to use for SMTP authentication
$mail->Username = $username;
//Password to use for SMTP authentication
$mail->Password = $password; //ELASTIC EMAILS
//Set who the message is to be sent from
$mail->setFrom($from_email, $from_name);
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//tildes y ñ
$mail->addCustomHeader('charset=UTF-8');
$mail->CharSet = 'UTF-8';

// PHP Mailer

$enlace = mysqli_connect($cfg_servidor, $cfg_usuario, $cfg_password, $cfg_basephp);
$consulta = "SELECT email FROM $lista ORDER by id ASC";

if ($resultado = mysqli_query($enlace, $consulta)) {

$cont = 1;

/* obtener el array asociativo */
while ($fila = mysqli_fetch_assoc($resultado)) {

//PHPMAILER

//Set who the message is to be sent to
//$mail->addAddress('whoto@example.com', 'John Doe');
$mail->addAddress($fila['email'], 'Cliente');
//Set the subject line
$mail->Subject = $asunto;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('newsletter.html'), __DIR__);
//$mail->isHTML(true);

$mail->isHTML(true); 
$mail->MsgHTML($body_full);

//Replace the plain text body with one created manually
$mail->AltBody = $asunto;

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
//PHPMAILER
		
		echo "<font color='green'><br> $cont .<strong>Campaña enviada a: </strong>".$fila['email']."</font></br>";
        $mail->ClearAllRecipients();
		$cont = $cont + 1;
    }

    /* liberar el conjunto de resultados */
    mysqli_free_result($resultado);
}

/* cerrar la conexión */
mysqli_close($enlace);