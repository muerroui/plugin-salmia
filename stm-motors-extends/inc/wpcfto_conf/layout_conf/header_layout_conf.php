<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	
	$conf = array(
		'header_layout' =>
			array(
				'label' => esc_html__( 'Header Layout', 'stm_motors_extends' ),
				'type' => 'select',
				'options' => stm_me_wpcfto_headers_list(),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||car_rental||equipment||listing||listing_two||listing_three||listing_four||listing_five||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_bg_color' =>
			array(
				'label' => esc_html__( 'Header Background Color', 'stm_motors_extends' ),
				'type' => 'color',
				'description' => esc_html__('Does not work when the Transparent Header option for the page is enabled.', 'stm_motors_extends'),
				'mode' => 'background-color',
				'output' => '
									#header .header-main,
									#header .stm_motorcycle-header .stm_mc-main.header-main,
									.home #header .header-main-listing-five.stm-fixed,
									.stm-layout-header-listing #wrapper #header .header-listing.listing-nontransparent-header,
									.stm-layout-header-listing #wrapper #header .header-listing:after,
									#header .header-listing.stm-fixed,
									.header-service.service-notransparent-header,
									.stm-template-boats .header-listing.stm-fixed,
									.stm-template-car_dealer_two.no_margin #wrapper #stm-boats-header #header:after,
									.stm-template-aircrafts:not(.home):not(.stm-inventory-page):not(.single-listings) #wrapper #header,
									.stm-layout-header-aircrafts #header .header-listing,
									.stm-layout-header-equipment #header .header-listing,
									.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #header:after,
									.stm-layout-header-boats #stm-boats-header #header:after,
									.stm-template-rental_two #wrapper .header-main
									',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_mobile_bg_color' =>
			array(
				'label' => esc_html__( 'Mobile Header Background Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'background-color',
				'output' => '#header .header-main.header-main-mobile,
						#header .header-main.header-main-mobile .mobile-menu-holder,
						#header .stm_motorcycle-header.header-main-mobile .stm_mc-main.header-main,
						#header .stm_motorcycle-header.header-main-mobile .stm_mc-nav,
						.stm-layout-header-car_rental .header-main-mobile .stm-opened-menu-listing,
						.stm-layout-header-car_rental .header-main-mobile .stm-opened-menu-listing #top-bar,
						.stm-layout-header-equipment #header .header-listing.header-main-mobile,
						.stm-layout-header-equipment #header .header-listing.header-main-mobile .header-inner-content .mobile-menu-holder .header-menu,
						.stm-layout-header-listing #wrapper #header .header-listing.header-main-mobile.listing-nontransparent-header,
						.stm-layout-header-listing #wrapper #header .header-main-mobile.header-listing:after,
						.stm-layout-header-listing #wrapper #header .header-main-mobile.header-listing .stm-opened-menu-listing,
						.stm-layout-header-listing #wrapper #header .header-main-mobile.header-listing .stm-opened-menu-listing #top-bar,
						#header .header-listing.stm-fixed.header-main-mobile,
						.header-service.service-notransparent-header.header-main-mobile,
						.stm-boats-mobile-header,
						.stm-boats-mobile-menu,
						.stm-template-aircrafts:not(.home):not(.stm-inventory-page):not(.single-listings) #wrapper #header .header-main-mobile,
						.stm-layout-header-aircrafts #header .header-listing.header-main-mobile,
						.stm-template-rental_two #wrapper .header-main.header-main-mobile,
						.header-magazine.header-main-mobile,
						.header-magazine.header-main-mobile .stm-opened-menu-magazine,
						.stm-layout-header-motorcycle .stm_motorcycle-header.header-main-mobile .stm_mc-nav .main-menu .container .inner .header-menu
				',
				'style_important' => true,
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_text_color' =>
			array(
				'label' => esc_html__( 'Header Text Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '
							#wrapper #header .header-main .heading-font,
							#wrapper #header .header-main .heading-font a,
							#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions .heading-font,
							#wrapper #header .header-inner-content .listing-right-actions .head-phone-wrap .heading-font,
							#wrapper #header .header-magazine .container .magazine-service-right .magazine-right-actions .pull-right a.lOffer-compare,
							#wrapper #header .stm_motorcycle-header .stm_mc-main.header-main .header-main-phone a,
							.stm-layout-header-listing_five #wrapper .lOffer-compare,
							.stm-layout-header-listing_five #wrapper .header-main .stm-header-right .head-phone-wrap .ph-title,
							.stm-layout-header-listing_five #wrapper .header-main .stm-header-right .head-phone-wrap .phone
							',
				'dependency' => [
					'key' => 'header_layout',
					'value' => 'boats||car_dealer||car_magazine||car_rental||equipment||listing_four||motorcycle||listing_five',
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_mobile_text_color' =>
			array(
				'label' => esc_html__( 'Mobile Header Text Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '
							#wrapper #header .header-main.header-main-mobile .heading-font,
							#wrapper #header .header-main.header-main-mobile .heading-font a,
							#header .header-main.header-main-mobile,
							#header .stm_motorcycle-header.header-main-mobile .stm_mc-main.header-main .header-main-phone a,
							.header-magazine.header-main-mobile .pull-right a
							',
				'dependency' => [
					'key' => 'header_layout',
					'value' => 'car_dealer||car_rental||listing_four||motorcycle',
				],
				'style_important' => true,
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_icon_color' =>
			array(
				'label' => esc_html__( 'Header Socials Icon Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '
							#wrapper #header .header-main .header-main-socs i,
							#wrapper #header .header-magazine .magazine-service-right .header-main-socs i
							',
				'dependency' => [
					'key' => 'header_layout',
					'value' => 'car_dealer||car_magazine||listing_four||motorcycle',
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_mobile_icon_color' =>
			array(
				'label' => esc_html__( 'Mobile Header Socials Icon Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '
							#wrapper #header .header-main.header-main-mobile .header-main-socs i,
							#wrapper #header .header-main-mobile .header-main i,
							#header .stm_motorcycle-header.header-main-mobile .stm_mc-main.header-main .header-main-socs ul li a,
							.header-magazine.header-main-mobile .pull-right .header-main-socs i
							',
				'dependency' => [
					'key' => 'header_layout',
					'value' => 'motorcycle'
				],
				'style_important' => true,
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_sticky' =>
			array(
				'label' => esc_html__( 'Sticky', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||car_rental||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle||service',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Sticky', 'stm_motors_extends' ),
			),
		'header_sticky_padding' =>
			array(
				'label' => esc_html__('Padding', 'stm_motors_extends' ),
				'type' => 'spacing',
				'units' => ['px', 'em'],
				'output' => '.header-service.header-service-fixed.header-service-sticky.service-notransparent-header,
					.header-service.header-service-fixed.header-service-sticky,
					.stm-layout-header-listing_five #wrapper #header .header-main.stm-fixed,
					.header-nav-fixed.header-nav-sticky,
					.stm-layout-header-car_dealer_two.no_margin #stm-boats-header #header .stm-fixed,
					#wrapper #header .header-listing.stm-fixed,
					.stm-layout-header-car_magazine #wrapper #header .header-magazine.header-magazine-fixed.stm-fixed
					',
				'mode' => 'padding',
				'style_important' => true,
				'value' => [
					'top' => '10',
					'right' => '',
					'bottom' => '13',
					'left' => '',
					'unit' => 'px',
				],
				'dependency' => [
					[
						'key' => 'header_sticky',
						'value' => 'not_empty'
					],
					[
						'key' => 'header_layout',
						'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||car_rental||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||service'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Sticky', 'stm_motors_extends' ),
			),
		'header_sticky_bg' =>
			array(
				'label' => esc_html__( 'Background Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'background-color',
				'style_important' => true,
				'output' => '
					#header-nav-holder .header-nav.header-nav-default.header-nav-sticky,
					.header-service.header-service-fixed.header-service-sticky.service-notransparent-header,
					.header-service.header-service-fixed.header-service-sticky,
					.stm-layout-header-boats #stm-boats-header #header .header-listing-boats.stm-fixed,
					.stm-layout-header-car_magazine #wrapper #header .header-magazine.header-magazine-fixed.stm-fixed,
					.stm-layout-header-motorcycle #wrapper #header .stm_motorcycle-header.stm-fixed .stm_mc-main.header-main,
					.stm-layout-header-motorcycle .stm_motorcycle-header.stm-fixed .stm_mc-nav .main-menu .container .inner .header-menu,
					.stm-layout-header-motorcycle .stm_motorcycle-header.stm-fixed .stm_mc-nav .main-menu .container .inner:before,
					.stm-layout-header-motorcycle .stm_motorcycle-header.stm-fixed .stm_mc-nav .main-menu .container .inner:after,
					.stm-layout-header-listing_five #wrapper #header .header-main.stm-fixed,
					.stm-layout-header-car_dealer_two #stm-boats-header #header .stm-fixed:after,
					#wrapper #header .header-listing.stm-fixed,
					#wrapper #header .header-listing.stm-fixed:after
				',
				'dependency' => [
					'key' => 'header_sticky',
					'value' => 'not_empty'
				],
				'submenu' => esc_html__( 'Sticky', 'stm_motors_extends' ),
			),
		'phone_settings_start' =>
			array(
				'label' => esc_html__('Phone Settings', 'stm_motors_extends'),
				'type' => 'notice',
				'group' => 'started',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer||motorcycle||car_rental||equipment'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_main_phone_show_on_mobile' =>
			array(
				'label' => esc_html__( 'Main Phone Show on Mobile', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer||motorcycle||car_rental'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_main_phone_icon' =>
			array(
				'label' => esc_html__( 'Main Phone Icon', 'stm_motors_extends' ),
				'type' => 'icon_picker',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer||car_rental||equipment'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_main_phone_label_color' =>
			array(
				'label' => esc_html__( 'Main Phone Label Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '#wrapper #header .header-main .header-main-phone .phone .phone-label',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_main_phone_label' =>
			array(
				'label' => esc_html__( 'Main Phone Label', 'stm_motors_extends' ),
				'type' => 'text',
				'dependency' => array(
						array(
							'key' => 'header_layout',
							'value' => 'car_dealer||equipment'
						),
						array(
							'key' => 'header_current_layout',
							'value' => 'listing_six',
							'section' => 'general_tab'
						)
					),
				'dependencies' => '||',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_main_phone' =>
			array(
				'label' => esc_html__( 'Main Phone', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '878-9671-4455',
				'dependency' => array(
					array(
						'key' => 'header_layout',
						'value' => 'car_dealer||motorcycle||car_rental||equipment'
					),
					array(
						'key' => 'header_current_layout',
						'value' => 'listing_six',
						'section' => 'general_tab'
					)
				),
				'dependencies' => '||',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phones_show_on_mobile' =>
			array(
				'label' => esc_html__( 'Secondary Phones Show on Mobile', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phone_label_1_color' =>
			array(
				'label' => esc_html__( 'Secondary Phone 1 Label Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '#wrapper #header .header-main .header-secondary-phone .phone .phone-label',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phone_label_1' =>
			array(
				'label' => esc_html__( 'Secondary Phone 1 Label', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Service',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phone_1' =>
			array(
				'label' => esc_html__( 'Secondary Phone 1', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '878-3971-3223',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phone_label_2_color' =>
			array(
				'label' => esc_html__( 'Secondary Phone 2 Label Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'color',
				'output' => '#wrapper #header .header-main .header-secondary-phone .phone:nth-child(2) .phone-label',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phone_label_2' =>
			array(
				'label' => esc_html__( 'Secondary Phone 2 Label', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Parts',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_secondary_phone_2' =>
			array(
				'label' => esc_html__( 'Secondary Phone 2', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '878-0910-0770',
				'group' => 'ended',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'address_settings_start' =>
			array(
				'label' => esc_html__('Address Settings', 'stm_motors_extends'),
				'type' => 'notice',
				'group' => 'started',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'address_show_on_mobile' =>
			array(
				'label' => esc_html__( 'Address Show on Mobile', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_address_icon' =>
			array(
				'label' => esc_html__( 'Address Icon', 'stm_motors_extends' ),
				'type' => 'icon_picker',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_address' =>
			array(
				'label' => esc_html__( 'Address', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '1840 E Garvey Ave South West Covina, CA 91791',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_address_url' =>
			array(
				'label' => esc_html__( 'Google Map Address URL', 'stm_motors_extends' ),
				'type' => 'text',
				'group' => 'ended',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer||car_magazine'
				),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'header_listing_layout_image_bg' =>
			array(
				'label' => esc_html__( 'Listing Layout Header Image for Non-transparent Option', 'stm_motors_extends' ),
				'type' => 'image',
				'dependency' => [
					'key' => 'header_layout',
					'value' => 'listing||listing_two||listing_three||listing_four'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'sticky_notice' =>
			array(
				'label' => esc_html__( 'Settings Not Available on This Layout', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'rental_two',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Sticky', 'stm_motors_extends' ),
			),
	);
	
	$optionsStart = apply_filters('motors_wpcfto_header_start_config', array());
	
	$conf = (!empty($optionsStart)) ? array_merge($optionsStart, $conf) : $conf;
	$conf = apply_filters('motors_wpcfto_header_end_config', $conf);
	
	$conf = array(
		'name' => 'Header',
		'fields' => $conf,
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );