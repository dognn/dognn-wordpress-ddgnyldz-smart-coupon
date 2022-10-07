<?php
if ( ! defined( 'ABSPATH' ) ) exit;

include_once ABSPATH . 'wp-admin/includes/plugin.php';

function ai_coupon_inline_css(){ ?>
	<style type="text/css">
		#smartModal .popup-content {
    	background-color: <?php echo get_option( 'ai_style_bg_color_field' ) ?>;
		}
		#smartModal .popup-content .item-buy a {
	    background-color: <?php echo get_option( 'ai_style_button_bg_color_field' ).' !important' ?>;
	    color: <?php echo get_option( 'ai_style_button_text_color_field').' !important' ?>;
	    }
	    #smartModal .popup-content .item-title {
	    color: <?php echo get_option( 'ai_style_product_title_color_field' ) ?>;
		}
		.hot-ball-container,
		.hot-ball-container:before,
    	.hot-ball-container:after {
    	background-color: <?php echo get_option( 'ai_style_hotball_color_field' ) ?> ;
    	}
    	div#smartHotBall h4{
    		color: <?php echo get_option( 'ai_style_hotball_text_color_field' ) ?>;
    	}
	</style>
	<script type="text/javascript">
		var aiCouponMaxtime = <?php echo get_option( 'ai_discount_lifetime_field' ) ?>
	</script>
 <?php }
add_action('get_footer','ai_coupon_inline_css');

function smart_coupon_ai_sub_menu() {
    add_submenu_page(
        'smart-coupon',
        __('Smart AI Coupon','smart-coupon'),
        __('Smart AI Coupon','smart-coupon'),
        'manage_options',
        'smart-ai-coupon',
        'smart_coupon_ai_page_contents'
    );
}
add_action( 'admin_menu', 'smart_coupon_ai_sub_menu' );

function smart_coupon_ai_page_contents() {
    ?>
    <div class="smart-coupon-container">
        <div class="smart-coupon-left">
            <h1 class="smart-coupon-h1"> <?php esc_html_e( 'AI Smart Coupon Settings', 'smart-coupon' ); ?> </h1>
            <form class="smart-coupon-form" method="POST"  action="options.php">
            <?php
            settings_fields( 'smart-ai-coupon' );
            do_settings_sections( 'smart-ai-coupon' );
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
            <img src="<?php echo plugins_url().'/ddgnyldz-smart-coupon/assets/img/smart-ai-coupon.png' ?>">
        </div>
    </div>
    <?php
}

add_action( 'admin_init', 'smart_coupon_ai_settings_init' );

function smart_coupon_ai_settings_init() {
    //add_settings_section( $id, $title, $callback, $page )
    add_settings_section(
        'smart_coupon_ai_setting_section',
        __( 'AI Coupon Settings', 'smart-coupon' ),
        'smart_coupon_ai_section_callback_function',
        'smart-ai-coupon'
    );
        //add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array )

    /*Coupon İs Active */
        add_settings_field(
           'smart_coupon_ai_is_active',
           __( 'Activate', 'smart-coupon' ),
           'smart_coupon_ai_setting_markup_checkbox',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'checkbox',
            'default'      => 'false',
            'name'         => 'smart_coupon_ai_is_active',
            'description'  => __( 'Set Active or Deactive ( default : deactive )', 'smart-coupon' )
           )
        );

        register_setting( 'smart-ai-coupon', 'smart_coupon_ai_is_active');
    /* END Coupon Is Active */

    /*AI Coupon Name */
        add_settings_field(
           'coupon_ai_name_field',
           __( 'AI Coupon Name', 'smart-coupon' ),
           'smart_coupon_ai_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'coupon_ai_name_field',
            'description'  => __( 'Set your coupon name ( when discount applied customers will see the name on cart total section )', 'smart-coupon' )
           )
        );

        register_setting( 'smart-ai-coupon', 'coupon_ai_name_field','esc_attr' );
    /* END AI Coupon Name */

    /* AI Coupon Discount Percentage */
     add_settings_field(
           'ai_discount_percentage_field',
           __( 'Set Coupon Discount Percentage ( % )', 'smart-coupon' ),
           'smart_coupon_ai_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'number',
            'min'         => 1,
            'max'         => 100,
            'name'         => 'ai_discount_percentage_field',
            'description'  => __( 'Discount will apply automaticly for less cheaper product in cart', 'smart-coupon' )

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_discount_percentage_field','esc_attr' );
    /* END AI Coupon Discount */

    /* AI Coupon Discount Percentage */
     add_settings_field(
           'ai_discount_lifetime_field',
           __( 'Coupon Lifetime', 'smart-coupon' ),
           'smart_coupon_ai_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'number',
            'min'         => 5,
            'max'         => 2000,
            'name'         => 'ai_discount_lifetime_field',
            'description'  => __( 'Set Coupon Lifetime by minute', 'smart-coupon' ),
            'default'      => 30

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_discount_lifetime_field','esc_attr' );
    /* END AI Coupon Discount */

    /* AI Background Color */
     add_settings_field(
           'ai_style_bg_color_field',
           __( 'Background Color', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_bg_color_field',
            'description'  => __( 'Set Your Background Color (Exp: #ffbe43)', 'smart-coupon' ),
            'default' => '#ffbe43'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_bg_color_field','esc_attr' );
    /* END AI Background Color */

    /* AI Button Background */
     add_settings_field(
           'ai_style_button_bg_color_field',
           __( 'Button Background Color', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_button_bg_color_field',
            'description'  => __( 'Set Your Button Background Color (Exp: #ffbe43)', 'smart-coupon' ),
            'default' => '#edcf83'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_button_bg_color_field','esc_attr' );
    /* END AI Button Background */

    /* AI Button Text Color */
     add_settings_field(
           'ai_style_button_text_color_field',
           __( 'Button Text Color', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_button_text_color_field',
            'description'  => __( 'Set Your Button Text Color (Exp: #ffbe43)', 'smart-coupon' ),
            'default' => '#333'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_button_text_color_field','esc_attr' );
    /* END AI Button Text Color */

    /* AI Product Title */
     add_settings_field(
           'ai_style_product_title_color_field',
           __( 'Product Title Color', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_product_title_color_field',
            'description'  => __( 'Set Your Product Title Color (Exp: #ffbe43)', 'smart-coupon' ),
            'default' => '#000'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_product_title_color_field','esc_attr' );
    /* END AI Product Title */

    /* AI HotBall Text */
     add_settings_field(
           'ai_style_hotball_text_field',
           __( 'HotBall Text', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_hotball_text_field',
            'description'  => __( 'Set Your HotBall Text', 'smart-coupon' ),
            'default' => 'HOT !'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_hotball_text_field','esc_attr' );
    /* END HotBall Text */

    /* AI HotBall BG */
     add_settings_field(
           'ai_style_hotball_color_field',
           __( 'HotBall Background Color', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_hotball_color_field',
            'description'  => __( 'Set Your HotBall Background Color', 'smart-coupon' ),
            'default' => '#ffc800'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_hotball_color_field','esc_attr' );
    /* END HotBall BG */

    /* AI HotBall Text Color */
     add_settings_field(
           'ai_style_hotball_text_color_field',
           __( 'HotBall Text Color', 'smart-coupon' ),
           'smart_coupon_ai_style_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'text',
            'name'         => 'ai_style_hotball_text_color_field',
            'description'  => __( 'Set Your HotBall Text Color', 'smart-coupon' ),
            'default' => '#fff'

           )
        );

        register_setting( 'smart-ai-coupon', 'ai_style_hotball_text_color_field','esc_attr' );
    /* END HotBall Text Color */

    /* AI Right Side With Own HTML */
     add_settings_field(
           'ai_html_field',
           __( 'Right Side HTML Edit', 'smart-coupon' ),
           'smart_coupon_ai_html_setting_markup',
           'smart-ai-coupon',
           'smart_coupon_ai_setting_section',
           array(
            'type'         => 'textarea',
            'name'         => 'ai_html_field',
            'description'  => __( 'Customize Right Side With Own HTML', 'smart-coupon' ),
            'default' => '<h4>Yeni Dönem Başladı !</h4><p>Yapay zeka tarafından sitemizde gezerken en çok beğendiğiniz ürünü bulup ona özel %10 indirim veriyoruz size! </p><p>Ee ben zaten sepete eklemiştim diyorsan, indirimin <b>sepete</b> tanımladı.</p><p>Süre: 24 Saat</p>'
           )
        );

        register_setting( 'smart-ai-coupon', 'ai_html_field' );
    /* END AI Right Side With Own HTML */
}
function smart_coupon_ai_section_callback_function() {
    //echo '<p>'._e( 'You can manage your setting from here', 'smart-coupon' ).'</p>';
}

function smart_coupon_ai_html_setting_markup($args) {
   $option = !empty(get_option($args['name'])) ? get_option($args['name']) : $args['default'];
   wp_editor( $option, 'ai_html_editor', array( 
        'textarea_name' => $args['name'],
        'editor_class'  => 'ai_html_editor',
        'media_buttons' => false,
        'tinymce'       => true,
        'quicktags'		=> true,
        'tabfocus_elements'   => ':prev,:next',
    ) );
   /*$html = '';
    $html .= '<div class="coupon-input-area">';
    $html .= '<textarea cols="40" rows="5" class="coupon-input" name="'. $args['name'] .'">' . $option . '</textarea>';
    $html .= '<small class="coupon-desc" >'.$args['description'].'</small>';
    $html .= '</div>';
    echo $html;*/
}

function smart_coupon_ai_style_setting_markup($args) {
   $option = !empty(get_option($args['name'])) ? get_option($args['name']) : $args['default'];
   $html = '';
    $html .= '<div class="coupon-input-area">';
    $html .= '<input class="coupon-input" type="'.$args['type'].'" name="'. $args['name'] .'" value="' . $option . '" />';
    $html .= '<small class="coupon-desc" >'.$args['description'].'</small>';
    $html .= '</div>';
    echo $html;
}

function smart_coupon_ai_setting_markup($args) {
   $option = !empty(get_option($args['name'])) ? get_option($args['name']) : $args['default'];
   $html = '';
   $is_number_field = $args['type'] == "number" ? 'min="'.$args['min'].'" max="'.$args['max'].'"' : '';
    $html .= '<div class="coupon-input-area">';
    $html .= '<input class="coupon-input" '.$is_number_field.' type="'.$args['type'].'" name="'. $args['name'] .'" value="' . $option . '" />';
    $html .= '<small class="coupon-desc" >'.$args['description'].'</small>';
    $html .= '</div>';
    echo $html;
}

function smart_coupon_ai_setting_markup_checkbox($args) {
   $option = get_option($args['name']);
   $html = '';
   $html .= '<div class="coupon-input-area">';
   $html .= '<input class="coupon-input" type="'.$args['type'].'"  name="'.$args['name'].'" value="1" '.checked( 1,  $option, false).' />';
   $html .= '<small class="coupon-desc">'.$args['description'].'</small>';
   $html .= '</div>';
   echo $html;
   
}