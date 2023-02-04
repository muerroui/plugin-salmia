<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('admin_init', 'stm_listings_metaboxes');

function stm_listings_metaboxes()
{

    $users = get_users(array(
        'blog_id' => $GLOBALS['blog_id'],
        'orderby' => 'registered',
        'order' => 'ASC',
        'count_total' => false,
        'fields' => 'all',
    ));

    $users_dropdown = array(
        'no' => esc_html__('Not assigned', 'stm_vehicles_listing'),
    );

    if (!is_wp_error($users)) {
        foreach ($users as $user) {
            $users_dropdown[$user->data->ID] = $user->data->user_login;
        }
    }

    STM_Vehicle_PostType::addMetaBox('single_car_options', __('Single car page options', 'stm_vehicles_listing'), array('listings'), '', '', '', array(
        'fields' => array(
            'automanager_id' => array(
                'label' => __('Car ID', 'stm_vehicles_listing'),
                'type' => 'hidden',
            ),
            'stm_car_user' => array(
                'label' => __('User added', 'stm_vehicles_listing'),
                'type' => 'select',
                'options' => $users_dropdown,
            ),
            'stm_car_views' => array(
                'label' => __('Car Views', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'divider_main' => array(
                'label' => __('Main Options', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'stock_number' => array(
                'label' => __('Stock number', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'serial_number' => array(
                'label' => __('Serial number', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'registration_number' => array(
                'label' => __('Registration number', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'vin_number' => array(
                'label' => __('VIN number', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'registration_date' => array(
                'label' => __('Registration date', 'stm_vehicles_listing'),
                'type' => 'datepicker',
            ),
            'history' => array(
                'label' => __('History', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'history_link' => array(
                'label' => __('History Link', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'car_brochure' => array(
                'label' => __('Brochure (.pdf)', 'stm_vehicles_listing'),
                'type' => 'file',
            ),
            'stm_car_location' => array(
                'label' => __('Location', 'stm_vehicles_listing'),
                'type' => 'location',
            ),
            'stm_lat_car_admin' => array(
                'label' => __('Latitude', 'stm_vehicles_listing'),
                'type' => 'hidden',
            ),
            'stm_lng_car_admin' => array(
                'label' => __('Longtitude', 'stm_vehicles_listing'),
                'type' => 'hidden',
            ),
            'divider_mpg' => array(
                'label' => __('MPG', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'city_mpg' => array(
                'label' => __('City MPG', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'highway_mpg' => array(
                'label' => __('Highway MPG', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'divider_0' => array(
                'label' => __('Price Options', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'regular_price_label' => array(
                'label' => __('Regular price label (default - Buy For)', 'stm_vehicles_listing'),
                'type' => 'text',
                'default' => __('Buy For', 'stm_vehicles_listing'),
            ),
            'regular_price_description' => array(
                'label' => __('Regular price description (default - Included Taxes & Checkup)', 'stm_vehicles_listing'),
                'type' => 'text',
                'default' => __('Included Taxes & Checkup', 'stm_vehicles_listing'),
            ),
            'special_price_label' => array(
                'label' => __('Special price label (default - MSRP)', 'stm_vehicles_listing'),
                'type' => 'text',
                'default' => __('MSRP', 'stm_vehicles_listing'),
            ),
            'instant_savings_label' => array(
                'label' => __('Instant savings label (default - Instant Savings:)', 'stm_vehicles_listing'),
                'type' => 'text',
                'default' => __('Instant Savings:', 'stm_vehicles_listing'),
            ),
            'car_price_form' => array(
                'label' => __('Enable "Get Car Price" Form', 'stm_vehicles_listing'),
                'type' => 'checkbox',
            ),
            'car_mark_woo_online' => array(
                'label' => __('Sell a car Online', 'stm_vehicles_listing'),
                'type' => 'checkbox',
            ),
            'car_mark_as_sold' => array(
                'label' => __('Mark car as sold', 'stm_vehicles_listing'),
                'type' => 'checkbox',
            ),
            'car_price_form_label' => array(
                'label' => __('Custom label instead of price', 'stm_vehicles_listing'),
                'type' => 'text',
                'description' => __('This text will appear instead of price', 'stm_vehicles_listing'),
            ),
            'divider' => array(
                'label' => __('Gallery Options', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'gallery' => array(
                'label' => __('Gallery', 'stm_vehicles_listing'),
                'type' => 'images',
            ),
            'divider_2' => array(
                'label' => __('Video Options', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'video_preview' => array(
                'label' => __('Video Preview', 'stm_vehicles_listing'),
                'type' => 'image',
            ),
            'gallery_video' => array(
                'label' => __('Gallery Video <br/> (URL Embed video)', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'gallery_videos' => array(
                'label' => __('Additional videos <br/> (URL Embed video)', 'stm_vehicles_listing'),
                'type' => 'repeat_single_text',
            ),
            'divider_3' => array(
                'label' => __('Compare Options', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'additional_features' => array(
                'label' => __('Additional features', 'stm_vehicles_listing'),
                'type' => 'text',
                'description' => __('Separate features with commas, ex: Auxiliary heating,Bluetooth,CD player,Central locking', 'stm_vehicles_listing'),
            ),
            'divider_4' => array(
                'label' => __('Car certified logos', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'certified_logo_1' => array(
                'label' => __('Certified Logo 1', 'stm_vehicles_listing'),
                'type' => 'image',
            ),
            'certified_logo_2' => array(
                'label' => __('Certified Logo 2', 'stm_vehicles_listing'),
                'type' => 'image',
            ),
            'certified_logo_2_link' => array(
                'label' => __('History link 2', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
        ),
    ));

    STM_Vehicle_PostType::addMetaBox('special_offers', __('Special offer settings', 'stm-post-type'), array('listings'), '', '', '', array(
        'fields' => array(
            'special_car' => array(
                'label' => __('Special offer', 'stm-post-type'),
                'type' => 'checkbox',
            ),
            'special_text' => array(
                'label' => __('Special offer text', 'stm-post-type'),
                'type' => 'text',
            ),
            'special_image' => array(
                'label' => __('Special Offer Banner', 'stm-post-type'),
                'type' => 'image',
            ),
            'divider_main' => array(
                'label' => __('Badge style on inner pages', 'stm_vehicles_listing'),
                'type' => 'separator',
            ),
            'badge_text' => array(
                'label' => __('Badge Text (default - SPECIAL)', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'badge_bg_color' => array(
                'label' => __('Badge Background Color', 'stm_vehicles_listing'),
                'type' => 'color_picker',
            ),
        ),
    ));

    $options = get_option('stm_vehicle_listing_options');

    if (!empty($options)) {
        $taxonomies = array(
            'price' => array(
                'label' => __('Regular Price', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
            'sale_price' => array(
                'label' => __('Sale Price', 'stm_vehicles_listing'),
                'type' => 'text',
            ),
        );

        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'fields' => 'all',
            'pad_counts' => false,
        );

        foreach ($options as $key => $option) {
            $terms = get_terms($option['slug'], $args);

            $field_type = 'listing_select';
            if (!empty($option['numeric'])) {
                $field_type = 'text';
            }

            $single_term = array(
                'none' => 'None',
            );

            foreach ($terms as $tax_key => $taxonomy) {
                if (!empty($taxonomy)) {
                    $single_term[$taxonomy->slug] = $taxonomy->name;
                }
            }

            if ($option['slug'] != 'price') {

                $taxonomies[$option['slug']] = array(
                    'label' => $option['single_name'],
                    'type' => $field_type,
                    'options' => $single_term,
                );
            }

        }

        if (class_exists('STM_Vehicle_PostType')) {
            STM_Vehicle_PostType::addMetaBox('listing_filter', __('Car Options', 'stm_vehicles_listing'), array('listings'), '', '', '', array(
                'fields' => $taxonomies,
            ));
        }
    }
}