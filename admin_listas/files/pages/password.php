<?php  session_start();  ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
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
<?php include("header.php"); ?>
<div id="menu">
	<h3>Change Password</h3>
	<div id="problem"></div>
	<table style="background-color: #ECFFB0; color:#000; border-spacing:0px; border:#FFF solid 2px; width:100%">
    <tr style="background:#000;color:#fff"><td>Actual user name:</td><td class="center"><input type="text" name="user" id="user" placeholder="actual user name" class="input-medium"/></td></tr>
    
    <tr style="background:#000;color:#fff"><td>Actual password:</td><td class="center"><input type="password" name="pass" id="pass" placeholder="actual password" class="input-medium"/></td></tr>
    
    <tr><td><span class="label label-info">New user name:</span></td><td class="center"><input type="text" name="nuser" id="nuser" placeholder="new user name" class="input-medium"/></td></tr>
    
    <tr><td><span class="label label-info">New password:</span></td><td class="center"><input type="password" name="npass" id="npass" placeholder="new password" class="input-medium"/></td></tr>
    
    <tr><td><span class="label label-info">Enter new password again:</span></td><td class="center"><input type="password" name="n2pass" id="n2pass" placeholder="new password again" class="input-medium"/></td></tr>
    </table><br/>
    
    <div class="right"><input type="button" value="Change Password" id="change" class="btn btn-success"/></div>
</div>
</div></body>
</html>