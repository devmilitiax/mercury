<?php
error_reporting(0);

session_start();
include 'csrf.class.php'; //ANTI CSRF
require_once 'class.inputfilter.php'; //ANTI XSS
include '../mysqli.inc.php';

$filtro = new InputFilter();
$csrf = new csrf();

// Genera un identificador y lo valida
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
 
// Genera nombres aleatorios para el formulario
$form_names = $csrf->form_names(array('user', 'password'), false);
 
if(isset($_POST[$form_names['user']], $_POST[$form_names['password']])) {
        // Revisa si el identificador y su valor son válidos.
        if($csrf->check_valid('post')) {
                // Get the Form Variables.
                $user = $filtro->process($_POST[$form_names['user']]);
                $password = md5($_POST[$form_names['password']]);
				
				$connect=mysqli_connect($cfg_servidor,$cfg_usuario,$cfg_password,$cfg_basephp);
				$ssql = "SELECT * FROM administrator WHERE username='$user' and pass='$password'";
				$rs = mysqli_query($connect,$ssql); 
 
				if (mysqli_num_rows($rs)!=0){
					//usuario y contraseña válidos
					//defino una sesion y guardo datos
					$row=mysqli_fetch_array($rs,MYSQLI_ASSOC);
					session_start();
					$_SESSION["admin"]=$row['username']; 
					//header ("Location: ../index.php");
					print("<script>window.location.replace('../index.php');</script>"); 
				}else {
					//si no existe le mando otra vez a la portada
					$mensaje="invalid password";
					print "<script>alert('$mensaje')</script>";
					print("<script>window.location.replace('index.php');</script>"); 
					//header("Location: index.php");
				} 
				mysqli_free_result($rs);
				mysqli_close($connect); 
        }
        // Regenera un valor aleatorio nuevo para el formulario.
        $form_names = $csrf->form_names(array('user', 'password'), true);
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form p-l-55 p-r-55 p-t-178" action="index.php" method="post">
				<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
					<span class="login100-form-title">
						Sign in
					</span>

					<div class="wrap-input100 validate-input m-b-16" data-validate="Please enter a username">
						<input class="input100 validate[required,custom[email]] feedback-input" type="text" name="<?= $form_names['user']; ?>" placeholder="Username">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Please enter a password">
						<input class="input100 validate[required,length[6,300]] feedback-input" type="password" name="<?= $form_names['password']; ?>" placeholder="Password">
						<span class="focus-input100"></span>
					</div>
					<br>
					<!--<div class="text-right p-t-13 p-b-23">
						<span class="txt1">
							Olvidaste
						</span>

						<a href="#" class="txt2">
							Usuario / Contraseña?
						</a>
					</div>-->
					<br>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Enter
						</button>
					</div>
					<br><br>
					<!--<div class="flex-col-c p-t-70 p-b-40">
						<span class="txt1 p-b-9">
							No tienes una cuenta?
						</span>

						<a href="https://www.mercurylauncher.com" class="txt3">
							Registrate
						</a>
					</div>-->
				</form>
			</div>
		</div>
	</div>
	
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>