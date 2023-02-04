<?php
new Motors_WPCFTO();

class Motors_WPCFTO
{
	
	private $currentLayout = '';
	
	public function __construct()
	{
		$this->currentLayout = get_option( 'stm_motors_chosen_template', 'car_dealer' );
		
		if(get_transient('temp_setup_layout')) {
			$this->currentLayout = get_transient('temp_setup_layout');
		}
		
		add_filter( 'wpcfto_field_stm-hidden', function () {
			return STM_MOTORS_EXTENDS_PATH . '/inc/wpcfto_conf/custom_fields/stm-hidden.php';
		} );
		
		add_action( 'init', array( $this, 'layout_conf_autoload' ) );
		add_action( 'admin_bar_menu', array( $this, 'stm_me_admin_bar_item'), 500 );
		add_action( 'wp_ajax_wpcfto_save_settings', array( $this, 'motors_save_settings' ), 9, 1 );
		add_action( 'wp_ajax_stm_demo_import_content_after_generate_wpcfto_css', array( $this, 'motors_save_settings' ), 20, 1 );
		add_filter( 'wpcfto_options_page_setup', array( $this, 'motors_layout_options' ) );
	}

	function stm_me_admin_bar_item ( WP_Admin_Bar $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$admin_bar->add_menu( array(
			'id'    => 'stm-me-theme-options',
			'parent' => null,
			'group'  => null,
			'title' => '<span class="ab-icon"><img style="width: 20px !important" src="' . get_template_directory_uri() . '/assets/admin/images/icon.png' . '" /></span><span class="ab-label">' . esc_html__('Theme Options', 'stm_motors_extends') , '</span>',
			'href'  => admin_url( '?page=wpcfto_motors_' . $this->currentLayout . '_settings' ),
			'meta' => [
				'title' => esc_html__('Theme Options', 'stm_motors_extends'),
			]
		) );
	}
	
	public function layout_conf_autoload()
	{
		$configMap = array(
			'header_sm_logo' => [ 'all' ],
			'header_sm_menu' => [ 'all' ],
			'header_sm_socials' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'listing_five', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'service' ],
			'header_sm_buttons' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'listing_five', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'service' ],
			'stm_paypal_options_conf' => [ 'listing', 'listing_two', 'listing_three', 'listing_four' ],
			'site_style_conf' => [ 'all' ],
			'general_conf' => [ 'all' ],
			'top_bar_conf' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'listing_five', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'service' ],
			'header_layout_conf' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'listing_five', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'rental_two', 'service' ],
			'blog_conf' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'listing_five', 'listing_six', 'motorcycle', 'aircrafts', 'boats', 'car_rental' ],
			'inventory_conf' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'motorcycle', 'aircrafts', 'boats' ],
			'car_settings_conf' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'motorcycle', 'aircrafts', 'boats' ],
			'sell_a_car' => ['car_dealer_two'],
			'user_dealer_conf' => [ 'listing', 'listing_two', 'listing_three', 'listing_four' ],
			'rental_layout_conf' => [ 'car_rental', 'rental_two' ],
			'auto_parts_layout_conf' => [ 'auto_parts' ],
			'shop_conf' => [ 'car_dealer', 'car_dealer_two', 'car_magazine', 'equipment', 'listing', 'listing_two', 'listing_three', 'listing_four', 'motorcycle', 'aircrafts', 'boats', 'car_rental', 'rental_two', 'auto_parts' ],
			'typography_conf' => [ 'all' ],
			'socials_conf' => [ 'all' ],
			'footer_layout_conf' => [ 'all' ],
			'custom_css_conf' => [ 'all' ],
			'custom_js_conf' => [ 'all' ],
			'stm_motors_events' => [ 'car_magazine' ],
			'stm_motors_review' => [ 'car_magazine', 'listing_two', 'listing_three' ]
		);
		
		foreach ( $configMap as $file_name => $layouts ) {
			if ( $layouts[0] == 'all' || in_array( stm_me_get_current_layout(), $layouts ) ) {
				require_once( STM_MOTORS_EXTENDS_PATH . '/inc/wpcfto_conf/layout_conf/' . $file_name . '.php' );
			}
		}
	}
	
	public function motors_layout_options( $setup )
	{
		$opts = apply_filters( 'motors_get_all_wpcfto_config', array() );
		
		$setup[] = array(
			/*
			 * Here we specify option name. It will be a key for storing in wp_options table
			 */
			'option_name' => 'wpcfto_motors_' . $this->currentLayout . '_settings',
			
			'title' => esc_html__( 'Theme options', 'stm_motors_extends' ),
			'sub_title' => esc_html__( 'by StylemixThemes', 'stm_motors_extends' ),
			'logo' => get_template_directory_uri() . '/assets/admin/images/logo.png',
			
			/*
			 * Next we add a page to display our awesome settings.
			 * All parameters are required and same as WordPress add_menu_page.
			 */
			'page' => array(
				'page_title' => 'Theme Options',
				'menu_title' => 'Theme Options',
				'menu_slug' => 'wpcfto_motors_' . $this->currentLayout . '_settings',
				'icon' => get_template_directory_uri() . '/assets/admin/images/icon.png',
				'position' => 3,
			),
			
			/*
			 * And Our fields to display on a page. We use tabs to separate settings on groups.
			 */
			'fields' => $opts
		);
		
		return $setup;
	}
	
	public function motors_save_settings( $layout = '' )
	{
		if(isset($_GET['stm_demo_import_template'])) $layout = esc_html($_GET['stm_demo_import_template']);
		
		if ( !current_user_can( 'manage_options' ) ) {
			die;
		}
		
		if ( empty( $layout ) ) {
			check_ajax_referer( 'wpcfto_save_settings', 'nonce' );
			if ( empty( $_REQUEST['name'] ) ) {
				die;
			}
		}
		
		global $wp_filesystem;
		
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		
		$styles = '';
		
		if ( empty( $layout ) ) {
			$request_body = file_get_contents( 'php://input' );
			if ( !empty( $request_body ) ) {
				$request_body = json_decode( $request_body, true );
				$styles = self::stm_me_collect_wpcfto_styles( $request_body );
			}
		} else {
			$options = wpcfto_get_settings_map( 'settings', 'wpcfto_motors_' . $layout . '_settings' );
			
			$styles = self::stm_me_collect_wpcfto_styles( $options );
		}
		
		$upload_dir = wp_upload_dir();
		
		if ( !$wp_filesystem->is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
			do_action( 'stm_create_dir' );
		}
		
		if ( !empty( $styles ) ) {
			$css_to_filter = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles );
			$css_to_filter = str_replace( array(
				"\r\n",
				"\r",
				"\n",
				"\t",
				'  ',
				'    ',
				'    '
			), '', $css_to_filter );
			
			$custom_style_file = $upload_dir['basedir'] . '/stm_uploads/wpcfto-generate.css';
			
			$wp_filesystem->put_contents( $custom_style_file, $css_to_filter, FS_CHMOD_FILE );
			
			$current_style = get_option( 'stm_wpcfto_style', '1' );
			update_option( 'stm_wpcfto_style', $current_style + 1 );
		}
	}
	
	private function stm_me_collect_wpcfto_styles( $request_body )
	{
		$styles = '';
		
		$curentLayout = $request_body['general_tab']['fields']['header_current_layout']['value'];
		$headerLayout = $request_body['header']['fields']['header_layout']['value'];
		
		foreach ( $request_body as $section_name => $section ) {
			foreach ( $section['fields'] as $field_name => $field ) {
				
				if ( !empty( $field['output'] ) && !empty( $field['value'] ) ) {
					
					if(isset($field['dependency']) && !$this->stm_me_parse_dependency($request_body, $section['fields'], $field['dependency'], (isset($field['dependencies'])) ? $field['dependencies'] : '', $curentLayout, $headerLayout)) continue;
				
					$units = '';
					$important = ( isset( $field['style_important'] ) ) ? ' !important' : '';
					
					if ( !empty( $field['units'] ) ) {
						$units = $field['units'];
					}
					
					if ( is_array( $field['mode'] ) ) {
						foreach ( $field['mode'] as $mode ) {
							$styles .= $field['output'] . '{' . $mode . ':' . $field['value'] . $units . $important . ';}';
						}
					} else {
						if ( $field['type'] == 'spacing' && !empty( $field['mode'] ) ) {
							$unit = $field['value']['unit'];
							$top = ( $field['value']['top'] === "0" || (int) $field['value']['top'] > 0 ) ? $field['mode'] . '-top: ' . $field['value']['top'] . $unit . ' ' . $important . ';' : '';
							$left = ( $field['value']['left'] === "0" || (int) $field['value']['left'] > 0 ) ? $field['mode'] . '-left: ' . $field['value']['left'] . $unit . ' ' . $important . ';' : '';
							$right = ( $field['value']['right'] === "0" || (int) $field['value']['right'] > 0 ) ? $field['mode'] . '-right: ' . $field['value']['right'] . $unit . ' ' . $important . ';' : '';
							$bottom = ( $field['value']['bottom'] === "0" || (int) $field['value']['bottom'] > 0 ) ? $field['mode'] . '-bottom: ' . $field['value']['bottom'] . $unit . ' ' . $important . ';' : '';
							
							$styles .= $field['output'] . '{' . $top . ' ' . $right . ' ' . $bottom . ' ' . $left . '}';
						} elseif ( $field['type'] == 'typography' ) {
							$styles .= $field['output'] . '{';
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'font-family', $field['excluded'] ) ) ) ) $styles .= 'font-family:' . $field['value']['font-family'];
							if ( !empty( $field['value']['backup-font'] ) )
								$styles .= ', ' . $field['value']['backup-font'];
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'color', $field['excluded'] ) ) ) ) $styles .= '; color:' . $field['value']['color'] . ' ' . $important . ';';
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'font-size', $field['excluded'] ) ) ) ) $styles .= '; font-size:' . $field['value']['font-size'] . 'px';
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'line-height', $field['excluded'] ) ) ) ) $styles .= '; line-height:' . $field['value']['line-height'] . 'px';
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'font-weight', $field['excluded'] ) ) ) ) $styles .= '; font-weight:' . $field['value']['font-weight'];
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'font-style', $field['excluded'] ) ) ) ) $styles .= '; font-style:' . $field['value']['font-style'];
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'text-align', $field['excluded'] ) ) ) ) $styles .= '; text-align:' . $field['value']['text-align'];
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'text-transform', $field['excluded'] ) ) ) ) $styles .= '; text-transform:' . $field['value']['text-transform'];
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'letter-spacing', $field['excluded'] ) ) ) ) $styles .= '; letter-spacing:' . $field['value']['letter-spacing'] . 'px';
							if ( !isset( $field['excluded'] ) || ( ( isset( $field['excluded'] ) && !in_array( 'word-spacing', $field['excluded'] ) ) ) ) $styles .= '; word-spacing:' . $field['value']['word-spacing'] . 'px';
							$styles .= '; }';
						} else {
							if ( $field_name == 'hma_underline' || $field_name == 'hma_hover_underline' ) {
								$styles .= $field['output'] . '{' . $field['mode'] . ': 2px solid ' . $field['value'] . $important . ';}';
							} else {
								$styles .= $field['output'] . '{' . $field['mode'] . ':' . $field['value'] . $units . $important . ';}';
							}
						}
					}
				}
			}
		}
		
		return $styles;
	}
	
	private function stm_me_parse_dependency($configAll, $configSection, $dependency, $dependencies = '', $curentLayout, $headerLayout) {
		
		if(empty($dependencies)) {
			$options = explode('||', $dependency['value']);
			foreach ( $options as $opt ) {
				if($dependency['key'] == 'header_current_layout') {
					if($curentLayout == $opt) return true;
				} else if($dependency['key'] == 'header_layout') {
					if($headerLayout == $opt) return true;
				} else if($dependency['value'] == 'not_empty') {
					if(isset($dependency['section'])) {
						if(!empty($configAll[$dependency['section']]['fields'][$dependency['key']])) return true;
					} else {
						if(!empty($configSection[$dependency['key']])) return true;
					}
				}
			}
		} else {
			$boolArray = array();
			foreach ($dependency as $k => $depends) {
				$boolOpt = [];
				$options = explode( '||', $depends['value'] );
				
				foreach ( $options as $opt ) {
					if($depends['key'] == 'header_current_layout') {
						if($curentLayout == $opt) $boolOpt[] = 1;
					} else if($depends['key'] == 'header_layout') {
						if($headerLayout == $opt) $boolOpt[] = 1;
					} else if($depends['value'] == 'not_empty') {
						if(isset($depends['section'])) {
							if(!empty($configAll[$depends['section']]['fields'][$depends['key']])) $boolOpt[] = 1;
						} else {
							if(!empty($configSection[$depends['key']])) $boolOpt[] = 1;
						}
					}
				}
				
				$boolArray[] = (count($boolOpt) == 0) ? 0 : 1;
			}
			
			if($dependencies == '||' && array_sum($boolArray) > 0) return true;
			if($dependencies == '&&' && array_sum($boolArray) == count($boolArray)) return true;
		}
		
		return false;
	}
}

add_filter( 'stm_me_get_wpcfto_mod', 'stm_me_motors_get_wpcfto_mod', 10, 2 );
function stm_me_motors_get_wpcfto_mod( $opt_name, $default = '', $returnDefault = false )
{
	$wpcftoOptName = 'wpcfto_motors_' . stm_me_get_current_layout() . '_settings';
	$options = get_option( $wpcftoOptName, array() );
	
	if ( !empty( $options[$opt_name] ) ) {
		return apply_filters( 'wpcfto_motors_' . $opt_name, $options[$opt_name] );
	}
	
	return ($returnDefault) ? $default : false;
}

function stm_me_set_wpcfto_mod( $opt_name, $value )
{
	$wpcftoOptName = 'wpcfto_motors_' . stm_me_get_current_layout() . '_settings';
	$options = get_option( $wpcftoOptName, array() );
	
	if ( !empty( $options[$opt_name] ) ) {
		$options[$opt_name] = apply_filters( 'wpcfto_motors_set_option_' . $opt_name, $value );
	}
	
	update_option( $wpcftoOptName, $options );
}

function stm_me_wpcfto_parse_spacing( $settings )
{
	if ( empty( $settings ) ) return '';
	
	$style = ( !empty( $settings['top'] ) ) ? 'margin-top: ' . $settings['top'] . 'px; ' : '';
	$style .= ( !empty( $settings['right'] ) ) ? 'margin-right: ' . $settings['right'] . 'px; ' : '';
	$style .= ( !empty( $settings['bottom'] ) ) ? 'margin-bottom: ' . $settings['bottom'] . 'px; ' : '';
	$style .= ( !empty( $settings['left'] ) ) ? 'margin-left: ' . $settings['left'] . 'px; ' : '';
	
	return $style;
}

function stm_me_get_wpcfto_icon( $option_name, $default_icon, $other_classes = '' )
{
	$icon_array = stm_me_motors_get_wpcfto_mod( $option_name, false );

	$style_array = [];

	// if color is not default
	if(!empty($icon_array['color']) && $icon_array['color'] != '#000') {
		$style_array['color'] = $icon_array['color'];
	}

	// if icon size is not default
	if(!empty($icon_array['size']) && $icon_array['size'] != 15) {
		$style_array['size'] = $icon_array['size'];
	}

	// if icon is set
	if ( $icon_array && !empty($icon_array['icon']) ) {
		$default_icon = $icon_array['icon'];
	}

	// style string
	$style_string = '';
	if(!empty($style_array['color'])) $style_string .= 'color: '. $style_array['color'] .'; ';
	if(!empty($style_array['size'])) $style_string .= 'font-size: '. $style_array['size'] .'px;';

	$icon_element = '<i class="' . esc_attr( $default_icon . ' ' . $other_classes ) . '" style="' . esc_attr( $style_string ) . '"></i>';

	return $icon_element;
}

add_filter( 'stm_me_get_wpcfto_img_src', 'stm_me_motors_get_wpcfto_img_src', 10, 3 );
function stm_me_motors_get_wpcfto_img_src( $opt_name, $default, $size = 'full' )
{
	$logo_main = stm_me_motors_get_wpcfto_mod( $opt_name, $default, true );
	
	return ( is_int( $logo_main ) ) ? wp_get_attachment_image_url( $logo_main, $size ) : $logo_main;
}

function stm_me_wpcfto_sidebars()
{
	$sidebars = array(
		'no_sidebar' => esc_html__( 'Without sidebar', 'stm_motors_extends' ),
		'default' => esc_html__( 'Primary sidebar', 'stm_motors_extends' ),
	);
	
	$query = get_posts( array( 'post_type' => 'sidebar', 'posts_per_page' => -1 ) );
	
	if ( $query ) {
		foreach ( $query as $post ) {
			$sidebars[$post->ID] = get_the_title( $post->ID );
		}
	}
	
	return $sidebars;
}

function stm_me_wpcfto_pages_list()
{
	$post_types[] = __( '--- Default ---', 'stm_motors_extends' );
	$query = get_posts( array( 'post_type' => 'page', 'posts_per_page' => -1 ) );
	
	if ( $query ) {
		foreach ( $query as $post ) {
			$post_types[$post->ID] = get_the_title( $post->ID );
		}
	}
	
	return $post_types;
}

function stm_me_wpcfto_socials()
{
	$socials = array(
		'facebook' => esc_html__( 'Facebook', 'stm_motors_extends' ),
		'twitter' => esc_html__( 'Twitter', 'stm_motors_extends' ),
		'vk' => esc_html__( 'VK', 'stm_motors_extends' ),
		'instagram' => esc_html__( 'Instagram', 'stm_motors_extends' ),
		'behance' => esc_html__( 'Behance', 'stm_motors_extends' ),
		'dribbble' => esc_html__( 'Dribbble', 'stm_motors_extends' ),
		'flickr' => esc_html__( 'Flickr', 'stm_motors_extends' ),
		'git' => esc_html__( 'Git', 'stm_motors_extends' ),
		'linkedin' => esc_html__( 'Linkedin', 'stm_motors_extends' ),
		'pinterest' => esc_html__( 'Pinterest', 'stm_motors_extends' ),
		'yahoo' => esc_html__( 'Yahoo', 'stm_motors_extends' ),
		'delicious' => esc_html__( 'Delicious', 'stm_motors_extends' ),
		'dropbox' => esc_html__( 'Dropbox', 'stm_motors_extends' ),
		'reddit' => esc_html__( 'Reddit', 'stm_motors_extends' ),
		'soundcloud' => esc_html__( 'Soundcloud', 'stm_motors_extends' ),
		'google' => esc_html__( 'Google', 'stm_motors_extends' ),
		'google-plus' => esc_html__( 'Google +', 'stm_motors_extends' ),
		'skype' => esc_html__( 'Skype', 'stm_motors_extends' ),
		'youtube' => esc_html__( 'Youtube', 'stm_motors_extends' ),
		'youtube-play' => esc_html__( 'Youtube Play', 'stm_motors_extends' ),
		'tumblr' => esc_html__( 'Tumblr', 'stm_motors_extends' ),
		'whatsapp' => esc_html__( 'Whatsapp', 'stm_motors_extends' ),
	);
	
	return $socials;
}

function stm_me_wpcfto_kv_socials()
{
	$socials = stm_me_wpcfto_socials();
	
	$newSocials = array();
	
	foreach ( $socials as $k => $social ) {
		$newSocials[] = array(
			'key' => $k,
			'label' => $social
		);
	}
	
	return $newSocials;
}

function stm_me_wpcfto_headers_list()
{
	$headers = array(
		'car_dealer' => esc_html__( 'Dealer', 'stm_motors_extends' ),
		'car_dealer_two' => esc_html__( 'Dealer Two', 'stm_motors_extends' ),
		'listing' => esc_html__( 'Classified', 'stm_motors_extends' ),
		'listing_five' => esc_html__( 'Classified Five', 'stm_motors_extends' ),
		'boats' => esc_html__( 'Boats', 'stm_motors_extends' ),
		'motorcycle' => esc_html__( 'Motorcycle', 'stm_motors_extends' ),
		'car_rental' => esc_html__( 'Rental', 'stm_motors_extends' ),
		'car_magazine' => esc_html__( 'Magazine', 'stm_motors_extends' ),
		'aircrafts' => esc_html__( 'Aircrafts', 'stm_motors_extends' ),
		'equipment' => esc_html__( 'Equipment', 'stm_motors_extends' ),
	);
	
	return $headers;
}

add_filter( 'stm_selected_header', 'stm_me_get_header_layout' );

function stm_me_get_header_layout()
{
	$selLayout = get_option( 'stm_motors_chosen_template' );
	
	if ( empty( $selLayout ) ) return 'car_dealer';
	
	$arrHeader = array(
		'service' => 'car_dealer',
		'listing_two' => 'listing',
		'listing_three' => 'listing',
		'listing_four' => 'car_dealer',
	);
	
	$defaultHeader = ( !empty( $arrHeader[$selLayout] ) ) ? $arrHeader[$selLayout] : $selLayout;
	
	/*
	 * aircrafts
	 * boats
	 * car_dealer
	 * car_dealer_two
	 * equipment
	 * listing
	 * listing_five
	 * magazine
	 * motorcycle
	 * car_rental
	 * */
	
	if ( stm_is_listing_six() ) return 'listing_five';
	
	return stm_me_motors_get_wpcfto_mod( 'header_layout', $defaultHeader, true );
}

function stm_me_wpcfto_positions()
{
	$positions = array(
		'left' => esc_html__( 'Left', 'stm_motors_extends' ),
		'right' => esc_html__( 'Right', 'stm_motors_extends' ),
	);
	
	return $positions;
}

function stm_me_wpcfto_sortby()
{
	$sortBy = array(
		'date_high' => esc_html__( 'Date: newest first', 'stm_motors_extends' ),
		'date_low' => esc_html__( 'Date: oldest first', 'stm_motors_extends' ),
		'price_low' => esc_html__( 'Price: lower first', 'stm_motors_extends' ),
		'price_high' => esc_html__( 'Price: highest first', 'stm_motors_extends' ),
		'mileage_low' => esc_html__( 'Mileage: lowest first', 'stm_motors_extends' ),
		'mileage_high' => esc_html__( 'Mileage: highest first', 'stm_motors_extends' ),
	);
	
	return $sortBy;
}

add_filter( 'wpcfto_icons_set', 'stm_me_wpcfto_custom_icons' );

function stm_me_wpcfto_custom_icons( $iconsCollect )
{
	$iconsConfMap = array(
		'aircrafts_icons',
		'auto_parts_icons',
		'listing_icons',
		'magazine_icons',
		'boat_icons',
		'moto_icons',
		'rental_one_icons',
		'service_icons'
	);
	
	foreach ( $iconsConfMap as $file_name ) {
		if(file_exists(get_stylesheet_directory() . '/assets/icons_json/' . $file_name . '.json')) {
			$iconConfig = json_decode( file_get_contents( get_template_directory_uri() . '/assets/icons_json/' . $file_name . '.json' ) );
			
			$prefix = $iconConfig->preferences->fontPref->prefix;
			
			foreach ( $iconConfig->icons as $k => $icon ) {
				$iconsCollect[] = array( 'title' => $prefix . $icon->properties->name, 'searchTerms' => array( $icon->properties->name ) );
			}
		}
	}
	
	if ( defined('CEI_CLASSES_PATH') ) {
		$extra_fonts = get_option( 'stm_fonts' );
		if ( empty( $extra_fonts ) ) {
			$extra_fonts = array();
		}
		$font_configs = $extra_fonts;
		
		$upload_dir = wp_upload_dir();
		$path = trailingslashit( $upload_dir['basedir'] );
		$url = trailingslashit( $upload_dir['baseurl'] );
		
		foreach ( $font_configs as $key => $config ) {
			if ( empty( $config['full_path'] ) ) {
				$font_configs[$key]['include'] = $path . $font_configs[$key]['include'];
				$font_configs[$key]['folder'] = $url . $font_configs[$key]['folder'];
			}
		}

		if ( !empty($font_configs) ) {
			
			foreach ( $font_configs as $k => $val ) {

				if(empty($font_configs[$k]['json'])) continue;

				$config_exists = file_exists($font_configs[$k]['include'] . '/' . $font_configs[$k]['config']);
				$json_exists = file_exists($font_configs[$k]['include'] . '/' . $font_configs[$k]['json']);

				if( $config_exists && $json_exists ) {

					require_once $font_configs[$k]['include'] . '/' . $font_configs[$k]['config'];

					$selection = json_decode(file_get_contents($font_configs[$k]['include'] . '/' . $font_configs[$k]['json']), true);

					if(!empty($selection)) {
						if(!empty($selection['preferences']) && !empty($selection['preferences']['fontPref']) && !empty($selection['preferences']['fontPref']['prefix'])) {
							$prefix = $selection['preferences']['fontPref']['prefix'];

							if(!isset($icons)) continue;

							foreach ( $icons[$k] as $key => $item ) {
								$iconsCollect[] = array( 'title' => $prefix . $item['class'], 'searchTerms' => array( $item['tags'] ) );
							}
						}
					}
				}

			}
		}
	}
	
	return $iconsCollect;
}

function wpcfto_print_settings()
{
	echo json_encode( get_option( 'wpcfto_motors_' . get_option( 'stm_motors_chosen_template', 'car_dealer' ) . '_settings', true ) );
	die;
}