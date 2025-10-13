<?php
//MOSTRAR ERRORES
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//MOSTRAR ERRORES

//NO MOSTRAR ERRORES
error_reporting(0);
ini_set('display_errors', 0);
//NO MOSTRAR ERRORES

// Para funciones propias
$cfg_servidor="localhost";
$cfg_usuario="mercury";
$cfg_password="mercury";
$cfg_basephp="mercury";

$conexion = new mysqli($cfg_servidor,$cfg_usuario,$cfg_password,$cfg_basephp);

if ( $conexion->connect_error ) 
{ 
    die('Error de Conexion'. $conexion->connect_error);
}
else
{
    //echo 'Conexion OK';
}
// Para funciones propias

// DATAGRIDS

//configuraciones para cada cliente
//$config["script_url"] = "https://www.ventason.com/mercury/datagrid/";
$config["script_url"] = "http://localhost/mercury/datagrid/";
$config["downloadURL"] = $config["script_url"] . "script/downloads/";
$config["database"] = "mercury";
$config["username"] = "mercury";
$config["password"] = "mercury";
$config["lang"] = "es";
$config["viewbtn"] = false;
$config["csvBtn"] = false;
$config["printBtn"] = false;
$config["excelBtn"] = false;
$config["pdfBtn"] = false;
$config["clonebtn"] = true;
//$pdocrud->setSettings("clonebtn", true);
$config["required"]= false;
$config["deleteMultipleBtn"]= false;
$config["checkboxCol"] = false;
//$config["characterset"] = "utf8";
//$config["inlineEditbtn"] = true;

// DATAGRIDS     

//SELECTS CON BUSQUEDA
// Declaramos las credenciales de conexion
$server = "localhost";
$username = "mercury";
$password = "mercury";
$dbname = "mercury";

// Creamos la conexion MySQL
try{
	//Creamos la variable de conexión $b
   $db = new PDO("mysql:host=$server;dbname=$dbname","$username","$password");
   $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
   die('Error: No se puede conectar a MySQL');
}
//SELECTS CON BUSQUEDA

// Para Listas PHPMYADMIN
$cfg_usuario_listas="mercury";
$cfg_password_listas="mercury";
// Para Listas PHPMYADMIN
?>