<?php
if ( ! defined( 'ABSPATH' ) ) exit;

include_once ABSPATH . 'wp-admin/includes/plugin.php';

function smart_coupon_admin_menu() {
    add_menu_page(
        __( 'Smart Coupon', 'smart-coupon' ),
        __( 'Smart Coupon', 'smart-coupon' ),
        'manage_options',
        'smart-coupon',
        'smart_coupon_page_contents',
        'dashicons-clipboard',
        3
    );
}
add_action( 'admin_menu', 'smart_coupon_admin_menu' );

function smart_coupon_page_contents() {
    ?>
    <div class="smart-coupon-container">
        <div class="smart-coupon-left">
            <h1 class="smart-coupon-h1"> <?php esc_html_e( 'Smart Coupon Settings', 'smart-coupon' ); ?> </h1>
            <form class="smart-coupon-form" method="POST" name="smart-coupon-form" action="options.php">
            <?php
            settings_fields( 'smart-coupon' );
            do_settings_sections( 'smart-coupon' );
            if (is_plugin_active('woocommerce/woocommerce.php')) {
                submit_button();
            }else {
                $class = 'notice notice-error';
                $message = __( 'Install and Activate Woocommerce Plugin ', 'smart-coupon');
                $link = get_site_url().'/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term';
                printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Here !</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_html( $link ) ); 
            }
            ?>
            </form>
        </div>
        <div class="smart-coupon-right">
            
        </div>
    </div>
    <?php
}


add_action( 'admin_init', 'smart_coupon_settings_init' );

function smart_coupon_settings_init() {
    //add_settings_section( $id, $title, $callback, $page )
    add_settings_section(
        'smart_coupon_setting_section',
        __( 'Coupon Settings', 'smart-coupon' ),
        'smart_coupon_section_callback_function',
        'smart-coupon'
    );
        //add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array )

    /*Coupon İs Active */
        add_settings_field(
           'smart_coupon_is_active',
           __( 'Activate', 'smart-coupon' ),
           'smart_coupon_setting_markup_checkbox',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'checkbox',
            'default'      => 'false',
            'name'         => 'smart_coupon_is_active',
            'description'  => __( 'Set Active or Deactive ( default : deactive )', 'smart-coupon' )
           )
        );

        register_setting( 'smart-coupon', 'smart_coupon_is_active');
    /* END Coupon Is Active */

    /*Coupon Name */
        add_settings_field(
           'coupon_name_field',
           __( 'Coupon Name', 'smart-coupon' ),
           'smart_coupon_setting_markup',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'coupon_name_field',
            'description'  => __( 'Set your coupon name ( when discount applied customers will see the name on cart total section )', 'smart-coupon' )
           )
        );

        register_setting( 'smart-coupon', 'coupon_name_field','esc_attr' );
    /* Coupon Name */

     /* Coupon Discount Percentage */
     add_settings_field(
           'discount_percentage_field',
           __( 'Set Coupon Discount Percentage ( % )', 'smart-coupon' ),
           'smart_coupon_setting_markup',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'number',
            'min'         => 1,
            'max'         => 100,
            'name'         => 'discount_percentage_field',
            'description'  => __( 'Discount will apply automaticly for less cheaper product in cart', 'smart-coupon' )

           )
        );

        register_setting( 'smart-coupon', 'discount_percentage_field','esc_attr' );
    /* END Coupon Discount */

    /* Coupon Active Product Count*/
     add_settings_field(
           'coupon_active_product_field',
           __( 'When Coupon Active Product Count', 'smart-coupon' ),
           'smart_coupon_setting_markup',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'number',
            'min'         => 1,
            'max'         => 10,
            'name'         => 'coupon_active_product_field',
            'description'  => __( 'Set minimum product count for apply discount in cart', 'smart-coupon' )
           )
        );

        register_setting( 'smart-coupon', 'coupon_active_product_field','esc_attr' );
    /* Coupon Product Count*/

    /* Discount Type*/
     add_settings_field(
           'coupon_discount_type_field',
           __( 'Discount Type', 'smart-coupon' ),
           'smart_coupon_setting_discount_type',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'select',
            'name'         => 'coupon_discount_type_field',
            'description'  => __( 'Chose discount type discount in cart', 'smart-coupon' ),
            'default'      => 'cart-total'
           )
        );

        register_setting( 'smart-coupon', 'coupon_discount_type_field','esc_attr' );
    /* END  Discount Type*/

    /* Display Slogan Place */
     add_settings_field(
           'coupon_slogan_place_field',
           __( 'Slogan Display Place', 'smart-coupon' ),
           'smart_coupon_setting_slogan_place',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'select',
            'name'         => 'coupon_slogan_place_field',
            'description'  => __( 'Set slogan display place in cart', 'smart-coupon' ),
            'default'      => 'cart-total'
           )
        );

        register_setting( 'smart-coupon', 'coupon_slogan_place_field','esc_attr' );
    /* END  Display Slogan Place */

    /* Set Coupon Text (Will Show Total Section on Cart / Checkout ) */
     add_settings_field(
           'coupon_text_field',
           __( 'Set Coupon Slogan', 'smart-coupon' ),
           'smart_coupon_setting_markup',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'coupon_text_field',
            'description'  => __( 'Discount applied customers will see the coupon slogan on cart total section', 'smart-coupon' )
           )
        );

        register_setting( 'smart-coupon', 'coupon_text_field','esc_attr' );
    /* Set Coupon Text */

    /* Set Exclude Product List */
     add_settings_field(
           'exclude_products_field',
           __( 'Excluded Products', 'smart-coupon' ),
           'smart_coupon_setting_markup_products',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'select',
            'name'         => 'exclude_products_field',
            'description'  => __( 'You can set exclude product list(Product ID) for count products in cart (exp: 15,20,30)', 'smart-coupon' )
           )
        );

        register_setting( 'smart-coupon', 'exclude_products_field');
    /* END Set Exclude Product List */

    /* Set Exclude Product List By Category */
     /*add_settings_field(
           'exclude_categories_field',
           __( 'Excluded Categories', 'smart-coupon' ),
           'smart_coupon_setting_markup_categories',
           'smart-coupon',
           'smart_coupon_setting_section',
           array(
            'type'         => 'select',
            'name'         => 'exclude_categories_field',
            'description'  => __( 'You can set exclude Categories list(Category Slug) for count products in cart (exp: pantolon,gomlek)', 'smart-coupon' )
           )
        );

        register_setting( 'smart-coupon', 'exclude_categories_field'); */
    /* END Set Exclude Product List By Category */
}


function smart_coupon_section_callback_function() {
    //echo '<p>'._e( 'You can manage your setting from here', 'smart-coupon' ).'</p>';
}


function smart_coupon_setting_markup($args) {
   $option = get_option($args['name']);
   $html = '';
   $is_number_field = $args['type'] == "number" ? 'min="'.$args['min'].'" max="'.$args['max'].'"' : '';
    $html .= '<div class="coupon-input-area">';
    $html .= '<input class="coupon-input" '.$is_number_field.' type="'.$args['type'].'" name="'. $args['name'] .'" value="' . $option . '" />';
    $html .= '<small class="coupon-desc" >'.$args['description'].'</small>';
    $html .= '</div>';
    echo $html;
}

function smart_coupon_setting_markup_checkbox($args) {
   $option = get_option($args['name']);
   $html = '';
   $html .= '<div class="coupon-input-area">';
   $html .= '<input class="coupon-input" type="'.$args['type'].'"  name="'.$args['name'].'" value="1" '.checked( 1,  $option, false).' />';
   $html .= '<small class="coupon-desc">'.$args['description'].'</small>';
   $html .= '</div>';
   echo $html;
   
}

function smart_coupon_setting_markup_products($args){
    $woo_active = is_plugin_active('woocommerce/woocommerce.php');
    
    $html = '';
    $option = empty(get_option($args['name'])) ? array() : get_option($args['name']);
    if ($woo_active) {
        $products = 
         get_posts( array(
          'post_type' => 'product',
          'numberposts' => -1,
          'post_status' => 'publish',
          'fields' => 'ids',
      ));

        //echo var_dump($products);
    }else{
        $products = array();
    }
    $html .= '<select class="smart-coupon-product-select2" name="'.$args['name'].'[]" multiple="multiple" >'; 
    foreach ($products as $product) { 
        if (in_array($product, $option)) {
            $html .= '<option selected class="coupon-input" value="'.$product.'">'.get_the_title($product).'</option>';
        }else{
            $html .= '<option class="coupon-input" value="'.$product.'">'.get_the_title($product).'</option>';
        } 
        
     } 
    $html .= '</select>';
    echo $html;
}

function smart_coupon_setting_markup_categories($args){

    $html = '';
    $option = empty(get_option($args['name'])) ? array() : get_option($args['name']);
    $cat_args = array( 'type' => 'product', 'taxonomy' => 'product_cat' ); 
    $categories = get_categories( $cat_args );
    $html .= '<select class="smart-coupon-category-select2" name="'.$args['name'].'[]" multiple="multiple" >'; 
    foreach ($categories as $cat) { 
        if (in_array($cat->slug, $option)) {
            $html .= '<option selected class="coupon-input" value="'.$cat->slug.'">'.$cat->name.'</option>';
        }else {
            $html .= '<option class="coupon-input" value="'.$cat->slug.'">'.$cat->name.'</option>';
        }
        
     } 
    $html .= '</select>';
    echo $html;
} 

function smart_coupon_setting_discount_type($args){

    $html = '';
    $option = empty(get_option($args['name'])) ? '' : get_option($args['name']);
    $types = array(
        'cart-total'        => __('Sepet Tamamına','smart-coupon'),
        'cheapest-product'  => __('Sepetteki en ucuz ürüne','smart-coupon'),
        'expensive-product' => __('Sepetteki en pahalı ürüne','smart-coupon')
    );

    $html .= '<select class="smart-coupon-category-select2" name="'.$args['name'].'">'; 
    foreach ($types as $key => $discount) { 
        if ( $option == $key) {
            $html .= '<option selected class="coupon-input" value="'.$key.'">'.$discount.'</option>';
        }else {
            $html .= '<option class="coupon-input" value="'.$key.'">'.$discount.'</option>';
        }
        
     } 
    $html .= '</select>';
    echo $html;
} 

function smart_coupon_setting_slogan_place($args){

    $html = '';
    $option = empty(get_option($args['name'])) ? '' : get_option($args['name']);
    $places = array(
        'woocommerce_before_cart'        => __('Before Cart','smart-coupon'),
        'woocommerce_before_cart_table'  => __('Before Cart Table','smart-coupon'),
        'woocommerce_before_cart_contents' => __('Before Cart Table Content','smart-coupon'),
        'woocommerce_after_cart_contents' => __('After Cart Table Content','smart-coupon'),
        'woocommerce_after_cart_table' => __('After Cart Table','smart-coupon'),
        'woocommerce_cart_collaterals' => __('Cart Collaterals','smart-coupon'),
        'woocommerce_before_cart_totals' => __('Before Cart Totals','smart-coupon'),
        'woocommerce_cart_totals_before_shipping' => __('Cart Totals Before Shipping','smart-coupon'),
        'woocommerce_cart_totals_before_order_total' => __('Before Order Total','smart-coupon'),
        'woocommerce_proceed_to_checkout' => __('Before Proceed Button','smart-coupon'),
        'woocommerce_after_cart_totals' => __('After Cart Total','smart-coupon'),
        'woocommerce_after_cart' => __('After Cart','smart-coupon'),
    );

    $html .= '<select class="smart-coupon-category-select2" name="'.$args['name'].'">'; 
    foreach ($places as $key => $place) { 
        if ( $option == $key) {
            $html .= '<option selected class="coupon-input" value="'.$key.'">'.$place.'</option>';
        }else {
            $html .= '<option class="coupon-input" value="'.$key.'">'.$place.'</option>';
        }
        
     } 
    $html .= '</select>';
    echo $html;
} 



