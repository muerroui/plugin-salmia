<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	$conf = array(
		'classic_listing_title' =>
			array(
				'label' => esc_html__( 'Listing Archive "Title Box" Title', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Inventory',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'hide_price_labels' =>
			array(
				'label' => esc_html__( 'Hide Price Labels on Listing Archive', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_directory_title_default' =>
			array(
				'label' => esc_html__( 'Default Title', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Cars for sale',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'inventory_layout' =>
			array(
				'label' => esc_html__( 'Inventory Layout Mode', 'stm_motors_extends' ),
				'type' => 'radio',
				'options' =>
					array(
						'light' => 'Light',
						'dark' => 'Dark',
					),
				'value' => 'dark',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer_two',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_archive' =>
			array(
				'label' => esc_html__( 'Inventory Archive Page', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose listing archive page',
				'options' => stm_me_wpcfto_pages_list(),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_sidebar' =>
			array(
				'label' => esc_html__( 'Inventory Sidebar', 'stm_motors_extends' ),
				'type' => 'select',
				'options' => stm_me_wpcfto_sidebars(),
				'value' => 'no_sidebar',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_boat_filter' =>
			array(
				'label' => esc_html__( 'Use Boats Filter Style', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'boats',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_list_sort_slug' =>
			array(
				'label' => esc_html__( 'List Version Sort Parameter (Type Slug)', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'make',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'boats',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_grid_choices' =>
			array(
				'label' => esc_html__( 'Items Per Page Choices.', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '9,12,18,27',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'boats',
					'section' => 'general_tab'
				],
				'description' => esc_html__( 'Grid version. Ex: 9,12,18,27', 'stm_motors_extends' ),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'compare_page' =>
			array(
				'label' => esc_html__( 'Compare Page', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose landing page for compare',
				'options' => stm_me_wpcfto_pages_list(),
				'value' => '156',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_view_type' =>
			array(
				'label' => esc_html__( 'Listing View Type', 'stm_motors_extends' ),
				'type' => 'radio',
				'options' =>
					array(
						'grid' => 'Grid',
						'list' => 'List',
					),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'general_tab'
				],
				'value' => 'list',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'enable_search' =>
			array(
				'label' => esc_html__( 'Bind WP Search form with Inventory', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'enable_features_search' =>
			array(
				'label' => esc_html__( 'Display Additional Features on Inventory Filter', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'enable_favorite_items' =>
			array(
				'label' => esc_html__( 'Enable Favorite Button', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'default_sort_by' =>
			array(
				'label' => esc_html__( 'Default Sort Option', 'stm_motors_extends' ),
				'type' => 'select',
				'value' => 'date_high',
				'options' => stm_me_wpcfto_sortby(),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'listing_filter_position' =>
			array(
				'label' => esc_html__( 'Filter Position', 'stm_motors_extends' ),
				'type' => 'select',
				'value' => 'left',
				'options' => stm_me_wpcfto_positions(),
				'dependency' => [
					[
						'key' => 'listing_boat_filter',
						'value' => 'empty',
					],
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
						'section' => 'general_tab'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'price_currency_name' =>
			array(
				'label' => esc_html__( 'Price Currency Name', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'USD',
				'submenu' => esc_html__( 'Price', 'stm_motors_extends' ),
			),
		'price_currency' =>
			array(
				'label' => esc_html__( 'Price Currency', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '$',
				'submenu' => esc_html__( 'Price', 'stm_motors_extends' ),
			),
		'price_currency_position' =>
			array(
				'label' => esc_html__( 'Price Currency Position', 'stm_motors_extends' ),
				'type' => 'select',
				'options' =>
					array(
						'left' => 'Left',
						'right' => 'Right',
					),
				'value' => 'left',
				'submenu' => esc_html__( 'Price', 'stm_motors_extends' ),
			),
		'price_delimeter' =>
			array(
				'label' => esc_html__( 'Price Delimeter', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => ' ',
				'submenu' => esc_html__( 'Price', 'stm_motors_extends' ),
			),
		'currency_list' =>
			array(
				'label' => esc_html__( 'Multiple Currencies', 'stm_motors_extends' ),
				'description' => 'Conversion Rate should be delimited by dot (example: 1.2)',
				'type' => 'repeater',
				'load_labels' => array(
					'add_label' => esc_html__('Add Currency', 'stm_motors_extends'),
				),
				'fields' =>
					array(
						'currency' =>
							array(
								'type' => 'text',
								'label' => esc_html__( 'Currency', 'stm_vehicles_listing' )
							),
						'symbol' =>
							array(
								'type' => 'text',
								'label' => esc_html__( 'Symbol', 'stm_vehicles_listing' )
							),
						'to' =>
							array(
								'type' => 'text',
								'label' => esc_html__( 'Conversation Rate', 'stm_vehicles_listing' )
							),
					),
				'submenu' => esc_html__( 'Price', 'stm_motors_extends' ),
			),
		'enable_location' =>
			array(
				'label' => esc_html__( 'Show Location/Include Location in Filter', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Filter by location', 'stm_motors_extends' ),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
			),
		'distance_measure_unit' =>
			array(
				'label' => esc_html__( 'Unit Measurement', 'stm_motors_extends' ),
				'type' => 'select',
				'options' =>
					array(
						'miles' => 'Miles',
						'kilometers' => 'Kilometers',
					),
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'enable_location',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Filter by location', 'stm_motors_extends' ),
			),
		'distance_search' =>
			array(
				'label' => esc_html__( 'Set Max Search Radius', 'stm_motors_extends' ),
				'type' => 'text',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'enable_location',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Filter by location', 'stm_motors_extends' ),
			),
		'location_empty_notice' =>
			array(
				'label' => esc_html__( 'Settings Not Available For This Layout', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => array(
					'key' => 'header_current_layout',
					'value' => 'aircrafts||equipment',
					'section' => 'general_tab'
				),
				'submenu' => esc_html__( 'Filter by location', 'stm_motors_extends' ),
			),
		'listing_directory_title_frontend' =>
			array(
				'label' => esc_html__( 'Display Generated Car Title as:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '{make} {serie} {ca-year}',
				'description' => '&quot;Put in curly brackets slug of taxonomy. For Example - {make} {serie} {ca-year}. Leave empty if you want to display value car title.&quot;',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'grid_title_max_length' =>
			array(
				'label' => esc_html__( 'Grid Item Title Max Length', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '44',
				'dependency' => array(
					'key' => 'listing_view_type',
					'value' => 'grid',
				),
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_generated_title_as_label' =>
			array(
				'label' => esc_html__( 'Show Two First Parameters as a Badge', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'description' => 'Archive Page and Single Listing',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'listing_directory_enable_dealer_info' =>
			array(
				'label' => esc_html__( 'Enable Dealer Info on Listing', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'listing||listing_two||listing_three||listing_four||aircrafts',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_stock' =>
			array(
				'label' => esc_html__( 'Show Stock', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_test_drive' =>
			array(
				'label' => esc_html__( 'Show Test Drive Schedule', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_compare' =>
			array(
				'label' => esc_html__( 'Show Compare', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_share' =>
			array(
				'label' => esc_html__( 'Show Share Block', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_pdf' =>
			array(
				'label' => esc_html__( 'Show PDF brochure', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_quote' =>
			array(
				'label' => esc_html__( 'Show "Quote by Phone"', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_trade' =>
			array(
				'label' => esc_html__( 'Show "Trade Value" Popup', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'motorcycle',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_calculate' =>
			array(
				'label' => esc_html__( 'Show "Calculate Payment" Popup', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_vin' =>
			array(
				'label' => esc_html__( 'Show "VIN" Link', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'motorcycle',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_certified_logo_1' =>
			array(
				'label' => esc_html__( 'Show Certified Logo 1', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'show_listing_certified_logo_2' =>
			array(
				'label' => esc_html__( 'Show Certified Logo 2', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					[
					'key' => 'header_current_layout',
					'value' => 'aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
					],
					[
						'key' => 'listing_view_type',
						'value' => 'list',
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Grid/List Card', 'stm_motors_extends' ),
			),
		'sidebar_filter_bg' =>
			array(
				'label' => esc_html__( 'Listing Sidebar Filter Background', 'stm_motors_extends' ),
				'type' => 'image',
				'dependency' => [
					[
						'key' => 'listing_boat_filter',
						'value' => 'empty',
					],
					[
						'key' => 'header_current_layout',
						'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
						'section' => 'general_tab'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),

		'show_sold_listings' =>
			array(
				'label' => esc_html__( 'Sold Listings', 'stm_motors_extends' ),
				'description' => 'Display sold listings in the Classic and Modern filters',
				'type' => 'checkbox',
				'value' => false,
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'group' => 'started',
			),
		'sold_badge_bg_color' =>
			array(
				'label' => esc_html__( 'Sold Badge Background Color', 'stm_motors_extends' ),
				'type' => 'color',
				'mode' => 'background-color',
				'value' => '#fc4e4e',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
				'group' => 'ended',
				'dependency' => array(
					'key' => 'show_sold_listings',
					'value' => 'not_empty'
				)
			),
		
	);
	
	$conf = array(
		'name' => esc_html__( 'Inventory settings', 'stm_motors_extends' ),
		'fields' => apply_filters( 'motors_merge_wpcfto_config', $conf )
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );