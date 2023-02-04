<?php
add_filter('motors_get_all_wpcfto_config', function($global_conf) {
	$conf = array (
		'name' => 'Custom JS',
		'fields' => array (
			'footer_custom_scripts' =>
				array(
					'type' => 'ace_editor',
					'lang' => 'javascript',
				),
		),
	);
	
	$global_conf['custom_js'] = $conf;
	
	return $global_conf;
}, 10, 1);