<?php
session_start ();
session_destroy();
header( "Location: login_v8/index.php" );
?>