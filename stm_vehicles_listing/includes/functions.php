<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Get filter configuration
 *
 * @param array $args
 *
 * @return array
 */
function stm_listings_attributes( $args = array() )
{
	$args = wp_parse_args( $args, array(
		'where' => array(),
		'key_by' => ''
	) );
	
	$result = array();
	$data = array_filter( (array)get_option( 'stm_vehicle_listing_options' ) );
	
	foreach ( $data as $key => $_data ) {
		$passed = true;
		foreach ( $args['where'] as $_field => $_val ) {
			if ( array_key_exists( $_field, $_data ) && $_data[$_field] != $_val ) {
				$passed = false;
				break;
			}
		}
		
		if ( $passed ) {
			if ( $args['key_by'] ) {
				$result[$_data[$args['key_by']]] = $_data;
			} else {
				$result[] = $_data;
			}
		}
	}
	
	return apply_filters( 'stm_listings_attributes', $result, $args );
}

/**
 * Get single attribute configuration by taxonomy slug
 *
 * @param $taxonomy
 *
 * @return array|mixed
 */
function stm_listings_attribute( $taxonomy )
{
	$attributes = stm_listings_attributes( array( 'key_by' => 'slug' ) );
	if ( array_key_exists( $taxonomy, $attributes ) ) {
		return $attributes[$taxonomy];
	}
	
	return array();
}

/**
 * Get all terms grouped by taxonomy for the filter
 *
 * @return array
 */
function stm_listings_filter_terms($hideEmpty = false)
{
	static $terms;
	$hideEmpty = false;
	if ( isset( $terms ) ) {
		return $terms;
	}
	
	$filters = stm_listings_attributes( array( 'where' => array( 'use_on_car_filter' => true ), 'key_by' => 'slug' ) );
	
	$numeric = array_keys( stm_listings_attributes( array(
		'where' => array(
			'use_on_car_filter' => true,
			'numeric' => true
		),
		'key_by' => 'slug'
	) ) );
	
	$_terms = array();
	
	if ( count( $numeric ) ) {
		$_terms = get_terms( array(
			'taxonomy' => $numeric,
			'hide_empty' => $hideEmpty,
			'update_term_meta_cache' => false,
		) );

		foreach ($numeric as $numItem) {
            if(in_array($numItem, $numeric)) {
                $_terms = array_merge( $_terms, get_terms( array(
                    'taxonomy' => $numItem,
                    'hide_empty' => false,
                    'update_term_meta_cache' => false,
                ) ) );
            }
        }
	}
	
	$taxes = array_diff( array_keys( $filters ), $numeric );
	$taxes = apply_filters( 'stm_listings_filter_taxonomies', $taxes );
	
	$_terms = array_merge( $_terms, get_terms( array(
		'taxonomy' => $taxes,
		'hide_empty' => $hideEmpty,
		'update_term_meta_cache' => false,
	) ) );
	
	$terms = array();
	
	foreach ( $taxes as $tax ) {
		$terms[$tax] = array();
	}
	
	foreach ( $_terms as $_term ) {
		$terms[$_term->taxonomy][$_term->slug] = $_term;
	}
	
	$terms = apply_filters( 'stm_listings_filter_terms', $terms );
	
	return $terms;
}

/**
 * Drop-down options grouped by attribute for the filter
 *
 * @return array
 */
function stm_listings_filter_options($hideEmpty = false)
{
	static $options;
	
	if ( isset( $options ) ) {
		return $options;
	}
	
	$filters = stm_listings_attributes( array( 'where' => array( 'use_on_car_filter' => true ), 'key_by' => 'slug' ) );
	$terms = stm_listings_filter_terms($hideEmpty);
	$options = array();
	
	foreach ( $terms as $tax => $_terms ) {
		$_filter = isset( $filters[$tax] ) ? $filters[$tax] : array();
		$options[$tax] = _stm_listings_filter_attribute_options( $tax, $_terms );
		
		if ( empty( $_filter['numeric'] ) || !empty( $_filter['use_on_car_filter_links'] ) ) {
			$_remaining = stm_listings_options_remaining( $terms[$tax], stm_listings_query() );
			
			foreach ( $_terms as $_term ) {
				if ( isset( $_remaining[$_term->term_taxonomy_id] ) ) {
					$options[$tax][$_term->slug]['count'] = (int)$_remaining[$_term->term_taxonomy_id];
				} else {
					$options[$tax][$_term->slug]['count'] = 0;
					//$options[$tax][$_term->slug]['disabled'] = true;
				}
			}
		}
	}
	
	$options = apply_filters( 'stm_listings_filter_options', $options );
	
	return $options;
}

/**
 * Get list of attribute options filtered by query
 *
 * @param array $terms
 * @param WP_Query $from
 *
 * @return array
 */
function stm_listings_options_remaining( $terms, $from = null )
{
	/** @var WP_Query $from */
	$from = is_null( $from ) ? $GLOBALS['wp_query'] : $from;
	
	//if (empty($terms) || (!count($from->get('meta_query', array())) && !count($from->get('tax_query')))) {
	if ( empty( $terms ) || is_null( $from ) ) {
		return array();
	}
	
	global $wpdb;
	$meta_query = new WP_Meta_Query( $from->get( 'meta_query', array() ) );
	$meta_query_count = new WP_Meta_Query( $from->get( 'meta_query_count', array() ) );
	
	$tax_query = new WP_Tax_Query( $from->get( 'tax_query', array() ) );
	$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
	$meta_query_count_sql = $meta_query_count->get_sql( 'post', $wpdb->posts, 'ID' );
	$tax_query_sql = $tax_query->get_sql( $wpdb->posts, 'ID' );
	$term_ids = wp_list_pluck( $terms, 'term_taxonomy_id' );
	$post_type = $from->get( 'post_type' );
	
	$sold_car = $from->get( 'sold_car' );
	$sold_sql = [ 'where' => '', 'join' => '' ];
	
	if ( $sold_car == 'on' OR $sold_car == 'off' ) {
		$sold_sql['join'] = "\n LEFT JOIN " . $wpdb->postmeta . " as p_meta ON (" . $wpdb->posts . ".ID = p_meta.post_id AND p_meta.meta_key = 'car_mark_as_sold' )";

		if ( $sold_car == 'on' ) {
			$sold_sql['where'] = "\n AND ( p_meta.post_id IS NOT NULL  AND  p_meta.meta_value = 'on' ) ";
		}

		if ( $sold_car == 'off' ) {
			$sold_sql['where'] = "\n AND ( p_meta.post_id IS NULL  OR  p_meta.meta_value = '' ) ";
		}
	}
	
	// Generate query
	$query = array();
	$query['select'] = "SELECT term_taxonomy.term_taxonomy_id, COUNT( {$wpdb->posts}.ID ) as count";
	$query['from'] = "FROM {$wpdb->posts}";
	$query['join'] = "INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
	$query['join'] .= "\nINNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )";
	//$query['join'] .= "\nINNER JOIN {$wpdb->terms} AS terms USING( term_id )";
	$query['join'] .= "\n" . $tax_query_sql['join'] . $meta_query_count_sql['join'] . $sold_sql['join'];
	$query['where'] = "WHERE {$wpdb->posts}.post_type IN ( '{$post_type}' ) AND {$wpdb->posts}.post_status = 'publish' ";
	$query['where'] .= "\n" . $tax_query_sql['where'] . $meta_query_count_sql['where'] . $sold_sql['where'];
	$query['where'] .= "\nAND term_taxonomy.term_taxonomy_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")";
	$query['group_by'] = "GROUP BY term_taxonomy.term_taxonomy_id";
	
	$query = apply_filters( 'stm_listings_options_remaining_query', $query );
	$query = join( "\n", $query );
	
	$results = $wpdb->get_results( $query );
	$results = wp_list_pluck( $results, 'count', 'term_taxonomy_id' );
	
	return $results;
}

/**
 * Filter configuration array
 *
 * @return array
 */
function stm_listings_filter( $source = null, $hideEmpty = false )
{
	$query = stm_listings_query( $source );
	$total = $query->found_posts;
	$filters = stm_listings_attributes( array( 'where' => array( 'use_on_car_filter' => true ), 'key_by' => 'slug' ) );
	$options = stm_listings_filter_options($hideEmpty);
	$terms = stm_listings_filter_terms();
	$url = '';
	
	$compact = compact( 'options', 'filters', 'total', 'url' );
	
	if ( isset( $_GET['result_with_posts'] ) ) {
		$filterParams = explode( ',', $_GET['filter-params'] );
		$fp = '';
		$fpParams = '';
		foreach ( $filterParams as $k => $val ) {
			$get = ( $val == 'price' ) ? 'max_price' : $val;
			if ( isset( $_GET[$get] ) && !empty( $_GET[$get] ) ) {
				
				if ( empty( $fp ) ) $fp .= $filters[$val]['single_name'];
				else if ( !empty( $fp ) && $k != 0 && $k != ( count( $filterParams ) - 1 ) ) $fp .= ', ' . $filters[$val]['single_name'];
				else if ( $k >= 1 && !empty( $fp ) ) $fp .= esc_html__( ' and ', 'stm_vehicles_listing' ) . $filters[$val]['single_name'];
			}
		}
		
		if ( !empty( $fp ) ) $fp = esc_html__( 'By ', 'stm_vehicles_listing' ) . $fp;
		
		$posts = add_review_info_to_listing( $query->posts );
		$compact = compact( 'options', 'filters', 'total', 'url', 'posts', 'fp' );
	}
	
	if ( isset( $_GET['offset'] ) ) {
		$result_count = count( $query->get_posts() );
		$offset = $_GET['offset'] + 1;
		if ( $offset * $_GET['posts_per_page'] <= $total ) {
			
			$offset = ( $offset * $_GET['posts_per_page'] >= $total ) ? 0 : $offset;
			
			$compact = compact( 'options', 'filters', 'total', 'url', 'posts', 'offset', 'fp', 'result_count' );
		}
	}
	
	return apply_filters( 'stm_listings_filter', $compact, $terms );
}

function add_review_info_to_listing( $posts )
{
	$newPosts = array();
	
	foreach ( $posts as $k => $post ) {
		$postId = $post->ID;
		$reviewId = get_post_id_by_meta_k_v( 'review_car', $postId );
		
		$startAt = get_post_meta( $reviewId, 'show_title_start_at', true );
		$price = stm_listing_price_view( get_post_meta( $postId, 'stm_genuine_price', true ) );
		$hwy = get_post_meta( $postId, 'highway_mpg', true );
		$cwy = get_post_meta( $postId, 'sity_mpg', true );
		
		$title = $post->post_title;
		
		if ( !is_null( $reviewId ) ) {
			$title = '<span>' . $title . '</span> ' . string_max_charlength( get_the_title( $reviewId ), 55 );
		}
		
		$cars_in_compare = array();
		if ( !empty( $_COOKIE['compare_ids'] ) ) {
			$cars_in_compare = $_COOKIE['compare_ids'];
		}
		
		$car_already_added_to_compare = '';
		$car_compare_status = esc_html__( 'Add to compare', 'stm_vehicles_listing' );
		
		if ( !empty( $cars_in_compare ) and in_array( $postId, $cars_in_compare ) ) {
			$car_already_added_to_compare = 'active';
			$car_compare_status = esc_html__( 'Remove from compare', 'stm_vehicles_listing' );
		}
		
		$imgUrl = get_the_post_thumbnail_url( $postId, 'stm-img-255-160' );
		
		if ( empty( $imgUrl ) && !is_null( $reviewId ) ) {
			$imgData = get_the_post_thumbnail_url( $reviewId, 'stm-img-255-160' );
			$imgUrl = ( !empty( $imgData ) ) ? $imgData : get_template_directory_uri() . '/assets/images/plchldr255_160.jpg';
		} elseif ( !$imgUrl ) {
			$imgUrl = get_template_directory_uri() . '/assets/images/plchldr255_160.jpg';
		}
		
		$postLink = get_the_permalink( $postId );
		$excerpt = apply_filters( 'the_content', get_the_excerpt( $postId ) );
		
		$newPost = array();
		
		$newPost['id'] = $postId;
		$newPost['car_already_added'] = $car_already_added_to_compare;
		$newPost['car_compare_status'] = $car_compare_status;
		$newPost['title'] = $title;
		$newPost['generate_title'] = stm_generate_title_from_slugs( $postId, false );;
		$newPost['excerpt'] = $excerpt;
		$newPost['url'] = $postLink;
		$newPost['img_url'] = $imgUrl;
		$newPost['price'] = $price;
		$newPost['show_start_at'] = $startAt;
		$newPost['hwy'] = $hwy;
		$newPost['cwy'] = $cwy;
		
		
		if ( !is_null( $reviewId ) ) {
			
			$performance = get_post_meta( $reviewId, 'performance', true );
			$comfort = get_post_meta( $reviewId, 'comfort', true );
			$interior = get_post_meta( $reviewId, 'interior', true );
			$exterior = get_post_meta( $reviewId, 'exterior', true );
			
			$ratingSumm = ( ( $performance + $comfort + $interior + $exterior ) / 4 );
			
			$newPost['ratingSumm'] = $ratingSumm;
			$newPost['ratingP'] = $ratingSumm * 20;
			$newPost['performance'] = $performance;
			$newPost['performanceP'] = $performance * 20;
			$newPost['comfort'] = $comfort;
			$newPost['comfortP'] = $comfort * 20;
			$newPost['interior'] = $interior;
			$newPost['interiorP'] = $interior * 20;
			$newPost['exterior'] = $exterior;
			$newPost['exteriorP'] = $exterior * 20;
		}
		
		$newPosts[$k] = (object)$newPost;
	}
	
	return $newPosts;
}

function get_post_id_by_meta_k_v( $key, $value )
{
	global $wpdb;
	$meta = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM " . $wpdb->postmeta . " WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
	
	return ( count( $meta ) > 0 ) ? $meta[0]->post_id : null;
}

/**
 * Retrieve input data from $_POST, $_GET by path
 *
 * @param $path
 * @param $default
 *
 * @return mixed
 */
function stm_listings_input( $path, $default = null )
{
	
	if ( trim( $path, '.' ) == '' ) {
		return $default;
	}
	
	foreach ( array( $_POST, $_GET ) as $source ) {
		$value = $source;
		foreach ( explode( '.', $path ) as $key ) {
			if ( !is_array( $value ) || !array_key_exists( $key, $value ) ) {
				$value = null;
				break;
			}
			
			$value = &$value[$key];
		}
		
		if ( !is_null( $value ) ) {
			return $value;
		}
	}
	
	return $default;
}

/**
 * Current URL with native WP query string parameters ()
 *
 * @return string
 */
function stm_listings_current_url()
{
	global $wp, $wp_rewrite;
	
	$url = preg_replace( "/\/page\/\d+/", '', $wp->request );
	$url = home_url( $url . '/' );
	if ( !$wp_rewrite->permalink_structure ) {
		parse_str( $wp->query_string, $query_string );
		
		$leave = array( 'post_type', 'pagename', 'page_id', 'p' );
		$query_string = array_intersect_key( $query_string, array_flip( $leave ) );
		
		$url = trim( add_query_arg( $query_string, $url ), '&' );
		$url = str_replace( '&&', '&', $url );
	}
	
	return apply_filters( 'stm_listings_current_url', $url );
}

function _stm_listings_filter_attribute_options( $taxonomy, $_terms )
{
	
	$attribute = stm_listings_attribute( $taxonomy );
	$attribute = wp_parse_args( $attribute, array(
		'slug' => $taxonomy,
		'single_name' => '',
		'numeric' => false,
		'slider' => false,
	) );
	
	$options = array();
	
	if ( !$attribute['numeric'] ) {
		
		
		$options[''] = array(
			'label' => apply_filters( 'stm_listings_default_tax_name', $attribute['single_name'] ),
			'selected' => stm_listings_input( $attribute['slug'] ) == null,
			'disabled' => false,
		);
		
		foreach ( $_terms as $_term ) {
			$options[$_term->slug] = array(
				'label' => $_term->name,
				'selected' => stm_listings_input( $attribute['slug'] ) == $_term->slug,
				'disabled' => false,
				'count' => $_term->count,
			);
		}
	} else {
		$numbers = array();
		foreach ( $_terms as $_term ) {
			$numbers[intval( $_term->slug )] = $_term->name;
		}
		ksort( $numbers );
		
		if ( !empty( $attribute['slider'] ) ) {
			foreach ( $numbers as $_number => $_label ) {
				$options[$_number] = array(
					'label' => $_label,
					'selected' => stm_listings_input( $attribute['slug'] ) == $_label,
					'disabled' => false,
				);
			}
		} else {
			
			$options[''] = array(
				'label' => sprintf( __( 'Max %s', 'stm_vehicles_listing' ), $attribute['single_name'] ),
				'selected' => stm_listings_input( $attribute['slug'] ) == null,
				'disabled' => false,
			);
			
			$_prev = null;
			$_affix = empty( $attribute['affix'] ) ? '' : __( $attribute['affix'], 'stm_vehicles_listing' );
			
			foreach ( $numbers as $_number => $_label ) {
				
				if ( $_prev === null ) {
					$_value = '<' . $_number;
					$_label = '< ' . $_label . ' ' . $_affix;
				} else {
					$_value = $_prev . '-' . $_number;
					$_label = $_prev . '-' . $_label . ' ' . $_affix;
				}
				
				$options[$_value] = array(
					'label' => $_label,
					'selected' => stm_listings_input( $attribute['slug'] ) == $_value,
					'disabled' => false,
				);
				
				$_prev = $_number;
			}
			
			if ( $_prev ) {
				$_value = '>' . $_prev;
				$options[$_value] = array(
					'label' => '>' . $_prev . ' ' . $_affix,
					'selected' => stm_listings_input( $attribute['slug'] ) == $_value,
					'disabled' => false,
				);
			}
		}
	}
	
	return $options;
}

if ( !function_exists( 'stm_listings_user_defined_filter_page' ) ) {
	function stm_listings_user_defined_filter_page()
	{
		return apply_filters( 'stm_listings_inventory_page_id', stm_me_get_wpcfto_mod( 'listing_archive', false ) );
	}
}

function stm_listings_paged_var()
{
	global $wp;
	
	$paged = null;
	
	if ( isset( $wp->query_vars['paged'] ) ) {
		$paged = $wp->query_vars['paged'];
	} elseif ( isset( $_GET['paged'] ) ) {
		$paged = sanitize_text_field( $_GET['paged'] );
	}
	
	return $paged;
}

/**
 * Listings post type identifier
 *
 * @return string
 */
if ( !function_exists( 'stm_listings_post_type' ) ) {
	function stm_listings_post_type()
	{
		return apply_filters( 'stm_listings_post_type', 'listings' );
	}
}

add_action( 'init', 'stm_listings_init', 1 );

function stm_listings_init()
{
	
	$options = get_option( 'stm_post_types_options' );
	
	$stm_vehicle_options = wp_parse_args( $options, array(
		'listings' => array(
			'title' => __( 'Listings', 'stm_vehicles_listing' ),
			'plural_title' => __( 'Listings', 'stm_vehicles_listing' ),
			'rewrite' => 'listings'
		),
	) );
	
	register_post_type( stm_listings_post_type(), array(
		'labels' => array(
			'name' => $stm_vehicle_options['listings']['plural_title'],
			'singular_name' => $stm_vehicle_options['listings']['title'],
			'add_new' => __( 'Add New', 'stm_vehicles_listing' ),
			'add_new_item' => __( 'Add New Item', 'stm_vehicles_listing' ),
			'edit_item' => __( 'Edit Item', 'stm_vehicles_listing' ),
			'new_item' => __( 'New Item', 'stm_vehicles_listing' ),
			'all_items' => __( 'All Items', 'stm_vehicles_listing' ),
			'view_item' => __( 'View Item', 'stm_vehicles_listing' ),
			'search_items' => __( 'Search Items', 'stm_vehicles_listing' ),
			'not_found' => __( 'No items found', 'stm_vehicles_listing' ),
			'not_found_in_trash' => __( 'No items found in Trash', 'stm_vehicles_listing' ),
			'parent_item_colon' => '',
			'menu_name' => __( $stm_vehicle_options['listings']['plural_title'], 'stm_vehicles_listing' ),
		),
		'menu_icon' => 'dashicons-location-alt',
		'show_in_nav_menus' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'author', 'revisions' ),
		'rewrite' => array( 'slug' => $stm_vehicle_options['listings']['rewrite'] ),
		'has_archive' => ( get_option( 'stm_motors_chosen_template' ) == 'equipment' && empty( stm_me_get_wpcfto_mod( 'listing_archive', '' ) ) ) ? false : true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'hierarchical' => false,
		'menu_position' => null,
	) );
	
}

add_filter( 'get_pagenum_link', 'stm_listings_get_pagenum_link' );

function stm_listings_get_pagenum_link( $link )
{
	return remove_query_arg( 'ajax_action', $link );
}

/*Functions*/
function stm_check_motors()
{
	return apply_filters( 'stm_listing_is_motors_theme', false );
}

require_once 'templates.php';
require_once 'enqueue.php';
require_once 'vehicle_functions.php';

add_action( 'init', 'stm_listings_include_customizer' );

function stm_listings_include_customizer()
{
	if ( !stm_check_motors() ) {
		require_once 'customizer/customizer.class.php';
	}
}

function stm_listings_search_inventory()
{
	return apply_filters( 'stm_listings_default_search_inventory', false );
}

function stm_listing_magazine_body_class( $classes )
{
	$classes[] = 'no_margin';
	
	return $classes;
}

function stm_listings_dynamic_string_translation_e( $desc, $string )
{
	do_action( 'wpml_register_single_string', 'stm_vehicles_listing', $desc, $string );
	echo apply_filters( 'wpml_translate_single_string', $string, 'stm_vehicles_listing', $desc );
}

function stm_listings_dynamic_string_translation( $desc, $string )
{
	do_action( 'wpml_register_single_string', 'stm_vehicles_listing', $desc, $string );
	return apply_filters( 'wpml_translate_single_string', $string, 'stm_vehicles_listing', $desc );
}