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

		$product_image = get_option("pick_pack_product_image_upload");
		$product_title = get_option("pick_pack_product_title");
		$product_description = get_option("pick_pack_product_text");

		$fragile = [];
		$large = [];

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
		if ($cart_count == 1 ){
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				# code...
				if ($cart_item['product_id'] == $product_id){
					$remove_eco_bag = true;
				}

			}
		}

		if (count($fragile) != 0 || count($large) != 0 || $remove_eco_bag){
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			     if ( $cart_item['product_id'] == $product_id ) {
			          WC()->cart->remove_cart_item( $cart_item_key );
			     }
			}
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/pick-pack-public-popup.php';
	}

	/**
	 * Add product into cart
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
		/*session_start();

		$product_id = $cart->cart_contents[ $cart_item_key ]['product_id'];
		if(!empty($_SESSION["pick_pack_product_added"]) && $_SESSION["pick_pack_product_added"] == $product_id ){
			unset($_SESSION['pick_pack_product_added']);
		}

		exit;*/
	}	
	//remove bag from query
	public function remove_bag_from_query($query){
		if ( ! is_admin() && $query->get('post_type') == "product") {
			
			$product = null;

			$product = get_page_by_title( 'Pick Pack', OBJECT, 'product' ); 


			if ( ! is_null( $product) ){
			    $query->set( 'post__not_in', array($product->ID) );
			}
			

		}
	}	

	//filter bag from related prodcts
	public function filter_bag_from_related_products($related_posts, $product_id, $args){

		$product = null;

		$product = get_page_by_title( 'Pick Pack', OBJECT, 'product' );

		$exclude_ids = [];

		if ( ! is_null( $product) ){

			$exclude_ids = array($product->ID);
		}
		return array_diff( $related_posts, $exclude_ids );
	}

	public function order_payment_complete($order_id){
		$order = wc_get_order( $order_id );

		file_put_contents(get_template_directory() . '/somefilename.txt', print_r($order->get_items(), true), FILE_APPEND);
	}

}


