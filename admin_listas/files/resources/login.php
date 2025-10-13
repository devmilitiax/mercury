<?php
	include_once("program_file.php");
	include_once("../config/config.php");
	include_once("../config/users.php");
	$usuario=$_REQUEST['usuario'];
	$contra=$_REQUEST['contra'];
	$tiempo=0;
	if(crypt(md5(sha1($usuario)),"mhfsnucg")==$a){
		if(crypt(md5(sha1($contra)),"mhfsnucg")==$b){
			session_start();
                        $_SESSION['username']=$usuario;
                        $_SESSION['admin']=true;
			echo "true";
		}
        }else{
            if(isset($users[$usuario]) && $users[$usuario]==$contra){
                session_start();
                $_SESSION['username']=$usuario;
                $_SESSION['admin']=false;
		echo "true";
            }
        }
?>