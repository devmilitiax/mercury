<?php  @session_start();  ?>
<div id="header">
	<div id="over_header"><a href="../main.php"><img src="../images/DB Logo.jpg" /></a></div>
	<div id="in_header">
	<?php if(isset($_SESSION['user'])){ ?>
		<div class="navbar">
        	<div class="navbar-inner">
            	<div class="container">
                    <a class="brand" href="../main.php">Menu</a>      
                    <ul class="nav" role="navigation">
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop1" class="dropdown-toggle" data-toggle="dropdown" href="" data-toggle="dropdown"><i class="icon-upload"></i> Import <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="insertar_csv.php"><i class="icon-file"></i> From a CSV file</a></li>
                          <li><a href="insertar_sql.php"><i class="icon-file"></i> From a SQL file</a></li>
                          <li><a href="insertar_excel.php"><i class="icon-file"></i> From a Excel file</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop2" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-download-alt"></i> Export <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="exportar_info.php?archivo=csv"><i class="icon-file"></i> To a CSV file</a></li>
                          <li><a href="exportar_info.php?archivo=sql"><i class="icon-file"></i> To a SQL file</a></li>
                          <li><a href="exportar_info.php?archivo=excel"><i class="icon-file"></i> To a Excel file</a></li>
                          <li><a href="exportar_info.php?archivo=pdf"><i class="icon-file"></i> To a PDF file</a></li>
                          <li><a href="exportar_info.php?archivo=xml"><i class="icon-file"></i> To a XML file</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop3" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-retweet"></i> Backups <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="backup_option.php"><i class="icon-lock"></i> Create Backup</a></li>
                          <li><a href="insertar_sql.php"><i class="icon-folder-open"></i> Restore Backup</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop4" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Queries <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="consultar_info.php"><i class="icon-th-large"></i> Simple</a></li>
                          <li><a href="consultar_info_avanzada.php"><i class="icon-th-list"></i> Advanced</a></li>
						  <li><a href="join_tables.php"><i class="icon-random"></i> Join Tables</a></li>
                          <li><a href="ejecutar_queries.php"><i class="icon-edit"></i> Custom SQL</a></li>
						  <li><a href="consultar_list.php"><i class="icon-book"></i> Saved Queries</a></li>
                        </ul>
                      </li>
                    </ul>
                    <ul class="nav pull-right">
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop5" href="" role="button" class="dropdown-toggle" data-toggle="dropdown"><strong style="margin-right: 10px; font-size: 18px;" class="label label-info"><?php echo $_SESSION["username"];?></strong> <i class="icon-wrench"></i> Options <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="configuracion.php"><i class="icon-tasks"></i> DB Options</a></li>
                          <li><a href="other_options.php"><i class="icon-pencil"></i> Other Options</a></li>
                          <li><a href="../documentation/documentation.html"><i class="icon-book"></i> Documentation</a></li>
                          <?php if($_SESSION['admin']) {?><li><a href="password.php"><i class="icon-user"></i> Change Password</a></li><?php } ?>
                          <li role="presentation" class="divider"></li>
                          <li><a href="../resources/logout.php"><i class="icon-remove"></i> Logout</a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                    </ul>
            	</div> <!--End class container -->
        	</div> <!--End navbar-inner-->
        </div> <!--End navbar-->
    <?php }else{ ?>
        <div class="navbar">
        	<div class="navbar-inner">
            	<div class="container">
                    <a class="brand" href="">Menu</a>      
                    <ul class="nav pull-right">
                      <li class="divider-vertical"></li>
                      <li class="dropdown"><a id="drop5" href="" role="button" class="dropdown-toggle" data-toggle="dropdown">Options <b class="caret"></b></a>
                        <ul role="menu" class="dropdown-menu">
                          <li><a href="../documentation/documentation.html"><i class="icon-book"></i> Documentation</a></li>
                          <li><a href="password.php"><i class="icon-user"></i> Change Password</a></li>
                          <li role="presentation" class="divider"></li>
                          <li><a href="../../index.php"><i class="icon-user"></i> Login </i></a></li>
                        </ul>
                      </li>
                      <li class="divider-vertical"></li>
                    </ul>
            	</div> <!--End class container -->
        	</div> <!--End navbar-inner-->
        </div> <!--End navbar-->
    <?php } ?>
    </div> <!--ENd in_header-->
</div><!--End header-->
<script type="text/javascript">
		$('.dropdown-toggle').dropdown()
</script>