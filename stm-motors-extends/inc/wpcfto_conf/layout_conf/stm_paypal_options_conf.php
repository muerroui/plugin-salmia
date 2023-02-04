<?php
add_filter( 'motors_merge_wpcfto_config', function ( $conf_for_merge ) {
	$conf = array(
				'paypal_currency' =>
					array(
						'label' => esc_html__( 'Currency', 'stm_motors_extends' ),
						'type' => 'text',
						'value' => 'USD',
						'description' => 'Ex.: USD',
						'submenu' => esc_html__( 'Pay Pal Settings', 'stm_motors_extends' ),
					),
				'paypal_email' =>
					array(
						'label' => esc_html__( 'Paypal Email', 'stm_motors_extends' ),
						'type' => 'text',
						'submenu' => esc_html__( 'Pay Pal Settings', 'stm_motors_extends' ),
					),
				'paypal_mode' =>
					array(
						'label' => esc_html__( 'Paypal Mode', 'stm_motors_extends' ),
						'type' => 'select',
						'options' =>
							array(
								'sandbox' => 'Sandbox',
								'live' => 'Live',
							),
						'value' => 'sandbox',
						'submenu' => esc_html__( 'Pay Pal Settings', 'stm_motors_extends' ),
					),
				'membership_cost' =>
					array(
						'label' => esc_html__( 'Membership Price', 'stm_motors_extends' ),
						'type' => 'text',
						'description' => 'Membership submission price',
						'submenu' => esc_html__( 'Pay Pal Settings', 'stm_motors_extends' ),
					),
	);
	
	return array_merge($conf_for_merge, $conf);
} );