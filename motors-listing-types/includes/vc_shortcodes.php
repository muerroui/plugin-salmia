<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'stm_motors_listing_types_vc_shortcodes' );
function stm_motors_listing_types_vc_shortcodes() {

	if ( ! function_exists( 'vc_map' ) ) {
		return false;
	}

	$listings = STMMultiListing::stm_get_listings();

	$listings[] = array(
		'slug'  => 'listings',
		'label' => __( 'Listings', 'motors_listing_types' ),
	);

	// post types
	$post_types = $filter_options = array();

	if ( ! empty( $listings ) ) {
		$stm_filter_options = array();
		foreach ( $listings as $key => $listing ) {

			if ( empty( $listing['label'] ) || empty( $listing['slug'] ) ) {
				continue;
			}

			$post_types[ $listing['label'] ] = $listing['slug'];

			if ( function_exists( 'stm_get_listings_filter' ) && function_exists( 'stm_get_car_filter' ) ) {
				if ( $listing['slug'] == 'listings' ) {
					$filter_options = stm_get_car_filter();
				} else {
					$filter_options = stm_get_listings_filter( $listing['slug'], array( 'where' => array( 'use_on_car_filter' => true ) ), false );
				}
			}

			if ( ! empty( $filter_options ) ) {
				foreach ( $filter_options as $filter_option ) {
					$key                        = $filter_option['single_name'] . ' (' . $filter_option['slug'] . ')';
					$stm_filter_options[ $key ] = $filter_option['slug'];
				}
			}
		}
	}

	$only_custom_post_types = array();

	foreach ( $listings as $key => $listing ) {
		if ( $listing['slug'] != 'listings' ) {
			$only_custom_post_types[ $listing['label'] ] = $listing['slug'];
		}
	}

	$stm_filter_options_location             = $stm_filter_options;
	$stm_filter_options_location['Location'] = 'location';

	// pages
	$pages = get_posts(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => -1,
		)
	);

	$select_pages = array( __( 'Select', 'motors_listing_types' ) => 0 );
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$select_pages[ get_the_title( $page->ID ) ] = $page->ID;
		}
	}

	vc_map(
		array(
			'name'          => __( 'MLT Compare', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_compare',
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_compare.php',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'params'        => array(
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Add Buttons', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_add_buttons',
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_add_buttons.php',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'params'        => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Default listing type label', 'motors_listing_types' ),
					'param_name' => 'stm_listings_label',
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Default listing type icon', 'motors_listing_types' ),
					'param_name' => 'stm_listings_icon',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Default listing type icon color', 'motors_listing_types'),
					'param_name' => 'icon_color',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Default listing type add page', 'motors_listing_types' ),
					'param_name' => 'add_page_id',
					'value'      => $select_pages,
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Add Form', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_add_form',
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_add_form.php',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'params'        => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Listing type', 'motors_listing_types' ),
					'param_name' => 'post_type',
					'value'      => $only_custom_post_types,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Include listing title', 'motors_listing_types' ),
					'param_name' => 'show_car_title',
					'std'        => 'yes',
					'value'      => array(
						esc_html__( 'Yes', 'motors_listing_types' ) => 'yes',
						esc_html__( 'No', 'motors_listing_types' ) => 'no',
					),
				),
				array(
					'type'        => 'stm_autocomplete_vc_multilist',
					'heading'     => __( 'Main taxonomies to fill', 'motors_listing_types' ),
					'param_name'  => 'taxonomy',
					'description' => __(
						'Type slug of the category (don\'t delete anything from autocompleted suggestions)',
						'motors_listing_types'
					),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __(
						'Show number fields as input instead of dropdown',
						'motors_listing_types'
					),
					'param_name' => 'use_inputs',
					'value'      => array(
						__( 'Yes', 'motors_listing_types' ) => 'yes',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Allowed histories', 'motors_listing_types' ),
					'param_name'  => 'stm_histories',
					'description' => esc_html__(
						'Enter allowed histories, separated by comma without spaces. Example - (Carfax, AutoCheck, Carfax 1 Owner, etc)',
						'motors_listing_types'
					),
				),
				array(
					'type'       => 'param_group',
					'heading'    => __( 'Items', 'motors_listing_types' ),
					'param_name' => 'items',
					'value'      => urlencode(
						json_encode(
							array(
								array(
									'label' => __( 'Listing feature title', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Listing features', 'motors_listing_types' ),
									'value' => '',
								),
							)
						)
					),
					'params'     => array(
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Listing feature section title', 'motors_listing_types' ),
							'param_name'  => 'tab_title_single',
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Listing feature section features', 'motors_listing_types' ),
							'param_name'  => 'tab_title_labels',
							'description' => esc_html__(
								'Enter features, separated by comma without spaces. Example - (Bluetooth,DVD Player,etc)',
								'motors_listing_types'
							),
						),
					),
					'group'      => esc_html__( 'Step 2 features', 'motors_listing_types' ),
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => __( 'Media gallery notification text', 'motors_listing_types' ),
					'param_name' => 'content',
					'group'      => esc_html__( 'Step 3 gallery', 'motors_listing_types' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Seller template phrases', 'motors_listing_types' ),
					'param_name'  => 'stm_phrases',
					'description' => esc_html__(
						'Enter phrases, separated by comma without spaces. Example - (Excellent condition, Always garaged, etc)',
						'motors_listing_types'
					),
					'group'       => esc_html__( 'Step 5 phrases', 'motors_listing_types' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title for new users', 'motors_listing_types' ),
					'param_name' => 'stm_title_user',
					'group'      => esc_html__( 'Register/Login User', 'motors_listing_types' ),
				),
				array(
					'type'       => 'textarea',
					'heading'    => __( 'Text for new users', 'motors_listing_types' ),
					'param_name' => 'stm_text_user',
					'group'      => esc_html__( 'Register/Login User', 'motors_listing_types' ),
				),
				array(
					'type'       => 'vc_link',
					'heading'    => __( 'Agreement page', 'motors_listing_types' ),
					'param_name' => 'link',
					'group'      => esc_html__( 'Register/Login User', 'motors_listing_types' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Price title', 'motors_listing_types' ),
					'param_name' => 'stm_title_price',
					'group'      => esc_html__( 'Price', 'motors_listing_types' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Show Price label', 'motors_listing_types' ),
					'param_name' => 'show_price_label',
					'group'      => esc_html__( 'Price', 'motors_listing_types' ),
					'std'        => 'no',
					'value'      => array(
						esc_html__( 'Yes', 'motors_listing_types' ) => 'yes',
						esc_html__( 'No', 'motors_listing_types' ) => 'no',
					),
				),
				array(
					'type'       => 'textarea',
					'heading'    => __( 'Price description', 'motors_listing_types' ),
					'param_name' => 'stm_title_desc',
					'group'      => esc_html__( 'Price', 'motors_listing_types' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Similar Listings', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_similar_listings',
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_similar_listings.php',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'params'        => array(
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Search Tabs', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_search_tabs',
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_search_tabs.php',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'params'        => array(
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show Category Listings amount', 'motors_listing_types' ),
					'param_name' => 'show_amount',
					'value'      => array(
						__( 'Yes', 'motors_listing_types' ) => 'yes',
					),
					'std'        => 'yes',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Search button label', 'motors_listing_types' ),
					'param_name' => 'search_button_label',
					'std'        => __( 'Search', 'motors_listing_types' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Select prefix', 'motors_listing_types' ),
					'param_name' => 'select_prefix',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Select affix', 'motors_listing_types' ),
					'param_name' => 'select_affix',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number Select prefix', 'motors_listing_types' ),
					'param_name' => 'number_prefix',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number Select affix', 'motors_listing_types' ),
					'param_name' => 'number_affix',
				),
				array(
					'type'        => 'param_group',
					'heading'     => __( 'Items', 'motors_listing_types' ),
					'param_name'  => 'items',
					'description' => __( 'Enter values for items - title, sub title.', 'motors_listing_types' ),
					'value'       => urlencode(
						json_encode(
							array(
								array(
									'label' => __( 'Taxonomy', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Listing Type', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Tab Title', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Tab ID', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Filters', 'motors_listing_types' ),
									'value' => '',
								),
							)
						)
					),
					'params'      => array(
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Listing Type', 'motors_listing_types' ),
							'param_name'  => 'tab_listing_type',
							'admin_label' => true,
							'value'       => $post_types,
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Tab title', 'motors_listing_types' ),
							'param_name'  => 'tab_title_single',
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Tab ID', 'motors_listing_types' ),
							'param_name'  => 'tab_id_single',
							'admin_label' => true,
						),
						array(
							'type'       => 'checkbox',
							'heading'    => __( 'Select Taxonomies, which will be in this tab as filter', 'motors_listing_types' ),
							'param_name' => 'filter_selected',
							'value'      => $stm_filter_options_location,
						),
					),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	$carousel_types[ __( 'All listing types', 'motors_listing_types' ) ] = 'all';

	$carousel_types = array_merge( $carousel_types, $post_types );

	$per_slide = array(
		__( '2 items', 'motors_listing_types' ) => '2',
		__( '3 items', 'motors_listing_types' ) => '3',
		__( '4 items', 'motors_listing_types' ) => '4',
	);

	vc_map(
		array(
			'name'          => __( 'MLT Classic Carousel', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_classic_carousel',
			'icon'          => 'stm_listing_types_classic_carousel',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_classic_carousel.php',
			'params'        => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Listing to show', 'motors_listing_types' ),
					'param_name' => 'post_type',
					'value'      => $carousel_types,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Autoplay Carousel', 'motors_listing_types' ),
					'param_name' => 'autoplay',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Listings limit', 'motors_listing_types' ),
					'param_name'  => 'limit',
					'description' => __( 'Enter "-1" for unlimited number of listings', 'motors_listing_types' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Number of items per slide', 'motors_listing_types' ),
					'param_name' => 'vis_limit',
					'value'      => $per_slide,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Sort items', 'motors_listing_types' ),
					'param_name' => 'sort_by',
					'value'      => array(
						__( 'Only Featured', 'motors_listing_types' ) => 'featured',
						__( 'Most Recent', 'motors_listing_types' ) => 'recent',
						__( 'Most Popular', 'motors_listing_types' ) => 'popular',

					),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Tabs Style 2', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_tabs_style_2',
			'icon'          => 'stm_listing_types_tabs_style_2',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_tabs_style_2.php',
			'params'        => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title', 'motors_listing_types' ),
					'param_name' => 'title',
					'value'      => __( 'Browse by type', 'motors_listing_types' ),
				),
				array(
					'type'        => 'param_group',
					'heading'     => __( 'Items', 'motors_listing_types' ),
					'param_name'  => 'items',
					'description' => __( 'Enter values for listings - title, limit.', 'motors_listing_types' ),
					'value'       => urlencode(
						json_encode(
							array(
								array(
									'label' => __( 'Tab Title', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Listing Type', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Number of listings to show in tab', 'motors_listing_types' ),
									'value' => '8',
								),
								array(
									'label' => __( 'Order items by', 'motors_listing_types' ),
									'value' => '',
								),
								array(
									'label' => __( 'Tab ID', 'motors_listing_types' ),
									'value' => '',
								),
							)
						)
					),
					'params'      => array(
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Tab title', 'motors_listing_types' ),
							'param_name'  => 'tab_title_single',
							'admin_label' => true,
						),
						array(
							'type'        => 'dropdown',
							'heading'     => __( 'Listing Type', 'motors_listing_types' ),
							'param_name'  => 'tab_listing_type',
							'admin_label' => true,
							'value'       => $post_types,
						),
						array(
							'type'       => 'textfield',
							'heading'    => __( 'Number of listings to show in tab', 'motors_listing_types' ),
							'param_name' => 'tab_limit',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => __( 'Order items by', 'motors_listing_types' ),
							'param_name' => 'order_by',
							'value'      => array(
								__( 'Most Recent', 'motors_listing_types' ) => 'recent',
								__( 'Most Popular', 'motors_listing_types' ) => 'popular',

							),
						),

						array(
							'type'       => 'textfield',
							'heading'    => __( 'Tab ID', 'motors_listing_types' ),
							'param_name' => 'tab_id_single',
						),
					),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Include featured items', 'motors_listing_types' ),
					'param_name' => 'featured',
					'value'      => array(
						__( 'Yes', 'motors_listing_types' ) => 'yes',
					),
					'std'        => 'yes',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Featured tabs label', 'motors_listing_types' ),
					'param_name' => 'featured_label',
					'std'        => __( 'Featured items', 'motors_listing_types' ),
					'dependency' => array(
						'element' => 'featured',
						'value'   => 'yes',
					),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show "Show more" button in tabs', 'motors_listing_types' ),
					'param_name' => 'show_more',
					'value'      => array(
						__( 'Yes', 'motors_listing_types' ) => 'yes',
					),
					'std'        => 'yes',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'Css', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Featured Masonry Carousel', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_masonry_carousel',
			'icon'          => 'stm_listing_types_masonry_carousel',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_masonry_carousel.php',
			'params'        => array(
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Title', 'motors_listing_types' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Items Per Row', 'motors_listing_types' ),
					'param_name' => 'row_number',
					'value'      => array(
						'3' => '3',
						'4' => '4',
						'5' => '5',
					),
					'std'        => '4',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Listing to show', 'motors_listing_types' ),
					'param_name' => 'post_type',
					'value'      => $carousel_types,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Autoplay Carousel', 'motors_listing_types' ),
					'param_name' => 'autoplay',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Listings limit', 'motors_listing_types' ),
					'param_name'  => 'limit',
					'description' => __( 'Enter "-1" for unlimited number of listings', 'motors_listing_types' ),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Order items by', 'motors_listing_types' ),
					'param_name' => 'order_by',
					'value'      => array(
						__( 'Most Recent', 'motors_listing_types' ) => 'recent',
						__( 'Most Popular', 'motors_listing_types' ) => 'popular',

					),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Latest blog posts', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_latest_posts',
			'icon'          => 'stm_listing_types_latest_posts',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_latest_posts.php',
			'params'        => array(
				array(
					'type'       => 'textfield',
					'holder'     => 'div',
					'heading'    => __( 'Title', 'stm_motors_classified_five' ),
					'param_name' => 'title',
				),
				array(
					'type'       => 'textarea_html',
					'heading'    => __( 'Subtitle content', 'stm_motors_classified_five' ),
					'param_name' => 'content',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Number of Posts', 'stm_motors_classified_five' ),
					'param_name' => 'posts_per_page',
					'std'        => 3,
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);

	vc_map(
		array(
			'name'          => __( 'MLT Classic Filter', 'motors_listing_types' ),
			'base'          => 'stm_listing_types_classic_filter',
			'icon'          => 'stm_listing_types_classic_filter',
			'category'      => __( 'Motors Listing Types', 'motors_listing_types' ),
			'html_template' => MULTILISTING_PATH . '/vc_templates/stm_listing_types_classic_filter.php',
			'params'        => array(
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Listing type', 'motors_listing_types' ),
					'param_name' => 'post_type',
					'value'      => $only_custom_post_types,
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Posts per page on list view', 'motors_listing_types' ),
					'param_name' => 'ppp_on_list',
					'value'      => '10',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Posts per page on grid view', 'motors_listing_types' ),
					'param_name' => 'ppp_on_grid',
					'value'      => '9',
				),
				// TODO: Make quantity per row work for multilisting inventory
				// array(
				// 'type' => 'dropdown',
				// 'heading' => __('Quantity of listing per row on grid view', 'motors_listing_types'),
				// 'param_name' => 'quant_listing_on_grid',
				// 'value' => array (
				// '2' => '2',
				// '3' => '3',
				// ),
				// 'std' => '3'
				// ),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS', 'motors_listing_types' ),
					'param_name' => 'css',
					'group'      => __( 'Design options', 'motors_listing_types' ),
				),
			),
		)
	);
}

function stm_autocomplete_vc_st_tax_multilist( $settings, $value ) {
	$listings = STMMultiListing::stm_get_listings();
	$taxes    = $temp = array();
	if ( ! empty( $listings ) ) {
		foreach ( $listings as $key => $listing ) {
			$slug = $listing['slug'];
			$temp = get_object_taxonomies( $slug, 'objects' );
			foreach ( $temp as $item ) {
				$taxes[ $listing['label'] . ' - ' . $item->label ] = $item->name;
			}
		}
	}
	return '<div class="stm_autocomplete_vc_field">'
		. '<script type="text/javascript">'
		. 'var st_vc_taxonomies = ' . json_encode( $taxes )
		. '</script>'
		. '<input 
				type="text" 
				name="' . esc_attr( $settings['param_name'] ) . '" 
				class="stm_autocomplete_vc wpb_vc_param_value wpb-textinput ' .
				esc_attr( $settings['param_name'] ) . ' ' .
				esc_attr( $settings['type'] ) . '_field" 
				value="' . esc_attr( $value ) . '" />' .
		'</div>';
}

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param(
		'stm_autocomplete_vc_multilist',
		'stm_autocomplete_vc_st_tax_multilist',
		MULTILISTING_PLUGIN_URL . '/assets/js/jquery-ui.min.js'
	);
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_STM_Listing_Types_Compare extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Add_Buttons extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Add_Form extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Similar_Listings extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Search_Tabs extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Classic_Carousel extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Tabs_Style_Two extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Masonry_Carousel extends WPBakeryShortCode{}
	class WPBakeryShortCode_STM_Listing_Types_Latest_Blog_Posts extends WPBakeryShortCode{}
}
