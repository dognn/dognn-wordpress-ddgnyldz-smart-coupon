<?php
/*
Plugin Name: Ddgnyldz Smart Coupon
Plugin URI: https://www.linkedin.com/in/doğan-yıldız-9a2a8223a/
Description: You can set smart coupon for customers with Woocommerce.
Version: 1.0.0
Author: Dogan Yildz
Author URI: https://www.linkedin.com/in/doğan-yıldız-9a2a8223a/
License: GPLv2 or later
Text Domain: smart-coupon
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$plugin_dir = plugin_dir_path( __FILE__ );

require_once $plugin_dir .'inc/customizer.php';
require_once $plugin_dir .'inc/customizer-ai.php';
require_once $plugin_dir .'inc/woo-discount.php';
require_once $plugin_dir .'inc/woo-ai-coupon.php';



function smart_coupon_assets_admin_init() {
    $plugin_dir = plugin_dir_url( __FILE__ );
    $screen = get_current_screen();
    //echo var_dump($screen);
    if ($screen->base == 'toplevel_page_smart-coupon' || $screen->base == 'akilli-kupon_page_smart-ai-coupon') {
    	//CSS
    	wp_register_style('smart-coupon-custom-css', $plugin_dir.'/assets/css/smart-coupon.css',array(), '1.0', 'all');
        wp_enqueue_style('smart-coupon-custom-css');

    	wp_register_style('smart-coupon-select2', $plugin_dir.'/assets/css/select2.min.css');
        wp_enqueue_style('smart-coupon-select2');

        

        //JS
        wp_register_script( 'smart-coupon-jquery', $plugin_dir.'/assets/js/jquery-3.6.0.min.js',array('jquery'));
        wp_enqueue_script('smart-coupon-jquery'); 

        wp_register_script( 'smart-coupon-custom-js', $plugin_dir.'/assets/js/smart-coupon.js');
        wp_enqueue_script('smart-coupon-custom-js');  

        wp_register_script( 'smart-coupon-select2', $plugin_dir.'/assets/js/select2.min.js');
        wp_enqueue_script('smart-coupon-select2');  
    }
}
add_action( 'admin_enqueue_scripts','smart_coupon_assets_admin_init');

//Front AI-COUPON ASSETS
function smart_coupon_assets_init(){
    $plugin_dir = plugin_dir_url( __FILE__ );
    //css
    wp_register_style('smart-coupon-ai-custom-css', $plugin_dir.'/assets/css/smart-coupon-ai.css');
    wp_enqueue_style('smart-coupon-ai-custom-css');
    //js
    wp_register_script( 'smart-coupon-ai-custom-js', $plugin_dir.'/assets/js/smart-coupon-ai.js');
    wp_enqueue_script('smart-coupon-ai-custom-js'); 
}
add_action( 'get_footer', 'smart_coupon_assets_init',999);

function plugin_load_textdomain() {
load_plugin_textdomain( 'smart-coupon', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'plugin_load_textdomain' );



