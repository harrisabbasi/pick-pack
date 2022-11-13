<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       pick-pack.ca
 * @since      1.0.0
 *
 * @package    Pick_Pack
 * @subpackage Pick_Pack/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pick_Pack
 * @subpackage Pick_Pack/admin
 * @author     Pick Pack <admin@pick-pack.ca>
 */
class Pick_Pack_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pick-pack-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name."-font-icon", 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name."-bootstrap-css", 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_media();
		/*wp_enqueue_script( $this->plugin_name.'-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js' , $this->version, false );*/
		wp_enqueue_script( $this->plugin_name."-bootstrap-js-2", 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pick-pack-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Check If woocommerce plugin is active or not
	 */
	public function pick_pack_check_woocommerce_is_active(){

		if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Get Graphina animation lite version basename
        $basename = '';
        $plugins = get_plugins();
    
        foreach ($plugins as $key => $data) {
            if ($data['TextDomain'] === "woocommerce") {
                $basename = $key;
            }
        }


		if (!is_plugin_active($basename)){

			$plugin = $basename;

            $activation_url = esc_url(wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin));

            $message = '<strong>'.esc_html__(' Pick Pack ','pick-pack').'</strong>'. esc_html__(' requires ','pick-pack'). '<strong>'.esc_html__(' WooCommerce').'</strong>'. esc_html__(' plugin to be active. Please activate WooCommerce for Pick Pack to continue.', 'pick-pack');

            $button_text = esc_html__(' Activate WooCommerce ', 'pick-pack');

            $button = "<p><a href='{$activation_url}' class='button-primary'>{$button_text}</a></p>";

            printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);

			if (isset($_GET['activate'])) unset($_GET['activate']);
			deactivate_plugins( PICK_PACK_ROOT );
		}else{

			$this->pick_pack_create_product();

		}		
	}

	/**
	 * Create Pick Pack Product
	 */
	public function pick_pack_create_product(){

		$id = get_option('pick_pack_product');
		$eco_bag_price = get_option('eco_bag_price');
		
		if(empty($id)){

			$post_args = array(
				'post_title' => esc_html__('Pick Pack','pick-pack'), 
				'post_type' => 'product',
				'post_status' => 'publish' 
			);
		
			$post_id = wp_insert_post( $post_args );
		   
			if(! empty( $post_id )){
	
				update_option("pick_pack_product",$post_id);

				if (empty($eco_bag_price)){
					$eco_bag_price = 3;
				}
	
				if ( function_exists( 'wc_get_product' ) ) {
					$product = wc_get_product( $post_id );
					$product->set_sku( 'pick-pack-' . $post_id );
					$product->set_regular_price( $eco_bag_price );
					$product->save();
				}
			}
		}
		
	}

	/**
	 * Add Admin menu
	 */
	public function pick_pack_add_admin_menu(){
		add_menu_page(
			__( 'Pick Pack', 'textdomain' ),
			__( 'Pick Pack','textdomain' ),
			'manage_options',
			'pick-pack',
			array($this,'pick_pack_package_admin_menu_function'),
			plugins_url( 'pick-pack/assets/images/pick-pack-logo.png' )
		);

		add_submenu_page( 'pick-pack', 'Pick Pack Orders', 'Orders', 'manage_options','edit.php?post_type=pickpackorders');
	}

	public function pick_pack_package_admin_menu_function() {


		if($_SERVER["REQUEST_METHOD"]=="POST"){
			/*if(isset($_POST["pick_pack_product_image_upload"]) && isset($_POST["pick_pack_product_title"]) && isset($_POST["pick_pack_product_text"])){*/
				/*update_option("pick_pack_product_image_upload", $_POST["pick_pack_product_image_upload"]);
				update_option("pick_pack_product_title", $_POST["pick_pack_product_title"]);
				update_option("pick_pack_product_text", $_POST["pick_pack_product_text"]);*/

			$taxonomy = 'product_cat';
			$categories_2 = get_categories(array('taxonomy' => $taxonomy, 'hide_empty' => false));
			
			//Remove fragile and large from list
			foreach( $categories_2 as $key => $category ) {
			
				if ($category->name == "Large Product" || $category->name == "Fragile Product"){
					unset($categories[$key]);
				}
			}

			foreach ($categories_2 as $key => $category) {
				if (isset($_POST['product_per_bag_' . $category->term_id])){
					update_option('product_per_bag_' . $category->term_id, $_POST['product_per_bag_' . $category->term_id]);
				}
			}

			if (isset($_POST["pick_pack_token"])){
				update_option('temp_eco_bag_token', $_POST["pick_pack_token"]);
			}
			
		}	
		/*}*/

		/*$product_image = get_option("pick_pack_product_image_upload");
		$product_title = get_option("pick_pack_product_title");
		$product_description = get_option("pick_pack_product_text");*/
		/*$eco_bag_token = false;*///eco bag token
		/*$pick_pack_token = false;*///temp eco bag token

		
		if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Get Graphina animation lite version basename
        $basename = '';
        $plugins = get_plugins();
    
        foreach ($plugins as $key => $data) {
            if ($data['TextDomain'] === "woocommerce") {
                $basename = $key;
            }
        }


		if (is_plugin_active($basename)){

			$eco_bag_price = get_option('eco_bag_price', 3);
			$eco_bag_token= get_option("eco_bag_token", true);
			$pick_pack_token= get_option("temp_eco_bag_token", '');
			$taxonomy = 'product_cat';
			$categories = get_categories(array('taxonomy' => $taxonomy, 'hide_empty' => false));
			
			//Remove fragile and large from list
			foreach( $categories as $key => $category ) {
			
				if ($category->name == "Large Product" || $category->name == "Fragile Product"){
					unset($categories[$key]);
				}
			}
			// Retrieve the option values from database
			$category_array = [];

			foreach ($categories as $category) {

				$category_array[] = array('category_id' => $category->term_id, 'category_value' => get_option('product_per_bag_' . $category->term_id, 1), 'category_name' => $category->name);
			}

			$args = array(
			    'category' => array( 'Large Product' ),
			    'orderby'  => 'name',
			);
			$products = wc_get_products( $args );

			$args_2 = array(
			    'category' => array( 'Fragile Product' ),
			    'orderby'  => 'name',
			);
			$products_2 = wc_get_products( $args_2 );

			$eco_bags_sold = get_option('eco_bags_sold', 0);

			require_once( plugin_dir_path( __FILE__ ) . 'partials/pick-pack-admin-integration.php');
		}
		else{
			echo 'Please install WooCommerce to use Pick Pack Plugin';
		}


        
    }

	/**
	 * Add the fragile and large category
	 */
    public function add_two_categories(){

		if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Get Graphina animation lite version basename
        $basename = '';
        $plugins = get_plugins();
    
        foreach ($plugins as $key => $data) {
            if ($data['TextDomain'] === "woocommerce") {
                $basename = $key;
            }
        }


		if (is_plugin_active($basename)){

			$this->custom_post_type();

    		//Get all product categories
    		$taxonomy = 'product_cat';
    		$categories = get_categories(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    		$count = 0;

    		//Check if fragile and large category present
    		foreach( $categories as $category ) {
   
    			if ($category->name == "Large Product" || $category->name == "Fragile Product"){
    				$count++;
    			}
    		}

    		//Create categories if they do not exist
    		if ($count != 2){
    			$category_id =  wp_insert_term('Large Product', $taxonomy, array('description' => "A large product not available with eco bag"));
    			$category_id_2 = wp_insert_term('Fragile Product', $taxonomy, array('description' => "A Fragile product not available with eco bag"));

    			
    			if (is_wp_error($category_id_2) || is_wp_error($category_id)){
    				printf('<div class="error"><p>Could not create the categories</p></div>');
    			}
    		}
    	}

    		
    }

    /**
     * Post request from payment method registration and update request
     */
    public function pick_pack_payment(){

    	wp_verify_nonce($_POST['_wpnonce'], 'my-nonce');

    	$token = get_option('temp_eco_bag_token');

    	if ($token === false || empty($token)){
    		echo 'Please insert your pick pack token in the dashboard and press save button';
    		exit;
    	}

    	$return_url = get_home_url();

    	if ($_POST['token_update']=== 'true'){
    		$URL = SERVER_URL . 'index.php?eco_bag_token=' . $token . '&return_url=' . urlencode($return_url) . '&action=update';
    	}
    	else{
    		$URL = SERVER_URL . 'index.php?eco_bag_token=' . $token . '&return_url=' . urlencode($return_url);
    	}


    	header('Location: '. $URL);
    	


   	}

   	public function custom_post_type(){

   		register_post_type( 'pickpackorders',
   		// CPT Options
   		array(
   		  'labels' => array(
   		   'name' => __( 'Pick Pack Orders' ),
   		   'singular_name' => __( 'Pick Pack Order' )
   		  ),
   		  'capability_type' => 'post',
   		  'supports' => array(''
   		  	
   		  ),
   		  'capabilities' => array(
   		      'create_posts' => 'do_not_allow', // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
   		   ),
   		  'map_meta_cap' => true,
   		  'public' => true,
   		  'has_archive' => false,
   		  'rewrite' => array('slug' => 'pick-pack-orders'),
   		  'show_in_menu' => 'edit.php?post_type=pickpackorders'
   		 )
   		);
   		
   	}

   	public function add_custom_columns_orders_admin($columns){
   		return array_merge($columns, ['price' => __('Price', 'textdomain'), 'pick_pack_bags_sold' => __('Pick Pack Bags Sold', 'textdomain')]);

   	}

   	public function fill_custom_columns_orders_admin($column_key, $post_id){

   		if ($column_key == 'price') {

			$price = get_post_meta($post_id, 'price', true);

			if (!$price || $price == '') {

				echo '<span style="color:red;">'; _e('Not available', 'textdomain'); echo '</span>';
			} else {

				echo '<span style="color:green;">'; _e($price, 'textdomain'); echo '</span>';
			}
   		}

   		if ($column_key == 'pick_pack_bags_sold') {

			$eco_bags_sold = get_post_meta($post_id, 'quantity', true);

			if (!$eco_bags_sold || $eco_bags_sold == '') {

				echo '<span style="color:red;">'; _e('Not available', 'textdomain'); echo '</span>';
			} else {

				echo '<span style="color:green;">'; _e($eco_bags_sold, 'textdomain'); echo '</span>';
			}
   		}
   	}


}
