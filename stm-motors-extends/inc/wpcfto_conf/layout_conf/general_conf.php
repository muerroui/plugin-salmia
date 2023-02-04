<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	
	$conf = apply_filters( 'motors_wpcfto_general_start_config', $global_conf );
	
	$general_conf = array(
		'google_api_key' =>
			array(
				'label' => esc_html__( 'Google Maps API Key', 'stm_motors_extends' ),
				'type' => 'text',
				'description' => 'Enable Google Maps Service. To obtain the keys please visit: <a href="https://cloud.google.com/maps-platform/">here</a>',
			),
		'enable_recaptcha' =>
			array(
				'label' => esc_html__( 'reCaptcha (v3)', 'stm_motors_extends' ),
				'type' => 'checkbox',
				'description' => 'Enable Google reCaptcha. To obtain the keys please visit: <a href="https://www.google.com/recaptcha/admin">here</a>',
				'group' => 'started',
			),
		'recaptcha_public_key' =>
			array(
				'label' => esc_html__( 'Public key', 'stm_motors_extends' ),
				'type' => 'text',
				'dependency' => [
					'key' => 'enable_recaptcha',
					'value' => 'not_empty'
				]
			),
		'recaptcha_secret_key' =>
			array(
				'label' => esc_html__( 'Secret key', 'stm_motors_extends' ),
				'type' => 'text',
				'group' => 'ended',
				'dependency' => [
					'key' => 'enable_recaptcha',
					'value' => 'not_empty'
				]
			),

	);
	
	$conf = array_merge( $conf, $general_conf );
	
	$conf = array(
		'name' => 'General',
		'fields' => $conf
	);
	
	$global_conf['general_tab'] = $conf;
	
	return $global_conf;
}, 10, 1 );