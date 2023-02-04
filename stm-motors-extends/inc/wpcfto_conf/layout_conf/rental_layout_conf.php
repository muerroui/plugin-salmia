<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	$conf = array(
		'name' => 'Rental Layout Settings',
		'fields' =>
			array(
				'rental_datepick' =>
					array(
						'label' => esc_html__( 'Reservation Date Page', 'stm_motors_extends' ),
						'type' => 'select',
						'options' => stm_me_wpcfto_pages_list(),
						'description' => 'Choose page for reservation date',
					),
				'order_received' =>
					array(
						'label' => esc_html__( 'Checkout Order Received Endpoint Page', 'stm_motors_extends' ),
						'type' => 'select',
						'options' => stm_me_wpcfto_pages_list(),
						'description' => 'Choose a page to display content from, on order received endpoint.',
					),
				'enable_fixed_price_for_days' =>
					array(
						'label' => esc_html__( 'Enable Fixed Price for Quantity Days', 'stm_motors_extends' ),
						'type' => 'checkbox',
					),
				'discount_program_desc' =>
					array(
						'label' => esc_html__( 'Popup Discount Program Description', 'stm_motors_extends' ),
						'type' => 'textarea',
						'dependency' => [
							'key' => 'enable_fixed_price_for_days',
							'value' => 'empty'
						]
					),
			),
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );