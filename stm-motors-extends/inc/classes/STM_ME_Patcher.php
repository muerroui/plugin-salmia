<?php
new STM_ME_Patcher();

class STM_ME_Patcher
{
	private static $currentLayout = '';
	private static $updates = [
		'1.0.1' => [
			'migrate_from_customizer_to_wpcfto'
		]
	];
	
	public function __construct()
	{
		self::$currentLayout = get_option( 'stm_motors_chosen_template' );
		
		add_action( 'init', array( self::class, 'init_patcher' ), 100, 1 );
	}
	
	public static function init_patcher()
	{
		if ( version_compare( get_option( 'motors_extends_version', '1.4.3' ), STM_MOTORS_EXTENDS_PLUGIN_VERSION, '<' ) ) {
			self::update_version();
		}
	}
	
	public static function get_updates()
	{
		return self::$updates;
	}
	
	public static function needs_to_update()
	{
		$current_db_version = get_option( 'motors_extends_db_version' );
		$update_versions = array_keys( self::get_updates() );
		usort( $update_versions, 'version_compare' );
		
		return !is_null( $current_db_version ) && version_compare( $current_db_version, end( $update_versions ), '<' );
	}
	
	private static function maybe_update_db_version()
	{
		if ( self::needs_to_update() ) {
			$current_db_version = get_option( 'motors_extends_db_version', '1.0.0' );
			$updates = self::get_updates();
			
			foreach ( $updates as $version => $callback_arr ) {
				if ( version_compare( $current_db_version, $version, '<' ) ) {
					foreach ( $callback_arr as $callback ) {
						call_user_func( [ self::class, $callback ] );
					}
				}
			}
		}
		
		update_option( 'motors_extends_db_version', STM_MOTORS_EXTENDS_DB_VERSION, true );
	}
	
	public static function update_version()
	{
		update_option( 'motors_extends_version', STM_MOTORS_EXTENDS_PLUGIN_VERSION, true );
		self::maybe_update_db_version();
	}
	
	private static function migrate_from_customizer_to_wpcfto()
	{
		$opts = apply_filters( 'motors_get_all_wpcfto_config', array() );
		
		if ( !empty( $opts ) ) {
			$settings = array();
			
			$optionsTypography = array(
				'logo_font_family',
				'typography_menu_font_family',
				'typography_body_font_family',
				'typography_heading_font_family',
			);
			
			$optionsHeadingsTypography = array(
				'typography_h1_font_size',
				'typography_h2_font_size',
				'typography_h3_font_size',
				'typography_h4_font_size',
				'typography_h5_font_size',
				'typography_h6_font_size'
			);
			
			$optionsDefaultHeadingsSizeTypography = array(
				'typography_h1_font_size' => '50',
				'typography_h2_font_size' => '36',
				'typography_h3_font_size' => '24',
				'typography_h4_font_size' => '16',
				'typography_h5_font_size' => '14',
				'typography_h6_font_size' => '12'
			);
			
			$optionsSpacing = array(
				'menu_icon_top_margin',
				'menu_top_margin',
				'logo_margin_top',
				'hma_item_padding',
			);
			
			foreach ( $opts as $section_name => $section ) {
				foreach ( $section['fields'] as $field_name => $field ) {
					
					if($field_name == 'typography_main_menu_font_settings') {
						$settings[$field_name] = array(
							'font-family' => get_theme_mod( 'typography_menu_font_family', 'Montserrat' ),
							'font-weight' => 700,
							'font-style' => 'normal',
							'subset' => 'latin',
							'color' => get_theme_mod( 'typography_menu_color', '#ffffff' ),
							'font-size' => get_theme_mod( 'typography_menu_font_size', '13' ),
							'line-height' => get_theme_mod( 'typography_menu_font_size', '13' ) + 6,
							'text-align' => 'left',
							'word-spacing' => '0',
							'letter-spacing' => '0',
							'backup-font' => 'Arial',
							'google-weight' => 'regular',
							'font-data' => self::get_font_data_by_font_name(get_theme_mod( 'typography_heading_font_family', 'Montserrat' ))
						);
						
						continue;
					}
					
					if ( in_array( $field_name, $optionsTypography ) ) {
						switch ( $field_name ) {
							case 'logo_font_family':
								$value = self::get_default_logo_font_settings();
								break;
							case 'typography_menu_font_family':
								$value = self::get_default_menu_font_settings();
								break;
							case 'typography_body_font_family':
								$value = self::get_default_body_font_settings();
								break;
							case 'typography_heading_font_family':
								$value = self::get_default_heading_font_settings();
								break;
						}
						
						$settings[$field_name] = $value;
						continue;
					}
					
					if(in_array($field_name, $optionsHeadingsTypography )) {
						
						$fw = ( in_array( $field_name, array( 'typography_h5_font_size', 'typography_h6_font_size' ) ) ) ? 400 : 700;
						
						$settings[$field_name] = array(
							'font-weight' => $fw,
							'font-style' => 'normal',
							'subset' => 'latin',
							'color' => get_theme_mod( 'typography_heading_color', '#232628' ),
							'font-size' => get_theme_mod( $field_name, $optionsDefaultHeadingsSizeTypography[$field_name] ),
							'line-height' => get_theme_mod( $field_name, $optionsDefaultHeadingsSizeTypography[$field_name] ) + 6,
							'text-align' => 'left',
							'word-spacing' => '0',
							'letter-spacing' => '0',
							'backup-font' => 'Arial',
							'google-weight' => 'regular',
							'font-data' => self::get_font_data_by_font_name(get_theme_mod( 'typography_heading_font_family', 'Montserrat' ))
						);
						
						continue;
					}
					
					if ( in_array( $field_name, $optionsSpacing ) ) {
						$value = array(
							'top' => get_theme_mod( $field_name, '' ),
							'left' => '',
							'right' => '',
							'bottom' => '',
							'unit' => 'px',
						);
					}
					
					if ( !empty( get_theme_mod( $field_name ) ) ) {
						
						$value = get_theme_mod( $field_name );
						
						if ( $field['type'] == 'image' ) {
							$value = attachment_url_to_postid( $value );
						}
						
						if ( in_array( $field_name, array( 'footer_socials_enable', 'header_socials_enable', 'top_bar_socials_enable' ) ) ) {
							$value = explode( ',', $value );
						}
						
						if ( $field_name == 'currency_list' ) {
							if ( get_theme_mod( "currency_list" ) ) {
								$currList = json_decode( get_theme_mod( "currency_list" ) );
								$currency = explode( ",", $currList->currency );
								$symbol = explode( ",", $currList->symbol );
								$to = explode( ",", $currList->to );
								
								$wpcftoCurrConf = array();
								
								for ( $i = 0; $i < count( $currency ); $i++ ) {
									$wpcftoCurrConf[$i]['closed_tab'] = 1;
									$wpcftoCurrConf[$i]['currency'] = $currency[$i];
									$wpcftoCurrConf[$i]['symbol'] = $symbol[$i];
									$wpcftoCurrConf[$i]['to'] = $to[$i];
								}
								
								$value = $wpcftoCurrConf;
							}
							
						}
						
						if ( $field_name == 'socials_link' ) {
							$socials_values = array();
							parse_str( $value, $socials_values );
							
							$value = array();
							foreach ( $socials_values as $k => $val ) {
								$value[] = array(
									'key' => $k,
									'value' => $val
								);
							}
						}
						
						$settings[$field_name] = $value;
					} else if (isset($field['value']) && !empty($field['value'])) {
						$value = $field['value'];
						$settings[$field_name] = $value;
					}
				}
			}
			
			update_option( 'wpcfto_motors_' . self::$currentLayout . '_settings', $settings );
			do_action('stm_importer_done', self::$currentLayout);
		}
	}
	
	private static function get_font_data_by_font_name( $fontName )
	{
		$fontsData = json_decode( file_get_contents( STM_MOTORS_EXTENDS_PATH . '/nuxy/metaboxes/assets/webfonts/google-fonts.json' ), true );
		
		foreach ( $fontsData['items'] as $item ) {
			if ( $item['family'] == $fontName ) {
				return $item;
			}
		}
	}
	
	private static function get_default_logo_font_settings()
	{
		
		return array(
			'font-family' => get_theme_mod( 'logo_font_family', 'Montserrat' ),
			'font-weight' => '500',
			'font-style' => 'normal',
			'subset' => 'latin',
			'color' => get_theme_mod( 'logo_color', '#000' ),
			'font-size' => get_theme_mod( 'logo_font_size', '24' ),
			'line-height' => '22',
			'text-align' => 'left',
			'word-spacing' => '0',
			'letter-spacing' => '0',
			'backup-font' => 'Arial',
			'google-weight' => '500',
			'font-data' => self::get_font_data_by_font_name(get_theme_mod( 'logo_font_family', 'Montserrat' ))
		);
	}
	
	private static function get_default_menu_font_settings()
	{
		return array(
			'font-family' => get_theme_mod( 'typography_menu_font_family', 'Montserrat' ),
			'font-weight' => '700',
			'font-style' => 'normal',
			'subset' => 'latin',
			'color' => get_theme_mod( 'typography_menu_color', '#232628' ),
			'font-size' => get_theme_mod( 'typography_menu_font_size', '13' ),
			'line-height' => '52',
			'text-align' => 'left',
			'word-spacing' => '0',
			'letter-spacing' => '0',
			'backup-font' => 'Arial',
			'google-weight' => '700',
			'font-data' => self::get_font_data_by_font_name(get_theme_mod( 'typography_menu_font_family', 'Montserrat' ))
		);
	}
	
	private static function get_default_body_font_settings()
	{
		return array(
			'font-family' => get_theme_mod( 'typography_body_font_family', 'Open Sans' ),
			'font-weight' => 400,
			'font-style' => 'normal',
			'subset' => 'latin',
			'color' => get_theme_mod( 'typography_body_color', '#232628' ),
			'font-size' => get_theme_mod( 'typography_body_font_size', '14' ),
			'line-height' => get_theme_mod( 'typography_body_line_height', '22' ),
			'text-align' => 'left',
			'word-spacing' => '0',
			'letter-spacing' => '0',
			'backup-font' => 'Arial',
			'google-weight' => 'regular',
			'font-data' => self::get_font_data_by_font_name(get_theme_mod( 'typography_body_font_family', 'Open Sans' ))
		);
	}
	
	private static function get_default_heading_font_settings()
	{
		return array(
			'font-family' => get_theme_mod( 'typography_heading_font_family', 'Montserrat' ),
			'font-weight' => '500',
			'font-style' => 'normal',
			'subset' => 'latin',
			'color' => get_theme_mod( 'typography_heading_color', '#232628' ),
			'font-size' => '14',
			'line-height' => '20',
			'text-align' => 'left',
			'word-spacing' => '0',
			'letter-spacing' => '0',
			'backup-font' => 'Arial',
			'google-weight' => '500',
			'font-data' => self::get_font_data_by_font_name(get_theme_mod( 'typography_heading_font_family', 'Montserrat' ))
		);
	}
}