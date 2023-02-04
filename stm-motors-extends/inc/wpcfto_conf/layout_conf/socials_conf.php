<?php
add_filter('motors_get_all_wpcfto_config', function($global_conf) {
	$conf = array(
		'name' => 'Socials',
		'fields' =>
			array(
				'socials_link' =>
					array(
						'label' => esc_html__('Socials Links', 'stm_motors_extends' ),
						'type' => 'multi_input',
						'options' => stm_me_wpcfto_kv_socials(),
					),
			),
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
}, 10, 1);