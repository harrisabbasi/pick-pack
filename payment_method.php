<?php
if(!empty($_SERVER['HTTP_REFERER'])){
	if(!defined("ABSPATH")){
		define( 'WP_USE_THEMES', false );
		require_once('C:/wamp64/www/relearning_wp/wp-load.php');


	}

	$token = bin2hex(random_bytes(16));
	if (!function_exists('update_option')){
		exit;
	}
	update_option('eco_bag_token', $token);

	$URL = 'http://localhost/plugin_server/index.php?eco_bag_token=' . $token;
	header('Location: '.$URL);
}
?>