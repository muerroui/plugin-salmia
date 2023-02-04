<?php
add_filter( 'motors_wpcfto_header_end_config', function ( $conf ) {
	$config = array (
		'header_socials_enable' =>
			array(
				'label' => esc_html__('Header Socials', 'stm_motors_extends' ),
				'type' => 'multi_checkbox',
				'options' => stm_me_wpcfto_socials(),
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer||car_magazine||listing_four||motorcycle'
				),
				'submenu' => esc_html__( 'Socials', 'stm_motors_extends' ),
			),
		'soc_empty_notice' =>
			array(
				'label' => esc_html__( 'Settings Not Available For This Header Type', 'stm_motors_extends' ),
				'type' => 'notice',
				'dependency' => array(
					'key' => 'header_layout',
					'value' => 'car_dealer_two||aircrafts||boats||equipment||listing||listing_two||listing_three||listing_five||car_rental||service',
				),
				'submenu' => esc_html__( 'Socials', 'stm_motors_extends' ),
			),
	);
	
	return array_merge($conf, $config);
}, 30, 1);