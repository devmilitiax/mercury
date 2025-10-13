<?php include('mysqli.inc.php'); ?>
 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="AdminLTE-2.4.3/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <!-- Status -->
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>

      <!-- search form (Optional) -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Buscar...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENUS</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="index.php"><i class="fa fa-home"></i> <span>Newsletters</span></a></li>
        <!--<li><a href="listas.php"><i class="fa fa-list-alt"></i> <span>Listas de Envio</span></a></li>-->
        <li class="treeview">
          <a href="#"><i class="fa fa-list-alt"></i> <span>Mailing Lists</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
		    <li><a href="admin_listas" target="_blank">Import/Export <br>PDO Multi-DB Info Manager</a></li>
            <li><a href="phpMyAdmin-5.2.1-all-languages/?pma_username=<?php echo $cfg_usuario_listas; ?>&pma_password=<?php echo $cfg_password_listas; ?>" target="_blank">Import/Export phpMyAdmin</a></li> --
            <!-- <li><a href="#">Administrar</a></li> -->
          </ul>
        </li>
		<li><a href="bulk.php"><i class="fa fa-rocket"></i> <span>Mercury Launcher</span></a></li>
        <!-- <li class="treeview">
          <a href="#"><i class="fa fa-line-chart"></i> <span>Estadisticas/Reportes</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Link in level 2</a></li>
            <li><a href="#">Link in level 2</a></li>
          </ul>
        </li> -->
		<!--<li><a href="nube_free.php"><i class="fa fa-cloud"></i> <span>Nube Free</span></a></li>-->
		<li><a href="smtp.php"><i class="fa fa-envelope"></i> <span>SMTP</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>