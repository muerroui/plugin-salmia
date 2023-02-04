<?php
add_filter( 'motors_get_all_wpcfto_config', function ( $global_conf ) {
	$conf = array(
		'name' => 'Shop',
		'fields' =>
			array(
				'shop_sidebar' =>
					array(
						'label' => esc_html__('Choose Shop Sidebar', 'stm_motors_extends' ),
						'type' => 'select',
						'options' => stm_me_wpcfto_sidebars(),
						'value' => '768',
					),
				'shop_sidebar_position' =>
					array(
						'label' => esc_html__('Shop Sidebar Position', 'stm_motors_extends' ),
						'type' => 'radio',
						'options' =>
							array(
								'left' => 'Left',
								'right' => 'Right',
							),
						'value' => 'left',
					),
			),
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
} );