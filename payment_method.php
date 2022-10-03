<?php

if(!defined("ABSPATH")){
	define( 'WP_USE_THEMES', false );
	require_once('../../../wp-load.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	wp_verify_nonce($_POST['_wpnonce'], 'my-nonce');

	$token = bin2hex(random_bytes(16));

	update_option('temp_eco_bag_token', $token);

	$return_url = plugins_url('/pick-pack/payment_method.php');

	$URL = 'http://localhost/plugin_server/index.php?eco_bag_token=' . $token . '&return_url=' . urlencode($return_url);
	header('Location: '. $URL);
}
else{
	if (get_option('temp_eco_bag_token') == $_GET['token'] && $_GET['status'] == 'success'){
		update_option('eco_bag_token', $_GET['token']);
		delete_option('temp_eco_bag_token');
		header('Location: '. get_dashboard_url() . 'admin.php?page=pick-pack&status=success');
	}
	else{
		header('Location: '. get_dashboard_url() . 'admin.php?page=pick-pack&status=failure');
	}
}
?>