<?php @session_start(); ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="stylesheet" href="files/styles/style.css" type="text/css">
	<link href="files/styles/bootstrap.css" rel="stylesheet" media="screen">
    <link href="files/styles/bootstrap-responsive.css" rel="stylesheet" media="screen">
	<link rel="shortcut icon" href="files/images/DB ico.ico" type="image/x-icon">
    <link rel="shortcut icon" href="files/images/DB ico.png" type="image/png">
	<script src="files/scripts/jquery.js" type="text/javascript"></script>
	<script src="files/scripts/script.js" type="text/javascript"></script>
	<script src="files/scripts/bootstrap.min.js"></script>
	<script src="files/scripts/bootstrap-dropdown.js"></script>
    <script src="files/scripts/bootstrap-collapse.js"></script>
    <script src="files/scripts/bootstrap-popover.js"></script>
    <script src="files/scripts/bootstrap-alert.js"></script>
    <script src="files/scripts/bootstrap-modal.js"></script>
	<?php include_once("files/config/config.php")?>
</head>
<body><div id="container" class="form-inline">
<?php 
	if(isset($_SESSION['user'])){ 
		echo "<meta http-equiv='REFRESH' content='0,url=files/main.php'>";
	}else{
?>
    <div id="header">
    <div id="over_header"><img src="files/images/DB Logo.jpg" /></div>
    <div id="in_header">
            <div class="navbar">
        	<div class="navbar-inner">
            	<div class="container">
                    <ul class="nav pull-right">
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop5" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Options <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="files/documentation/documentation.html"><i class="icon-book"></i> Documentation</a></li>
                          <li><a href="files/pages/password.php"><i class="icon-user"></i> Change Password</a></li>
                          <li role="presentation" class="divider"></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                    </ul>
            	</div> <!--End class container -->
        	</div> <!--End navbar-inner-->
        </div> <!--End navbar-->
    	</div>
    </div>
    <div id='login'>
    <form action="files/resources/login.php" method="get" id="form_login">
    <table>
    	<tr><td colspan="2"><h2>Log-in</h2></td></tr>
    	<tr><td>DB User: </td><td><input type="text" id="usuario_ini" name="usuario" class="input-medium" required/></td></tr>
        <tr><td>DB Password: </td><td><input type="password" id="contra_ini" name="contra" class="input-medium" required/></td></tr>
        <tr><td>Keep session open: </td><td style="text-align:center"><input type="checkbox" name="abierta" id="abierta"></td></tr>
        <tr><td colspan="2" style="text-align:right"><input type="button" value="enter" id="entrar" class='btn btn-success'></td></tr>
    </table>
    </form>
    </div>
<?php
	}
?>
</div>
<script type="text/javascript">	$('.dropdown-toggle').dropdown() </script>
</body>
</html>