<?php

if ( !function_exists( 'stm_slider_listing_info' ) ) {
	function stm_slider_listing_info( $atts )
	{
		
		$model = uListing\Classes\StmListing::load( get_post( $atts['listing_id'] ) );
		
		$title = $model->getOptions( $atts['listing_title'] );
		$title = ( !empty( $title[0] ) ) ? $title[0]->option_name : '';
		
		$subtitle = $model->getOptions( $atts['listing_subtitle'] );
		$subtitle = ( !empty( $subtitle[0] ) ) ? $subtitle[0]->option_name : '';
		
		$price = $model->getOptions( $atts['listing_price'] );
		$price = ( !empty( $price[0] ) && !empty( $price[0]->option_name ) ) ? $price[0]->option_name : ( ( !empty( $price[0]->value ) ) ? $price[0]->value : '' );
		
		$attr = ( !empty( $atts['listing_attr_slugs'] ) ) ? explode( ',', $atts['listing_attr_slugs'] ) : null;
		
		ob_start();
		?>
        <div class="stm-slider-listing-info-wrap">
            <div class="sl-info-left">
                <div class="make heading-font"><?php echo esc_html( $title ); ?></div>
                <div class="model heading-font"><?php echo esc_html( $subtitle ); ?></div>
                <div class="options">
                    <ul>
						<?php
						foreach ( $attr as $slug ):
							$attrData = $model->getOptions( trim( $slug ) );
							if ( !empty( $attrData ) ) :
								?>
                                <li>
                                    <span class="label heading-font"><?php echo esc_html( $attrData[0]->attribute_title ); ?></span>
                                    <span class="value heading-font"><?php echo esc_html( $attrData[0]->value ); ?></span>
                                </li>
							<?php
							endif;
						endforeach;
						?>
                    </ul>
                </div>
            </div>
            <div class="sl-info-right">
                <div class="price-wrap">
                    <span class="from heading-font"><?php echo esc_html__( 'From: ', 'motors' ) ?></span>
                    <span class="price heading-font"><?php echo ulisting_currency_format( $price ); ?></span>
                </div>
                <div class="button-wrap">
                    <a href="<?php echo get_the_permalink( $atts['listing_id'] ); ?>" class="heading-font">BUY NOW</a>
                </div>
            </div>
        </div>
		
		<?php
		$info = ob_get_clean();
		
		return $info;
	}
}

add_shortcode( 'listing_info', 'stm_slider_listing_info' );

function stm_me_getSearchForm( $atts )
{
	
	$form = '<form method="get" id="searchform" action="' . home_url( '/' ) . '">';
	$form .= '<div class="searchform-wrapper">
				<div class="search-wrapper">
					<input placeholder="' . $atts['placeholder'] . '" type="text" class="form-control search-input" value="' . get_search_query() . '" name="s" id="s" />
					<button type="submit" class="search-submit" ><i class="fas fa-search"></i></button>
				</div>';
	$form .= '<div class="checkbox-wrapper">';
	if ( isset( $atts['post_types'] ) && !empty( $atts['post_types'] ) ) {
		foreach ( explode( ',', $atts['post_types'] ) as $value ) {
			$form .= '<label for="rev-search-' . $value . '">
							<input id="rev-search-' . $value . '" type="checkbox" name="search_by_post_type[]" value="' . $value . '" />
							' . $value . '
						</label>';
		}
	}
	$form .= '</div>';
	$form .= '</div></form>';
	
	return $form;
}

add_shortcode( 'get_search_form', 'stm_me_getSearchForm' );

function stm_me__gdpr_checkbox_shcode()
{
	return '<div class="motors-gdpr" style="margin: 20px 0;"><label><input type="checkbox" name="motors-gdpr-agree" value="agree" data-need="true" required />' . esc_html__( 'I agree with storaging of my data by this website.', 'stm_motors_extends' ) . '</label></div>';
}

add_shortcode( 'motors_gdpr_checkbox', 'stm_me__gdpr_checkbox_shcode' );

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	
	function stm_autocomplete_vc_st( $settings, $value )
	{
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">'
			. 'var st_vc_taxonomies = ' . json_encode( stm_get_categories() )
			. '</script>'
			. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
			'</div>';
	}
	
	vc_add_shortcode_param( 'stm_autocomplete_vc', 'stm_autocomplete_vc_st', STM_MOTORS_EXTENDS_URL . '/inc/vc_extends/jquery-ui.min.js' );
	
	function stm_autocomplete_vc_st_taxonomies( $settings, $value )
	{
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">'
			. 'var st_vc_taxonomies = ' . json_encode( stm_get_taxonomies() )
			. '</script>'
			. '<input type="text" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
			'</div>';
	}
	
	vc_add_shortcode_param( 'stm_autocomplete_vc_taxonomies', 'stm_autocomplete_vc_st_taxonomies', STM_MOTORS_EXTENDS_URL . '/inc/vc_extends/jquery-ui.min.js' );
	
	function stm_autocomplete_vc_st_location( $settings, $value )
	{
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">
            jQuery("#ca_location_listing_filter_map").on("click", function() {
                
                var input = document.getElementById("ca_location_listing_filter_map");
                
                if(input !== null) {
                    
                    var autocomplete = new google.maps.places.Autocomplete(input);
        
                    google.maps.event.addListener(autocomplete, "place_changed", function (e) {
                        var place = autocomplete.getPlace();
        
                        var lat = "";
                        var lng = "";
        
                        if (typeof(place.geometry) != "undefined") {
                            lat = place.geometry.location.lat();
                            lng = place.geometry.location.lng();
                        } else {
                            lat = "";
                            lng = "";
                        }
        
                        jQuery("input[name=\'lat\']").val(lat);
                        jQuery("input[name=\'lng\']").val(lng);
                    });
        
                    google.maps.event.addDomListener(input, "keydown", function (e) {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                        }
                    });
                }
            });
            </script>'
			. '<input type="text" id="ca_location_listing_filter_map" name="' . esc_attr( $settings['param_name'] ) . '" class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
			'</div>';
	}
	
	vc_add_shortcode_param( 'stm_autocomplete_vc_location', 'stm_autocomplete_vc_st_location', STM_MOTORS_EXTENDS_URL . '/inc/vc_extends/jquery-ui.min.js' );
}

function stm_me_theme_deregister()
{
	
	if ( class_exists( 'woocommerce' ) && !defined('ULISTING_VERSION') ) {
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );
		
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
	}
	
	/*Deregister theme styles and scripts*/
	wp_dequeue_script( 'listings-frontend' );
	wp_deregister_style( 'listings-frontend' );
	
	wp_dequeue_style( 'listings-add-car' );
	wp_deregister_style( 'listings-add-car' );
	
	wp_dequeue_script( 'listings-add-car' );
	wp_deregister_script( 'listings-add-car' );
}

add_action( 'wp_enqueue_scripts', 'stm_me_theme_deregister', 999 );

function stm_me_theme_deregister_high()
{
	wp_dequeue_script( 'isotope' );
	wp_deregister_script( 'isotope' );
}

add_action( 'wp_enqueue_scripts', 'stm_me_theme_deregister_high', 10 );

/*WP MAIL FUNC*/

function stm_set_mail_html_content_type()
{
	return 'text/html';
}

function stm_me_set_html_content_type()
{

}

add_action( 'stm_set_html_content_type', 'stm_me_set_html_content_type' );

function stm_me_remove_mail_content_type_filter()
{

}

add_action( 'stm_remove_mail_content_type_filter', 'stm_me_remove_mail_content_type_filter' );

/*WP MAIL FUNC*/

function stm_remove_woo_widgets()
{
	unregister_widget( 'WC_Widget_Recent_Products' );
	unregister_widget( 'WC_Widget_Featured_Products' );
	//unregister_widget( 'WC_Widget_Product_Categories' );
	unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
	//unregister_widget( 'WC_Widget_Cart' );
	unregister_widget( 'WC_Widget_Layered_Nav' );
	unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
	//unregister_widget( 'WC_Widget_Price_Filter' );
	unregister_widget( 'WC_Widget_Product_Search' );
	//unregister_widget( 'WC_Widget_Top_Rated_Products' );
	unregister_widget( 'WC_Widget_Recent_Reviews' );
	unregister_widget( 'WC_Widget_Recently_Viewed' );
	unregister_widget( 'WC_Widget_Best_Sellers' );
	unregister_widget( 'WC_Widget_Onsale' );
	unregister_widget( 'WC_Widget_Random_Products' );
}

function stm_me_get_current_layout () {
    return get_option('stm_motors_chosen_template');
}

function stm_me_is_boats () {
    $boats = stm_me_get_current_layout();
	
	if ( $boats ) {
		if ( $boats == 'boats' ) {
			$boats = true;
		} else {
			$boats = false;
		}
	} else {
		if ( get_current_blog_id() == 4 ) return true;
		else return false;
	}
	
	return $boats;
}

function stm_me_is_dealer_two () {
	$dealer = stm_me_get_current_layout();
	
	if ( $dealer ) {
		if ( $dealer == 'car_dealer_two' ) {
			$dealer = true;
		} else {
			$dealer = false;
		}
	} else {
		if ( get_current_blog_id() == 9 ) return true;
		else return false;
	}
	
	return $dealer;
}

function stm_me_is_dealer () {
	$dealer = stm_me_get_current_layout();
	
	if ( $dealer ) {
		if ( $dealer == 'car_dealer' ) {
			$dealer = true;
		} else {
			$dealer = false;
		}
	} else {
		if ( get_current_blog_id() == 1 ) return true;
		else return false;
	}
	
	return $dealer;
}

if ( !function_exists( 'stm_me_is_listing' ) ) {
	function stm_me_is_listing( $only = array() )
	{
		if ( count( $only ) > 0 ) {
			$listing = stm_me_get_current_layout();
			
			foreach ( $only as $layout ) {
				if ( $layout == $listing ) return true;
			}
		}
		
		return false;
	}
}

function stm_me_posts_join_paged()
{
	remove_filter( 'posts_join_paged', 'stm_edit_join_posts' );
}

add_action( 'stm_me_edit_join_posts', 'stm_me_posts_join_paged' );

function stm_me_show_filter_by_location()
{
	remove_filter( 'posts_orderby', 'stm_show_filter_by_location' );
}

add_action( 'stm_me_posts_orderby', 'stm_me_show_filter_by_location' );

function stm_me_wp_mail( $to, $subject, $body, $headers )
{
	add_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
	wp_mail( $to, $subject, $body, $headers );
	remove_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
}

add_action( 'stm_wp_mail', 'stm_me_wp_mail', 10, 4 );

function stm_me_wp_mail_files( $to, $subject, $body, $headers, $files )
{
	add_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
	wp_mail( $to, $subject, $body, $headers, $files );
	remove_filter( 'wp_mail_content_type', 'stm_set_mail_html_content_type' );
}

add_action( 'stm_wp_mail_files', 'stm_me_wp_mail_files', 10, 5 );

function stm_me_get_global_server_val( $val )
{
	
	return $_SERVER[$val];
}

add_filter( 'stm_get_global_server_val', 'stm_me_get_global_server_val' );

function stm_me_balance_tags( $text )
{
	return balanceTags( $text, true );
}

add_filter( 'stm_balance_tags', 'stm_me_balance_tags' );

function stm_me_rental_add_meta_box()
{
	add_action( 'add_meta_boxes', 'stmMeAddPricePerHourMetaBox' );
}

function stmMeAddPricePerHourMetaBox()
{
	
	$title = __( 'Car Rent Price Info', 'stm_motors_extends' );
	
	if ( get_the_ID() != stm_get_wpml_product_parent_id( get_the_ID() ) ) {
		$title = __( 'Car Rent Price Info (This fields are not editable.)', 'stm_motors_extends' );
	}
	
	add_meta_box(
		'price_per_hour'
		, $title
		, array( 'PricePerHour', 'pricePerHourView' )
		, 'product'
		, 'advanced'
		, 'high'
	);
}

add_action( 'stm_rental_meta_box', 'stm_me_rental_add_meta_box' );


// Add hidden price before user can update plugin
function stm_add_genuine_price_hidden()
{
	add_meta_box( 'stm_genuine_price', 'stm genuine price', 'stm_me_do_genuine_price_hidden', stm_listings_post_type() );
}

function stm_me_do_genuine_price_hidden()
{
	add_action( 'add_meta_boxes', 'stm_add_genuine_price_hidden' );
}

add_action( 'stm_do_genuine_price_hidden', 'stm_me_do_genuine_price_hidden' );

if ( !function_exists( 'stm_paypal_url' ) ) {
	function stm_paypal_url()
	{
		$paypal_mode = stm_me_get_wpcfto_mod( 'paypal_mode', 'sandbox' );
		$paypal_url = ( $paypal_mode == 'live' ) ? 'www.paypal.com' : 'www.sandbox.paypal.com';
		
		return $paypal_url;
	}
}

if ( !function_exists( 'stm_check_payment' ) ) {
	
	function stm_check_payment( $data )
	{
		
		if ( !empty( $data['invoice'] ) ) {
			
			$invoice = $data['invoice'];
			
			$req = 'cmd=_notify-validate';
			
			foreach ( $data as $key => $value ) {
				$value = urlencode( stripslashes( $value ) );
				$req .= "&$key=$value";
			}
			
			echo 'https://' . stm_paypal_url() . '/cgi-bin/webscr';
			
			$ch = curl_init( 'https://' . stm_paypal_url() . '/cgi-bin/webscr' );
			curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
			curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
			curl_setopt( $ch, CURLOPT_SSLVERSION, 6 );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) );
			
			if ( !( $res = curl_exec( $ch ) ) ) {
				echo( "Got " . curl_error( $ch ) . " when processing IPN data" );
				
				curl_close( $ch );
				
				return false;
			}
			curl_close( $ch );
			
			if ( strcmp( $res, "VERIFIED" ) == 0 ) {
				
				update_user_meta( intval( $invoice ), 'stm_payment_status', 'completed' );
				
				$member_admin_email_subject = esc_html__( 'New Payment received', 'stm_motors_extends' );
				$member_admin_email_message = esc_html__( 'User paid for submission. User ID:', 'stm_motors_extends' ) . ' ' . $invoice;
				
				do_action( 'stm_set_html_content_type' );
				
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
				$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( apply_filters( 'stm_get_global_server_val', 'SERVER_NAME' ) ) );
				$headers = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";
				
				do_action( 'stm_wp_mail_files', get_bloginfo( 'admin_email' ), $member_admin_email_subject, nl2br( $member_admin_email_message ), $headers );
			}
		}
	}
}

if ( !empty( $_GET['stm_check_membership_payment'] ) ) {
	header( 'HTTP/1.1 200 OK' );
	stm_check_payment( $_REQUEST );
	exit;
}

function stm_me_create_dir()
{
	global $wp_filesystem;
	
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}
	
	$upload_dir = wp_upload_dir();
	
	$wp_filesystem->mkdir( $upload_dir['basedir'] . '/stm_uploads', FS_CHMOD_DIR );
}

add_action( 'stm_create_dir', 'stm_me_create_dir' );

function stm_me_modify_key ($key) {
	return strtolower( str_replace( array(' ', '/'), '_', $key ) );
}

