<?php
add_filter('motors_get_all_wpcfto_config', function($global_conf) {
	$conf = array (
		'name' => 'Blog page',
		'fields' => array (
			'view_type' =>
				array (
					'label' => esc_html__( 'Blog Archive Page Layout', 'stm_motors_extends' ),
					'type' => 'radio',
					'options' =>
						array (
							'grid' => 'Grid',
							'list' => 'List',
						),
					'value' => 'grid',
				),
			'sidebar' =>
				array (
					'label' => esc_html__( 'Archive Page Sidebar', 'stm_motors_extends' ),
					'type' => 'select',
					'options' => stm_me_wpcfto_sidebars(),
					'value' => 'default',
				),
			'sidebar_blog' =>
				array (
					'label' => esc_html__( 'Blog Post Sidebar', 'stm_motors_extends' ),
					'type' => 'select',
					'options' => stm_me_wpcfto_sidebars(),
					'value' => 'default',
				),
			'sidebar_position' =>
				array (
					'label' => esc_html__( 'Sidebar Position', 'stm_motors_extends' ),
					'type' => 'radio',
					'options' =>
						array (
							'left' => 'Left',
							'right' => 'Right',
						),
					'value' => 'right',
				),
			'blog_show_excerpt' =>
				array (
					'label' => esc_html__( 'Show Excerpt (for Grid View)', 'stm_motors_extends' ),
					'type' => 'checkbox',
					'dependency' => [
						'key' => 'header_current_layout',
						'value' => 'aircrafts||boats||car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||motorcycle',
						'section' => 'general_tab'
					],
				),
		),
	);
	
	$global_conf['blog_conf'] = $conf;
	
	return $global_conf;
}, 10, 1);