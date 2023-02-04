<?php
add_filter('motors_get_all_wpcfto_config', function($global_conf) {
	$conf = array (
		'name' => 'Custom CSS',
		'fields' => array (
			'custom_css' =>
				array(
					'type' => 'ace_editor',
					'lang' => 'css',
				),
		),
	);
	
	$global_conf['custom_css'] = $conf;
	
	return $global_conf;
}, 10, 1);