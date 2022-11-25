<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       pick-pack.ca
 * @since      1.0.0
 *
 * @package    Pick_Pack
 * @subpackage Pick_Pack/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pick_Pack
 * @subpackage Pick_Pack/public
 * @author     Pick Pack <admin@pick-pack.ca>
 */
class Pick_Pack_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pick_Pack_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pick_Pack_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pick-pack-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pick_Pack_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pick_Pack_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js' , $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pick-pack-public.js', array( 'jquery' ), $this->version, false );
		$jsarray = array(
			'class_name' => 'single_add_to_cart_button',
			'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('_wpnonce')
			
		);
		wp_localize_script( $this->plugin_name, 'php_vars', $jsarray ); 
	}


	/**
	 * Add model
	 */
	public function pick_pack_add_model(){
		session_start();
		$product_id = get_option("pick_pack_product");

		/*$product_image = get_option("pick_pack_product_image_upload");
		$product_title = get_option("pick_pack_product_title");
		$product_description = get_option("pick_pack_product_text");*/

		$fragile = [];
		$large = [];
		//Find out if there is a fragile or large product
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		   
		   $product_id_2 = $cart_item['product_id'];

		   $terms = get_the_terms( $product_id_2, 'product_cat' );

		   if ($terms != false && !is_wp_error($terms)){

		   	foreach ($terms as $term) {
		   		if ($term->name == "Fragile Product"){
		   			$fragile[] = $cart_item['data']->name . ' is a fragile product';
		   		}

		   		if ($term->name == "Large Product"){
		   			$large[] = $cart_item['data']->name . ' is a large product';
		   		}
		   		
		   	}
		   
		   }
		}
		
		$remove_eco_bag = false;
		$cart_count = count(WC()->cart->get_cart());
		$fragile_count = count($fragile);
		$large_count = count($large);
		$pick_pack_count = 0;
		$points_allocated_less = false;

		if ($this->pick_pack_woo_in_cart($product_id)){
			$pick_pack_count++;
		}

		//If the only remaining product is eco bag
		if ($cart_count == 1 ){
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				# code...
				if ($cart_item['product_id'] == $product_id){
					$remove_eco_bag = true;
				}

			}
		}

		//if only large or fragile products are present
		if ($cart_count == ($fragile_count + $large_count + $pick_pack_count)){
			$remove_eco_bag = true;
		}

		$points = $this->get_eco_bag_quantity(WC()->cart, false);
		//points less than 5
		if (($fragile_count > 0 || $large_count > 0) && $points < 5){
			$remove_eco_bag = true;
			$points_allocated_less = true;
		}

		//Get the eco bag key
		$eco_bag_key = '';
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			# code...
			if ($cart_item['product_id'] == $product_id){
				$eco_bag_key = $cart_item_key; 
			}

		}

		if ($remove_eco_bag){
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			     if ( $cart_item['product_id'] == $product_id ) {
			          WC()->cart->remove_cart_item( $cart_item_key );
			          $cart_count--;
			     }
			}
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/pick-pack-public-popup.php';
	}

	/**
	 * Add product into cart. Handles the ajax request from the pop up modal
	 */
	public function pick_pack_add_to_cart_product_callback(){
		
		session_start();

		$nonce = check_ajax_referer( '_wpnonce', 'security');

		global $woocommerce;

		$product_id = get_option("pick_pack_product");

		$status = ["status"=>"false","product_add"=>"false"];

		if(!empty($product_id)){			

			$add = $woocommerce->cart->add_to_cart( $product_id,1 );
			
			if($add){
				$status["status"] = true;
				$status["product_add"] = true;
				$_SESSION["pick_pack_product_added"] = $product_id;
			}

		}

		echo json_encode($status,true);
		wp_die();

	}

	/**
	 * Check product in cart or not
	 */
	public function pick_pack_woo_in_cart($product_id) {
        global $woocommerce;         
        foreach($woocommerce->cart->get_cart() as $key => $val ) {
            $_product = $val['data'];
            if($product_id == $_product->id ) {
                return true;
            }
        }         
        return false;
	}

	/**
	 * When item remove from cart
	 */
	public function pick_pack_remove_item_from_cart($cart_item_key, $cart){

		if (is_checkout()){
			return;
		}
		session_start();
		$product_id = get_option("pick_pack_product");


		$line_item = $cart->removed_cart_contents[ $cart_item_key ];
		$product_id_temp = $line_item[ 'product_id' ];

		/*file_put_contents(get_template_directory() . '/somefilename.txt', print_r($product, true), FILE_APPEND);*/
		if ($product_id == $product_id_temp){
			
			if(!empty($_SESSION["pick_pack_product_added"]) && $_SESSION["pick_pack_product_added"] == $product_id ){
				/*file_put_contents(get_template_directory() . '/somefilename.txt', 'unset', FILE_APPEND);*/
				unset($_SESSION['pick_pack_product_added']);
			}
		}
		
		/*$product_id = $cart->cart_contents[ $cart_item_key ]['product_id'];*/
		
		

		
	}	
	//remove bag from query
	public function remove_bag_from_query($query){
		if ( ! is_admin() && $query->get('post_type') == "product") {
			
			$product = null;

			$product = get_page_by_title( 'Pick Pack', OBJECT, array('product') ); 


			if ( ! is_null( $product) ){
			    $query->set( 'post__not_in', array($product->ID) );
			}
			

		}
	}	

	//filter bag from related prodcts
	public function filter_bag_from_related_products($related_posts, $product_id, $args){

		$product = null;

		$product = get_page_by_title( 'Pick Pack', OBJECT, array('product'));

		$exclude_ids = [];

		if ( ! is_null( $product) ){

			$exclude_ids = array($product->ID);
		}
		return array_diff( $related_posts, $exclude_ids );
	}

	public function order_payment_complete($order_id){
		$order = wc_get_order( $order_id );

		foreach ($order->get_items() as $item_id => $item ){

			$item_data = $item->get_data();

			if ($item_data['name'] == "Pick Pack"){
				$eco_bag_quantity = $item_data['quantity'];

				$eco_bags_sold_array = get_option('eco_bags_sold', array());
				$eco_bag_price = get_option('eco_bag_price', 3);

				$eco_bags_sold_array[] = array('price' => $eco_bag_price, 'quantity' => $eco_bag_quantity);

				$option = update_option('eco_bags_sold', $eco_bags_sold_array);

				if ($option){
					$post_id = wp_insert_post(array(
						'post_type' => 'pickpackorders',
						'post_status' => 'publish',
						'post_title' => 'Order with a Pick Pack Bag',
						'meta_input' => array(
							'price' => $eco_bag_price,
							'quantity' => $eco_bag_quantity
						)
					));
				}

				$eco_bag_token = get_option('eco_bag_token');
				$request = new WP_Http();

				
				$body = array('eco_bags_sold' => $eco_bag_quantity, 'eco_bag_price' => $eco_bag_price, 'order_id' => $post_id, 'timestamp' => date('Y/m/d h:i:s', time()), 'eco_bag_token' => $eco_bag_token, 'url' => get_site_url());

				$url = SERVER_URL . 'dashboard/order_webhook.php';

				$response = $request->get($url, array('body' => $body));

				if (isset($response->errors)) {

				    return false;

				}

				if ($response['response']['code'] === 200) {
					
					return true;
				}	

				

			}
		}
		/*file_put_contents(get_template_directory() . '/somefilename.txt', print_r($order->get_items(), true), FILE_APPEND);*/
	}

	public function change_cart_item_quantities($cart) {
    
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // HERE below define your specific products IDs
    $specific_ids = array(get_option("pick_pack_product"));

    // Checking cart items
    foreach( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $product_id = $cart_item['data']->get_id();
        // Check for specific product IDs and change quantity
        if( in_array( $product_id, $specific_ids )){
        	$new_qty = $this->get_eco_bag_quantity($cart); // New quantity
            $cart->set_quantity( $cart_item_key, $new_qty ); // Change quantity
        }
    }

	}

	public function get_eco_bag_quantity($cart, $bags = true){

		$item_bags = 0;

		foreach( $cart->get_cart() as $cart_item_key => $cart_item ) {

			$skip = false;

		    $product_id = $cart_item['data']->get_id();

		    if (get_option("pick_pack_product") == $product_id){
		    	continue;
		    }
		    
		    $taxonomy = 'product_cat';
		    $categories = get_the_terms($product_id, 'product_cat');

		    foreach ($categories as $category) {
		    	if ($category->name == "Large Product" || $category->name == "Fragile Product"){
		    		$skip = true;
		    	}
		    }

		    if (count($categories) > 1 && !$skip){
		    	$category_selected = get_post_meta($product_id, 'category_selected', true);
		    	$categories[0] = get_term($category_selected);
		    }
		    /*var_dump($categories[0]);*/
		    if (!$skip){

		    	$product_per_bag = get_option('product_per_bag_' . $categories[0]->term_id, 1);

		    	$item_bags += $cart_item['quantity'] * $product_per_bag;
		    }
		    

		    /*if ($item_bags < 1){
		    	$remainder += $item_bags;
		    }*/
		    /*else{
		    	$wholesome_bags += floor($item_bags);
		    	$remainder += ($cart_item['quantity'] % $product_per_bag) / $product_per_bag;
		    }*/

		    	
		}

		$wholesome_bags = ceil($item_bags / 20);

		if ($bags){
			return $wholesome_bags;
		}
		else{
			return $item_bags;
		}
		

	}

	public function return_from_payment_method(){

		if (isset($_GET['token']) && isset($_GET['status'])){
			if (get_option('temp_eco_bag_token') == $_GET['token'] && $_GET['status'] == 'success'){

				update_option('eco_bag_token', $_GET['token']);
				/*delete_option('temp_eco_bag_token');*/

				exit( wp_redirect( get_dashboard_url() . 'admin.php?page=pick-pack&status=success' ));
				/*header('Location: '. get_dashboard_url() . 'admin.php?page=pick-pack&status=success');*/
			}
			else{
				/*header('Location: '. get_dashboard_url() . 'admin.php?page=pick-pack&status=failure');*/
				exit( wp_redirect( get_dashboard_url() . 'admin.php?page=pick-pack&status=failure' ));
			}
		}

	}

	public function country_option_checkout_page(){
		
		$product_id = get_option("pick_pack_product");
		session_start();

		/*file_put_contents(get_template_directory() . '/somefilename.txt', 'haris', FILE_APPEND);*/
		if (WC()->checkout->get_value('billing_country') !== 'CA'){
			/*file_put_contents(get_template_directory() . '/somefilename.txt', 'abbasi', FILE_APPEND);*/
			if($this->pick_pack_woo_in_cart($product_id)){
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			        if ( $cart_item['data']->get_id() == $product_id ) {

			        	/*remove_action( 'woocommerce_cart_item_removed', array( 'Pick_Pack_Public', 'pick_pack_remove_item_from_cart' ), 10 );*/
			        	WC()->cart->remove_cart_item( $cart_item_key );
			         
			     	}
				}
			}
		}
		else{
			/*file_put_contents(get_template_directory() . '/somefilename.txt', 'yassar', FILE_APPEND);*/
			if(!$this->pick_pack_woo_in_cart($product_id) && !empty($_SESSION["pick_pack_product_added"]) && $_SESSION["pick_pack_product_added"] === $product_id){
				/*file_put_contents(get_template_directory() . '/somefilename.txt', 'khan', FILE_APPEND);*/
				$add = WC()->cart->add_to_cart( $product_id,1 );
				if ($add){
					$new_qty = $this->get_eco_bag_quantity(WC()->cart); // New quantity

			    	foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			    	    // Check for specific product IDs and change quantity
			    	    if( $cart_item['product_id'] == $product_id ){
			    	        
			    	        WC()->cart->set_quantity( $cart_item_key, $new_qty ); // Change quantity

			    	    }
			    	    	
			    	 }
			    }
			}
		}

			/*wp_send_json_success( 'It works' );*/
	


			
	}

	public function curl_webhook_receive(){
		if (isset($_GET['request']) && $_GET['request'] === 'curl' && SERVER_URL . 'dashboard/update.php' === $_SERVER['HTTP_REFERER']){
			if (update_option('eco_bag_price', $_GET['price'])){

				$_product = wc_get_product( get_option('pick_pack_product') );

				if ($_product !== null && $_product !== false){

					$_product->set_regular_price( $_GET['price'] );
					$_product->save();
					echo 'success';
				}
				else{
					echo 'failure';
				}
				
			}
			else{
				echo 'failure';
			}
			exit;
		}
	}

	public function curl_eco_bag_orders(){
		if (isset($_GET['request']) && $_GET['request'] === 'curl' && $_GET['type'] === 'orders' && SERVER_URL . 'dashboard/cronjob/cronjob_realtime.php' === $_SERVER['HTTP_REFERER']){

			echo 'OK';
			
			exit;
			
		}
	}
	

}




