<?php
// add_action('init', function(){
//     echo '<pre>';
//     print_r(STM_MOTORS_APP_ID);
//     echo '</pre>';
// });
//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
add_action('rest_api_init', 'stm_mra_register_routes');
$translations = get_option('translations', '');
function stm_mra_register_routes()
{
	$routesArr = array(
		array(// App SETTINGS
			'route' => '/settings/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_settings',
				'permission_callback' => '__return_true'
			)
		),
		array(
			'route' => '/login/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_login',
				'permission_callback' => '__return_true'
			)
		),
		array(
			'route' => '/registration/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_registration',
				'permission_callback' => '__return_true'
			)
		),
		array(// Main Page SETTINGS
			'route' => '/main-page/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_main_page',
				'permission_callback' => '__return_true'
			)
		),
		array(// Main Page RECENT Load More
			'route' => '/recent-load-more/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_recent_load_more',
				'permission_callback' => '__return_true'
			)
		),
		array(// DEALER
			'route' => '/user/(?P<id>\d+)',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_user_by_id',
				'permission_callback' => '__return_true',
				'args' => array(
					'id' => array(
						'validate_callback' => function ($param, $request, $key) {
							return is_numeric($param);
						}
					)
				)
			)
		),
		array(// PRIVATE
			'route' => '/private-user/(?P<id>\d+)',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_private_user_by_id',
				'args' => array(
					'id' => array(
						'validate_callback' => function ($param, $request, $key) {
							return is_numeric($param);
						}
					)
				),
				'permission_callback' => '__return_true'
			)
		),
		array(// PRIVATE PROFILE LOAD MORE MY INVENTORY
			'route' => '/priv-prof-load-more/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_pp_load_more',
				'permission_callback' => '__return_true'
			)
		),
		array(// PRIVATE PROFILE LOAD MORE MY FAVOURITES
			'route' => '/priv-prof-load-more-fav/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_pp_load_more_fav',
				'permission_callback' => '__return_true'
			)
		),
		array(// UPDATE PROFILE
			'route' => '/update-profile/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_update_profile',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET ALL LISTING
			'route' => '/listings/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_listings_list',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET ALL LISTING
			'route' => '/user-listings/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_user_listings',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET ALL TAXONOMIES
			'route' => '/categoies/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_categories_list',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET FAVORITES
			'route' => '/favorites/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_favorites_list',
				'permission_callback' => '__return_true'
			)
		),
		array(// ACTION WITH FAVORITES
			'route' => '/action-with-favorite/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_action_with_favorites',
				'permission_callback' => '__return_true'
			)
		),
		array(// REMOVE FROM FAVORITES
			'route' => '/remove-from-favorite/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_remove_from_favorites',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET FEATURED
			'route' => '/featured/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_featured_list',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET SALES
			'route' => '/sales/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_sale_list',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET LISTING BY ID
			'route' => '/listing/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_listing_by_id',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET LISTING BY ID
			'route' => '/get-edit-car/(?P<id>\d+)',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_edit_car',
				'args' => array(
					'id' => array(
						'validate_callback' => function ($param, $request, $key) {
							return is_numeric($param);
						}
					)
				),
				'permission_callback' => '__return_true'
			)
		),
		array(// GET LISTINGS BY FILTER
			'route' => '/filtered-listings/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_filtered_listings_list',
				'permission_callback' => '__return_true'
			)
		),
		array(// GET FILTER
			'route' => '/filter/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_get_filter',
				'permission_callback' => '__return_true'
			)
		),
		array(// Add A Car Paramms
			'route' => '/add-car/',
			'args' => array(
				'methods' => 'GET',
				'callback' => 'stm_mra_add_a_car_paramms',
				'permission_callback' => '__return_true'
			)
		),
		array(// Add A Car
			'route' => '/add-a-car/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_add_a_car',
				'permission_callback' => '__return_true'
			)
		),
		array(// Edit A Car
			'route' => '/edit-car/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_edit_a_car',
				'permission_callback' => '__return_true'
			)
		),
		array(// Delete A Car
			'route' => '/delete-car/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_delete_car',
				'permission_callback' => '__return_true'
			)
		),
		array(// Add A Car Upload Media
			'route' => '/upload-media/',
			'args' => array(
				'methods' => 'POST',
				'callback' => 'stm_mra_add_a_car_upload_media',
				'permission_callback' => '__return_true'
			)
		),
	);
	apply_filters('stm_mra_register_route', $routesArr);
	foreach ($routesArr as $k => $routeSettings) {
		register_rest_route(STM_MOTORS_APP_ID, $routeSettings['route'], $routeSettings['args']);
	}
}

function stm_mra_login()
{

	if (!empty($_POST)) {
		$login = sanitize_text_field($_POST['stm_login']);
		$pass = sanitize_text_field($_POST['stm_pass']);
		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
			$user = get_user_by('email', $login);
		} else {
			$user = get_user_by('login', $login);
		}
		if ($user) {
			$username = $user->data->user_login;
		}
		$creds = array();
		$creds['user_login'] = $username;
		$creds['user_password'] = $pass;
		$user = wp_signon($creds, true);
		if (is_wp_error($user)) {
			$response['code'] = 401;
			$response['message'] = esc_html__('Wrong Username or Password', 'motors');
		} else {
			if (empty(get_user_meta($user->ID, 'stm_app_token', true))) {
				$token = md5(uniqid(rand(), true));
				update_user_meta($user->ID, 'stm_app_token', $token);
			} else {
				$token = get_user_meta($user->ID, 'stm_app_token', true);
			}
			unset($user->caps);
			unset($user->cap_key);
			unset($user->allcaps);
			unset($user->filter);
			$user->role = (!empty($user->roles)) ? $user->roles[1] : '';
			$user->token = $token;
			$user->phone = get_user_meta($user->ID, 'stm_phone', true);
			$user->f_name = get_user_meta($user->ID, 'first_name', true);
			$user->l_name = get_user_meta($user->ID, 'last_name', true);
			$response['code'] = 200;
			$response['message'] = esc_html__('Successfully logged in. Redirecting...', 'motors');
			$response['user'] = $user->data;
		}
		wp_send_json($response);
	}
}

function stm_mra_registration()
{
	$response = array();
	$errors = array();
	if (empty($_POST['stm_nickname'])) {
		$errors['stm_nickname'] = true;
	} else {
		$user_login = sanitize_text_field($_POST['stm_nickname']);
	}
	if (empty($_POST['stm_user_first_name'])) {
		$user_name = '';
	} else {
		$user_name = sanitize_text_field($_POST['stm_user_first_name']);
	}
	if (empty($_POST['stm_user_last_name'])) {
		$user_lastname = '';
	} else {
		$user_lastname = sanitize_text_field($_POST['stm_user_last_name']);
	}
	if (empty($_POST['stm_user_phone'])) {
		$user_phone = '';
	} elseif (empty($_POST['stm_user_phone'])) {
		$errors['stm_user_phone'] = true;
	} else {
		$user_phone = sanitize_text_field($_POST['stm_user_phone']);
	}
	if (!is_email($_POST['stm_user_mail'])) {
		$errors['stm_user_mail'] = true;
	} else {
		$user_mail = sanitize_email($_POST['stm_user_mail']);
	}
	if (empty($_POST['stm_user_password'])) {
		$errors['stm_user_password'] = true;
	} else {
		$user_pass = $_POST['stm_user_password'];
	}
	if (empty($errors)) {
		$user_data = array(
			'user_login' => $user_login,
			'user_pass' => $user_pass,
			'first_name' => $user_name,
			'last_name' => $user_lastname,
			'user_email' => $user_mail
		);
		$user_id = wp_insert_user($user_data);
		if (!is_wp_error($user_id)) {
			$token = md5(uniqid(rand(), true));
			update_user_meta($user_id, 'stm_app_token', $token);
			update_user_meta($user_id, 'stm_phone', $user_phone);
			update_user_meta($user_id, 'stm_show_email', 'show');
			wp_cache_delete($user_id, 'user_meta');
			$user = get_userdata($user_id);
			unset($user->caps);
			unset($user->cap_key);
			unset($user->allcaps);
			unset($user->filter);
			$user->role = (!empty($user->roles)) ? $user->roles[1] : '';
			$user->token = $token;
			$user->phone = get_user_meta($user->ID, 'stm_phone', true);
			$user->f_name = get_user_meta($user->ID, 'first_name', true);
			$user->l_name = get_user_meta($user->ID, 'last_name', true);
			$restricted = false;
			$restrictions = stm_get_post_limits($user_id);
			if ($restrictions['posts'] < 1 && stm_enablePPL()) {
				$restricted = true;
			}
			$user->image = upload_media($user_id, 'stm_user_avatar');
			$response['message'] = esc_html__('Congratulations! You have been successfully registered. Redirecting to your account profile page.', 'motors');
			$response['restricted'] = $restricted;
			$response['user'] = $user->data;
			$response['code'] = 200;
			add_filter('wp_mail_content_type', 'stm_set_html_content_type_mail');
			/*Mail admin*/
			$to = get_bloginfo('admin_email');
			if (function_exists('generateSubjectView')) {
				$subject = generateSubjectView('new_user', array('user_login' => $user_login));
			} elseif (function_exists('stm_generate_subject_view')) {
				$subject = stm_generate_subject_view('new_user', array('user_login' => $user_login));
			}
			if (function_exists('generateTemplateView')) {
				$body = generateTemplateView('new_user', array('user_login' => $user_login));
			} elseif (function_exists('stm_generate_template_view')) {
				$body = stm_generate_template_view('new_user', array('user_login' => $user_login));
			}
			wp_mail($to, $subject, $body);
			/*Mail user*/
			if (function_exists('generateSubjectView')) {
				$subjectUser = generateSubjectView('welcome', array('user_login' => $user_login));
			} elseif (function_exists('stm_generate_subject_view')) {
				$subjectUser = stm_generate_subject_view('welcome', array('user_login' => $user_login));
			}
			if (function_exists('generateTemplateView')) {
				$bodyUser = generateTemplateView('welcome', array('user_login' => $user_login));
			} elseif (function_exists('stm_generate_template_view')) {
				$bodyUser = stm_generate_template_view('welcome', array('user_login' => $user_login));
			}
			wp_mail($user_mail, $subjectUser, $bodyUser);
			remove_filter('wp_mail_content_type', 'stm_set_html_content_type_mail');

		} else {
			$response['message'] = $user_id->get_error_message();
		}
	} else {
		$response['message'] = esc_html__('Please fill required fields', 'stm-motors-application');
	}
	$response['errors'] = $errors;
	wp_send_json($response);
}

function stm_mra_add_a_car()
{
	stm_ma_add_a_car();
}

function stm_mra_add_a_car_upload_media()
{
	stm_ma_add_a_car_media_two($_POST['user_id'], $_POST['user_token'], $_POST['post_id']);
}

function stm_mra_edit_a_car()
{
	stm_ma_add_a_car(true);
}

function stm_mra_delete_car()
{

	$demo = stm_is_site_demo_mode();
	if ($demo) {
		$response['message'] = esc_html__('Site is on demo mode', 'stm-motors-application');
		wp_send_json($response);
	}
	if (empty($_POST['user_id']) && empty($_POST['user_token']) && get_user_meta($_POST['user_id'], 'stm_app_token', true) != $_POST['user_token']) {
		$response['status'] = 403;
		$response['message'] = esc_html__('Token expired', 'stm-motors-application');
		wp_send_json($response);
	} else {
		$car = intval($_POST['post_id']);
		$author = get_post_meta($car, 'stm_car_user', true);
		$userId = $_POST['user_id'];
		if (intval($author) == intval($userId)) {
			do_action('remove_car_from_all', $car);
			if (wp_trash_post($car, false)) {
				$response['status'] = 200;
				$response['message'] = esc_html__('Car deleted', 'stm-motors-application');
			} else {
				$response['status'] = 403;
				$response['message'] = esc_html__('Something went wrong', 'stm-motors-application');
			}
			wp_send_json($response);
		}
	}
}

function stm_mra_add_a_car_paramms()
{
	$stepOne = explode(',', get_option('add_car_step_one', "add_media,make,serie,ca-year,mileage,exterior-color"));
	$stepTwo = explode(',', get_option('add_car_step_two', "engine,fuel,transmission,drive,body,location,price"));
	$stepThree = explode(',', get_option('add_car_step_three', "seller_notes,stm_additional_features"));
	$stepsParamms = array();
	$stepsParamms['step_one'] = addACarStepParse($stepOne);
	$stepsParamms['step_two'] = addACarStepParse($stepTwo);
	$stepsParamms['step_three'] = addACarStepParse($stepThree);
	wp_send_json($stepsParamms);
}

function stm_mra_get_settings()
{
	// wp_send_json( array('message' => 'just testing') );
	$cacheVersion = get_option('app_cache_version', '1');
	$posts = new WP_Query(array('post_type' => 'listings', 'post_status' => 'publish', 'posts_per_page' => -1));
	wp_reset_postdata();
	$ads_settings = get_option('ads_settings', '');
	if ($cacheVersion == $_GET['acv']) {
		wp_send_json(array('num_of_listings' => $posts->found_posts, 'ads_settings' => json_encode($ads_settings)));
	}
	$appType = get_option('app_type', 'dealership');
	$mainColor = get_option('main_color', '#1bc744');
	$secondColor = get_option('second_color', '#2d60f3');
	$gridType = get_option('grid_view_type', 'grid_one');
	$inventoryView = get_option('inventory_view', 'inventory_view_grid');
	$gsapAndroid = get_option('gsap_android', '');
	$gsapIos = get_option('gsap_ios', '');
	$currency = get_theme_mod('price_currency', '$');
	$currencyName = get_theme_mod("price_currency_name", "USD");
	$translations = get_option('translations', '');
	$custom_logo_url = '';
	if (function_exists('stm_me_get_wpcfto_img_src'))
	$custom_logo_url = stm_me_get_wpcfto_img_src( 'logo', get_template_directory_uri() );
	$logo =(!empty($custom_logo_url))?$custom_logo_url:'';
	$plchldr_id = get_option('plchldr_attachment_id', 0);
	$placeholder = ($plchldr_id == 0) ? STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png' : wp_get_attachment_image_url($plchldr_id);
	$response = array(
		'acv' => $cacheVersion,
		'app_type' => $appType,
		'main_color' => $mainColor,
		'secondary_color' => $secondColor,
		'num_of_listings' => $posts->found_posts,
		'grid_view_style' => $gridType,
		'inventory_view' => $inventoryView,
		'api_key_android' => $gsapAndroid,
		'api_key_ios' => $gsapIos,
		'currency' => $currency,
		'currency_name' => $currencyName,
		'translations' => json_encode($translations),
		'ads_settings' => json_encode($ads_settings),
		'logo' => esc_url($logo),
		'placeholder' => $placeholder
	);
	wp_send_json($response);
}

function stm_mra_get_main_page()
{
	$mpSettings = get_option('main_page_settings', '');
	$mpRecom = (!empty($mpSettings)) ? $mpSettings['main_page_recom_ppp'] : '10';
	$mpRecent = (!empty($mpSettings)) ? $mpSettings['main_page_recent_add_ppp'] : '10';
	$mpViewType = (!empty($mpSettings)) ? $mpSettings['main_page_ra_view'] : 'main_ra_list_view';
	//$mpRecom = $mpRecent =2;
	$paged_recent = ($_GET['paged_recent']) ? $_GET['paged_recent'] : 1;
	$paged_featured = ($_GET['paged_featured']) ? $_GET['paged_featured'] : 1;
	$rec = stm_ma_get_last_listings_pagination(0, $mpRecent, $paged_recent);
	if ($mpRecent > $rec['foundPosts']) {
		$offset = 0;
		$limit = 0;
	} else {
		$offset = $mpRecent;
		$limit = $mpRecent;
	}
	$featured = stm_ma_get_featured_listings_pagination($mpRecom, $paged_featured);
	$response = array(
		'featured' => $featured['data'],
		'recent' => $rec['data'],
		'viewType' => $mpViewType,
		'offset' => $offset,
		'limit' => $limit,
		'featured_max_num_pages' => $featured['featured_max_num_pages'],
		'last_max_num_pages' => $rec['last_max_num_pages'],
	);
	wp_send_json($response);
}

function stm_mra_get_recent_load_more()
{
	$offset = $_GET['offset'];
	$limit = $_GET['limit'];
	$data = stm_ma_get_last_listings($offset, $limit);
	if ($_GET['offset'] > $data['foundPosts']) {
		$offset = 0;
		$limit = 0;
	} else {
		$offset = $_GET['limit'] + $_GET['offset'];
		$limit = $_GET['limit'];
	}
	$response = array(
		'recent' => $data['data'],
		'offset' => $offset,
		'limit' => $limit
	);
	wp_send_json($response);
}

function stm_mra_get_listings_list()
{
	$listings = new WP_Query(array(
		'post_type' => 'listings',
		'post_status' => 'publish',
		'posts_per_page' => 10
	));
	$newListings = array();
	$result = array('status' => 401, 'listings' => array(), 'showed_paramms' => '');
	if ($listings->have_posts()) {
		$gridOpt = get_option('grid_view_settings', array());
		$listOpt = get_option('list_view_settings', array());
		$title = (!empty($gridOpt['go_two'])) ? $gridOpt['go_two'] : '';
		$subTitle = (!empty($gridOpt['go_one'])) ? $gridOpt['go_one'] : '';
		$info = (!empty($gridOpt['go_three'])) ? $gridOpt['go_three'] : '';
		$listTitle = (!empty($listOpt['list_title'])) ? $listOpt['list_title'] : '';
		$listInfoOne = (!empty($listOpt['list_info_one'])) ? $listOpt['list_info_one'] : '';
		$listInfoTwo = (!empty($listOpt['list_info_two'])) ? $listOpt['list_info_two'] : '';
		$listInfoThree = (!empty($listOpt['list_info_three'])) ? $listOpt['list_info_three'] : '';
		$listInfoFour = (!empty($listOpt['list_info_four'])) ? $listOpt['list_info_four'] : '';
		while ($listings->have_posts()) {
			$listings->the_post();
			$car_media = stm_get_car_medias(get_the_id());
			$gallery = array();
			if (!empty($car_media)) {
				foreach ($car_media['car_photos'] as $link) {
					if (strpos($link, 'motors.loc')) {
						$link = stm_ma_replace_host($link);
					}
					$gallery[] = array('url' => $link);
				}
			}
			$featureImg = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'stm-img-690-410');
			if (!$featureImg) {
				$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
			}
			if (strpos($featureImg, 'motors.loc')) {
				$featureImg = stm_ma_replace_host($featureImg);
			}
			if (!$featureImg) $featureImg = '';
			$price = get_post_meta(get_the_id(), 'price', true);
			$sale_price = get_post_meta(get_the_id(), 'sale_price', true);
			if (empty($price) and !empty($sale_price)) {
				$price = $sale_price;
			}
			$genTitle = stm_ma_generate_title_from_slugs(get_the_ID(), $title);
			$genSubTitle = stm_ma_generate_title_from_slugs(get_the_ID(), $subTitle);
			$genListTitle = stm_ma_generate_title_from_slugs(get_the_ID(), $listTitle);
			$infos = stm_ma_get_tax_info(get_the_ID(), $info);
			$infosListOne = stm_ma_get_tax_info(get_the_ID(), $listInfoOne);
			$infosListTwo = stm_ma_get_tax_info(get_the_ID(), $listInfoTwo);
			$infosListThree = stm_ma_get_tax_info(get_the_ID(), $listInfoThree);
			$infosListFour = stm_ma_get_tax_info(get_the_ID(), $listInfoFour);
			$newListings[] = array(
				'ID' => get_the_ID(),
				'imgUrl' => $featureImg,
				'gallery' => $gallery,
				'imgCount' => count($gallery),
				'price' => (!empty($price)) ? str_replace('   ', ' ', stm_listing_price_view(trim($price))) : 'No Price',
				'grid' => array(
					'title' => $genTitle,
					'subTitle' => $genSubTitle,
					'infoIcon' => $infos['info_3'],
					'infoTitle' => $infos['info_1'],
					'infoDesc' => $infos['info_2'],
				),
				'list' => array(
					'title' => $genListTitle,
					'infoOneIcon' => $infosListOne['info_3'],
					'infoOneTitle' => $infosListOne['info_1'],
					'infoOneDesc' => $infosListOne['info_2'],
					'infoTwoIcon' => $infosListTwo['info_3'],
					'infoTwoTitle' => $infosListTwo['info_1'],
					'infoTwoDesc' => $infosListTwo['info_2'],
					'infoThreeIcon' => $infosListThree['info_3'],
					'infoThreeTitle' => $infosListThree['info_1'],
					'infoThreeDesc' => $infosListThree['info_2'],
					'infoFourIcon' => $infosListFour['info_3'],
					'infoFourTitle' => $infosListFour['info_1'],
					'infoFourDesc' => $infosListFour['info_2'],
				)
			);
		}
		$newListings = apply_filters('stm_ma_listings_data', $newListings);
		$result = array('status' => 200, 'listings' => $newListings, 'showed_paramms' => $gridOpt);
	}
	wp_reset_postdata();
	wp_send_json($result);
}

function stm_mra_get_categories_list()
{

}

function stm_mra_get_favorites_list()
{

}

function stm_mra_action_with_favorites()
{
	$response = array();
	if (!empty($_POST['carId'])) {
		$car_id = intval($_POST['carId']);
		$post_status = get_post_status($car_id);
		if (!$post_status) {
			$post_status = 'deleted';
		}
		if ((get_user_meta($_POST['userId'], 'stm_app_token', true) == $_POST['userToken']) and $post_status == 'publish' or $post_status == 'pending' or $post_status == 'draft' or $post_status == 'deleted') {
			$user_id = $_POST['userId'];
			$user_added_fav = get_the_author_meta('stm_user_favourites', $user_id);
			if (empty($user_added_fav)) {
				update_user_meta($user_id, 'stm_user_favourites', $car_id);
			} else {
				$user_added_fav = array_filter(explode(',', $user_added_fav));
				$response['fil'] = $user_added_fav;
				$response['id'] = $car_id;
				if (in_array(strval($car_id), $user_added_fav)) {
					$user_added_fav = array_diff($user_added_fav, array($car_id));
				} else {
					$user_added_fav[] = $car_id;
				}
				$user_added_fav = implode(',', $user_added_fav);
				update_user_meta($user_id, 'stm_user_favourites', $user_added_fav);
			}
			$response['code'] = 200;
			$response['message'] = ($_POST['action'] == 'add') ? 'Added To Favorites' : 'Removed From Favorites';
		} else {
			$response['code'] = 403;
			$response['message'] = 'Invalid Data';
		}
	} else {
		$response['code'] = 403;
		$response['message'] = 'Invalid Data';
	}
	wp_send_json($response);
}

function stm_mra_remove_from_favorites()
{
	$response = array();
	if (!empty($_POST['carId'])) {
		$car_id = intval($_POST['carId']);
		$post_status = get_post_status($car_id);
		if (!$post_status) {
			$post_status = 'deleted';
		}
		if (!empty($_POST['user_id']) && !empty($_POST['user_token']) && (get_user_meta($_POST['user_id'], 'stm_app_token', true) == $_POST['user_token']) and $post_status == 'publish' or $post_status == 'pending' or $post_status == 'draft' or $post_status == 'deleted') {
			$user_id = $_POST['user_id'];
			$user = get_userdata($user_id);
			$user_added_fav = get_the_author_meta('stm_user_favourites', $user_id);
			$user_added_fav = array_filter(explode(',', $user_added_fav));
			if (in_array(strval($car_id), $user_added_fav)) {
				$response['fil'] = $user_added_fav;
				$response['id'] = $car_id;
				$user_added_fav = array_diff($user_added_fav, array($car_id));
				$user_added_fav = implode(',', $user_added_fav);
				update_user_meta($user->ID, 'stm_user_favourites', $user_added_fav);
				$favList = stm_ma_get_user_favourites($user->ID, 5, 0, true);
				$offsetSum = 0;
				$limit = 0;
				if ($favList['found_posts'] > 5) {
					$offsetSum = 5;
					$limit = 5;
				} else if ($favList['found_posts'] <= 5) {
					$offsetSum = $favList['found_posts'];
					$limit = 5;
				}
				$response['favourites_found'] = $favList['found_posts'];
				$response['favourites'] = $favList['listings'];
				$response['offset'] = $offsetSum;
				$response['limit'] = $limit;
				$response['code'] = 200;
				$response['message'] = 'Removed From Favorites';
			} else {
				$response['code'] = 403;
				$response['message'] = 'Invalid Data';
			}
		} else {
			$response['code'] = 403;
			$response['message'] = 'Invalid Data';
		}
	} else {
		$response['code'] = 403;
		$response['message'] = 'Invalid Data';
	}
	wp_send_json($response);
}

function stm_mra_get_featured_list()
{
	$mpSettings = get_option('main_page_settings', '');
	$mpRecom = (!empty($mpSettings)) ? $mpSettings['main_page_recom_ppp'] : '10';
	$response = array(
		'featured' => stm_ma_get_featured_listings($mpRecom),
	);
	wp_send_json($response);
}

function stm_mra_get_sale_list()
{

}

function stm_mra_get_listing_by_id()
{
	$listingId = (int)$_GET['id'];
	$user_added_by = get_post_meta($listingId, 'stm_car_user', true);
	$listing = get_post($listingId);
	$response = array();
	if (!empty($listing)) {
		$detailsOpt = get_option('listing_details_settings', array());
		$id = $listing->ID;
		$title = (!empty($detailsOpt['ld_title'])) ? $detailsOpt['ld_title'] : '';
		$subTitle = (!empty($detailsOpt['ld_subtitle'])) ? $detailsOpt['ld_subtitle'] : '';
		$info = (!empty($detailsOpt['ld_info'])) ? explode(',', $detailsOpt['ld_info']) : '';
		$car_media = stm_get_car_medias_mobile($id);
		$gallery = array();
		if (!empty($car_media)) {
			foreach ($car_media['car_photos'] as $link) {
				if (strpos($link, 'motors.loc')) {
					$link = stm_ma_replace_host($link);
				}
				$gallery[] = array('url' => $link);
			}
		}
		$featureImg = wp_get_attachment_image_url(get_post_thumbnail_id($id), 'stm-img-690-410');
		if (!$featureImg) {
			$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
		}
		if (strpos($featureImg, 'motors.loc')) {
			$featureImg = stm_ma_replace_host($featureImg);
		}
		$price = get_post_meta($id, 'price', true);
		$sale_price = get_post_meta($id, 'sale_price', true);
		if (empty($price) and !empty($sale_price)) {
			$price = $sale_price;
		}
		$carLat = get_post_meta($id, 'stm_lat_car_admin', true);
		$carLng = get_post_meta($id, 'stm_lng_car_admin', true);
		$carLocation = get_post_meta($id, 'stm_car_location', true);
		$genTitle = stm_ma_generate_title_from_slugs($id, $title);
		$genSubTitle = stm_ma_generate_title_from_slugs($id, $subTitle);
		$response = array(
			'ID' => $id,
			'imgUrl' => $featureImg,
			'gallery' => $gallery,
			'imgCount' => count($gallery),
			'price' => (!empty($price)) ? str_replace('   ', ' ', stm_listing_price_view(trim($price))) : esc_html__('No Price', 'stm-motors-application'),
			'title' => $genTitle,
			'subTitle' => $genSubTitle,
			'content' => preg_replace('/\[(.*?)\]/', '', $listing->post_content),
			'car_location' => $carLocation,
			'car_lat' => $carLat,
			'car_lng' => $carLng,
			'info' => array(),
		);
		foreach ($info as $inf) {
			$response['info'][] = stm_ma_get_tax_info($id, $inf);
		}
		$features = get_post_meta($id, 'additional_features', true);
		$features = (!empty($features)) ? explode(',', $features) : '';
		if (!empty($features)):
			foreach ($features as $key => $feature):
				$response['features'][] = $feature;
			endforeach;
		endif;
		$response['author']['ID'] = 0;
		$response['author']['token'] = '';
		$response['author']['name'] = 'N/A';
		$response['author']['last_name'] = '';
		$response['author']['user_role'] = 'N/A';
		$response['author']['reg_date'] = 'N/A';
		$response['author']['stm_seller_notes'] = 'N/A';
		$response['author']['phone'] = '';
		if (!empty($user_added_by)) {
			$user_fields = stm_get_user_custom_fields($user_added_by);
			$user_meta = get_userdata($user_added_by);
			$roles = $user_meta->roles;
			$role = (!empty($roles) && 'stm_dealer' == $roles[1]) ? 'Dealer' : 'Private Seller';
			$date = date('d M Y', strtotime($user_meta->user_registered));
			$response['author'] = $user_fields;
			$response['author']['stm_seller_notes'] = stripslashes($user_fields['stm_seller_notes']);
			if (strpos($user_fields['dealer_image'], 'motors.loc')) {
				$response['author']['dealer_image'] = stm_ma_replace_host($user_fields['dealer_image']);
				$response['author']['logo'] = stm_ma_replace_host($user_fields['logo']);
			}
			$response['author']['user_role'] = $role;
			$response['author']['reg_date'] = $date;
		}
	}
	$response['listing_seller_note'] = get_post_meta($id, 'listing_seller_note', true);
	$query = stm_similar_cars_apps();
	$similarCars = array();
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$id = get_the_ID();
			$price = get_post_meta($id, 'price', true);
			$sale_price = get_post_meta($id, 'sale_price', true);
			if (empty($price) and !empty($sale_price)) {
				$price = $sale_price;
			}
			$featureImg = wp_get_attachment_image_url(get_post_thumbnail_id($id), 'stm-img-690-410');
			if (!$featureImg) {
				$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
			}
			if (strpos($featureImg, 'motors.loc')) {
				$featureImg = stm_ma_replace_host($featureImg);
			}
			$similarCars[] = array(
				'ID' => $id,
				'title' => get_the_title(),
				'price' => (!empty($price)) ? str_replace('   ', ' ', stm_listing_price_view(trim($price))) : 'No Price',
				'img' => $featureImg
			);

		}
	}
	$response['inFavorites'] = false;
	if (!empty($_GET['user_id'])) {
		$user_added_fav = get_the_author_meta('stm_user_favourites', $_GET['user_id']);
		$user_added_fav = array_filter(explode(',', $user_added_fav));
		if (!empty($user_added_fav) && in_array($listingId, $user_added_fav)) {
			$response['inFavorites'] = true;
		}
	}
	$response['similar'] = $similarCars;
	$response = apply_filters('stm_ma_listing_details', $response);
	wp_send_json($response);
}

function stm_mra_get_filtered_listings_list()
{
	$limit = $_GET['limit'];
	$offset = $_GET['offset'];
	$args = _stm_listings_build_query_args(array('posts_per_page' => $limit, 'offset' => $offset), $source = null);
	$args['posts_per_page'] = $limit;
	$listings = new WP_Query($args);
	$newListings = array();
	$result = array('status' => 404, 'listings' => array(), 'showed_paramms' => '');
	if ($listings->have_posts()) {
		$gridOpt = get_option('grid_view_settings', array());
		$listOpt = get_option('list_view_settings', array());
		$title = (!empty($gridOpt['go_two'])) ? $gridOpt['go_two'] : '';
		$subTitle = (!empty($gridOpt['go_one'])) ? $gridOpt['go_one'] : '';
		$info = (!empty($gridOpt['go_three'])) ? $gridOpt['go_three'] : '';
		$listTitle = (!empty($listOpt['list_title'])) ? $listOpt['list_title'] : '';
		$listInfoOne = (!empty($listOpt['list_info_one'])) ? $listOpt['list_info_one'] : '';
		$listInfoTwo = (!empty($listOpt['list_info_two'])) ? $listOpt['list_info_two'] : '';
		$listInfoThree = (!empty($listOpt['list_info_three'])) ? $listOpt['list_info_three'] : '';
		$listInfoFour = (!empty($listOpt['list_info_four'])) ? $listOpt['list_info_four'] : '';
		$gridView = get_option('grid_view_type', array());
		$imgcount = ($gridView == 'grid_one') ? 'imgcount' : '';
		while ($listings->have_posts()) {
			$listings->the_post();
			$car_media = stm_get_car_medias(get_the_id());
			$gallery = array();
			if (!empty($car_media)) {
				foreach ($car_media['car_photos'] as $link) {
					if (strpos($link, 'motors.loc')) {
						$link = stm_ma_replace_host($link);
					}
					$gallery[] = array('url' => $link);
				}
			}
			$featureImg = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'stm-img-690-410');
			if (!$featureImg) {
				$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
			}
			if (strpos($featureImg, 'motors.loc')) {
				$featureImg = stm_ma_replace_host($featureImg);
			}
			if (!$featureImg) $featureImg = '';
			$price = get_post_meta(get_the_id(), 'price', true);
			$sale_price = get_post_meta(get_the_id(), 'sale_price', true);
			if (empty($price) and !empty($sale_price)) {
				$price = $sale_price;
			}
			$genTitle = stm_ma_generate_title_from_slugs(get_the_ID(), $title);
			$genSubTitle = stm_ma_generate_title_from_slugs(get_the_ID(), $subTitle);
			$genListTitle = stm_ma_generate_title_from_slugs(get_the_ID(), $listTitle);
			$infos = stm_ma_get_tax_info(get_the_ID(), $info);
			$infosListOne = stm_ma_get_tax_info(get_the_ID(), $listInfoOne);
			$infosListTwo = stm_ma_get_tax_info(get_the_ID(), $listInfoTwo);
			$infosListThree = stm_ma_get_tax_info(get_the_ID(), $listInfoThree);
			$infosListFour = stm_ma_get_tax_info(get_the_ID(), $listInfoFour);
			$infos['info_1'] = (empty($imgcount)) ? $infos['info_1'] : $imgcount;
			$newListings[] = array(
				'ID' => get_the_ID(),
				'imgUrl' => $featureImg,
				'gallery' => $gallery,
				'imgCount' => count($gallery),
				'price' => (!empty($price)) ? str_replace('   ', ' ', stm_listing_price_view(trim($price))) : 'No Price',
				'grid' => array(
					'title' => $genTitle,
					'subTitle' => $genSubTitle,
					'infoIcon' => $infos['info_3'],
					'infoTitle' => $infos['info_1'],
					'infoDesc' => $infos['info_2'],
				),
				'list' => array(
					'title' => $genListTitle,
					'infoOneIcon' => $infosListOne['info_3'],
					'infoOneTitle' => $infosListOne['info_1'],
					'infoOneDesc' => $infosListOne['info_2'],
					'infoTwoIcon' => $infosListTwo['info_3'],
					'infoTwoTitle' => $infosListTwo['info_1'],
					'infoTwoDesc' => $infosListTwo['info_2'],
					'infoThreeIcon' => $infosListThree['info_3'],
					'infoThreeTitle' => $infosListThree['info_1'],
					'infoThreeDesc' => $infosListThree['info_2'],
					'infoFourIcon' => $infosListFour['info_3'],
					'infoFourTitle' => $infosListFour['info_1'],
					'infoFourDesc' => $infosListFour['info_2'],
				)
			);
		}
		$result = array('status' => 200, 'listings' => $newListings, 'limit' => $limit, 'offset' => $limit + $offset, 'showed_paramms' => $gridOpt);
	} else {
		$result['message'] = 'Not Found';
		$result['limit'] = $limit;
		$result['offset'] = $limit + $offset;
	}
	if ($_GET['offset'] > $listings->found_posts || $listings->found_posts < $_GET['limit']) {
		$result['limit'] = 0;
		$result['offset'] = 0;
	}
	wp_reset_postdata();
	wp_send_json($result);
}

function stm_mra_get_user_by_id($params)
{
	$userId = $params->get_param('id');
	$query = (function_exists('stm_user_listings_query')) ? stm_user_listings_query($userId, 'publish', 6, false) : '';
	$user_fields = stm_get_user_custom_fields($userId);
	$response['author'] = $user_fields;
	$response['author']['stm_seller_notes'] = stripslashes($user_fields['stm_seller_notes']);
	if (strpos($user_fields['dealer_image'], 'motors.loc')) {
		$response['author']['dealer_image'] = stm_ma_replace_host($user_fields['dealer_image']);
		$response['author']['logo'] = stm_ma_replace_host($user_fields['logo']);
		$response['author']['image'] = stm_ma_replace_host($user_fields['image']);
	}
	$response['listings'] = stm_ma_get_listing_obj($query);
	wp_send_json($response);

}

function stm_mra_get_private_user_by_id($params)
{
	$userId = $params->get_param('id');
	$query = (function_exists('stm_user_listings_query')) ? stm_user_listings_query($userId, array('publish', 'pending'), -1, false) : '';
	$user_fields = stm_get_user_custom_fields($userId);
	$response['author'] = $user_fields;
	$response['author']['stm_seller_notes'] = stripslashes($user_fields['stm_seller_notes']);
	if (strpos($user_fields['dealer_image'], 'motors.loc')
		|| strpos($user_fields['logo'], 'motors.loc')
		|| strpos($user_fields['image'], 'motors.loc')) {

		$response['author']['dealer_image'] = stm_ma_replace_host($user_fields['dealer_image']);
		$response['author']['logo'] = stm_ma_replace_host($user_fields['logo']);
		$response['author']['image'] = stm_ma_replace_host($user_fields['image']);
	}
	$fav = stm_ma_get_user_favourites($userId, -1, 0, true);
	$response['listings_found'] = $query->found_posts;
	$response['listings'] = stm_ma_get_listing_obj($query);
	$response['favourites_found'] = $fav['found_posts'];
	$response['favourites'] = $fav['listings'];
	wp_send_json($response);

}

function stm_mra_pp_load_more()
{
	$query = (function_exists('stm_user_listings_query')) ? stm_user_listings_query($_GET['user_id'], 'publish', $_GET['limit'], false, $_GET['offset']) : '';
	$offset = $_GET['limit'] + $_GET['offset'];
	$limit = $_GET['limit'];
	if ($_GET['offset'] > $query->found_posts) {
		$offset = 0;
		$limit = 0;
	}
	$response['listings_found'] = $query->found_posts;
	$response['listings'] = stm_ma_get_listing_obj($query);
	$response['offset'] = $offset;
	$response['limit'] = $limit;
	wp_send_json($response);
}

function stm_mra_pp_load_more_fav()
{
	$userId = intval($_GET['user_id']);
	$limit = intval($_GET['limit']);
	$offset = intval($_GET['offset']);
	$offsetSum = $limit + $offset;
	$favList = stm_ma_get_user_favourites($userId, $limit, $offset, true);
	if ($_GET['offset'] > $favList['found_posts']) {
		$offsetSum = 0;
		$limit = 0;
	}
	$response['favourites_found'] = $favList['found_posts'];
	$response['favourites'] = $favList['listings'];
	$response['offset'] = $offsetSum;
	$response['limit'] = $limit;
	wp_send_json($response);
}

function stm_mra_get_filter()
{
	$filterParams = explode(',', get_option('filter_params', 'make,serie,ca-year,price'));
	$forApp = array();
	foreach ($filterParams as $filter) {
		if ($filter == 'search_radius') {
			$forApp[$filter] = array(
				'label' => 'Search radius',
			);
			continue;
		}
		$getTerms = get_terms(array('taxonomy' => $filter, 'hide_empty' => false, 'update_term_meta_cache' => false));
		if ($filter != 'price' && $filter != 'ca-year') {
			$newFilter = array();
			foreach ($getTerms as $term) {
				$image = get_term_meta($term->term_id, 'stm_image', true);
				$image = wp_get_attachment_image_src($image, 'stm-img-190-132');
				$category_image = $image[0];
				if (!$category_image) {
					$plchldr_id = get_option('plchldr_attachment_id', 0);
					$category_image = ($plchldr_id == 0) ? STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png' : wp_get_attachment_image_url($plchldr_id);
				}
				if (strpos($category_image, 'motors.loc')) {
					$category_image = stm_ma_replace_host($category_image);
				}
				$newFilter[] = array(
					'label' => $term->name,
					'slug' => $term->slug,
					'count' => $term->count,
					'logo' => ($category_image) ? $category_image : '',
					'parent' => get_term_meta($term->term_id, 'stm_parent', true)
				);

			}
			$forApp[$filter] = $newFilter;
		} else {
			$newFilter = array();
			foreach ($getTerms as $term) {
				$newFilter[] = array(
					'label' => $term->name,
					'value' => $term->slug,
				);

			}
			$filter = ($filter == 'ca-year') ? 'year' : $filter;
			if ($filter == 'year' || $filter == 'price') {
				asort($newFilter);
				$newFilter = array_values($newFilter);
			}
			$forApp[$filter] = $newFilter;
		}
	}
	$res = get_translations($forApp);
	wp_send_json($res);
}

function stm_mra_update_profile()
{

	$response = array();
	$errors = array();
	if (empty($_POST['stm_user_first_name'])) {
		$user_name = '';
	} else {
		$user_name = sanitize_text_field($_POST['stm_user_first_name']);
	}
	if (empty($_POST['stm_user_last_name'])) {
		$user_lastname = '';
	} else {
		$user_lastname = sanitize_text_field($_POST['stm_user_last_name']);
	}
	if (!empty($_POST['stm_user_phone'])) {
		$user_phone = sanitize_text_field($_POST['stm_user_phone']);
	}
	if (!is_email($_POST['stm_user_mail'])) {
		$errors['stm_user_mail'] = true;
	} else {
		$user_mail = sanitize_email($_POST['stm_user_mail']);
	}
	if (empty($_POST['stm_user_password'])) {
		$errors['stm_user_password'] = true;
	} else {
		$user_pass = $_POST['stm_user_password'];
	}
	if (empty($errors)) {
		if (get_user_meta($_POST['userId'], 'stm_app_token', true) == $_POST['userToken']) {
			$user_data = array(
				'ID' => $_POST['userId'],
				'user_pass' => $user_pass,
				'first_name' => $user_name,
				'last_name' => $user_lastname,
				'user_email' => $user_mail,
				'display_name' => $user_name . ' ' . $user_lastname
			);
			$user_id = wp_update_user($user_data);
			if (!is_wp_error($user_id)) {
				update_user_meta($user_id, 'stm_phone', $user_phone);
				update_user_meta($user_id, 'nickname', $user_name . $user_lastname);
				wp_cache_delete($user_id, 'user_meta');
				$user = get_userdata($user_id);
				unset($user->caps);
				unset($user->cap_key);
				unset($user->allcaps);
				unset($user->filter);
				$user->role = (!empty($user->roles)) ? $user->roles[1] : '';
				$user->token = $_POST['userToken'];
				$user->phone = get_user_meta($user->ID, 'stm_phone', true);
				$user->f_name = get_user_meta($user->ID, 'first_name', true);
				$user->l_name = get_user_meta($user->ID, 'last_name', true);
				$restricted = false;
				$restrictions = stm_get_post_limits($user_id);
				if ($restrictions['posts'] < 1 && stm_enablePPL()) {
					$restricted = true;
				}
				$user->image = upload_media($user_id, 'stm_user_avatar');
				$user->image = (!empty(get_user_meta($user_id, 'stm_user_avatar', true))) ? get_user_meta($user_id, 'stm_user_avatar', true) : 'null';
				$response['message'] = esc_html__('Congratulations! You have been successfully updated. Redirecting to your account profile page.', 'motors');
				$response['restricted'] = $restricted;
				$response['user'] = $user->data;
			} else {
				$response['message'] = $user_id->get_error_message();
			}
		} else {
			$response['code'] = 404;
			$response['message'] = esc_html__('Token Expired', 'stm-motors-application');
		}
	} else {
		$response['message'] = esc_html__('Please fill required fields', 'stm-motors-application');
	}
	$response['errors'] = $errors;
	wp_send_json($response);
}

function curl_get_file_contents($URL)
{
	$c = curl_init();
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_URL, $URL);
	$contents = curl_exec($c);
	curl_close($c);
	if ($contents) return $contents;
	else return FALSE;
}

function stm_mra_get_edit_car($params)
{
	$listingId = $params->get_param('id');
	$listing = get_post($listingId);
	$response = array();
	if (!empty($listing)) {
		$gallery = array();
		$i = 0;
		$featImgId = 0;
		if (has_post_thumbnail($listingId)) {
			$imgId = get_post_thumbnail_id($listingId);
			$featImgId = $imgId;
			$img_file = wp_get_attachment_image_url($imgId, 'stm-img-690-410');
			if ($img_file) {
				//$imgData = base64_encode(file_get_contents($img_file));
				//$imgData = base64_encode(curl_get_file_contents($img_file));
				//$src = 'data: ' . stm_ma_get_image_mime_type( $img_file ) . ';base64,' . $imgData;
				//$src = $imgData;
				$gallery[] = array('img_id' => $imgId, 'src' => $img_file, 'position' => $i, "key" => "item-" . $i);
				$i++;
			}
		}
		$car_media = get_post_meta($listingId, 'gallery', true);
		if (!empty($car_media)) {
			$car_media = array_unique($car_media);
			foreach ($car_media as $k => $imgId) {
				if ($featImgId == $imgId) continue;
				$img_file = wp_get_attachment_url($imgId);
				$imgData = base64_encode(file_get_contents($img_file));
				//$src = 'data: ' . stm_ma_get_image_mime_type( $img_file ) . ';base64,' . $imgData;
				$src = $imgData;
				$gallery[] = array('img_id' => $imgId, 'src' => $img_file, 'position' => $i, "key" => "item-" . $i);
				$i++;
			}
		}
		$price = get_post_meta($listingId, 'price', true);
		$sale_price = get_post_meta($listingId, 'sale_price', true);
		if (empty($price) and !empty($sale_price)) {
			$price = $sale_price;
		}
		$carLat = get_post_meta($listingId, 'stm_lat_car_admin', true);
		$carLng = get_post_meta($listingId, 'stm_lng_car_admin', true);
		$carLocation = get_post_meta($listingId, 'stm_car_location', true);
		$response = array(
			'ID' => $listingId,
			'gallery' => $gallery,
			'imgCount' => count($gallery),
			'price' => (!empty($price)) ? str_replace('   ', ' ', trim($price)) : esc_html__('No Price', 'stm-motors-application'),
			'content' => preg_replace('/\[(.*?)\]/', '', $listing->post_content),
			'car_location' => $carLocation,
			'car_lat' => $carLat,
			'car_lng' => $carLng,
			'info' => array(),
		);
		$stepOne = explode(',', get_option('add_car_step_one', "add_media,make,serie,ca-year,mileage,exterior-color"));
		$stepTwo = explode(',', get_option('add_car_step_two', "engine,fuel,transmission,drive,body,location,price"));
		$stepThree = explode(',', get_option('add_car_step_three', "seller_notes,stm_additional_features"));
		foreach (stm_listings_attributes(array('key_by' => 'slug')) as $attribute => $filter_option) {
			$optVal = stm_ma_get_option_key_value($listingId, $filter_option);
			$dataInfo = (is_array($optVal) && count($optVal) > 0) ? $optVal[0] : $optVal;
			if (is_array($stepOne) && array_search($attribute, $stepOne) !== false) $step = 'step_one';
			elseif (is_array($stepTwo) && array_search($attribute, $stepTwo) !== false) $step = 'step_two';
			elseif (is_array($stepThree) && array_search($attribute, $stepThree) !== false) $step = 'step_three';
			else continue;
			$attribute = ($attribute == 'ca-year') ? 'year' : $attribute;
			if ($attribute == 'price') {
				$response['info'][$step][$attribute] = ($dataInfo) ? $dataInfo : $price;
			} elseif ($attribute == 'location') {
				$response['info'][$step][$attribute] = ($dataInfo) ? $dataInfo : $carLocation;
			} else {
				$response['info'][$step][$attribute] = $dataInfo;
			}
		}
		$stepsme = ['step_one' => $stepOne, 'step_two' => $stepTwo, 'step_three' => $stepThree];
		foreach ($stepsme as $st => $steps_on) {
			foreach ($steps_on as $k => $attribute_on) {
				if ($attribute_on == 'location') {
					$steps_one[$st][$attribute_on] = ($response['info'][$st][$attribute_on]) ? $response['info'][$st][$attribute_on] : $carLocation;
				} elseif ($attribute_on == 'ca-year') {
					$steps_one[$st]['year'] = $response['info'][$st]['year'];
				} elseif ($attribute_on == 'seller_notes') {
					$steps_one[$st][$attribute_on] = get_post_meta($listingId, 'listing_seller_note', true);
				} else {
					if (!empty($attribute_on))
						$steps_one[$st][$attribute_on] = $response['info'][$st][$attribute_on];
				}

			}
		}
		$response['info'] = $steps_one;
		$features = get_post_meta($listingId, 'additional_features', true);
		$features = (!empty($features)) ? explode(',', $features) : '';
		$featuresList = $getTerms = get_terms(array('taxonomy' => 'stm_additional_features', 'hide_empty' => false, 'update_term_meta_cache' => false));
		if (!empty($features)):
			foreach ($featuresList as $key => $feature):
				if (in_array($feature->name, $features)) {
					$response['features'][$feature->slug] = $feature->name;
				}
			endforeach;
		else :
			$response['features'] = array();
		endif;
	}
	wp_send_json($response);
}

if (!function_exists('stm_get_car_medias_mobile')) {
	function stm_get_car_medias_mobile($post_id)
	{
		if (!empty($post_id)) {

			$image_limit = '';
			if (stm_pricing_enabled()) {
				$user_added = get_post_meta($post_id, 'stm_car_user', true);
				if (!empty($user_added)) {
					$limits = stm_get_post_limits($user_added);
					$image_limit = $limits['images'];
				}
			}
			$car_media = array();
			//Photo
			$car_photos = array();
			$car_gallery = get_post_meta($post_id, 'gallery', true);
			$car_videos_posters = get_post_meta($post_id, 'gallery_videos_posters', true);
			if (has_post_thumbnail($post_id)) {
				//$car_photos[] = wp_get_attachment_url(get_post_thumbnail_id($post_id));
			}
			if (!empty($car_gallery)) {
				$i = 0;
				foreach ($car_gallery as $car_gallery_image) {
					if (empty($image_limit)) {
						if (wp_get_attachment_url($car_gallery_image)) {
							$car_photos[] = wp_get_attachment_url($car_gallery_image);
						}
					} else {
						$i++;
						if ($i < $image_limit) {
							if (wp_get_attachment_url($car_gallery_image)) {
								$car_photos[] = wp_get_attachment_url($car_gallery_image);
							}
						}
					}
				}
			}
			$car_media['car_photos'] = $car_photos;
			$car_media['car_photos_count'] = count($car_photos);
			//Video
			$car_video = array();
			$car_video_main = get_post_meta($post_id, 'gallery_video', true);
			$car_videos = get_post_meta($post_id, 'gallery_videos', true);
			if (!empty($car_video_main)) {
				$car_video[] = $car_video_main;
			}
			if (!empty($car_videos)) {
				foreach ($car_videos as $car_video_single) {
					if (!empty($car_video_single)) {
						$car_video[] = $car_video_single;
					}
				}
			}
			$car_media['car_videos'] = $car_video;
			$car_media['car_videos_posters'] = $car_videos_posters;
			$car_media['car_videos_count'] = count($car_video);
			return $car_media;
		}
	}
}
