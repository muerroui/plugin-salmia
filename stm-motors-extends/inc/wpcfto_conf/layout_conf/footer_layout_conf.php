<?php
add_filter('motors_get_all_wpcfto_config', function($global_conf) {
	$conf = array(
		'name' => 'Footer',
		'fields' =>
			array(
				'footer_bg_color' =>
					array(
						'label' => esc_html__( 'Background Color', 'stm_motors_extends' ),
						'type' => 'color',
						'output' => '#footer-main, body.page-template-home-service-layout #footer #footer-main',
						'mode' => 'background-color',
						'value' => '#232628',
						'style_important' => true,
					),
				'footer_sidebar_count' =>
					array(
						'label' => esc_html__( 'Widget Areas', 'stm_motors_extends' ),
						'type' => 'select',
						'value' => 4,
						'options' =>
							array(
								0 => esc_html__('Disable Widget Area', 'stm_motors_extends'),
								1 => 1,
								2 => 2,
								3 => 3,
								4 => 4,
							),
					),
				'footer_copyright_color' =>
					array(
						'label' => esc_html__( 'Copyright Area Background Color', 'stm_motors_extends' ),
						'type' => 'color',
						'output' => '#footer-copyright, body.page-template-home-service-layout #footer #footer-copyright',
						'mode' => 'background-color',
						'value' => '#232628',
						'style_important' => true,
					),
				'footer_copyright' =>
					array(
						'label' => esc_html__( 'Copyright Enable', 'stm_motors_extends' ),
						'type' => 'checkbox',
						'value' => true,
					),
				'footer_copyright_text' =>
					array(
						'label' => esc_html__( 'Copyright', 'stm_motors_extends' ),
						'value' => '&copy; 2015 &lt;a target=&quot;_blank&quot; href=&quot;http://www.stylemixthemes.com/&quot;&gt;Stylemix Themes&lt;/a&gt;&lt;span class=&quot;divider&quot;&gt;&lt;/span&gt;Trademarks and brands are the property of their respective owners.',
						'type' => 'text',
						'dependency' => [
							'key' => 'footer_copyright',
							'value' => 'not_empty'
						]
					),
				'footer_socials_enable' =>
					array(
						'label' => esc_html__( 'Socials', 'stm_motors_extends' ),
						'type' => 'multi_checkbox',
						'options' => stm_me_wpcfto_socials(),
					),
			),
	);
	
	$global_conf[stm_me_modify_key( $conf['name'] )] = $conf;
	
	return $global_conf;
}, 10, 1);