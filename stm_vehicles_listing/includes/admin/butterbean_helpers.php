<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*Load butterbean framework*/
add_action('plugins_loaded', 'stm_listings_load_butterbean');
function stm_listings_load_butterbean()
{
    require_once(STM_LISTINGS_PATH . '/includes/admin/butterbean/butterbean.php');
}

function stm_listings_validate_checkbox($value)
{
    return wp_validate_boolean($value) ? 'on' : false;
}

function stm_listings_no_validate($value)
{
    return $value;
}

function stm_listings_validate_image($value)
{
    return !empty($value) ? intval($value) : false;
}

function stm_listings_validate_repeater($value, $butterbean)
{

    /*We need to save user additional features in hidden taxonomy*/
    if ($butterbean->name == 'additional_features') {
	    $new_terms = explode(',', $value);
        wp_set_object_terms( $butterbean->manager->post_id, $new_terms, 'stm_additional_features');
    }
    /*Saved*/

    return $value;
}

function stm_listings_validate_repeater_videos($value, $butterbean)
{
    if(!empty($value)) {
        foreach ($value as $k => $val) {
            if (empty($value[$k])) {
                unset($value[$k]);
            }
        }
    }

    return $value;
}

function stm_listings_multiselect($value, $butterbean)
{
	wp_set_object_terms( $butterbean->manager->post_id, $value, $butterbean->name);

    return $value ? implode(',', $value) : false;
}

function stm_listings_validate_gallery($value)
{
    $value = explode(',', $value);
    $values = array();

    $featured_image = '';

    if (!empty($value)) {
        $i = 0;
        foreach ($value as $img_id) {
            $i++;
            $img_id = intval($img_id);
            if (!empty($img_id)) {
                if ($i != 1) {
                    $values[] = $img_id;
                }
            }
        }
    }

    return !empty($values) ? $values : false;
}

function stm_gallery_videos_posters($value)
{
    if (!empty($value)) {
        $value = explode(',', $value);
    }
    return $value;
}

function stm_listings_get_user_list()
{
    $users_args = array(
        'blog_id' => $GLOBALS['blog_id'],
        'role' => '',
        'meta_key' => '',
        'meta_value' => '',
        'meta_compare' => '',
        'meta_query' => array(),
        'date_query' => array(),
        'include' => array(),
        'exclude' => array(),
        'orderby' => 'registered',
        'order' => 'ASC',
        'offset' => '',
        'search' => '',
        'number' => '',
        'count_total' => false,
        'fields' => 'all',
        'who' => ''
    );
    $users = get_users($users_args);
    $users_dropdown = array(
        '' => esc_html__('Not assigned', 'stm_vehicles_listing')
    );
    if (!is_wp_error($users)) {
        foreach ($users as $user) {
            $users_dropdown[$user->data->ID] = $user->data->user_login;
        }
    }

    return $users_dropdown;
}

function stm_listings_add_category_in()
{
    $response = array();
    $category = $term = '';

    if (!empty($_GET['category'])) {
        $category = sanitize_text_field($_GET['category']);
    }

    if (!empty($_GET['term'])) {
        $term = sanitize_text_field($_GET['term']);
    }

    if (!empty($term) and !empty($category)) {

        $term_slug = sanitize_title($term);
        $term_id = term_exists($term_slug, $category);
        if ($term_id === 0 or $term_id === null) {
            $term_id = wp_insert_term($term, $category);
        } else {
            $term_info = get_term_by('id', $term_id['term_id'], $category);
            $response['message'] = sprintf(esc_html__('%s already added!', 'stm_vehicles_listing'), $term_info->name);
            $response['status'] = 'success';
            $response['slug'] = $term_info->slug;
            $response['name'] = $term_info->name;

            wp_send_json($response);
            exit;
        }

        if (!empty($term_id) and !is_wp_error($term_id)) {
            $term_info = get_term_by('id', $term_id['term_id'], $category);
            $response['message'] = sprintf(esc_html__('%s added!', 'stm_vehicles_listing'), $term_info->name);
            $response['status'] = 'success';
            $response['slug'] = $term_info->slug;
            $response['name'] = $term_info->name;
        } else {
            $response['status'] = 'danger';
            $response['message'] = $term_id->get_error_message();
        }
    }

    wp_send_json($response);
    exit;
}

add_action('wp_ajax_stm_listings_add_category_in', 'stm_listings_add_category_in');

function stm_butterbean_save_post( $post_id ) {

    if ( stm_is_rental() && get_the_ID() != stm_get_wpml_product_parent_id( get_the_ID() ) ) {
        $selectedOffices = get_post_meta( stm_get_wpml_product_parent_id( get_the_ID() ), 'stm_rental_office', true );
        update_post_meta( $post_id, 'stm_rental_office', $selectedOffices );
    }

    $post_type = get_post_type($post_id);
    if ( stm_listings_post_type() != $post_type ) return;

    // manage price
    $price = '';

    if(!empty($_POST['butterbean_stm_car_manager_setting_price']) || !empty($_POST['butterbean_stm_car_manager_setting_sale_price'])) {
        $price = (!empty($_POST['butterbean_stm_car_manager_setting_sale_price'])) ? $_POST['butterbean_stm_car_manager_setting_sale_price'] : $_POST['butterbean_stm_car_manager_setting_price'];
    }

    if(!empty($_POST['stm_car_sale_price']) || !empty($_POST['stm_car_price'])) {
        $price = (!empty($_POST['stm_car_sale_price'])) ? $_POST['stm_car_sale_price'] : $_POST['stm_car_price'];
    }

    $rentPrice = '';
	if(!empty($_POST['butterbean_stm_car_manager_setting_rent_price']) || !empty($_POST['butterbean_stm_car_manager_setting_sale_rent_price'])) {
		$rentPrice = (!empty($_POST['butterbean_stm_car_manager_setting_sale_rent_price'])) ? $_POST['butterbean_stm_car_manager_setting_sale_rent_price'] : $_POST['butterbean_stm_car_manager_setting_rent_price'];
	}

    if($price != '') update_post_meta($post_id, 'stm_genuine_price', $price);
    if($rentPrice != '') update_post_meta($post_id, 'stm_genuine_rent_price', $rentPrice);

    // manage resetting car views
    if(isset($_POST['butterbean_stm_car_manager_setting_stm_car_views']) && empty($_POST['butterbean_stm_car_manager_setting_stm_car_views'])) {
        stm_delete_statistics_data($post_id, 'car');
    }

    // manage resetting phone views
    if(isset($_POST['butterbean_stm_car_manager_setting_stm_phone_reveals']) && empty($_POST['butterbean_stm_car_manager_setting_stm_phone_reveals'])) {
        stm_delete_statistics_data($post_id, 'phone');
    }
}

add_action( 'save_post', 'stm_butterbean_save_post', 100, 1 );


function stm_delete_statistics_data($post_id, $type) {
    $meta_key = ($type == 'car') ? 'stm_car_views' : 'stm_phone_reveals';
    $stat_meta_key = ($type == 'car') ? 'car_views_stat_' : 'phone_reveals_stat_';

    // empty total counter
    delete_post_meta($post_id, $meta_key);

    // empty statistics counters for the last 32 days
    $past = strtotime( date('Y-m-d', strtotime('-32 days', time())) );
    $today = strtotime( date('Y-m-d') );
    
    $dates_arr = [];

    for ( $i = $past; $i <= $today; $i = $i + 86400 ) {
        $date_str = date( 'Y-m-d', $i );
        array_push($dates_arr, $date_str);
    }

    foreach($dates_arr as $date_str) {
        delete_post_meta( $post_id, $stat_meta_key . $date_str );
    }
}