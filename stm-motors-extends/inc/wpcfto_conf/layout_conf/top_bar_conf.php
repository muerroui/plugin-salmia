<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	
	$conf = array(
		'name' => 'Top Bar',
		'fields' =>
			array(
				'top_bar_enable' =>
					array(
						'label' => esc_html__('Enable', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => true,
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_bg_color' =>
					array(
						'label' => esc_html__('Background Color', 'stm_motors_extends' ),
						'type' => 'color',
						'mode' => 'background-color',
						'output' => '#wrapper #top-bar,
									.top-bar-wrap, .stm-template-listing #top-bar,
									body.page-template-home-service-layout #top-bar,
									.stm-layout-header-listing #wrapper #top-bar,
									.stm-template-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar:after,
									.stm-template-aircrafts #wrapper #top-bar,
									.stm-template-listing_three #top-bar,
									#wrapper #stm-boats-header #top-bar:after,
									.stm-template-listing_five .top-bar-wrap ',
						'value' => '#232628',
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_text_color' =>
					array(
						'label' => esc_html__('Text Color', 'stm_motors_extends' ),
						'type' => 'color',
						'mode' => 'color',
						'output' => '
							#wrapper #top-bar .container,
							#wrapper .top-bar-wrap,
							.stm-layout-header-listing #wrapper #top-bar .container,
							#wrapper #top-bar .container .language-switcher-unit .stm_current_language,
							.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .container .top-bar-wrapper .language-switcher-unit .stm_current_language,
							#wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							#wrapper #stm-boats-header #top-bar .container .top-bar-info li,
							#wrapper #top-bar .container .header-login-url a,
							#wrapper #top-bar .container .select2-container--default .select2-selection--single .select2-selection__arrow b,
							.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__arrow b,
							.stm-layout-header-aircrafts #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__arrow b,
							.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .container .header-login-url a,
							#wrapper #header .top-bar-wrap .stm-c-f-top-bar .stm-top-address-wrap,
							#wrapper #header .top-bar-wrap .stm-c-f-top-bar .language-switcher-unit .stm_current_language,
							#wrapper #header .top-bar-wrap .stm-c-f-top-bar .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							#wrapper #header .top-bar-wrap .stm-c-f-top-bar .select2-container--default .select2-selection--single .select2-selection__arrow b,
							.stm-layout-header-car_dealer #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-car_dealer_two #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-listing #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-listing_five #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-listing_five .top-bar-wrap .stm-c-f-top-bar .currency-switcher .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__arrow b,
							.stm-layout-header-motorcycle #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-car_rental #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-car_magazine #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-aircrafts #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-equipment #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
							.stm-layout-header-boats #wrapper #top-bar .container .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered
							',
						'value' => '#ffffff',
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_icon_color' =>
					array(
						'label' => esc_html__('Icon Color', 'stm_motors_extends' ),
						'type' => 'color',
						'mode' => 'color',
						'output' => '
							#wrapper #top-bar .container .pull-right i,
							#wrapper #top-bar .container .stm-boats-top-bar-centered i,
							#wrapper #header .stm-c-f-top-bar i
							 ',
						'value' => '#ffffff',
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_link_color' =>
					array(
						'label' => esc_html__('Link Color', 'stm_motors_extends' ),
						'type' => 'color',
						'mode' => 'color',
						'output' => '
							body #wrapper #top-bar .container a,
							body #wrapper .top-bar-wrap a,
							body #wrapper #stm-boats-header #top-bar .container .top-bar-info li a,
							body #wrapper #top-bar .container .top_bar_menu ul li a,
							body #wrapper #top-bar .container .header-login-url a,
							.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .container .header-login-url a,
							body #wrapper #header .top-bar-wrap .stm-c-f-top-bar .stm-top-address-wrap a,
							.stm-layout-header-car_dealer #wrapper #top-bar .container a,
							.stm-layout-header-car_dealer_two #wrapper #top-bar .container a,
							.stm-layout-header-listing #wrapper #top-bar .container a,
							.stm-layout-header-listing_five #wrapper #top-bar .container a,
							.stm-layout-header-boats #wrapper #top-bar .container a,
							.stm-layout-header-motorcycle #wrapper #top-bar .container a,
							.stm-layout-header-car_rental #wrapper #top-bar .container a,
							.stm-layout-header-car_magazine #wrapper #top-bar .container a,
							.stm-layout-header-aircrafts #wrapper #top-bar .container a,
							.stm-layout-header-equipment #wrapper #top-bar .container a
							',
						'value' => '#ffffff',
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_login' =>
					array(
						'label' => esc_html__('Enable Login/Register', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => false,
						'description' => 'Depends on chosen theme layout. See more on documentation',
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_wpml_switcher' =>
					array(
						'label' => esc_html__('Language Switcher', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => true,
						'description' => 'WPML plugin is required',
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_currency_enable' =>
					array(
						'label' => esc_html__('Show Currency Switcher', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => false,
						'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
					),
				'top_bar_address_icon' =>
					array(
						'label' => esc_html__('Address Icon', 'stm_motors_extends' ),
						'type' => 'icon_picker',
						'submenu' => esc_html__( 'Address', 'stm_motors_extends' ),
					),
				'top_bar_address' =>
					array(
						'label' => esc_html__('Address', 'stm_motors_extends' ),
						'type' => 'text',
						'value' => '1010 Moon ave, New York, NY US',
						'submenu' => esc_html__( 'Address', 'stm_motors_extends' ),
					),
				// 'top_bar_address_url' => //not_used!!!!
				// 	array(
				// 		'label' => esc_html__('Google Map Address URL', 'stm_motors_extends' ),
				// 		'type' => 'text',
				// 		'submenu' => esc_html__( 'Address', 'stm_motors_extends' ),
				// 	),
				'top_bar_address_mobile' =>
					array(
						'label' => esc_html__('Show Address on Mobile', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => true,
						'submenu' => esc_html__( 'Address', 'stm_motors_extends' ),
					),
				'top_bar_working_hours_icon' =>
					array(
						'label' => esc_html__('Working Hours Icon', 'stm_motors_extends' ),
						'type' => 'icon_picker',
						'submenu' => esc_html__( 'Working Hours', 'stm_motors_extends' ),
					),
				'top_bar_working_hours' =>
					array(
						'label' => esc_html__('Working Hours', 'stm_motors_extends' ),
						'type' => 'text',
						'value' => 'Mon - Sat 8.00 - 18.00',
						'submenu' => esc_html__( 'Working Hours', 'stm_motors_extends' ),
					),
				'top_bar_working_hours_mobile' =>
					array(
						'label' => esc_html__('Show Working Hours on Mobile', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => true,
						'submenu' => esc_html__( 'Working Hours', 'stm_motors_extends' ),
					),
				'top_bar_phone_icon' =>
					array(
						'label' => esc_html__('Phone Number Icon', 'stm_motors_extends' ),
						'type' => 'icon_picker',
						'submenu' => esc_html__( 'Phone Number', 'stm_motors_extends' ),
					),
				'top_bar_phone' =>
					array(
						'label' => esc_html__('Phone Number', 'stm_motors_extends' ),
						'type' => 'text',
						'value' => '+1 212-226-3126',
						'submenu' => esc_html__( 'Phone Number', 'stm_motors_extends' ),
					),
				'top_bar_phone_mobile' =>
					array(
						'label' => esc_html__('Show Phone on Mobile', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => true,
						'submenu' => esc_html__( 'Phone Number', 'stm_motors_extends' ),
					),
				'top_bar_menu' =>
					array(
						'label' => esc_html__('Menu Enabled', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => false,
						'submenu' => esc_html__( 'Menu', 'stm_motors_extends' ),
					),
				'top_bar_menu_font_size' =>
					array(
						'label' => esc_html__('Menu Font Size', 'stm_motors_extends' ),
						'type' => 'text',
						'value' => '14',
						'mode' => 'font-size',
						'units' => 'px',
						'output' => '#wrapper #top-bar .top_bar_menu ul li a, #wrapper .stm-c-f-top-bar .top_bar_menu ul li a ',
						'submenu' => esc_html__( 'Menu', 'stm_motors_extends' ),
					),
				'top_bar_menu_text_color' =>
					array(
						'label' => esc_html__('Menu Text Color', 'stm_motors_extends' ),
						'type' => 'color',
						'value' => '#f1f1f1',
						'mode' => 'color',
						'output' => '
							body #wrapper #top-bar .container .top-bar-wrapper .pull-right .top_bar_menu ul li a,
							body #wrapper #top-bar .container .top-bar-wrapper .stm-boats-top-bar-right .top_bar_menu ul li a,
							#wrapper .stm-c-f-top-bar .top_bar_menu ul li a
							 ',
						'submenu' => esc_html__( 'Menu', 'stm_motors_extends' ),
					),
				'top_bar_menu_hover_text_color' =>
					array(
						'label' => esc_html__('Menu Hover Text Color', 'stm_motors_extends' ),
						'type' => 'color',
						'value' => '#ffffff',
						'mode' => 'color',
						'output' => '
								body #wrapper #top-bar .container .top-bar-wrapper .pull-right .top_bar_menu ul li a:hover,
								body #wrapper #top-bar .container .top-bar-wrapper .stm-boats-top-bar-right .top_bar_menu ul li a:hover,
								#wrapper .stm-c-f-top-bar .top_bar_menu ul li a:hover
						 ',
						'submenu' => esc_html__( 'Menu', 'stm_motors_extends' ),
					),
				'top_bar_socials_enable' =>
					array(
						'label' => esc_html__('Socials', 'stm_motors_extends' ),
						'type' => 'multi_checkbox',
						'options' => stm_me_wpcfto_socials(),
						'submenu' => esc_html__( 'Socials', 'stm_motors_extends' ),
					),/*Top bar setting end*/
			),
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );