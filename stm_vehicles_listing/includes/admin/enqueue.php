<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function stm_listings_admin_enqueue($hook)
{
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    wp_enqueue_style('stm-listings-datetimepicker', STM_LISTINGS_URL . '/assets/css/jquery.stmdatetimepicker.css', null, null, 'all');
    wp_enqueue_script('stm-listings-datetimepicker', STM_LISTINGS_URL . '/assets/js/jquery.stmdatetimepicker.js', array('jquery'), null, true);

    wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

    wp_enqueue_media();
    
    if (get_post_type() == 'product' or get_post_type() == 'listings' or get_post_type() == 'page' or $hook == 'listings_page_listing_categories') {

        wp_enqueue_script('stm-theme-multiselect', STM_LISTINGS_URL . '/assets/js/jquery.multi-select.js', array('jquery'));

        $screen = get_current_screen();
        
        wp_enqueue_script('stm-listings-js', STM_LISTINGS_URL . '/assets/js/vehicles-listing.js', array('jquery','jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-sortable'), '6.5.8.1');

        /*Google places*/
        $google_api_key = stm_me_get_wpcfto_mod('google_api_key', '');
        $google_api_map = 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=places';

        wp_register_script('stm_gmap', $google_api_map, array('jquery'), null, true);

        wp_enqueue_script('stm_gmap');
        wp_enqueue_script('stm-google-places', STM_LISTINGS_URL . '/assets/js/stm-google-places.js', 'stm_gmap', null, true);

    }
    wp_enqueue_style('stm_listings_listing_css', STM_LISTINGS_URL . '/assets/css/style.css');
}

add_action('admin_enqueue_scripts', 'stm_listings_admin_enqueue');