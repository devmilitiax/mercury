<?php
	$destination_path = "../sqlite/";
	 
	$result = 0;
	
	if(!is_dir($destination_path)){
		mkdir($destination_path);
	}
	 
	$target_path = $destination_path . basename( $_FILES['upload']['name']);
	 
	if(@move_uploaded_file($_FILES['upload']['tmp_name'], $target_path)) {
		$result = 1;
	}
	 
	sleep(1);
?>