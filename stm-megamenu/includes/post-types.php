<?php

add_action('init', array('STM_MegaMenu_PostType', 'init'));

class STM_MegaMenu_PostType {
	public static function init() {
		self::stm_motors_megamenu_init();
	}

	public static function stm_motors_megamenu_init()
	{

		$options = get_option('stm_post_types_options');

		$stm_megamenu_options = wp_parse_args($options, array(
			'stm_megamenu' => array(
				'title' => __('MegaMenu', 'stm_motors_megamenu'),
				'plural_title' => __('MegaMenu', 'stm_motors_megamenu'),
				'rewrite' => 'stm_megamenu',
			),
		));

		register_post_type('stm_megamenu', array(
			'labels' => array(
				'name' => $stm_megamenu_options['stm_megamenu']['plural_title'],
				'singular_name' => $stm_megamenu_options['stm_megamenu']['title'],
				'add_new' => __('Add New', 'stm_motors_megamenu'),
				'add_new_item' => __('Add New Item', 'stm_motors_megamenu'),
				'edit_item' => __('Edit Item', 'stm_motors_megamenu'),
				'new_item' => __('New Item', 'stm_motors_megamenu'),
				'all_items' => __('All Items', 'stm_motors_megamenu'),
				'view_item' => __('View Item', 'stm_motors_megamenu'),
				'search_items' => __('Search Items', 'stm_motors_megamenu'),
				'not_found' => __('No items found', 'stm_motors_megamenu'),
				'not_found_in_trash' => __('No items found in Trash', 'stm_motors_megamenu'),
				'parent_item_colon' => '',
				'menu_name' => __($stm_megamenu_options['stm_megamenu']['plural_title'], 'stm_motors_megamenu'),
			),
			'menu_icon' => 'dashicons-excerpt-view',
			'show_in_nav_menus' => true,
			'supports' => array('title', 'editor'),
			'rewrite' => array('slug' => $stm_megamenu_options['stm_megamenu']['rewrite']),
			'has_archive' => true,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'taxonomies' => array(),
		));
	}
}

