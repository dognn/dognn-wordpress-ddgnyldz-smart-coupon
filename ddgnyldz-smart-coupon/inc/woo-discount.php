<?php 
	if ( ! defined( 'ABSPATH' ) ) exit;

	// Fee INIT - smart_coupon_init
	if (get_option('smart_coupon_is_active')) {
	   	add_action( 'woocommerce_cart_calculate_fees', 'smart_coupon_init', 10,1 );
	}

	 
	add_action('updated_option', function($option_name="exclude_products_field") {
	     getProductIdsFromCart();
	}, 10, 1);


	function smart_coupon_init ($cart){
		 global $woocoomerce;
	    $coupon_name = get_option('coupon_name_field');
	    $active_count = get_option('coupon_active_product_field');
	    $coupon_text = get_option('coupon_text_field');
	    $coupon_is_active = get_option('smart_coupon_is_active');
	    $percentage = get_option('discount_percentage_field');
	    $discount_type = get_option('coupon_discount_type_field');
	    $slogan_place = get_option('coupon_slogan_place_field');
	    if($coupon_is_active){
		    
		    $cart_obj = $cart->get_cart();
		    $all_exclude_product_list = get_option( 'total_exc_product_ids_list' ); // filter excluded products

		        foreach ( $cart_obj as $cart_item ) {
		            $product = $cart_item['data'];
		            $product_price[] = $cart->get_tax_price_display_mode() == 'incl' ? wc_get_price_excluding_tax($product) : wc_get_price_including_tax($product); //Store all product price from cart items in Array 
		            $product_ids[] = $product->get_id();
		        }

		        $proCount = count(array_diff($product_ids,$all_exclude_product_list));
		        // Get Discount Type
		        $price = getDiscountType($discount_type, $product_price);

		        if ($proCount >= $active_count) {
		            $discount = $price * $percentage / 100; // calculation
		            if( isset($discount) && $discount > 0 ){
		                $cart->add_fee( __($coupon_name, 'woocommerce'), - $discount );
		            }
		        }
		    else{ 
		    	add_action( $slogan_place, function() {  // Add Cart Banner Text
		    		$cart = WC()->cart;
    				$all_exclude_product_list = get_option( 'total_exc_product_ids_list' );
		    		$cart_obj = $cart->get_cart();
		    		$active_count = get_option('coupon_active_product_field');
		    		$coupon_text = get_option('coupon_text_field');
		    		$percentage = get_option('discount_percentage_field');

		    		foreach ( $cart_obj as $cart_item ) {
		            $product = $cart_item['data'];
		            $product_ids[] = $product->get_id();
		        }
		        $proCount = count(array_diff($product_ids,$all_exclude_product_list));
		         echo '<p class="smart-coupon-info"><b>'.$active_count.' Ürün Kampanyası!</b> Fiyatı en yüksek ürünü <b>%'.$percentage.'</b> indirimli almak için <b>'.($active_count - $proCount).'</b> adet daha farklı ürün ekle!</p>'; 

				},99 ); 
		    }
		}
	}
	
function getDiscountType($discount_type, $product_price_list){
	switch ($discount_type) {
		case 'cheapest-product':
			return min($product_price_list);
			break;
		case 'expensive-product':
			return max($product_price_list);
			break;
		default:
			return array_sum($product_price_list);
			break;
	}
}

function getProductIdsFromCart(){
	$exclude_product_option = empty(get_option( 'exclude_products_field' )) ? array() : get_option( 'exclude_products_field' );

	foreach ($exclude_product_option as $value) {
		$product = wc_get_product($value);
		if ($product->get_type() == 'variable') {
				$variations = $product->get_available_variations();
				$variations_id = wp_list_pluck( $variations, 'variation_id' );
				$exclude_product_option = array_merge($variations_id, $exclude_product_option);
			}
		
	}
	//$exclude_product_by_category = getProductByExcludedCategory();
	//$exclude_product = array_merge(array_diff($exclude_product_option,$exclude_product_by_category),$exclude_product_by_category);
	$exclude_product = $exclude_product_option;
	
	if ( get_option( 'total_exc_product_ids_list' ) !== false ) {
		// The option already exists, so update it.
        update_option( 'total_exc_product_ids_list', $exclude_product );

    }else {
		// The option hasn't been created yet, so add it with $autoload set to 'no'.
        $deprecated = null;
        $autoload = 'no';
        add_option( 'total_exc_product_ids_list', $exclude_product, $deprecated, $autoload );
	}
}

/*function getProductByExcludedCategory(){
	$excluded_categories = empty(get_option( 'exclude_categories_field' )) ? array() : get_option( 'exclude_categories_field' );
	$variations_id = array();
   $products = get_posts( array(
      'post_type' => 'product',
      'numberposts' => -1,
      'post_status' => 'publish',
      'fields' => 'ids',
      'tax_query' => array(
         array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $excluded_categories,
            'operator' => 'IN',
         )
      ),
   ));
      foreach (array_unique($products) as $value) {
    		$product = wc_get_product($value);
    		if ($product->is_type( 'variable' )) {
				$variations = $product->get_available_variations();
				$variations_id = wp_list_pluck( $variations, 'variation_id' );
			}
    	}
   return array_merge($products,$variations_id);
} */

