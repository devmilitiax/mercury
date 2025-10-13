<?php
	$key="a6PN-MChv=-B=qjM_b#px_#";
	
	function decrypt($encrypted) {
		global $key;
		list($encrypted_data, $iv) = explode('::', base64_decode($encrypted), 2);
		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
	}
	
	function encrypt($data) {
	  global $key;
	  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	  $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
	  return base64_encode($encrypted . '::' . $iv);
	}
?>