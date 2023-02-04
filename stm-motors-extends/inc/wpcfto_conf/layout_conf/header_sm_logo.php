<?php
add_filter( 'motors_wpcfto_header_end_config', function ( $conf ) {
	$config = array (
		'logo' =>
			array(
				'label' => esc_html__('Logo', 'stm_motors_extends' ),
				'type' => 'image',
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||car_rental||rental_two||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle||service',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
			),
		'logo_width' =>
			array(
				'label' => esc_html__('Logo Width (px)', 'stm_motors_extends' ),
				'description' => esc_html__('The height of the image will increase proportionately', 'stm_motors_extends'),
				'type' => 'number',
				'submenu' => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
			),
		'logo_margin_top' =>
			array(
				'label' => esc_html__('Logo Margin', 'stm_motors_extends' ),
				'type' => 'spacing',
				'units' => ['px', 'em'],
				'value' => [
					'top' => '17',
					'right' => '',
					'bottom' => '',
					'left' => '',
					'unit' => 'px',
				],
				'dependency' => [
					'key' => 'header_current_layout',
					'value' => 'aircrafts||boats||car_dealer||car_dealer_two||car_magazine||car_rental||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle||service',
					'section' => 'general_tab'
				],
				'submenu' => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
			),
		'logo_font_family' =>
			array(
				'label' => esc_html__('Text Logo Font Family', 'stm_motors_extends' ),
				'type' => 'typography',
				'description' => 'If you dont have logo, you can customize your brand name',
				'output' => '#header .blogname h1',
				'submenu' => esc_html__( 'Logo & Title', 'stm_motors_extends' ),
			),
	);
	
	return array_merge($conf, $config);
}, 10, 1);