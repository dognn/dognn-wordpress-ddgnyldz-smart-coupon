<?php 
$is_active = get_option('smart_coupon_ai_is_active');


function set_user_visited_time_product_info() {
	global $post;
	if (! isset($_COOKIE['smart_popup_check'])) {

		if ( is_product()  ){
			
			if (! isset($_COOKIE['smart_coupon_ai_time_cookie'])) {
				setcookie('smart_coupon_ai_time_cookie',$post->ID.'/'.date("H:i:s"), time()+2147483647 ,"/");
			}else{
				$new = $_COOKIE['smart_coupon_ai_time_cookie'].'-'.$post->ID.'/'.date("H:i:s");
	        	setcookie('smart_coupon_ai_time_cookie',$new, time()+86400 ,"/");
			}	
		}

		if (isset($_COOKIE['smart_coupon_ai_time_cookie'])) {

			$product_ids = array_map(function($iter){ return explode('-',$iter);},explode('/',$_COOKIE['smart_coupon_ai_time_cookie']));
			
			foreach ($product_ids as $key => $value) {
				if (isset($value[1])) {
					$data[$key] = array(
						'productIds' => $value[1],
						'spendTime' => calculateTime($value[0]),
						'viewCount' => 1
					);
				}
			}

			if (isset($data)) {
				$reducedData = array_reduce($data, function ($a, $b) {
				if (isset($a[$b['productIds']])) {
					$a[$b['productIds']]['spendTime'] += $b['spendTime'];
					$a[$b['productIds']]['viewCount'] += $b['viewCount'] +1;
				}else{
					$a[$b['productIds']] = $b;
				}
			    return $a;
				});


				foreach ($reducedData as $value) {
					$powerProduct[$value['productIds']] =  (($value['spendTime'] / 7) * $value['viewCount']) * 0.01;
				}
				arsort($powerProduct,SORT_NUMERIC );

				//echo var_dump();

				if (! isset($_COOKIE['smart_coupon_ai_chosen_product_id'])) {
				setcookie('smart_coupon_ai_chosen_product_id',array_keys($powerProduct)[0], time()+2147483647 ,"/");
				}else{
		        	setcookie('smart_coupon_ai_chosen_product_id',array_keys($powerProduct)[0], time()+2147483647 ,"/");
				}
				// Open modal 
				//echo var_dump(intval(array_values($powerProduct)[0]));

				if (intval(array_values($powerProduct)[0]) > 400 ) {

					setcookie('smart_popup_check',1, time() + ( get_option( 'ai_discount_lifetime_field' ) * 60 ) ,"/");
				}
			}
		}
	}
	else{
		if (isset($_COOKIE['smart_coupon_ai_chosen_product_id'])) {
			$product = wc_get_product($_COOKIE['smart_coupon_ai_chosen_product_id']);
			popupCreator($product);
		}
	}
}
if ($is_active) {
	add_action( 'wp_body_open', 'set_user_visited_time_product_info',900 );
}

function calculateTime($timeData){
	$timeDif = explode('|', $timeData);
	if (isset($timeDif[1]) && $timeDif[0] !== 'undefined') {
		return strtotime($timeDif[1]) - strtotime($timeDif[0]);
	}
}

function popupCreator($product){
	$cart = WC()->cart;
	$product_ids = array();
	foreach ( $cart->get_cart() as $cart_item ) {
		$cart_product = $cart_item['data'];
		$product_ids[] = $cart_product->get_id();
	}
	if ($product->is_type( 'variable' )) {

		$variations = $product->get_available_variations();
		$variations_id = wp_list_pluck( $variations, 'variation_id' );
		$is_have = array_intersect($variations_id, $product_ids);
	}else{
		$is_have = in_array($product->get_id(),$product_ids);
	}
	//echo var_dump($is_have);
	$html = '';
	$html .= '<div id="smartModal" class="popup-container">';
	$html .= '<span class="close">&times;</span>';
	if (empty($product)) {
		$html .= '<div class="popup-container">';
		$html .= '<div class="item-title">';
		$html .=  'Sayafada gezinerek özel indirimler kazanabilirsiniz';
		$html .= '</div>';
		$html .= '</div>';
	}else{
		$html .= '<div class="popup-content">';
		$html .= '<div class="left">';
		$html .= '<div class="item-title">';
		$html .=  $product->get_name();
		$html .= '</div>';
		$html .= '<div class="item-image">';
		$html .= '<img src="'.wp_get_attachment_url( $product->get_image_id(),'thumbnail' ).'" />';
		$html .= '</div>';
		$html .= '<div class="item-buy">';
		if (!empty($is_have)) {
			$html .= '<a href="/wordpress/sepet/"  class="smart-coupon button" rel="nofollow">Sepetinde Ekli</a>';
		}else{
			if (!$product->is_type( 'variable' )) {
				$html .= '<a href="'.get_permalink( $product->get_id() ).'"  class="smart-coupon button  add_to_cart_button" rel="nofollow">İndirimli Sepete Ekle</a>';
			}else{
				$html .= '<a href="'.get_permalink( $product->get_id() ).'"  class="smart-coupon button  add_to_cart_button" rel="nofollow">Seçenekler</a>';
			}
		}
		
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="right">';
		$html .= get_option('ai_html_field');
		$html .= '</div>';
		$html .= '</div>';
	}
	$html .= '</div>';
	// HOTBALL BANNER 
	$html .= '<div id="smartHotBall" class="hot-ball-container">';
	$html .= '<div class="hot-ball-content">';
	$html .= '<h4 id="specialOfferBtn">'.get_option('ai_style_hotball_text_field').'</h4>';
	$html .= '</div>';
	$html .= '</div>';
	// END HOTBALL BANNER 
	echo $html;
}



function addFeeSpecProByAI($cart){

	if ( ( is_cart() || is_checkout() ) && isset($_COOKIE['smart_coupon_ai_chosen_product_id']) && isset($_COOKIE['smart_popup_check'])) {
		$product = wc_get_product($_COOKIE['smart_coupon_ai_chosen_product_id']);
		$cart_obj = $cart->get_cart();
		$discount_rate = get_option('ai_discount_percentage_field');
		foreach ( $cart_obj as $cart_item ) {
			$cart_product = $cart_item['data'];
			$product_list[$cart_product->get_id()] = $cart->get_tax_price_display_mode() == 'incl' ? wc_get_price_excluding_tax($cart_product) : wc_get_price_including_tax($cart_product); //Store all product price from cart items in Array 
		}
		
		if ($product->is_type( 'variable' )) {
				$variations = $product->get_available_variations();
				$variations_id = wp_list_pluck( $variations, 'variation_id' );
				$discount_product = array_intersect($variations_id,array_keys($product_list));
				if (!empty($discount_product)) {
					$discount = ($product_list[max($discount_product)] * $discount_rate) / 100;
					$cart->add_fee( get_option('coupon_ai_name_field'), - $discount );
				}
		}else{
			$discount_product = in_array($product->get_id(),array_keys($product_list));
			if (!empty($discount_product)) {
				
				$discount = ($product_list[$product->get_id()] * $discount_rate) / 100;
				$cart->add_fee( get_option('coupon_ai_name_field'), - $discount );
			}
		}

		
		
	}
}
if ($is_active) {
add_action( 'woocommerce_cart_calculate_fees', 'addFeeSpecProByAI', 11,1 );
}