<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('wp', 'stm_listings_template_actions');

function stm_listings_template_actions() {
    if ($action = stm_listings_input('ajax_action')) {
        define( 'DOING_AJAX', true );
        switch ($action) {
            case 'listings-result':
                $navType = (!empty($_GET['navigation_type'])) ? $_GET['navigation_type'] : null;
                $source = (!empty($_GET['posts_per_page'])) ? array( 'posts_per_page' => $_GET['posts_per_page'] ) : null;

                stm_listings_ajax_results($source, null, $navType);
                break;
            case 'listings-result-load':
                $navType = (!empty($_GET['navigation_type'])) ? $_GET['navigation_type'] : null;
                $source = (!empty($_GET['posts_per_page'])) ? array( 'posts_per_page' => $_GET['posts_per_page'] ) : null;
                $source = (!empty($_GET['offset'])) ? array( 'offset' => ($_GET['offset'] * $_GET['posts_per_page'])) : $source;

                stm_listings_items_ajax_results($source, null, $navType);
                break;
            case 'listings-sold':
                stm_listings_ajax_results(['sold_car' => 'on'], 'sold_car', null);
                break;
            case 'listings-binding':
                stm_listings_binding_results();
                break;
            case 'listings-items':
                stm_listings_items();
                break;
            case 'stm_load_dealers_list':
                stm_load_dealers_list();
                break;
        }
    }
}

/**
 * Ajax filter cars
 */
function stm_listings_ajax_results($source = null, $type = null, $navigation_type = null) {

	/* for taxonomy custom dev */
	if (str_contains($_SERVER['REQUEST_URI'], 'page')) {
		$url_components = parse_url( $_SERVER['REQUEST_URI']);
		parse_str($url_components['query'], $params);
		 if ($params['taxonomy']) {
				$_GET[$params['taxonomy']] = $params['term'];
		}
	}
	/**************************/
    $r = stm_listings_filter($source);
    $fragments = false;
    if ( ! empty( $_GET['fragments'] ) ) {
        $fragments = explode( ',', $_GET['fragments'] );
    }

    if ( ! $fragments || in_array( 'html', $fragments ) ) {
        ob_start();
        stm_listings_load_results($source, $type, $navigation_type);
        $r['html'] = ob_get_clean();
    }

    $r['filter_links']  = stm_get_car_filter_links();

    $sorts = get_stm_select_sorting_options_for_select2();

    if(sort_distance_nearby()){
        $sorts = array_merge(array(array('id' => 'distance_nearby', 'text' => esc_html__( 'Distance : nearby', 'stm_vehicles_listing' ))), $sorts);
    }

    $selected = stm_listings_input('sort_order', stm_get_default_sort_option());

    foreach ($sorts as $key => $value){
        if($value['id'] == $selected)
            $sorts[$key]['selected'] = true;
    }

    $r['sorts'] = $sorts;
    $r['url'] = remove_query_arg( array( 'ajax_action', 'fragments' ) );

    $r = apply_filters( 'stm_listings_ajax_results', $r );

    if ( $fragments ) {
        $r = array_intersect_key( $r, array_flip( $fragments ) );
    }

    wp_send_json( $r );
    exit;
}

/**
 * Ajax filter cars
 */
function stm_listings_items_ajax_results($source = null, $type = null, $navigation_type = null) {

    $r = stm_listings_filter($source);

    $fragments = false;
    if ( ! empty( $_GET['fragments'] ) ) {
        $fragments = explode( ',', $_GET['fragments'] );
    }

    if ( ! $fragments || in_array( 'html', $fragments ) ) {
        ob_start();
        stm_listings_load_items_results($source, $type, $navigation_type);
        $r['html'] = ob_get_clean();
    }

    $r['filter_links']  = stm_get_car_filter_links();

    $sorts = get_stm_select_sorting_options_for_select2();

    if(sort_distance_nearby()){
        $sorts = array_merge(array(array('id' => 'distance_nearby', 'text' => esc_html__( 'Distance : nearby', 'stm_vehicles_listing' ))), $sorts);
    }

    $selected = stm_listings_input('sort_order', stm_get_default_sort_option());

    foreach ($sorts as $key => $value){
        if($value['id'] == $selected)
            $sorts[$key]['selected'] = true;
    }

    $r['sorts'] = $sorts;
    $r['url'] = remove_query_arg( array( 'ajax_action', 'fragments' ) );

    $r = apply_filters( 'stm_listings_ajax_results', $r );

    if ( $fragments ) {
        $r = array_intersect_key( $r, array_flip( $fragments ) );
    }

    wp_send_json( $r );
    exit;
}

/**
 * Ajax filter binding
 */
function stm_listings_binding_results() {
	$r = stm_listings_filter();

	$fragments = false;
	if ( ! empty( $_GET['fragments'] ) ) {
		$fragments = array_filter( explode( ',', $_GET['fragments'] ) );
	}

	$r = apply_filters( 'stm_listings_binding_results', $r );

	if ( $fragments ) {
		$r = array_intersect_key( $r, array_flip( $fragments ) );
	}

	wp_send_json( $r );
	exit;
}

/**
 * Ajax filter items
 */
function stm_listings_items() {
	$r = array();

	$fragments = false;

	if ( ! empty( $_GET['fragments'] ) ) {
		$fragments = explode( ',', $_GET['fragments'] );
	}

	if (! $fragments ) {
		ob_start();
		stm_listings_load_results();
		$r['html'] = ob_get_clean();
	}

	$r = apply_filters( 'stm_listings_items', $r );

	if ( $fragments ) {
		$r = array_intersect_key( $r, array_flip( $fragments ) );
	}

	wp_send_json( $r );
	exit;
}

function stm_listings_ajax_save_user_data()
{

    $response = array();
	
	
    if (!is_user_logged_in()) {
        die('You are not logged in');
    }

    $got_error_validation = false;
    $error_msg = esc_html__('Settings Saved.', 'stm_vehicles_listing');

    $user_current = wp_get_current_user();
    $user_id = $user_current->ID;
    $user = stm_get_user_custom_fields($user_id);

    /*Get current editing values*/
    $user_mail = stm_listings_input('stm_email', $user['email']);
    $user_mail = sanitize_email($user_mail);
    /*Socials*/
    $socs = array('facebook', 'twitter', 'linkedin', 'youtube');
    $socials = array();
    if (empty($user['socials'])) {
        $user['socials'] = array();
    }
    foreach ($socs as $soc) {
        if (empty($user['socials'][$soc])) {
            $user['socials'][$soc] = '';
        }
        $socials[$soc] = stm_listings_input('stm_user_' . $soc, $user['socials'][$soc]);
    }

    $password_check = false;
    if(empty(get_user_meta($user_id, 'wsl_current_provider', true))) {
        if (!empty($_POST['stm_confirm_password'])) {
            $password_check = wp_check_password($_POST['stm_confirm_password'], $user_current->data->user_pass, $user_id);
        }

        if (!$password_check and !empty($_POST['stm_confirm_password'])) {
            $got_error_validation = true;
            $error_msg = esc_html__('Confirmation password is wrong', 'stm_vehicles_listing');
        }
    } else {
        $password_check = true;
    }

    $demo = stm_is_site_demo_mode();

    if ($password_check and !$demo) {
        //Editing/adding user filled fields
        /*Image changing*/
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
        if (!empty($_FILES['stm-avatar'])) {
            $file = $_FILES['stm-avatar'];
            if (is_array($file) and !empty($file['name'])) {
                $ext = pathinfo($file['name']);
                $ext = $ext['extension'];
                if (in_array($ext, $allowed)) {

                    $upload_dir = wp_upload_dir();
                    $upload_url = $upload_dir['url'];
                    $upload_path = $upload_dir['path'];


                    /*Upload full image*/
                    if (!function_exists('wp_handle_upload')) {
                        require_once(ABSPATH . 'wp-admin/includes/file.php');
                    }
                    $original_file = wp_handle_upload($file, array('test_form' => false));

                    if (!is_wp_error($original_file)) {
                        $image_user = $original_file['file'];
                        /*Crop image to square from full image*/
                        $image_cropped = image_make_intermediate_size($image_user, 160, 160, true);

                        /*Delete full image*/
                        if (file_exists($image_user)) {
                            unlink($image_user);
                        }

                        if (!$image_cropped) {
                            $got_error_validation = true;
                            $error_msg = esc_html__('Error, please try again', 'stm_vehicles_listing');

                        } else {

                            /*Get path and url of cropped image*/
                            $user_new_image_url = $upload_url . '/' . $image_cropped['file'];
                            $user_new_image_path = $upload_path . '/' . $image_cropped['file'];

                            /*Delete from site old avatar*/

                            $user_old_avatar = get_the_author_meta('stm_user_avatar_path', $user_id);
                            if (!empty($user_old_avatar) and $user_new_image_path != $user_old_avatar and file_exists($user_old_avatar)) {

                                /*Check if prev avatar exists in another users except current user*/
                                $args = array(
                                    'meta_key' => 'stm_user_avatar_path',
                                    'meta_value' => $user_old_avatar,
                                    'meta_compare' => '=',
                                    'exclude' => array($user_id),
                                );
                                $users_db = get_users($args);
                                if (empty($users_db)) {
                                    unlink($user_old_avatar);
                                }
                            }

                            /*Set new image tmp*/
                            $user['image'] = $user_new_image_url;


                            /*Update user meta path and url image*/
                            update_user_meta($user_id, 'stm_user_avatar', $user_new_image_url);
                            update_user_meta($user_id, 'stm_user_avatar_path', $user_new_image_path);

                            $response = array();
                            $response['new_avatar'] = $user_new_image_url;

                        }

                    }

                } else {
                    $got_error_validation = true;
                    $error_msg = esc_html__('Please load image with right extension (jpg, jpeg, png and gif)', 'stm_vehicles_listing');
                }
            }
        }

        /*Check if delete*/
        if (empty($_FILES['stm-avatar']['name'])) {
            if (!empty($_POST['stm_remove_img']) and $_POST['stm_remove_img'] == 'delete') {
                $user_old_avatar = get_the_author_meta('stm_user_avatar_path', $user_id);
                /*Check if prev avatar exists in another users except current user*/
                $args = array(
                    'meta_key' => 'stm_user_avatar_path',
                    'meta_value' => $user_old_avatar,
                    'meta_compare' => '=',
                    'exclude' => array($user_id),
                );
                $users_db = get_users($args);
                if (empty($users_db)) {
                    unlink($user_old_avatar);
                }
                update_user_meta($user_id, 'stm_user_avatar', '');
                update_user_meta($user_id, 'stm_user_avatar_path', '');

                $response['new_avatar'] = '';
            }
        }

        /*Change email*/
        $new_user_data = array(
            'ID' => $user_id,
            'user_email' => $user_mail
        );

        /*Change email visiblity*/
        if (!empty($_POST['stm_show_mail']) and $_POST['stm_show_mail'] == 'on') {
            update_user_meta($user_id, 'stm_show_email', 'show');
        } else {
            update_user_meta($user_id, 'stm_show_email', '');
        }

        if (!empty($_POST['stm_new_password']) and !empty($_POST['stm_new_password_confirm'])) {
            if ($_POST['stm_new_password_confirm'] == $_POST['stm_new_password']) {
                $new_user_data['user_pass'] = $_POST['stm_new_password'];
            } else {
                $got_error_validation = true;
                $error_msg = esc_html__('New password not saved, because of wrong confirmation.', 'stm_vehicles_listing');
            }
        }

        $user_error = wp_update_user($new_user_data);
        if (is_wp_error($user_error)) {
            $got_error_validation = true;
            $error_msg = $user_error->get_error_message();
        }

        /*Change fields with secondary privilegy*/
        /*POST key => user_meta_key*/
        $changed_info = array(
            'stm_first_name' => 'first_name',
            'stm_last_name' => 'last_name',
            'stm_phone' => 'stm_phone',
            'stm_user_facebook' => 'stm_user_facebook',
            'stm_user_twitter' => 'stm_user_twitter',
            'stm_user_linkedin' => 'stm_user_linkedin',
            'stm_user_youtube' => 'stm_user_youtube',
        );

	    $changed_info_empty  = array(
		    'stm_user_facebook' => 'stm_user_facebook',
		    'stm_user_twitter' => 'stm_user_twitter',
		    'stm_user_linkedin' => 'stm_user_linkedin',
		    'stm_user_youtube' => 'stm_user_youtube',
	    );
		
		$shipping_city = $_POST['shipping_city'];
		update_user_meta( $user_id, 'shipping_city', $shipping_city );
		
        foreach ($changed_info as $change_to_key => $change_info) {
            if (!empty($_POST[$change_to_key]) OR $changed_info_empty[$change_to_key]) {
                $escaped_value = sanitize_text_field($_POST[$change_to_key]);
	            update_user_meta($user_id, $change_info, $escaped_value);
            }
        }

    } else {
        if ($demo) {
            $got_error_validation = true;
            $error_msg = esc_html__('Site is on demo mode', 'stm_vehicles_listing');
        }
    }

    $response['error'] = $got_error_validation;
    $response['error_msg'] = $error_msg;

    wp_send_json($response);
}

add_action('wp_ajax_stm_listings_ajax_save_user_data', 'stm_listings_ajax_save_user_data');

add_action('trashed_post', 'delete_images_from_trashed_listing');

function delete_images_from_trashed_listing($postId) {
	if(isset($_COOKIE["deleteListingAttach"])) {
	    $featId = get_post_thumbnail_id($postId);
		$attachIds = get_post_meta($postId, "gallery");

		if(!empty($featId)) {
            wp_delete_attachment($featId, true);
        }

		if(isset($attachIds[0])) {
			foreach ($attachIds[0] as $k => $val) {
				wp_delete_attachment($val, true);
			}
		}

		unset($_COOKIE['deleteListingAttach']);
		setcookie('deleteListingAttach', null, -1, '/');
	}
}

function stm_on_publish_pending_post( $post ) {
	add_filter('wp_mail_content_type', 'stm_set_html_content_type_mail');
	if(stm_me_get_wpcfto_mod('send_email_to_user', false) && $post->post_type == 'listings') {
		$email = get_userdata($post->post_author);
		$to = $email->user_email;
		$post_id = $post->ID;

		$args = array (
			'car_id' => $post_id,
			'car_title' => get_the_title($post_id)
		);

		$subject = generateSubjectView('user_listing_approved', $args);
		$body = generateTemplateView('user_listing_approved', $args);
		wp_mail($to, $subject, $body);
	}
	remove_filter('wp_mail_content_type', 'stm_set_html_content_type_mail');
}
add_action(  'pending_to_publish',  'stm_on_publish_pending_post', 10, 1 );