<?php  session_start();  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Back-up Options</title>
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
</head>
<body><div id="container">
	<?php if(isset($_SESSION['user'])){
          include("header.php"); ?>
    <div id="menu">
		<?php
			include("../config/config.php");
			include("../config/".$file.".php");
			switch($db){
				case "mysql":
					echo '<form action="../resources/exportar_db_mysql.php" method="post">';
				break;
				case "postgres":
					echo '<form action="../resources/exportar_db_pg.php" method="post">';
				break;	
				case "sqlserver":
					echo '<form action="../resources/exportar_db_sqlserver.php" method="post">';
				break;	
				case "sqlite":
					echo '<form action="../resources/exportar_db_sqlite.php" method="post">';
				break;
			}
        ?>
            <table style="background:#FFF; color:#000; border-radius:5px">
            <tr class="header"><th colspan="2">Backup Options</th><th>Tables</th></tr>
            <tr><td>Get only data</td><td><input type="radio" name="option" value="data"></td><td rowspan="3" ><?php include("../resources/tablas_varias.php");?></td></tr>
            <tr><td>Get only database structure</td><td><input type="radio" name="option" value="structure"></td></tr>
            <tr><td>Get it all</td><td><input type="radio" value="all" name="option" checked></td></tr>
            <tr><td></td><td></td><td class="right"><input type="submit" value="Get Backup" class="btn btn-success"></td></tr>
            </table>
        </form>
    </div> <!--End menu-->
    <?php
        }else{
            echo "<meta http-equiv='REFRESH' content='0,url=../../index.php'>";
        }
    ?>
</div></body>
</html>