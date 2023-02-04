<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	$conf = array(
		'enable_woo_online' =>
			array(
				'label' => esc_html__( 'Enable Sell a Car Online (Woocommerce)', 'stm_motors_extends' ),
				'type' => 'checkbox',
			),
		'contact_us_page' =>
			array(
				'label' => esc_html__( 'Contact Us Page', 'stm_motors_extends' ) ,
				'type' => 'select',
				'description' => 'Choose page for contact us after order vehicle',
				'options' => stm_me_wpcfto_pages_list(),
				'value' => '2080',
			)
	);
	
	$conf = array(
		'name' => 'Sell a Car',
		'fields' => $conf
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );