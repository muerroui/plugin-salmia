<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	$conf = array(
		'show_trade_in' =>
			array(
				'label' => esc_html__( 'Show Trade In Button', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||motorcycle||equipment',
					'section' => 'general_tab'
				],
			),
		'show_offer_price' =>
			array(
				'label' => esc_html__( 'Show Offer Price Button', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two',
					'section' => 'general_tab'
				],
			),
		'show_calculator' =>
			array(
				'label' => esc_html__( 'Show Calculator', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||motorcycle||equipment||boats',
					'section' => 'general_tab'
				],
			),
		'show_added_date' =>
			array(
				'label' => esc_html__( 'Show Published Date', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
			),
		'show_print_btn' =>
			array(
				'label' => esc_html__( 'Show Print Button', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four||car_dealer||car_dealer_two',
					'section' => 'general_tab'
				],
			),
		'show_test_drive' =>
			array(
				'label' => esc_html__( 'Show Test Drive Schedule', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_stock' =>
			array(
				'label' => esc_html__( 'Show Stock', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_compare' =>
			array(
				'label' => esc_html__( 'Show Compare', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_share' =>
			array(
				'label' => esc_html__( 'Show Share Block', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||equipment||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_pdf' =>
			array(
				'label' => esc_html__( 'Show PDF brochure', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_certified_logo_1' =>
			array(
				'label' => esc_html__( 'Show Certified Logo 1', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||equipment||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_certified_logo_2' =>
			array(
				'label' => esc_html__( 'Show Certified Logo 2', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||equipment||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_featured_btn' =>
			array(
				'label' => esc_html__( 'Show Featured Button', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
			),
		'show_vin' =>
			array(
				'label' => esc_html__( 'Show VIN', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_search_results' =>
			array(
				'label' => esc_html__( 'Show Search Results', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||equipment||motorcycle||boats||aircrafts',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'search_results_full_width' =>
			array(
				'label' => esc_html__( 'Full Width Search Results', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'group' => 'started',
				'dependency' => [
					[
						'key' => 'show_search_results',
						'value' => 'not_empty',
					],
					[
						'key' => 'header_current_layout',
						'value' => 'car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||equipment||motorcycle||boats||aircrafts',
						'section' => 'general_tab'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'search_results_desktop_items' =>
			array(
				'label' => esc_html__( 'Number of Items on Desktop', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => esc_html__( 'Minimum width: 1025px.', 'stm_motors_extends' ),
				'options' =>
					array(
						'4' => '4 items',
						'5' => '5 items',
						'6' => '6 items',
					),
				'value' => '4',
				'dependency' => [
					[
						'key' => 'show_search_results',
						'value' => 'not_empty',
					],
					[
						'key' => 'search_results_full_width',
						'value' => 'not_empty',
					],
					[
						'key' => 'header_current_layout',
						'value' => 'car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||equipment||motorcycle||boats||aircrafts',
						'section' => 'general_tab'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'search_results_tablet_items' =>
			array(
				'label' => esc_html__( 'Number of Items on Tablet', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => esc_html__( 'Minimum width: 768px.', 'stm_motors_extends' ),
				'options' =>
					array(
						'3' => '3 items',
						'4' => '4 items',
						'5' => '5 items',
					),
				'value' => '3',
				'group' => 'ended',
				'dependency' => [
					[
						'key' => 'show_search_results',
						'value' => 'not_empty',
					],
					[
						'key' => 'search_results_full_width',
						'value' => 'not_empty',
					],
					[
						'key' => 'header_current_layout',
						'value' => 'car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||equipment||motorcycle||boats||aircrafts',
						'section' => 'general_tab'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_registered' =>
			array(
				'label' => esc_html__( 'Show Registered Date', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'show_history' =>
			array(
				'label' => esc_html__( 'Show History', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four||equipment||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'stm_show_number' =>
			array(
				'label' => esc_html__( 'Show Number', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'default_interest_rate' =>
			array(
				'label' => esc_html__( 'Default Interest Rate', 'stm_motors_extends' ),
				'type' => 'text',
				'submenu' => esc_html__( 'Calculator', 'stm_motors_extends' ),
			),
		'default_month_period' =>
			array(
				'label' => esc_html__( 'Default Month Period', 'stm_motors_extends' ),
				'type' => 'text',
				'submenu' => esc_html__( 'Calculator', 'stm_motors_extends' ),
			),
		'default_down_payment_type' =>
			array(
				'label' => esc_html__( 'Default Down Payment Type', 'stm_motors_extends' ),
				'type' => 'radio',
				'options' =>
					array(
						'static' => 'Static',
						'percent' => 'Percent',
					),
				'value' => 'static',
				'submenu' => esc_html__( 'Calculator', 'stm_motors_extends' ),
			),
		'default_down_payment' =>
			array(
				'label' => esc_html__( 'Down Payment Amount', 'stm_motors_extends' ),
				'type' => 'number',
				'submenu' => esc_html__( 'Calculator', 'stm_motors_extends' ),
			),
		'default_down_payment_percent' =>
			array(
				'label' => esc_html__( 'Down Payment Percent (%)', 'stm_motors_extends' ),
				'type' => 'number',
				'value' => '0',
				'submenu' => esc_html__( 'Calculator', 'stm_motors_extends' ),
			),
		'show_quote_phone' =>
			array(
				'label' => esc_html__( 'Show Quote By Phone', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'equipment||motorcycle',
					'section' => 'general_tab'
				],
			),
		'stm_single_car_page' =>
			array(
				'label' => esc_html__( 'Single Car Page Background', 'stm_motors_extends' ),
				'type' => 'image',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer_two||equipment||motorcycle',
					'section' => 'general_tab'
				],
			),
		'stm_car_link_quote' =>
			array(
				'label' => esc_html__( 'Request a Quote Link', 'stm_motors_extends' ),
				'type' => 'text',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer_two||equipment||motorcycle',
					'section' => 'general_tab'
				],
			),
		'stm_similar_query' =>
			array(
				'label' => esc_html__( 'Show Similar Cars by Parameter', 'stm_motors_extends' ),
				'type' => 'text',
				'description' => esc_html__( 'Enter slug of listing category, separated by comma, without spaces. Ex: make,condition', 'stm_motors_extends' ),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'carguru_style' =>
			array(
				'label' => esc_html__( 'CarGuru Style', 'stm_motors_extends' ),
				'type' => 'select',
				'options' =>
					array(
						'STYLE1' => 'Style 1',
						'STYLE2' => 'Style 2',
						'BANNER1' => 'Banner 1 - 900 x 60 pixels',
						'BANNER2' => 'Banner 2 - 900 x 42 pixels',
						'BANNER3' => 'Banner 3 - 748 x 42 pixels',
						'BANNER4' => 'Banner 4 - 550 x 42 pixels',
						'BANNER5' => 'Banner 5 - 374 x 42 pixels',
					),
				'value' => 'STYLE1',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'carguru_min_rating' =>
			array(
				'label' => esc_html__( 'CarGuru Minimum Rating to display', 'stm_motors_extends' ),
				'type' => 'select',
				'options' =>
					array(
						'GREAT_PRICE' => 'Great Price',
						'GOOD_PRICE' => 'Good Price',
						'FAIR_PRICE' => 'Fair Price',
					),
				'value' => 'GREAT_PRICE',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'carguru_default_height' =>
			array(
				'label' => esc_html__( 'CarGuru Enter Height (in pixels)', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '42',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
	);
	
	$conf = array(
		'name' => 'Single Listing',
		'fields' => $conf
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );