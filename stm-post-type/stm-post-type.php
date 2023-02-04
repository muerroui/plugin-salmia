<?php
/*
Plugin Name: STM Post Type
Plugin URI: https://stylemixthemes.com/
Description: STM Post Type
Author: Stylemix Themes
Author URI: https://stylemixthemes.com/
Text Domain: stm-post-type
Version: 4.7
*/

define( 'STM_POST_TYPE', 'stm-post-type' );

$plugin_path = dirname(__FILE__);

if(!is_textdomain_loaded('stm-post-type')) {
	load_plugin_textdomain('stm-post-type', false, 'stm-post-type/languages');
}

require_once $plugin_path . '/post_type.class.php';

$options = get_option('stm_post_types_options');
$choosenTemplate = get_option('stm_motors_chosen_template');

$defaultPostTypesOptions = array(
	'listings' => array(
		'title' => __( 'Listings', STM_POST_TYPE ),
		'plural_title' => __( 'Listings', STM_POST_TYPE ),
		'rewrite' => 'listings'
	),
);

$stm_post_types_options = wp_parse_args( $options, $defaultPostTypesOptions );


//Rental
STM_PostType::registerPostType( 'stm_office', __('Office', STM_POST_TYPE),
	array(
		'menu_icon' => 'dashicons-admin-multisite',
		'supports' => array( 'title' ),
		'exclude_from_search' => true,
		'publicly_queryable' => false
	)
);

STM_PostType::registerPostType( 'sidebar', __('Sidebar', STM_POST_TYPE),
	array(
		'menu_icon' => 'dashicons-schedule',
		'supports' => array( 'title', 'editor' ),
		'exclude_from_search' => true,
		'publicly_queryable' => false
	)
);

STM_PostType::registerPostType(
	'test_drive_request',
	__( 'Test Drive Requests', STM_POST_TYPE ),
	array(
		'pluralTitle' => __('Test drives', STM_POST_TYPE),
		'supports' => array( 'title' ),
		'exclude_from_search' => true,
		'publicly_queryable' => false,
		'show_in_menu' => 'edit.php?post_type=listings'
	) );

$title_box_opt = array(
	'page_bg_color' => array(
		'label' => __( 'Page Background Color', STM_POST_TYPE ),
		'type'  => 'color_picker'
	),
	'transparent_header' => array(
		'label'   => __( 'Transparent Header', STM_POST_TYPE ),
		'type'    => 'checkbox'
	),
	'separator_title_box' => array(
		'label'   => __( 'Title Box', STM_POST_TYPE ),
		'type'    => 'separator'
	),
	'alignment' => array(
		'label'   => __( 'Alignment', STM_POST_TYPE ),
		'type'    => 'select',
		'options' => array(
			'left' => __( 'Left', STM_POST_TYPE ),
			'center' => __( 'Center', STM_POST_TYPE ),
			'right' => __( 'Right', STM_POST_TYPE )
		)
	),
	'title' => array(
		'label'   => __( 'Title', STM_POST_TYPE ),
		'type'    => 'select',
		'options' => array(
			'show' => __( 'Show', STM_POST_TYPE ),
			'hide' => __( 'Hide', STM_POST_TYPE )
		)
	),
	'stm_title_tag' => array(
		'label'   => __( 'Select Title Tag', STM_POST_TYPE ),
		'type'    => 'select',
		'options' => array(
			'h2' => __( 'H2', STM_POST_TYPE ),
			'h1' => __( 'H1', STM_POST_TYPE )
		)
	),
	'sub_title' => array(
		'label'   => __( 'Sub Title', STM_POST_TYPE ),
		'type'    => 'text'
	),
	'title_box_bg_color' => array(
		'label' => __( 'Background Color', STM_POST_TYPE ),
		'type'  => 'color_picker'
	),
	'title_box_font_color' => array(
		'label' => __( 'Font Color', STM_POST_TYPE ),
		'type'  => 'color_picker'
	),
	'title_box_line_color' => array(
		'label' => __( 'Line Color', STM_POST_TYPE ),
		'type'  => 'color_picker'
	),
	'title_box_subtitle_font_color' => array(
		'label' => __( 'Sub Title Font Color', STM_POST_TYPE ),
		'type'  => 'color_picker'
	),
	'title_box_custom_bg_image' => array(
		'label' => __( 'Custom Background Image', STM_POST_TYPE ),
		'type'  => 'image'
	),
	'separator_breadcrumbs' => array(
		'label'   => __( 'Breadcrumbs', STM_POST_TYPE ),
		'type'    => 'separator'
	),
	'breadcrumbs' => array(
		'label'   => __( 'Breadcrumbs', STM_POST_TYPE ),
		'type'    => 'select',
		'options' => array(
			'show' => __( 'Show', STM_POST_TYPE ),
			'hide' => __( 'Hide', STM_POST_TYPE )
		)
	),
	'breadcrumbs_font_color' => array(
		'label' => __( 'Breadcrumbs Color', STM_POST_TYPE ),
		'type'  => 'color_picker'
	),
);

if($choosenTemplate == 'rental_two') {
	$title_box_opt['separator_home_page'] = array(
		'label'   => __( 'Home Page', STM_POST_TYPE ),
		'type'    => 'separator'
	);

	$title_box_opt['stm_select_home_page'] = array(
		'label'   => __( 'Select Home Page', STM_POST_TYPE ),
		'type'    => 'select',
		'options' => array(
			'home_page_1' => __( 'Home 1', STM_POST_TYPE ),
			'home_page_2' => __( 'Home 2', STM_POST_TYPE )
		)
	);
	$title_box_opt['home_page_logo'] = array(
		'label' => __( 'Home Page Logo', STM_POST_TYPE ),
		'type'  => 'image'
	);
}

if($choosenTemplate == 'motorcycle') {
	$title_box_opt['motorcycle_sep'] = array(
		'label'   => __( 'Additional Title Box opt (Motorcycle layout)', STM_POST_TYPE ),
		'type'    => 'separator'
	);
	$title_box_opt['sub_title_instead'] = array(
		'label'   => __( 'Text instead Title', STM_POST_TYPE ),
		'type'    => 'text'
	);
	$title_box_opt['disable_title_box_overlay'] = array(
		'label'   => __( 'Disable Title Box Color Overlay', STM_POST_TYPE ),
		'type'    => 'checkbox'
	);
}

if($choosenTemplate == 'car_magazine') {
    STM_PostType::addMetaBox( 'video_url', __( 'Set Youtube Url', STM_POST_TYPE ), array( 'post'), '', 'side', '', array(
        'fields' => array('video_url' => array(
            'label'   => __( 'Url', STM_POST_TYPE ),
            'type'    => 'text'
        ))
    ) );
}

if($choosenTemplate == 'listing_five') {
	$title_box_opt = array(
		'transparent_header' => array(
			'label'   => __( 'Transparent Header', STM_POST_TYPE ),
			'type'    => 'checkbox'
		),
	);
}

STM_PostType::addMetaBox( 'page_options', __( 'Page Options', STM_POST_TYPE ), array( 'page', 'post', 'listings', 'product', 'stm_events', 'stm_review' ), '', '', '', array(
	'fields' => $title_box_opt
) );

STM_PostType::addMetaBox( 'test_drive_form', __( 'Credentials', STM_POST_TYPE ), array( 'test_drive_request' ), '', '', '', array(
	'fields' => array(
		'name' => array(
			'label'   => __( 'Name', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'email' => array(
			'label'   => __( 'E-mail', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'phone' => array(
			'label'   => __( 'Phone', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'date' => array(
			'label'   => __( 'Day', STM_POST_TYPE ),
			'type'    => 'text'
		),
	)
));


if(function_exists('stm_is_magazine') && stm_is_magazine()) {
    /*STM_PostType::addMetaBox( 'page_options', __( 'Page Options', STM_POST_TYPE ), array( 'page', 'post', 'listings', 'product', 'stm_events', 'stm_review' ), '', '', '', array(
        'fields' => $title_box_opt
    ) );*/
}

$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
$available_cf7 = array();
if( $cf7Forms = get_posts( $args ) ){
	foreach($cf7Forms as $cf7Form){
		$available_cf7[$cf7Form->ID] = $cf7Form->post_title;
	};
} else {
	$available_cf7['No CF7 forms found'] = 'none';
};

$users_args = array(
    'blog_id'      => $GLOBALS['blog_id'],
    'role'         => '',
    'meta_key'     => '',
    'meta_value'   => '',
    'meta_compare' => '',
    'meta_query'   => array(),
    'date_query'   => array(),
    'include'      => array(),
    'exclude'      => array(),
    'orderby'      => 'registered',
    'order'        => 'ASC',
    'offset'       => '',
    'search'       => '',
    'number'       => '',
    'count_total'  => false,
    'fields'       => 'all',
    'who'          => ''
);
$users = get_users( $users_args );
$users_dropdown = array(
    'no' => esc_html__('Not assigned', STM_POST_TYPE)
);
if(!is_wp_error($users)) {
    foreach($users as $user) {
        $users_dropdown[$user->data->ID] = $user->data->user_login;
    }
}

STM_PostType::addMetaBox( 'service_info', esc_html__( 'Options', STM_POST_TYPE ), array( 'service' ), '', '', '', array(
	'fields' => array(
		'icon' => array(
			'label' => esc_html__( 'Icon', STM_POST_TYPE ),
			'type'  => 'iconpicker'
		),
		'icon_bg' => array(
			'label' => esc_html__( 'Icon Background Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		)
	)
) );

if($choosenTemplate == 'listing' || $choosenTemplate == 'listing_two' || $choosenTemplate == 'listing_three' || $choosenTemplate == 'listing_four') {

	STM_PostType::addMetaBox(
		'listing_seller_note',
		esc_html__( 'Seller`s note', STM_POST_TYPE ),
		array( 'listings' ),
		'',
		'normal',
		'high',
		array(
			'fields' => array(
				'listing_seller_note' => array(
					'label' => '',
					'type'  => 'texteditor',
					'class' => 'fullwidth'
				)
			)
		)
	);

	STM_PostType::registerPostType( 'dealer_review', __( 'Dealer Review', STM_POST_TYPE ),
		array(
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'editor' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false
		)
	);

	$rates = array();
	for($i=1; $i < 6; $i++) {
		$rates[$i] = $i;
	}

	$likes = array(
		'neutral' => esc_html__('Neutral', 'motors'),
		'yes' => esc_html__('Yes', 'motors'),
		'no' => esc_html__('No', 'motors'),
	);

	STM_PostType::addMetaBox( 'dealer_reviews', esc_html__( 'Reviews', STM_POST_TYPE ), array( 'dealer_review' ), '', '', '', array(
		'fields' => array(
			'stm_review_added_by' => array(
				'label'   => __( 'User added by', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $users_dropdown
			),
			'stm_review_added_on' => array(
				'label'   => __( 'User added on', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $users_dropdown
			),
			'stm_rate_1' => array(
				'label'   => __( 'Rate 1', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $rates
			),
			'stm_rate_2' => array(
				'label'   => __( 'Rate 2', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $rates
			),
			'stm_rate_3' => array(
				'label'   => __( 'Rate 3', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $rates
			),
			'stm_recommended' => array(
				'label'   => __( 'Recommended', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $likes
			),
		)
	) );
}

if($choosenTemplate == 'listing_two') {
    STM_PostType::registerPostType( 'car_value', __( 'Value My Car', STM_POST_TYPE ),
        array(
            'menu_icon'           => 'dashicons-groups',
            'supports'            => array( 'title', 'editor', 'thumbnail' ),
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'show_in_menu'        => false
        )
    );
}

function stm_plugin_styles() {
    $plugin_url =  plugins_url('', __FILE__);

    wp_enqueue_style( 'admin-styles', $plugin_url . '/assets/css/admin.css', null, null, 'all' );

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_style( 'stmcss-datetimepicker', $plugin_url . '/assets/css/jquery.stmdatetimepicker.css', null, null, 'all' );
    wp_enqueue_script( 'stmjs-datetimepicker', $plugin_url . '/assets/js/jquery.stmdatetimepicker.js', array( 'jquery' ), null, true );
	
	$google_api_key = '';
	
	if(function_exists('stm_me_get_wpcfto_mod')) {
		$google_api_key = stm_me_get_wpcfto_mod( 'google_api_key', '' );
	}
	
	if( !empty($google_api_key) ) {
		$google_api_map = 'https://maps.googleapis.com/maps/api/js?libraries=places&key='.$google_api_key.'&';
	} else {
		$google_api_map = 'https://maps.googleapis.com/maps/api/js?libraries=places';
	}

	wp_register_script( 'stm_gmap_admin', $google_api_map, array( 'jquery' ), null, true );

	wp_enqueue_script( 'stm_gmap_admin' );

	wp_enqueue_script( 'stmjs-admin-places', $plugin_url . '/assets/js/stm-admin-places.js', array( 'jquery' ), null, true );

    wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'stm_plugin_styles' );

require_once $plugin_path . '/rewrite.php';

