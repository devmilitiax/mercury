<?php @session_start();?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Menu</title>
    <link rel="stylesheet" href="styles/style.css" type="text/css">
    <link href="styles/bootstrap.css" rel="stylesheet" media="screen">
    <link href="styles/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link rel="shortcut icon" href="images/DB ico.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/DB ico.png" type="image/png">
    <script src="scripts/jquery.js" type="text/javascript"></script>
    <script src="scripts/script.js" type="text/javascript"></script>
    <script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/bootstrap-dropdown.js"></script>
    <script src="scripts/bootstrap-collapse.js"></script>
</head>
<body>
<div id="container">
	<?php	
        include("config/config.php");
        if(isset($_SESSION['user'])){
            if(isset($file)){
    ?>
    <div id="header">
    <div id="over_header"><a href=""><img src="images/DB Logo.jpg" /></a></div>
        <div class="navbar">
        	<div class="navbar-inner">
            	<div class="container">
                    <a class="brand" href="">Menu</a>      
                    <ul class="nav" role="navigation">
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop1" class="dropdown-toggle" data-toggle="dropdown" href="" data-toggle="dropdown"><i class="icon-upload"></i> Import <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="pages/insertar_csv.php"><i class="icon-file"></i> From a CSV file</a></li>
                          <li><a href="pages/insertar_excel.php"><i class="icon-file"></i> From a SQL file</a></li>
                          <li><a href="pages/insertar_sql.php"><i class="icon-file"></i> From a Excel file</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop2" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-download-alt"></i> Export <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="pages/exportar_info.php?archivo=csv"><i class="icon-file"></i> To a CSV file</a></li>
                          <li><a href="pages/exportar_info.php?archivo=sql"><i class="icon-file"></i> To a SQL file</a></li>
                          <li><a href="pages/exportar_info.php?archivo=excel"><i class="icon-file"></i> To a Excel file</a></li>
                          <li><a href="pages/exportar_info.php?archivo=pdf"><i class="icon-file"></i> To a PDF file</a></li>
                          <li><a href="pages/exportar_info.php?archivo=xml"><i class="icon-file"></i> To a XML file</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop3" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-retweet"></i> Backups <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="pages/backup_option.php"><i class="icon-lock"></i> Create Backup</a></li>
                          <li><a href="pages/insertar_sql.php"><i class="icon-folder-open"></i> Restore Backup</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop4" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Queries <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="pages/consultar_info.php"><i class="icon-th-large"></i> Simple</a></li>
                          <li><a href="pages/consultar_info_avanzada.php"><i class="icon-th-list"></i> Advanced</a></li>
                          <li><a href="pages/join_tables.php"><i class="icon-random"></i> Join Tables</a></li>
						  <li><a href="pages/ejecutar_queries.php"><i class="icon-edit"></i> Custom SQL</a></li>
						  <li><a href="pages/consultar_list.php"><i class="icon-book"></i> Saved Queries</a></li>
                        </ul>
                      </li>
                    </ul>
                    <ul class="nav pull-right">
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop5" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Options <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="pages/configuracion.php"><i class="icon-tasks"></i> DB Options</a></li>
                          <li><a href="pages/other_options.php"><i class="icon-pencil"></i> Other Options</a></li>
                          <li><a href="documentation/documentation.html"><i class="icon-book"></i> Documentation</a></li>
                          <li><a href="pages/password.php"><i class="icon-user"></i> Change Password</a></li>
                          <li role="presentation" class="divider"></li>
                          <li><a href="resources/logout.php"><i class="icon-remove"></i> Logout</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                    </ul>
            	</div> <!--End class container -->
        	</div> <!--End navbar-inner-->
        </div> <!--End navbar-->
    </div> <!--End header-->
	<div id="menu">
        <div  class="m_">
            <div id="b_insertar" class="boton">Import Data</div>
            <div id="m_insertar">
                <ul>
                    <li><a href="pages/insertar_csv.php"><img src="images/document_index.png"/><br/>From a CSV file</a></li>
                    <li><a href="pages/insertar_excel.php"><img src="images/file_extension_xls.png"/><br/>From a Excel file</a></li>
                    <li><a href="pages/insertar_sql.php"><img src="images/database.png"/><br/>From a SQL file</a></li>
                </ul>
            </div>
            <div id="b_exportar" class="boton">Export Data</div>
            <div id="m_exportar">
                <div><ul>
                    <li><a href="pages/exportar_info.php?archivo=csv"><img src="images/document_index.png"/><br/>To a CSV file</a></li>
                    <li><a href="pages/exportar_info.php?archivo=excel"><img src="images/file_extension_xls.png"/><br/>To a Excel file</a></li>
                    <li><a href="pages/exportar_info.php?archivo=sql"><img src="images/database.png"/><br/>To a SQL file</a></li>
                </ul></div>
                <div><ul><li><a href="pages/exportar_info.php?archivo=pdf"><img src="images/file_extension_pdf.png"/><br/>To a PDF file</a></li>
                    <li><a href="pages/exportar_info.php?archivo=xml"><img src="images/file_extension_xlm.png"/><br/>To a XML file</a></li>
                </ul></div>
            </div>
            <div id="b_consult" class="boton">Consult Data</div>
            <div id="m_consult">
                <div><ul>
                    <li><a href="pages/consultar_info.php"><img src="images/database_table.png"/><br/>Consult Info from a Table</a></li>
                    <li><a href="pages/consultar_info_avanzada.php"><img src="images/form.png"/><br/>Consult Info from a Table(Advanced)</a></li>
                   	<li><a href="pages/join_tables.php"><img src="images/sql_join_outer_exclude.png"/><br/>Consult Join Tables</a></li>
                </ul></div>
                <div><ul>
                	<li><a href="pages/consultar_list.php"><img src="images/backup_manager.png"/></br>Consult Saved Queries</a></li>
                    <li><a href="pages/ejecutar_queries.php"><img src="images/sql.png"/></br>Custom SQL Query Execute</a></li>
				</ul></div>
            </div>
            <div id="b_backup" class="boton">Backup Database</div>
            <div id="m_backup">
                <ul>
                    <li><a href="pages/backup_option.php"><img src="images/database_save.png"/><br/>Create a Database Backup</a></li>
                    <li><a href="pages/insertar_sql.php"><img src="images/database_add.png"/><br/>Restore a Database Backup</a></li>
                </ul>
            </div>
            <div id="b_option" class="boton">Options</div>
            <div id="m_option">
                <div><ul>
                     <li><a href="pages/configuracion.php"><img src="images/database_gear.png"/><br/>DB Options</a></li>
                     <li><a href="pages/other_options.php"><img src="images/google_webmaster_tools.png"/><br/>Others Options</a></li>
                     <li><a href="documentation/documentation.html"><img src="images/document_properties.png"/><br/>Documentation</a></li>
                </ul></div>
                <div><ul>
                	 <li><a href="pages/password.php"><img src="images/change_password.png"/><br/>Change Password</a></li>
                     <li><a href="resources/logout.php"><img src="images/cancel.png"/><br/>Logout</a></li>
                </ul></div>
            </div>
        </div>
    </div> <!--End Menu-->
    <script type="text/javascript">
		$('.dropdown-toggle').dropdown()
	</script>
	<?php
            }else{
                echo "<meta http-equiv='REFRESH' content='0,url=pages/configuracion.php'>";
            }
        }else{
            echo "<meta http-equiv='REFRESH' content='0,url=../index.php'>";
        }
    ?>
</div></body>
</html>