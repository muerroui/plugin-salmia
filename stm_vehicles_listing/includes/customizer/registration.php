<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$show_on_dealer = true;
$show_on_listing = false;

$positions = array(
    'left' => esc_html__('Left', 'stm_vehicles_listing'),
    'right' => esc_html__('Right', 'stm_vehicles_listing'),
);

STM_Customizer_Vehicle::setPanels(array(
    'listing' => array(
        'title' => esc_html__('Listing', 'stm_vehicles_listing'),
        'priority' => 30
    ),
));

$listing_features = array(
    'listing_archive' => array(
        'label' => esc_html__('Listing archive', 'stm_vehicles_listing'),
        'type' => 'stm-post-type',
        'post_type' => 'page',
        'description' => esc_html__('Choose listing archive page', 'stm_vehicles_listing'),
        'default' => ''
    ),
    'price_currency' => array(
        'label' => esc_html__('Price currency', 'stm_vehicles_listing'),
        'type' => 'text',
        'default' => '$'
    ),
    'price_currency_position' => array(
        'label' => esc_html__('Price currency position', 'stm_vehicles_listing'),
        'type' => 'stm-select',
        'choices' => $positions,
        'default' => 'left'
    ),
    'price_delimeter' => array(
        'label' => esc_html__('Price delimiter', 'stm_vehicles_listing'),
        'type' => 'text',
        'default' => ' '
    ),
    'show_listing_stock' => array(
        'label' => esc_html__('Show stock', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => $show_on_dealer
    ),
    'show_listing_compare' => array(
        'label' => esc_html__('Show compare', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => $show_on_dealer
    )
);

STM_Customizer_Vehicle::setSection('listing_features', array(
    'title' => esc_html__('Inventory settings', 'stm_vehicles_listing'),
    'panel' => 'listing',
    'fields' => $listing_features
));

$single_car_settings = array(
    'show_stock' => array(
        'label' => esc_html__('Show stock', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => true,
    ),
    'show_test_drive' => array(
        'label' => esc_html__('Show test drive schedule', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_compare' => array(
        'label' => esc_html__('Show compare', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_share' => array(
        'label' => esc_html__('Show share block', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_pdf' => array(
        'label' => esc_html__('Show PDF brochure', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_certified_logo_1' => array(
        'label' => esc_html__('Show certified logo 1', 'stm_vehicles_listing'),
        'type' => 'checkbox',
    ),
    'show_certified_logo_2' => array(
        'label' => esc_html__('Show certified logo 2', 'stm_vehicles_listing'),
        'type' => 'checkbox',
    ),
    'show_featured_btn' => array(
        'label' => esc_html__('Show featured button', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
    'single_car_break' => array(
        'type' => 'stm-separator',
    ),
    'show_vin' => array(
        'label' => esc_html__('Show VIN', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
    'show_registered' => array(
        'label' => esc_html__('Show Registered date', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
    'show_history' => array(
        'label' => esc_html__('Show History', 'stm_vehicles_listing'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
);

STM_Customizer_Vehicle::setSection('car_settings', array(
    'title' => esc_html__('Single Listing Settings', 'stm_vehicles_listing'),
    'panel' => 'listing',
    'fields' => $single_car_settings
));

STM_Customizer_Vehicle::setSection('user_dealer', array(
    'title' => esc_html__('User/Dealer options', 'stm_vehicles_listing'),
    'panel' => 'listing',
    'fields' => array(
        'user_add_car_page' => array(
            'label' => esc_html__('Add a Listing page', 'stm_vehicles_listing'),
            'type' => 'stm-post-type',
            'post_type' => 'page',
            'description' => esc_html__('Choose page for Add to Listing Page (Also, this page will be used for editing items)', 'stm_vehicles_listing'),
            'default' => '1755'
        ),
        'user_post_limit' => array(
            'label' => esc_html__('User Slots Limit:', 'stm_vehicles_listing'),
            'type' => 'text',
            'default' => esc_html__('3', 'stm_vehicles_listing'),
        ),
        'user_post_images_limit' => array(
            'label' => esc_html__('User Slot Images Upload Limit:', 'stm_vehicles_listing'),
            'type' => 'text',
            'default' => esc_html__('5', 'stm_vehicles_listing'),
        ),
        'user_premoderation' => array(
            'label' => esc_html__('Enable User ads moderation', 'stm_vehicles_listing'),
            'type' => 'stm-checkbox',
            'default' => true
        ),
        'site_demo_mode' => array(
            'label' => esc_html__('Site demo mode', 'stm_vehicles_listing'),
            'type' => 'stm-checkbox',
            'default' => false
        ),
    )
));