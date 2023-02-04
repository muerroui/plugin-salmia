<?php
// register listing types nuxy page
add_filter(
	'wpcfto_options_page_setup',
	function ( $setups ) {
		$pages = multilisting_get_all_pages();

		$setups[] = array(
			'option_name' => 'stm_motors_listing_types',
			'title'       => esc_html__( 'Listing Types', 'motors_listing_types' ),
			'sub_title'   => esc_html__( 'by StylemixThemes', 'motors_listing_types' ),
			'logo'        => get_template_directory_uri() . '/assets/admin/images/logo.png',
			'page'        => array(
				'page_title' => esc_html__( 'Motors Listing Types', 'motors_listing_types' ),
				'menu_title' => esc_html__( 'Listing Types', 'motors_listing_types' ),
				'menu_slug'  => 'stm_motors_listing_types',
				'icon'       => get_template_directory_uri() . '/assets/admin/images/icon.png',
				'position'   => 4,
			),
			'fields'      => array(
				'listing_types' => array(
					'name'   => esc_html__( 'Listing Types', 'motors_listing_types' ),
					'icon'   => 'fas fa-cubes',
					'fields' => array(
						'multilisting_repeater' => array(
							'type'        => 'repeater',
							'label'       => esc_html__( 'Listing Type', 'motors_listing_types' ),
							'load_labels' => array(
								'add_label' => esc_html__( 'Add Listing Type', 'motors_listing_types' ),
							),
							'fields'      => array(
								'label'          => array(
									'type'  => 'text',
									'label' => esc_html__( 'Name', 'motors_listing_types' ),
								),
								'slug'           => array(
									'type'        => 'text',
									'label'       => esc_html__( 'Slug', 'motors_listing_types' ),
									'description' => esc_html__( 'The URL-friendly version of the name. Accepts only letters, numbers, and hyphens', 'motors_listing_types' ),
								),
								'inventory_page' => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Inventory Page', 'motors_listing_types' ),
									'options' => $pages,
								),
								'add_page'       => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Add Listing Page', 'motors_listing_types' ),
									'options' => $pages,
								),
								'icon'           => array(
									'type'  => 'icon_picker',
									'label' => esc_html__( 'Icon', 'motors_listing_types' ),
								),
							),
						),
						'multilisting_current_motors_layout' => array(
							'type'  => 'text',
							'label' => esc_html__( 'Current Motors Layout', 'motors_listing_types' ),
							'value' => multilisting_get_current_motors_layout(),
						),
					),
				),
			),
		);

		// check for existing listing types and add settings for each listing type
		$options = get_option( 'stm_motors_listing_types' );

		if ( ! empty( $options['multilisting_repeater'] ) ) {
			foreach ( $options['multilisting_repeater'] as $key => $item ) {

				if ( empty( $item['label'] ) || empty( $item['slug'] ) ) {
					continue;
				}

				$icon  = ( $item['icon']['icon'] ) ? $item['icon']['icon'] : 'fas fa-car-alt';
				$confs = array_merge( multilisting_get_inventory_confs( $item['slug'] ), multilisting_get_single_confs( $item['slug'] ) );

				$arr_key = array_search( 'stm_motors_listing_types', array_column( $setups, 'option_name' ), true );

				if ( false !== $arr_key ) {
					$setups[ $arr_key ]['fields'][ $item['slug'] ]['name']   = $item['label'];
					$setups[ $arr_key ]['fields'][ $item['slug'] ]['icon']   = $icon;
					$setups[ $arr_key ]['fields'][ $item['slug'] ]['fields'] = $confs;
				}
			}
		}

		return $setups;
	}
);

function multilisting_get_all_pages() {
	// pages to assign
	$pages     = array();
	$get_pages = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		)
	);

	if ( ! empty( $get_pages ) ) {
		foreach ( $get_pages as $page ) {
			$pages[ $page->ID ] = $page->post_title;
		}
	}

	return $pages;
}

function multilisting_get_current_motors_layout() {
	$layout = get_option( 'stm_motors_chosen_template' );

	if ( empty( $layout ) ) {
		$layout = 'car_dealer';
	}

	return $layout;
}

function stm_multilisting_sort_options( $slug ) {
	$opts = (array) get_option( "stm_{$slug}_options", array() );

	$options = array();

	if ( ! empty( $opts ) ) {
		foreach ( $opts as $tax ) {
			if ( isset( $tax['numeric'] ) && ! empty( $tax['numeric'] ) ) {
				$options[ $tax['slug'] ] = $tax['single_name'];
			}
		}
	}

	return $options;
}

function multilisting_default_sortby( $slug ) {
	$sorts = array(
		'date_high' => esc_html__( 'Date: newest first', 'motors_listing_types' ),
		'date_low'  => esc_html__( 'Date: oldest first', 'motors_listing_types' ),
	);

	$options = stm_multilisting_sort_options( $slug );
	if ( ! empty( $options ) ) {
		foreach ( $options as $slug => $name ) {
			$sorts[ $slug . '_high' ] = sprintf( esc_html__( '%s: highest first', 'motors_listing_types' ), $name );
			$sorts[ $slug . '_low' ]  = sprintf( esc_html__( '%s: lowest first', 'motors_listing_types' ), $name );
		}
	}

	return $sorts;

}

function multilisting_get_inventory_confs( $slug ) {
	$inventory_settings = array();
	$cfto_sidebars      = array();
	$cfto_positions     = array();

	if ( function_exists( 'stm_me_wpcfto_sidebars' ) ) {
		$cfto_sidebars = stm_me_wpcfto_sidebars();
	}

	if ( function_exists( 'stm_me_wpcfto_positions' ) ) {
		$cfto_positions = stm_me_wpcfto_positions();
	}

	$pages = multilisting_get_all_pages();

	$conf = array(
		$slug . '_inventory_custom_settings'            => array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Custom Inventory Settings', 'motors_listing_types' ),
			'submenu'     => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'value'       => false,
			'description' => esc_html__( 'Ignores global Inventory Settings in Theme Options', 'motors_listing_types' ),
		),
		$slug . '_classic_listing_title'                => array(
			'label'        => esc_html__( 'Listing Archive "Title Box" Title', 'motors_listing_types' ),
			'type'         => 'text',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'value'        => '',
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'aircrafts||boats||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_hide_price_labels'                    => array(
			'label'        => esc_html__( 'Hide Price Labels on Listing Archive', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'aircrafts||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_listing_directory_title_default'      => array(
			'label'        => esc_html__( 'Default Title', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => '',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'aircrafts||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_listing_sidebar'                      => array(
			'label'        => esc_html__( 'Inventory Sidebar', 'motors_listing_types' ),
			'type'         => 'select',
			'options'      => $cfto_sidebars,
			'value'        => 'no_sidebar',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_listing_view_type'                    => array(
			'label'        => esc_html__( 'Listing View Type', 'motors_listing_types' ),
			'type'         => 'radio',
			'options'      =>
				array(
					'grid' => 'Grid',
					'list' => 'List',
				),
			'value'        => 'list',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_grid_title_max_length'                => array(
			'label'        => esc_html__( 'Grid Item Title Max Length', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => '44',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'grid',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_enable_features_search'               => array(
			'label'        => esc_html__( 'Display Additional Features on Inventory Filter', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_enable_favorite_items'                => array(
			'label'        => esc_html__( 'Enable Favorite Button', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_multilisting_sort_options'            => array(
			'label'        => esc_html__( 'Sort Options', 'motors_listing_types' ),
			'type'         => 'multi_checkbox',
			'options'      => stm_multilisting_sort_options( $slug ),
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_multilisting_default_sort_by'         => array(
			'label'        => esc_html__( 'Default Sort Option', 'motors_listing_types' ),
			'type'         => 'select',
			'value'        => 'date_high',
			'options'      => multilisting_default_sortby( $slug ),
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'description'  => esc_html__( 'Default option must be selected above', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_listing_filter_position'              => array(
			'label'        => esc_html__( 'Filter Position', 'motors_listing_types' ),
			'type'         => 'select',
			'value'        => 'left',
			'options'      => $cfto_positions,
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_enable_location'                      => array(
			'label'        => esc_html__( 'Show Location/Include Location in Filter', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_distance_measure_unit'                => array(
			'label'        => esc_html__( 'Unit Measurement', 'motors_listing_types' ),
			'type'         => 'select',
			'options'      =>
				array(
					'miles'      => 'Miles',
					'kilometers' => 'Kilometers',
				),
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_enable_location',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_distance_search'                      => array(
			'label'        => esc_html__( 'Set Max Search Radius', 'motors_listing_types' ),
			'type'         => 'text',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_enable_location',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_listing_directory_title_frontend'     => array(
			'label'       => esc_html__( 'Display Generated Listing Title as:', 'motors_listing_types' ),
			'type'        => 'text',
			'value'       => '',
			'description' => __( '&quot;Put in curly brackets slug of taxonomy. For Example - {' . $slug . '-color} {' . $slug . '-make} {' . $slug . '-year}. Leave empty if you want to display value listing title.&quot;', 'motors_listing_types' ),//phpcs:ignore
			'submenu'     => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'  => array(
				'key'   => $slug . '_inventory_custom_settings',
				'value' => 'not_empty',
			),
		),
		$slug . '_show_generated_title_as_label'        => array(
			'label'        => esc_html__( 'Show Two First Parameters as a Badge', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'description'  => 'Archive Page and Single Listing',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_listing_directory_enable_dealer_info' => array(
			'label'        => esc_html__( 'Enable Dealer Info on Listing', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six||aircrafts',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_stock'                   => array(
			'label'        => esc_html__( 'Show Stock', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_test_drive'              => array(
			'label'        => esc_html__( 'Show Test Drive Schedule', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_compare'                 => array(
			'label'        => esc_html__( 'Show Compare', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||boats||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_share'                   => array(
			'label'        => esc_html__( 'Show Share Block', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_pdf'                     => array(
			'label'        => esc_html__( 'Show PDF brochure', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_certified_logo_1'        => array(
			'label'        => esc_html__( 'Show Certified Logo 1', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_listing_certified_logo_2'        => array(
			'label'        => esc_html__( 'Show Certified Logo 2', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_listing_view_type',
					'value' => 'list',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_sidebar_filter_bg'                    => array(
			'label'        => esc_html__( 'Listing Sidebar Filter Background', 'motors_listing_types' ),
			'type'         => 'image',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_show_sold_listings'                   => array(
			'label'       => esc_html__( 'Sold Listings', 'motors_listing_types' ),
			'description' => 'Display sold listings in the Classic and Modern filters',
			'type'        => 'checkbox',
			'value'       => false,
			'submenu'     => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'  => array(
				'key'   => $slug . '_inventory_custom_settings',
				'value' => 'not_empty',
			),
		),
		$slug . '_sold_badge_bg_color'                  => array(
			'label'        => esc_html__( 'Sold Badge Background Color', 'motors_listing_types' ),
			'type'         => 'color',
			'mode'         => 'background-color',
			'value'        => '#fc4e4e',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_show_sold_listings',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_dealer_pay_per_listing'               => array(
			'label'        => esc_html__( 'Enable Pay Per Listing', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_pay_per_listing_price'                => array(
			'label'        => esc_html__( 'Pay Per Listing Price', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => '0',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_dealer_pay_per_listing',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_pay_per_listing_period'               => array(
			'label'        => esc_html__( 'Pay Per Listing Period (days)', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => '30',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_dealer_pay_per_listing',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_dealer_payments_for_featured_listing' => array(
			'label'        => esc_html__( 'Enable Paid Featured Listing', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_featured_listing_default_badge'       => array(
			'label'        => esc_html__( 'Featured Listing Label', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => 'Special',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_dealer_payments_for_featured_listing',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_featured_listing_price'               => array(
			'label'        => esc_html__( 'Featured Listing Price', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => '0',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_dealer_payments_for_featured_listing',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
		$slug . '_featured_listing_period'              => array(
			'label'        => esc_html__( 'Featured Listing Period (days)', 'motors_listing_types' ),
			'type'         => 'text',
			'value'        => '30',
			'submenu'      => esc_html__( 'Inventory Settings', 'motors_listing_types' ),
			'dependency'   => array(
				array(
					'key'   => $slug . '_dealer_payments_for_featured_listing',
					'value' => 'not_empty',
				),
				array(
					'key'   => $slug . '_inventory_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
			'dependencies' => '&&',
		),
	);

	return $conf;
}

function multilisting_get_single_confs( $slug ) {
	$single_settings = array();
	$cfto_sidebars   = array();
	$cfto_sorts      = array();
	$cfto_positions  = array();

	if ( function_exists( 'stm_me_wpcfto_sidebars' ) ) {
		$cfto_sidebars = stm_me_wpcfto_sidebars();
	}

	if ( function_exists( 'stm_me_wpcfto_sortby' ) ) {
		$cfto_sorts = stm_me_wpcfto_sortby();
	}

	if ( function_exists( 'stm_me_wpcfto_positions' ) ) {
		$cfto_positions = stm_me_wpcfto_positions();
	}

	$pages = multilisting_get_all_pages();

	$conf = array(
		$slug . '_single_custom_settings' => array(
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Custom Single Listing Settings', 'motors_listing_types' ),
			'submenu'     => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'       => false,
			'description' => esc_html__( 'Ignores global Single Listing settings in Theme Options', 'motors_listing_types' ),
		),
		$slug . '_show_trade_in'          => array(
			'label'        => esc_html__( 'Show Trade In Button', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||motorcycle||equipment',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_offer_price'       => array(
			'label'        => esc_html__( 'Show Offer Price Button', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_stm_car_link_quote'     =>
			array(
				'label'        => esc_html__( 'Request a Quote Link', 'stm_motors_extends' ),
				'type'         => 'text',
				'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
				'dependencies' => '&&',
				'value'        => '#1578032597180-dca29e61-e895',
				'dependency'   => array(
					array(
						'key'   => $slug . '_single_custom_settings',
						'value' => 'not_empty',
					),
					array(
						'key'     => 'multilisting_current_motors_layout',
						'value'   => 'car_dealer_two||equipment||motorcycle',
						'section' => 'listing_types',
					),
				),
			),
		$slug . '_show_calculator'        => array(
			'label'        => esc_html__( 'Show Calculator', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||motorcycle||equipment||boats',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_added_date'        => array(
			'label'        => esc_html__( 'Show Published Date', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_print_btn'         => array(
			'label'        => esc_html__( 'Show Print Button', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||listing||listing_two||listing_three||listing_four||listing_five||listing_six||car_dealer||car_dealer_two',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_test_drive'        => array(
			'label'        => esc_html__( 'Show Test Drive Schedule', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_stock'             => array(
			'label'        => esc_html__( 'Show Stock', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_compare'           => array(
			'label'      => esc_html__( 'Show Compare', 'motors_listing_types' ),
			'type'       => 'checkbox',
			'submenu'    => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'      => false,
			'dependency' => array(
				'key'   => $slug . '_single_custom_settings',
				'value' => 'not_empty',
			),
		),
		$slug . '_show_share'             => array(
			'label'        => esc_html__( 'Show Share Block', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_pdf'               => array(
			'label'        => esc_html__( 'Show PDF brochure', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_certified_logo_1'  => array(
			'label'        => esc_html__( 'Show Certified Logo 1', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_certified_logo_2'  => array(
			'label'        => esc_html__( 'Show Certified Logo 2', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||aircrafts||car_dealer||car_dealer_two||car_magazine||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six||motorcycle',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_featured_btn'      => array(
			'label'        => esc_html__( 'Show Featured Button', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_vin'               => array(
			'label'        => esc_html__( 'Show VIN', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'value'        => false,
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'ev_dealer||car_dealer||car_dealer_two||equipment||listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_registered'        => array(
			'label'        => esc_html__( 'Show Registered Date', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'value'        => false,
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_show_history'           => array(
			'label'        => esc_html__( 'Show History', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'value'        => false,
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six||equipment||motorcycle',
					'section' => 'listing_types',
				),
			),
		),
		$slug . '_stm_show_number'        => array(
			'label'        => esc_html__( 'Show Number', 'motors_listing_types' ),
			'type'         => 'checkbox',
			'value'        => false,
			'submenu'      => esc_html__( 'Single Listing Settings', 'motors_listing_types' ),
			'dependencies' => '&&',
			'dependency'   => array(
				array(
					'key'   => $slug . '_single_custom_settings',
					'value' => 'not_empty',
				),
				array(
					'key'     => 'multilisting_current_motors_layout',
					'value'   => 'listing||listing_two||listing_three||listing_four||listing_five||listing_six',
					'section' => 'listing_types',
				),
			),
		),
	);

	return $conf;
}

// get array of config key names
function multilisting_get_default_confs_array() {
	$inventory = multilisting_get_inventory_confs( 'temp' );
	$single    = multilisting_get_single_confs( 'temp' );

	$array = array(
		'inventory' => array(),
		'single'    => array(),
	);

	foreach ( $inventory as $key => $conf ) {
		if ( 'temp_inventory_custom_settings' !== $key ) {
			array_push( $array['inventory'], str_replace( 'temp_', '', $key ) );
		}
	}

	foreach ( $single as $key => $conf ) {
		if ( 'temp_single_custom_settings' !== $key ) {
			array_push( $array['single'], str_replace( 'temp_', '', $key ) );
		}
	}

	return $array;
}

function multilisting_override_default_configuration( $option_value = '', $option_name = '' ) {
	$post_type = STMMultiListing::stm_get_current_listing_slug();

	$confs = multilisting_get_default_confs_array();

	if ( ! empty( $post_type ) ) {
		$options = get_option( 'stm_motors_listing_types' );

		// inventory setting
		if ( in_array( $option_name, $confs['inventory'], true ) ) {
			// custom settings enabled for post type inventory
			if ( isset( $options[ $post_type . '_inventory_custom_settings' ] ) && true === $options[ $post_type . '_inventory_custom_settings' ] ) {
				return ( empty( $options[ $post_type . '_' . $option_name ] ) ) ? false : $options[ $post_type . '_' . $option_name ];
			}
		}

		// single setting
		if ( in_array( $option_name, $confs['single'], true ) ) {
			// custom settings enabled for post type inventory
			if ( isset( $options[ $post_type . '_single_custom_settings' ] ) && true === $options[ $post_type . '_single_custom_settings' ] ) {
				return $options[ $post_type . '_' . $option_name ];
			}
		}
	}

	return $option_value;
}


// register hooks
$all_confs = multilisting_get_default_confs_array();
$confs     = array_merge( $all_confs['inventory'], $all_confs['single'] );
foreach ( $confs as $key => $conf_name ) {
	add_filter( 'wpcfto_motors_' . $conf_name, 'multilisting_override_default_configuration', PHP_INT_MAX, 2 );
}


// check if listing types changed
add_filter( 'wpcfto_reload_after_save', 'reload_page_when_type_slugs_change', 10, 2 );
function reload_page_when_type_slugs_change( $id, $settings ) {
	if ( 'stm_motors_listing_types' === $id ) {
		$old = stm_wpcfto_get_options( 'stm_motors_listing_types' );

		if ( ! is_array( $settings['multilisting_repeater'] ) || ! is_array( $old['multilisting_repeater'] ) ) {
			return true; //reload the page to see the changes
		}

		// if number of post types changed
		if ( count( $settings['multilisting_repeater'] ) !== count( $old['multilisting_repeater'] ) ) {
			return true;
		}

		// number hasn't changed, let's check for slug changes
		$new_slugs = array_column( $settings['multilisting_repeater'], 'slug' );
		$old_slugs = array_column( $old['multilisting_repeater'], 'slug' );

		$a = array_diff( $new_slugs, $old_slugs );
		$b = array_diff( $old_slugs, $new_slugs );
		if ( count( $a ) > 0 || count( $b ) > 0 ) {
			return true;
		}
	}

	return false;
}

// validate slug
add_action( 'wpcfto_after_settings_saved', 'stm_prevent_identical_type_slugs', 15, 2 );
function stm_prevent_identical_type_slugs( $id, $settings ) {
	if ( 'stm_motors_listing_types' === $id ) {
		if ( ! empty( $settings['multilisting_repeater'] ) ) {

			$slugs = array();

			foreach ( $settings['multilisting_repeater'] as $key => $item ) {
				if ( empty( $item['label'] ) && empty( $item['slug'] ) ) {
					continue;
				}
				$slugs[ $key ] = ( empty( $item['slug'] ) ) ? sanitize_title( $item['label'] ) : sanitize_title( $item['slug'] );
			}

			$new_settings = $settings;

			foreach ( $settings['multilisting_repeater'] as $key => $item ) {
				if ( empty( $item['label'] ) && empty( $item['slug'] ) ) {
					unset( $new_settings['multilisting_repeater'][ $key ] );
					continue;
				}

				$slug  = ( empty( $item['slug'] ) ) ? sanitize_title( $item['label'] ) : sanitize_title( $item['slug'] );
				$found = array_keys( $slugs, $slug, true );

				// if slug already exists
				if ( count( $found ) > 1 && $key > 0 ) {
					$new_settings['multilisting_repeater'][ $key ]['slug'] = $slug . '-' . $key;
				} else {
					$new_settings['multilisting_repeater'][ $key ]['slug'] = $slug;
				}

				// assign default label if no label provided
				if ( empty( $item['label'] ) ) {
					$new_settings['multilisting_repeater'][ $key ]['label'] = esc_html__( 'Custom Listing', 'motors_listing_types' );
				}
			}

			update_option( $id, $new_settings );
		}
	}
}


function stm_get_listings_filter( $slug, $args = array( 'use_on_car_filter' => true ), $filter = true ) {
	$args = wp_parse_args(
		$args,
		array(
			'where'  => array(),
			'key_by' => '',
		)
	);

	$result = array();

	$data = array_filter( (array) get_option( "stm_{$slug}_options" ) );

	if ( stm_listings_post_type() === $slug ) {
		add_option( 'mlt_bypass_options_hook', 'yes' );
		$data = array_filter( (array) get_option( 'stm_vehicle_listing_options' ) );
	}

	foreach ( $data as $key => $_data ) {
		$passed = true;
		foreach ( $args['where'] as $_field => $_val ) {
			if ( array_key_exists( $_field, $_data ) && $_data[ $_field ] !== $_val ) {
				$passed = false;
				break;
			}
		}

		if ( $passed ) {
			if ( $args['key_by'] ) {
				$result[ $_data[ $args['key_by'] ] ] = $_data;
			} else {
				$result[] = $_data;
			}
		}
	}

	if ( $filter ) {
		$result = apply_filters( 'stm_listings_attributes', $result, $args );
	}

	return $result;

}


add_filter( 'stm_listings_ajax_results', 'multilisting_remove_posttype_url', 100 );
function multilisting_remove_posttype_url( $args ) {
	$args['url'] = remove_query_arg( array( 'posttype', 'ajax_action', 'fragments' ) );

	return $args;
}

add_action( 'wp_enqueue_scripts', 'multilisting_front_enqueue_scripts_styles' );
function multilisting_front_enqueue_scripts_styles() {

	wp_enqueue_style( 'multilisting', MULTILISTING_PLUGIN_URL . '/assets/css/multilisting.css', null, time(), 'all' );

	wp_enqueue_style( 'multilisting-grid', MULTILISTING_PLUGIN_URL . '/assets/css/stm-grid.css', null, MULTILISTING_V, 'all' );

	wp_enqueue_script(
		'multilisting',
		MULTILISTING_PLUGIN_URL . '/assets/js/multilisting.js',
		array( 'listings-filter' ),
		time(),
		true
	);
}

add_action( 'admin_enqueue_scripts', 'multilisting_admin_enqueue_scripts_styles' );
function multilisting_admin_enqueue_scripts_styles() {

	wp_enqueue_style( 'multilisting', MULTILISTING_PLUGIN_URL . '/assets/css/multilisting.css', null, time(), 'all' );

	wp_enqueue_script(
		'multilisting',
		MULTILISTING_PLUGIN_URL . '/assets/js/multilisting.js',
		array( 'jquery', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-sortable' ),
		time(),
		true
	);

	wp_enqueue_script( 'jquery-ui-sortable' );

	if ( defined( 'STM_LISTINGS_URL' ) ) {
		wp_enqueue_script( 'stm-theme-multiselect', STM_LISTINGS_URL . '/assets/js/jquery.multi-select.js', array( 'jquery' ) );//phpcs:ignore

		wp_enqueue_script( 'stm-listings-js', STM_LISTINGS_URL . '/assets/js/vehicles-listing.js', array( 'jquery', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), '6.5.8.1' );//phpcs:ignore
	}

	wp_localize_script(
		'multilisting',
		'stm_listings',
		array(
			'listings' => STMMultiListing::stm_get_listings(),
		)
	);

}

function stm_get_single_car_multilisting( $post_type ) {
	$args = array(
		'where'  => array( 'use_on_single_car_page' => true ),
		'key_by' => '',
	);

	$result = array();
	$data   = array_filter( (array) get_option( "stm_{$post_type}_options" ) );

	foreach ( $data as $key => $_data ) {
		$passed = true;
		foreach ( $args['where'] as $_field => $_val ) {
			if ( array_key_exists( $_field, $_data ) && $_data[ $_field ] !== $_val ) {
				$passed = false;
				break;
			}
		}

		if ( $passed ) {
			if ( $args['key_by'] ) {
				$result[ $_data[ $args['key_by'] ] ] = $_data;
			} else {
				$result[] = $_data;
			}
		}
	}

	return apply_filters( 'stm_listings_attributes', $result, $args );
}


function stm_get_custom_taxonomy_pt_count( $slug, $taxonomy, $pt ) {
	// this is cached function, so we can use it
	$total = 0;

	$args = array(
		'post_type'        => $pt,
		'post_status'      => 'publish',
		'posts_per_page'   => 1,
		'suppress_filters' => 0,
	);
	$meta = array(
		'relation' => 'AND',
		array(
			'relation' => 'OR',
			array(
				'key'     => 'car_mark_as_sold',
				'value'   => '',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'car_mark_as_sold',
				'value'   => '',
				'compare' => '=',
			),
		),
	);

	if ( ! empty( $slug ) && ! empty( $taxonomy ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'terms'    => $slug,
				'field'    => 'slug',
			),
		);
	}
	if ( ! empty( $taxonomy ) && is_array( $taxonomy ) ) {
		if ( 'listings' === $pt ) {
			$filter_options = get_option( 'stm_vehicle_listing_options' );
		} else {
			$filter_options = get_option( "stm_{$pt}_options", array() );
		}

		foreach ( $taxonomy as $tax ) {
			if ( ! empty( $filter_options ) ) {

				$taxonomy_info = array();

				foreach ( $filter_options as $filter_option ) {
					if ( $filter_option['slug'] === $tax ) {
						$taxonomy_info = $filter_option;
					}
				}

				if ( ! empty( $taxonomy_info['numeric'] ) && $taxonomy_info['numeric'] ) {
					$__args = array(
						'orderby'    => 'name',
						'order'      => 'ASC',
						'hide_empty' => false,
						'fields'     => 'all',
					);

					$numbers = array();

					$terms = get_terms( $tax, $__args );

					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$numbers[] = intval( $term->name );
						}
					}

					sort( $numbers );

					$start_value = ! empty( current( $numbers ) ) ? current( $numbers ) : 0;
					$end_value   = ! empty( end( $numbers ) ) ? end( $numbers ) : 0;

					$meta[1]['relation'] = 'AND';
					$meta[1][]           = array(
						'key'     => $tax,
						'value'   => $start_value,
						'compare' => '>=',
						'type'    => 'NUMERIC',
					);
					$meta[1][]           = array(
						'key'     => $tax,
						'value'   => $end_value,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					);

					continue;
				}
			}
		}
	}

	$args['meta_query'] = $meta;

	$count_posts = new WP_Query( $args );
	if ( ! is_wp_error( $count_posts ) ) {
		$total = $count_posts->found_posts;
	}

	if ( ! empty( $slug ) && ! empty( $taxonomy ) ) {
		$total = wp_count_posts( $pt );
		$total = $total->publish;
	}

	return $total;
}


if ( ! function_exists( 'stm_user_listings_query' ) ) {
	function stm_user_listings_query( $user_id, $status = 'publish', $per_page = - 1, $popular = false, $offset = 0, $data_desc = false, $get_all = false ) {
		$pay_per_listing = ( $get_all ) ? array() : array(
			'key'     => 'pay_per_listing',
			'compare' => 'NOT EXISTS',
			'value'   => '',
		);

		$post_types = array( stm_listings_post_type() );
		$listings   = STMMultiListing::stm_get_listings();
		if ( ! empty( $listings ) ) {
			foreach ( $listings as $key => $listing ) {
				array_push( $post_types, $listing['slug'] );
			}
		}

		if ( isset( $_GET['listing_type'] ) && ! empty( $_GET['listing_type'] ) && in_array( $_GET['listing_type'], $post_types, true ) ) { //phpcs:ignore
			$post_types = array( $_GET['listing_type'] );
		}

		$args = array(
			'post_type'      => $post_types,
			'post_status'    => $status,
			'posts_per_page' => $per_page,
			'offset'         => $offset,
			'author'         => $user_id,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'stm_car_user',
					'value'   => $user_id,
					'compare' => '=',
				),
				$pay_per_listing,
			),
		);

		if ( $popular ) {
			$args['order']   = 'ASC';
			$args['orderby'] = 'stm_car_views';
		}

		$query = new WP_Query( $args );
		wp_reset_postdata();

		return $query;

	}
}

function stm_user_multilistings_query(
	$user_id,
	$status = 'publish',
	$post_type = 'listings',
	$per_page = - 1,
	$popular = false,
	$offset = 0,
	$data_desc = false,
	$get_all = false
) {
	$pay_per_listing = ( $get_all ) ? array() : array(
		'key'     => 'pay_per_listing',
		'compare' => 'NOT EXISTS',
		'value'   => '',
	);

	$args = array(
		'post_type'      => $post_type,
		'post_status'    => $status,
		'posts_per_page' => $per_page,
		'offset'         => $offset,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => 'stm_car_user',
				'value'   => $user_id,
				'compare' => '=',
			),
			$pay_per_listing,
		),
	);

	if ( $popular ) {
		$args['order']   = 'ASC';
		$args['orderby'] = 'stm_car_views';
	}

	$query = new WP_Query( $args );
	wp_reset_postdata();

	return $query;
}

function stm_user_pay_per_multilistings_query(
	$user_id,
	$status = 'publish',
	$post_type = 'listings',
	$per_page = - 1,
	$popular = false,
	$offset = 0,
	$data_desc = false
) {
	$args = array(
		'post_type'      => $post_type,
		'post_status'    => $status,
		'posts_per_page' => $per_page,
		'offset'         => $offset,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => 'stm_car_user',
				'value'   => $user_id,
				'compare' => '=',
			),
			array(
				'key'     => 'pay_per_listing',
				'compare' => '=',
				'value'   => 'pay',
			),
		),
	);

	if ( $popular ) {
		$args['order']   = 'ASC';
		$args['orderby'] = 'stm_car_views';
	}

	$query = new WP_Query( $args );
	wp_reset_postdata();

	return $query;

}

function stm_multilisting_get_type_icon_by_slug( $slug ) {
	$options = get_option( 'stm_motors_listing_types' );

	if ( isset( $options['multilisting_repeater'] ) && ! empty( $options['multilisting_repeater'] ) ) {
		foreach ( $options['multilisting_repeater'] as $key => $item ) {
			if ( $item['slug'] === $slug ) {
				return $options['multilisting_repeater'][ $key ]['icon']['icon'];
			}
		}
	}

	return 'fas fa-car-alt';
}

// multilisting after post/page save
add_action( 'save_post', 'stm_multilisting_after_post_save', 150, 3 );
function stm_multilisting_after_post_save( $post_id, $post, $updated ) {
	$types = stm_listings_multi_type( false );
	if ( ! empty( $types ) && in_array( $post->post_type, $types, true ) ) {
		if ( isset( $_POST[ 'butterbean_' . $post->post_type . '_manager_setting_gallery' ] ) && ! empty( $_POST[ 'butterbean_' . $post->post_type . '_manager_setting_gallery' ] ) ) {//phpcs:ignore
			$attachment_ids = $_POST[ 'butterbean_' . $post->post_type . '_manager_setting_gallery' ]; //phpcs:ignore
			$exploded       = explode( ',', $attachment_ids );
			if ( is_array( $exploded ) && count( $exploded ) > 0 ) {
				$image_set = false;
				foreach ( $exploded as $img ) {
					if ( ! empty( $img ) && false === $image_set ) {
						set_post_thumbnail( $post_id, $img );
						$image_set = true;
					}
				}
			} else {
				delete_post_thumbnail( $post_id );
			}
		}
	}

	// listing type inventory details
	if ( preg_match( '/stm_listing_types_classic_filter/', $post->post_content, $match ) ) {
		preg_match_all( '/quant_listing_on_grid="(.*?)"/', $post->post_content, $quant_grid_items );
		preg_match_all( '/ppp_on_list="(.*?)"/', $post->post_content, $ppp_on_list );
		preg_match_all( '/ppp_on_grid="(.*?)"/', $post->post_content, $ppp_on_grid );

		update_post_meta( $post_id, 'quant_grid_items', ( ! empty( $quant_grid_items[1][0] ) ) ? $quant_grid_items[1][0] : 3 );
		update_post_meta( $post_id, 'ppp_on_list', ( ! empty( $ppp_on_list[1][0] ) ) ? $ppp_on_list[1][0] : 10 );
		update_post_meta( $post_id, 'ppp_on_grid', ( ! empty( $ppp_on_grid[1][0] ) ) ? $ppp_on_grid[1][0] : 9 );
	}
}

// override repeater script to make it collapsible
add_filter( 'wpcfto_field_repeater', 'multilisting_override_nuxy_repeater', 15 );
function multilisting_override_nuxy_repeater( $template ) {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'stm_motors_listing_types' ) { //phpcs:ignore
		return MULTILISTING_PATH . '/templates/nuxy/metaboxes/fields/repeater.php';
	}

	return $template;
}

// inventory filter post type hidden input
add_action( 'stm_listings_filter_after', 'multilisting_post_type_filter_after' );
function multilisting_post_type_filter_after() {
	$post_type = STMMultiListing::stm_get_current_listing_slug();
	if ( ! empty( $post_type ) ) {
		echo '<input type="hidden" name="posttype" value="' . esc_attr( $post_type ) . '" />';
	}
}

// imitate stm listings single body class
add_filter(
	'body_class',
	function ( $classes ) {
		$slugs = STMMultiListing::stm_get_listing_type_slugs();
		if ( is_singular( $slugs ) && ! in_array( 'single-listings', $classes, true ) ) {
			$classes = array_merge( $classes, array( 'single-listings' ) );
		}

		array_push( $classes, 'stm_motors_listing_types_multilisting_active' );

		// single on dealer two
		if ( is_singular( $slugs ) && stm_is_dealer_two() ) {
			$dark_light_mode = stm_me_get_wpcfto_mod( 'inventory_layout', 'dark' );
			array_push( $classes, 'inventory-' . esc_attr( $dark_light_mode ) );
		}

		return $classes;
	}
);

// load multilisting template
function stm_multilisting_load_template( $template, $vars = array() ) {
	extract( $vars );//phpcs:ignore

	if ( substr( $template, - 4 ) !== '.php' ) {
		$template .= '.php';
	}

	$template_path = apply_filters( 'stm_multilisting_template_path', MULTILISTING_PATH . $template, $template );

	include $template_path;
}

// include multilisting options for tax binding
add_action(
	'init',
	function () {
		add_filter( 'stm_include_multilisting_options', 'stm_multilisting_include_multilisting_options' );
	}
);

function stm_multilisting_include_multilisting_options( $options ) {
	if ( stm_is_multilisting() ) {
		$slugs = stm_listings_multi_type();

		if ( ! empty( $slugs ) ) {
			foreach ( $slugs as $slug ) {
				$type_options = (array) get_option( "stm_{$slug}_options", array() );

				if ( ! empty( $type_options ) ) {
					$options = array_merge( $options, $type_options );
				}
			}
		}
	}

	return $options;
}


// listing types js vars in admin
add_action( 'admin_footer', 'stm_multilisting_admin_js_vars' );
function stm_multilisting_admin_js_vars() {
	if ( isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ) {//phpcs:ignore
		$types = stm_listings_multi_type();

		if ( ! empty( $types ) ) :
			?>
			<script>
                var multilisting_types_admin_js = <?php echo wp_json_encode( $types );//phpcs:ignore ?>;
                var multilisting_current_type_admin_js = '<?php echo get_post_type( $_GET['post'] );//phpcs:ignore ?>';
			</script>
			<?php
		endif;
	}
}


