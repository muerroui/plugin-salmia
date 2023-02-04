<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_filter('request', 'stm_listings_query_vars');

function stm_listings_query_vars($query_vars)
{
	if ( !empty($query_vars['post_type']) && $query_vars['post_type'] == 'product' ) return $query_vars;
	
    $include_search = stm_listings_search_inventory();

    $is_listing = isset($query_vars['post_type'])
        && in_array(stm_listings_post_type(), (array)$query_vars['post_type']);

    /*Include search*/
    if ( $include_search and !empty($_GET['s']) ) {
        $is_listing = true;
    }

    if ( isset($query_vars['pagename']) ) {
        $listing_id = stm_listings_user_defined_filter_page();
        if ( $listing_id ) {
            $requested = get_page_by_path($query_vars['pagename']);
            if ( !empty($requested) and $is_listing = $listing_id == $requested->ID ) {
                unset($query_vars['pagename']);
            }
        }
    }

    if ( !empty($_GET['ajax_action']) and $_GET['ajax_action'] == 'listings-result' ) {
        unset($query_vars['pagename']);
        unset($query_vars['page_id']);
        $is_listing = true;
    }

    if ( $is_listing and !is_admin() and !isset($query_vars['listings']) ) {
		$query_vars = apply_filters('stm_listings_query_vars', _stm_listings_build_query_args($query_vars));
    }

    return $query_vars;
}

add_action('template_redirect', 'stm_listings_template_redirect', 0);

function stm_listings_template_redirect()
{
    if ( is_feed() ) {
        return;
    }

    if ( $listing_id = stm_listings_user_defined_filter_page() ) {
        if ( is_post_type_archive('listings') ) {
            $GLOBALS['listings_query'] = $GLOBALS['wp_the_query'];
            $query = new WP_Query('pagename=' . get_page_uri($listing_id));
            $GLOBALS['wp_query'] = $query;
            $GLOBALS['wp_the_query'] = $query;
            $GLOBALS['wp']->register_globals();

            if ( stm_is_magazine() ) {
                add_filter('body_class', 'stm_listing_magazine_body_class');
            }
        }
    }
}

/**
 * Get current listings query
 *
 * @return WP_Query
 */
function stm_listings_query($source = null)
{
    $newQuery = "";
    if ( isset($GLOBALS['listings_query']) && is_null($source) ) {
        $newQuery = $GLOBALS['listings_query'];
    } else {
	    $query_attr = _stm_listings_build_query_args([
		    'paged' => stm_listings_paged_var(),
	    ], $source );

        if ( !is_null($source) ) {
            foreach ($source as $k => $val) {
                $query_attr[$k] = $val;
            }
        }

		$newQuery = new WP_Query($query_attr);

		$GLOBALS['listings_query'] = $newQuery;
    }

    /*return isset($GLOBALS['listings_query']) ? $GLOBALS['listings_query'] : new WP_Query(_stm_listings_build_query_args(array(
        'paged' => stm_listings_paged_var()
    )));*/
    return $newQuery;
}


add_filter('posts_clauses_request', 'stm_listings_posts_clauses', 100, 2);

function stm_listings_posts_clauses($clauses, WP_Query $query)
{
    if ( !$query->get('filter_location') || !stm_listings_input('stm_lat') || !stm_listings_input('stm_lng') ) {
        return $clauses;
    }

    $formula = '6378.137 * ACOS(COS(RADIANS(stm_lat_prefix.meta_value)) * COS(RADIANS(:lat)) * COS(RADIANS(stm_lng_prefix.meta_value) - RADIANS(:lng)) + SIN(RADIANS(stm_lat_prefix.meta_value)) * SIN(RADIANS(:lat)))';
    $formula = strtr($formula, array(
        ':lat' => floatval(stm_listings_input('stm_lat')),
        ':lng' => floatval(stm_listings_input('stm_lng')),
    ));

    $clauses['fields'] .= ", ($formula) AS stm_distance";

    global $wpdb;
    $table_prefix = $wpdb->prefix;

    $clauses['join'] .= " INNER JOIN {$table_prefix}postmeta stm_lat_prefix ON ({$table_prefix}posts.ID = stm_lat_prefix.post_id AND stm_lat_prefix.meta_key = 'stm_lat_car_admin')";
    $clauses['join'] .= " INNER JOIN {$table_prefix}postmeta stm_lng_prefix ON ({$table_prefix}posts.ID = stm_lng_prefix.post_id AND stm_lng_prefix.meta_key = 'stm_lng_car_admin') ";

    if ($query->get('orderby') == 'stm_distance'){
        $clauses['orderby'] = 'stm_distance ASC, ' . $clauses['orderby'];
    }

    return apply_filters('stm_listings_clauses_filter', $clauses);
}

function _stm_listings_build_query_args($args, $source = null)
{
	if ( is_null($source) ) {
		$source = $_GET;
	} else {
		if ( $_GET ){
			$source = array_merge($source, $_GET);
		}
	}
	
	if (isset($_GET['keyword'])) {
		$args['s'] = $_GET['keyword'];
	}
	
    $args['post_type'] = stm_listings_post_type();

    $args['order'] = 'DESC';
    $args['orderby'] = 'date';

    foreach (stm_listings_attributes(array('key_by' => 'slug')) as $attribute => $filter_option) {

        if ( $filter_option['numeric'] ) {
            // Compatibility for min_
            if ( !empty($source['min_' . $attribute]) ) {
                $source[$attribute] = array('min' => $source['min_' . $attribute]);
            }

            // Compatibility for max_
            if ( !empty($source['max_' . $attribute]) ) {
                $maxArr = array('max' => $source['max_' . $attribute]);
                $source[$attribute] = (isset($source[$attribute]['min'])) ? array_merge($source[$attribute], $maxArr) : $maxArr;
            }
        }

        if ( empty($source[$attribute]) ) {
            continue;
        }

        $_value = $source[$attribute];

        if ( !is_array($_value) && $filter_option['numeric'] ) {
            if ( strpos(trim($_value, '-'), '-') !== false ) {
                $_value = explode('-', $_value);
                $_value = array(
                    'min' => $_value[0],
                    'max' => $_value[1],
                );
            } elseif ( strpos($_value, '>') === 0 ) {
                $_value = array(
                    'min' => str_replace('>', '', $_value),
                );
            } elseif ( strpos($_value, '<') === 0 ) {
                $_value = array(
                    'max' => str_replace('<', '', $_value),
                );
            }
        }


        if ( !is_array($_value) ) {
            // Exact value
            $args['tax_query'][] = array(
                'taxonomy' => $attribute,
                'field' => 'slug',
                'terms' => (array)$_value,
            );
            continue;
        }

        if ( !empty($_value['min']) || !empty($_value['max']) ) {
            $between = array(0, 9999999999);

			if ( $attribute == 'price' ) {
				if ( isset($_value['min']) ) $between[0] = stm_convert_to_normal_price($_value['min']);
				if ( isset($_value['max']) ) $between[1] = stm_convert_to_normal_price($_value['max']);

				$args['meta_query'][] = array(
					array(
						'key' => 'stm_genuine_price',
						'value' => $between,
						'type' => 'DECIMAL',
						'compare' => 'BETWEEN',
					),
				);

				continue;
			}

            if ( isset($_value['min']) ) $between[0] = $_value['min'];
            if ( isset($_value['max']) ) $between[1] = $_value['max'];

            // Range condition
            $args['meta_query'][] = array(
                'key' => $attribute,
                'value' => $between,
                'type' => 'DECIMAL',
                'compare' => 'BETWEEN',
            );

        } elseif ( array_filter($_value) ) {
            // Multiple values
            $args['tax_query'][] = array(
                'taxonomy' => $attribute,
                'terms' => $_value,
                'field' => 'slug',
            );
        }
    }

    if ( isset($args['meta_query']) && count($args['meta_query']) > 1 ) {
        $args['meta_query'] = array_merge(array('relation' => 'AND'), $args['meta_query']);
    }

    if ( !empty($source['popular']) && $source['popular'] == 'true' ) {
        $args['order'] = 'DESC';
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'stm_car_views';
    }

    $metaKey = '';

	$defaultSort = stm_me_get_wpcfto_mod('default_sort_by', 'date_high');
	$sortBy = (!empty($source['sort_order'])) ? $source['sort_order'] : $defaultSort;

    if ( !empty($sortBy) ) {
        switch ( $sortBy ) {
            case "price_low":
                $metaKey = 'stm_genuine_price';
                $args['meta_key'] = 'stm_genuine_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case "price_high":
                $metaKey = 'stm_genuine_price';
                $args['meta_key'] = 'stm_genuine_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case "date_low":
                $args['order'] = 'ASC';
                $args['orderby'] = 'date';
                break;
            case "mileage_low":
                $metaKey = 'mileage';
                $args['order'] = 'ASC';
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'mileage';
                break;
            case "mileage_high":
                $metaKey = 'mileage';
                $args['order'] = 'DESC';
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'mileage';
                break;
            case "distance_nearby":
                $args['order'] = 'ASC';
                $args['orderby'] = 'stm_distance';
                break;
            default:
                $args['order'] = 'DESC';
                $args['orderby'] = 'date';
        }
    }

    $args['sold_car'] = 'off';

    if ( function_exists('stm_sold_status_enabled') && stm_sold_status_enabled() ) {
		
		if ( !empty($source['sold_car']) ) {
            
            $args['sold_car'] = 'on';

			$args['meta_query'][] = [
				'relation' => 'OR',
				[
					'key' => 'car_mark_as_sold',
					'value' => 'on',
					'compare' => '='
				]
			];

		} else {

            $show_sold = stm_me_get_wpcfto_mod('show_sold_listings');

            if ( $show_sold ) {

                if ( !empty($source['listing_status']) && $source['listing_status'] == 'sold' ) {
                    $args['meta_query'][] = [
                        'relation' => 'OR',
                        [
                            'key' => 'car_mark_as_sold',
                            'value' => 'on',
                            'compare' => '='
                        ]
                    ];
                } elseif ( !empty($source['listing_status']) && $source['listing_status'] == 'active' ) {
                    $args['meta_query'][] = [
                        'relation' => 'OR',
                        [
                            'key' => 'car_mark_as_sold',
                            'value' => '',
                            'compare' => 'NOT EXISTS'
                        ],
                        [
                            'key' => 'car_mark_as_sold',
                            'value' => '',
                            'compare' => '='
                        ]
                    ];
                } else {
                    $args['meta_query'][] = [
                        'relation' => 'OR',
                        [
                            'key' => 'car_mark_as_sold',
                            'value' => 'on',
                            'compare' => '='
                        ],
                        [
                            'key' => 'car_mark_as_sold',
                            'value' => '',
                            'compare' => 'NOT EXISTS'
                        ],
                        [
                            'key' => 'car_mark_as_sold',
                            'value' => '',
                            'compare' => '='
                        ]
                    ];
                }

            } else {
                $args['meta_query'][] = [
                    'relation' => 'OR',
                    [
                        'key' => 'car_mark_as_sold',
                        'value' => '',
                        'compare' => 'NOT EXISTS'
                    ],
                    [
                        'key' => 'car_mark_as_sold',
                        'value' => '',
                        'compare' => '='
                    ]
                ];
            }
            
		}
	}

    $args['meta_query_count'][] = (isset($args['meta_query'])) ? $args['meta_query'] : [];

    if ( !empty($source['listing_type']) && $source['listing_type'] == 'with_review' ) {
        $args['meta_query'][] = [
            [
                'key' => 'has_review_car',
                'compare' => 'EXISTS'
            ]
        ];
    }

    if ( !empty($source['posts_per_page']) ) {
        $args['posts_per_page'] = $source['posts_per_page'];
    }

    if ( !empty($source['offset']) && !empty($source['posts_per_page']) ) {
        $args['offset'] = $source['offset'] * $source['posts_per_page'];
    }

    // Enables adding location conditions
    $args['filter_location'] = true;

    $blog_id = get_current_blog_id();

    // later used in STM Inventory Search Results shortcode 
    if ( !empty( $_COOKIE['stm_last_query_args_' . $blog_id] ) ) {
        unset( $_COOKIE['stm_last_query_args_' . $blog_id] );
    }

    if ( !headers_sent() ) {
        setcookie( 'stm_last_query_args_' . $blog_id, json_encode($args), time() + ( 86400 * 30 ), '/' );
    }

    // search results back link
    $link_get = $_GET;

    if ( isset($link_get['ajax_action']) && !empty($link_get['ajax_action']) ) {
        unset($link_get['ajax_action']);
    }

    $inventory_link = add_query_arg($link_get, get_the_permalink( stm_get_listing_archive_page_id() ));
    
    if ( !empty( $_COOKIE['stm_last_query_link_' . $blog_id] ) ) {
        unset( $_COOKIE['stm_last_query_link_' . $blog_id] );
    }

    if ( !headers_sent() ) {
        setcookie( 'stm_last_query_link_' . $blog_id, $inventory_link, time() + ( 86400 * 30 ), '/' );
    }

    return apply_filters('stm_listings_build_query_args', $args, $source);
}