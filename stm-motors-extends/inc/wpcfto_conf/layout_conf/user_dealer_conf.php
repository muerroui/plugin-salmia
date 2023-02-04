<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	$conf = array(
		'login_page' =>
			array(
				'label' => esc_html__( 'Login/Registration Page', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose page for login User/Dealer',
				'options' => stm_me_wpcfto_pages_list(),
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'enable_email_confirmation' => array(
			'label' => esc_html__( 'Enable Email Confirmation', 'stm_motors_extends' ),
			'type' => 'checkbox',
			'dependency' => [
				'key' => 'header_current_layout',
				'value' => 'listing||listing_two||listing_three||listing_four',
				'section' => 'general_tab'
			],
			'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
		),
		'dealer_list_page' =>
			array(
				'label' => esc_html__( 'Dealer List Page', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose page for Dealer list page',
				'options' => stm_me_wpcfto_pages_list(),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'user_add_car_page' =>
			array(
				'label' => esc_html__( 'Add a Car Page', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose page for Add to car Page (Also, this page will be used for editing items)',
				'options' => stm_me_wpcfto_pages_list(),
				'value' => '1755',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'user_sidebar' =>
			array(
				'label' => esc_html__( 'Default User Sidebar', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose page for value user sidebar',
				'options' => stm_me_wpcfto_sidebars(),
				'value' => '1725',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'User Options', 'stm_motors_extends' ),
			),
		'user_sidebar_position' =>
			array(
				'label' => esc_html__( 'User Sidebar Position', 'stm_motors_extends' ),
				'type' => 'radio',
				'options' =>
					array(
						'left' => 'Left',
						'right' => 'Right',
					),
				'value' => 'right',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'User Options', 'stm_motors_extends' ),
			),
		'dealer_sidebar' =>
			array(
				'label' => esc_html__( 'Default Dealer Sidebar', 'stm_motors_extends' ),
				'type' => 'select',
				'description' => 'Choose page for value user sidebar',
				'options' => stm_me_wpcfto_sidebars(),
				'value' => '1864',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'dealer_sidebar_position' =>
			array(
				'label' => esc_html__( 'Dealer Sidebar Position', 'stm_motors_extends' ),
				'type' => 'radio',
				'options' =>
					array(
						'left' => 'Left',
						'right' => 'Right',
					),
				'value' => 'right',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'dealer_rate_1' =>
			array(
				'label' => esc_html__( 'Rate 1 Label:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Customer Service',
				'group' => 'started',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'dealer_rate_2' =>
			array(
				'label' => esc_html__( 'Rate 2 Label:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Buying Process',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'dealer_rate_3' =>
			array(
				'label' => esc_html__( 'Rate 3 Label:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => 'Overall Experience',
				'group' => 'ended',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'user_post_limit' =>
			array(
				'label' => esc_html__( 'User Slots Limit:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '3',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'User Options', 'stm_motors_extends' ),
			),
		'user_post_images_limit' =>
			array(
				'label' => esc_html__( 'User Slot Images Upload Limit:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '5',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'User Options', 'stm_motors_extends' ),
			),
		'dealer_post_limit' =>
			array(
				'label' => esc_html__( 'Dealer Slots Limit:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '50',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'dealer_post_images_limit' =>
			array(
				'label' => esc_html__( 'Dealer Slot Images Upload Limit:', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '10',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'user_image_size_limit' =>
			array(
				'label' => esc_html__( 'Image Size Limit (Kb)', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '4000',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'send_email_to_user' =>
			array(
				'label' => esc_html__( 'Send Email to Dealer/Private Seller', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'description' => 'Ad to be waiting approve or ad has been approved',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'user_premoderation' =>
			array(
				'label' => esc_html__( 'Enable User Ads Moderation', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'User Options', 'stm_motors_extends' ),
			),
		'dealer_premoderation' =>
			array(
				'label' => esc_html__( 'Enable Dealer Ads Moderation', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'allow_dealer_add_new_category' =>
			array(
				'label' => esc_html__( 'Allow Dealer Add New Category', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'dealer_review_moderation' =>
			array(
				'label' => esc_html__( 'Enable Moderation For Dealer Review', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'dealer_pay_per_listing' =>
			array(
				'label' => esc_html__( 'Enable Pay Per Listing', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Pay Per Listing', 'stm_motors_extends' ),
			),
		'pay_per_listing_price' =>
			array(
				'label' => esc_html__( 'Pay Per Listing Price', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '0',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'dealer_pay_per_listing',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Pay Per Listing', 'stm_motors_extends' ),
			),
		'pay_per_listing_period' =>
			array(
				'label' => esc_html__( 'Pay Per Listing Period (days)', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '30',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'dealer_pay_per_listing',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Pay Per Listing', 'stm_motors_extends' ),
			),
		'dealer_payments_for_featured_listing' =>
			array(
				'label' => esc_html__( 'Enable Paid Featured Listing', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Paid Featured Listing', 'stm_motors_extends' ),
			),
		'featured_listing_default_badge' =>
			array(
				'label' => esc_html__( 'Featured Listing Label', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '0',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'dealer_payments_for_featured_listing',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Paid Featured Listing', 'stm_motors_extends' ),
			),
		'featured_listing_price' =>
			array(
				'label' => esc_html__( 'Featured Listing Price', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '0',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'dealer_payments_for_featured_listing',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Paid Featured Listing', 'stm_motors_extends' ),
			),
		'featured_listing_period' =>
			array(
				'label' => esc_html__( 'Featured Listing Period (days)', 'stm_motors_extends' ),
				'type' => 'text',
				'value' => '30',
				'dependency' => [
					[
						'key' => 'header_current_layout',
						'value' => 'listing||listing_two||listing_three||listing_four',
						'section' => 'general_tab'
					],
					[
						'key' => 'dealer_payments_for_featured_listing',
						'value' => 'not_empty'
					]
				],
				'dependencies' => '&&',
				'submenu' => esc_html__( 'Paid Featured Listing', 'stm_motors_extends' ),
			),
		'enable_plans' =>
			array(
				'label' => esc_html__( 'Enable Pricing Plans (Woocommerce)', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'pricing_link' =>
			array(
				'label' => esc_html__( 'Pricing Link', 'stm_motors_extends' ),
				'type' => 'select',
				'options' => stm_me_wpcfto_pages_list(),
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'listing||listing_two||listing_three||listing_four',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'site_demo_mode' =>
			array(
				'label' => esc_html__( 'Site Demo Mode', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'submenu' => esc_html__( 'Main', 'stm_motors_extends' ),
			),
		'user_options_empty_notice' =>
			array(
				'label' => esc_html__( 'Settings Available on Classified 1,2,3,4 Layouts', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => array(
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||aircrafts||boats||motorcycle||equipment',
					'section' => 'general_tab'
				),
				'submenu' => esc_html__( 'User Options', 'stm_motors_extends' ),
			),
		'dealer_options_empty_notice' =>
			array(
				'label' => esc_html__( 'Settings Available on Classified 1,2,3,4 Layouts', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => array(
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||aircrafts||boats||motorcycle||equipment',
					'section' => 'general_tab'
				),
				'submenu' => esc_html__( 'Dealer Options', 'stm_motors_extends' ),
			),
		'pp_empty_notice' =>
			array(
				'label' => esc_html__( 'Settings Available on Classified 1,2,3,4 Layouts', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => array(
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||aircrafts||boats||motorcycle||equipment',
					'section' => 'general_tab'
				),
				'submenu' => esc_html__( 'Pay Per Listing', 'stm_motors_extends' ),
			),
		'pf_empty_notice' =>
			array(
				'label' => esc_html__( 'Settings Available on Classified 1,2,3,4 Layouts', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => array(
					'key' => 'header_current_layout',
					'value' => 'car_dealer||car_dealer_two||aircrafts||boats||motorcycle||equipment',
					'section' => 'general_tab'
				),
				'submenu' => esc_html__( 'Paid Featured Listing', 'stm_motors_extends' ),
			),
	);
	
	$conf = array(
		'name' => esc_html__('User/Dealer', 'stm_motors_extends'),
		'fields' => $conf
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );