<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!defined("ABSPATH")){
		define( 'WP_USE_THEMES', false );
		require_once('../../../wp-load.php');


	}
	wp_verify_nonce($_POST['_wpnonce'], 'my-nonce');

	$token = bin2hex(random_bytes(16));

	update_option('eco_bag_token', $token);

	$URL = 'http://localhost/plugin_server/index.php?eco_bag_token=' . $token;
	header('Location: '.$URL);
}
?>