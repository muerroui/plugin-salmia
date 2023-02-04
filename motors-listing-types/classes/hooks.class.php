<?php
// phpcs:disable
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class HooksMultiListing extends STMMultiListing {


	public $add_listing_name = '';
	public function __construct() {
		 parent::__construct();

		add_action( 'template_redirect', array( $this, 'stm_listings_attributes_filter' ), 100 );
		add_filter( 'stm_listings_filter', array( $this, 'stm_listings_filter' ), 11, 2 );
		add_filter( 'stm_listings_build_query_args', array( $this, 'stm_query_args_post_type' ), 10, 2 );
		add_filter( 'option_stm_vehicle_listing_options', array( $this, 'stm_replace_vehicle_listing_option' ), 11, 2 );

		// Add permalink rule for add listing page
		add_action( 'init', array( $this, 'stm_add_post_type_rule' ) );
		add_filter( 'query_vars', array( $this, 'stm_add_post_type_select_white_list' ) );
		add_action( 'template_include', array( $this, 'stm_add_post_type_select' ) );

		add_action( 'init', array( $this, 'stm_register_post_types' ), 11 );

		add_filter( 'stm_listing_save_post_data', array( $this, 'stm_insert_post_add_post_type' ) );

		$add_listing_name       = ! empty( $this->settings['add_listing_name'] ) ? $this->settings['add_listing_name'] : 'select-listing';
		$this->add_listing_name = apply_filters( 'multilisting_add_listing_name', $add_listing_name );

		// Replace messages and profile page for listings
		add_filter( 'stm_filter_add_a_car', array( $this, 'stm_replace_add_listing_notifications_item' ) );
		add_filter( 'stm_filter_add_car_media', array( $this, 'stm_replace_add_listing_notifications_media' ) );

		add_filter( 'stm_similar_cars_query', array( $this, 'stm_listing_types_similar_listing_query' ) );

		add_filter( 'stm_filter_listing_link', array( $this, 'stm_filter_listing_link' ), 20, 2 );
		add_filter( 'stm_listings_binding_results', array( $this, 'stm_binding_results_return_200' ) );

		// add_action( 'woocommerce_checkout_update_order_meta', 'stm_before_create_order', 200, 2 );
		add_action( 'init', array( $this, 'stm_register_perpay' ) );

		add_action( 'stm_after_listing_gallery_saved', array( $this, 'stm_after_listing_gallery_saved' ) );
		add_filter( 'stm_listings_inventory_page_id', array( $this, 'stm_listings_inventory_page_id' ) );

		add_action( 'do_meta_boxes', array( $this, 'stm_remove_thumbnail_box' ) );

		add_filter( 'stm_similar_cars_query', array( $this, 'stm_similar_multilistings_query' ), 150 );
	}

	public function stm_similar_multilistings_query( $query ) {
		$current_type = get_post_type( get_the_ID() );

		if ( $current_type && in_array( $current_type, STMMultiListing::stm_get_listing_type_slugs() ) ) {
			$query['post_type'] = $current_type;
		}

		return $query;
	}

	public function stm_remove_thumbnail_box( $post_type ) {
		$listings   = STMMultiListing::stm_get_listings();
		$post_types = array();

		if ( ! empty( $listings ) ) {
			foreach ( $listings as $key => $listing ) {
				$post_types[] = $listing['slug'];
			}
		}

		if ( in_array( $post_type, $post_types ) ) {
			if ( ! empty( $post_types ) && in_array( $post_type, $post_types ) ) {
				remove_meta_box( 'postimagediv', $post_type, 'side' );
			}
		}
	}

	// filter categories for given post type
	public static function stm_listings_attributes_filter( $listing = '' ) {
		if ( empty( $listing ) ) {
			$listing = ( new STMMultiListing() )->stm_get_current_listing();
		}

		if ( empty( $listing ) ) {
			return;
		}

		$options = "stm_{$listing['slug']}_options";

		$stm_attributes = function ( $result, $args = array() ) use ( $options ) {
			$args = wp_parse_args(
				$args,
				array(
					'where'  => array(),
					'key_by' => '',
				)
			);

			$result = array();
			$data   = array_filter( (array) get_option( $options ) );

			foreach ( $data as $key => $_data ) {
				$passed = true;
				foreach ( $args['where'] as $_field => $_val ) {
					if ( array_key_exists( $_field, $_data ) && $_data[ $_field ] != $_val ) {
						$passed = false;
						break;
					}
				}

				if ( $passed ) {
					if ( $args['key_by'] ) {
						$result[ $_data[ $args['key_by'] ] ] = $_data;
					} else {
						$result[] = $_data;
					}
				}
			}

			return $result;
		};

		add_filter( 'stm_listings_attributes', $stm_attributes, 1001, 2 );
	}

	public function stm_listings_filter( $compact, $terms ) {
		
		$pt = $this->stm_get_current_listing();

		if ( ! wp_doing_ajax() && ! empty( $pt ) && isset( $pt['label'] ) && ! empty( $pt['label'] ) ) {
			$options = get_option( 'stm_motors_listing_types' );
			if ( isset( $options[ $pt['slug'] . '_inventory_custom_settings' ] ) && $options[ $pt['slug'] . '_inventory_custom_settings' ] == true ) {
				if ( ! empty( $options[ $pt['slug'] . '_listing_directory_title_default' ] ) ) {
					$compact['listing_title'] = $options[ $pt['slug'] . '_listing_directory_title_default' ];
				} else {
					$compact['listing_title'] = __( "{$pt['label']} for sale", 'motors_listing_types' );
				}
			} else {
						$compact['listing_title'] = __( "{$pt['label']} for sale", 'motors_listing_types' );
			}
		}

		return $compact;
	}

	public function stm_query_args_post_type( $args, $source ) {
		if ( $this->listings ) {
			foreach ( $this->listings as $key => $listing ) {
				if ( $listing['slug'] == get_post_type() ) {
					$args['post_type'] = get_post_type();
				}
				if ( ! empty( $source['posttype'] ) && $listing['slug'] == $source['posttype'] ) {
					$args['post_type'] = $listing['slug'];
				}
			}
		}
		return $args;
	}

	public function stm_replace_vehicle_listing_option( $value, $option ) {
		$post_type = $this->stm_get_current_listing();

		$bypass = get_option( 'mlt_bypass_options_hook' );

		if ( ! $post_type || $post_type == stm_listings_post_type() || $bypass ) {
			delete_option( 'mlt_bypass_options_hook' );
			return $value;
		}

		delete_option( 'mlt_bypass_options_hook' );

		if ( ! empty( $this->listings ) ) {
			foreach ( $this->listings as $key => $listing ) {
				if ( $post_type['slug'] == $listing['slug'] ) {
					return get_option( "stm_{$listing['slug']}_options", array() );
				}
			}
		}

		return $value;
	}

	public function stm_add_post_type_rule() {
		add_rewrite_rule(
			"^{$this->add_listing_name}[/]?$",
			'index.php?add_custom_listing=index',
			'top'
		);
	}

	public function stm_add_post_type_select_white_list( $query_vars ) {
		$query_vars[] = 'add_custom_listing';
		return $query_vars;
	}

	function stm_add_post_type_select( $template ) {
		if ( get_query_var( 'add_custom_listing' ) == false || get_query_var( 'add_custom_listing' ) == '' ) {
			return $template;
		}
		return MULTILISTING_PATH . '/templates/select-add-listing.php';
	}

	protected function registerListings( $listing ) {
		register_post_type(
			$listing['slug'],
			array(
				'labels'             => array(
					'name'               => __( $listing['label'], 'motors_listing_types' ),
					'singular_name'      => __( $listing['label'], 'motors_listing_types' ),
					'add_new'            => __( 'Add New', 'motors_listing_types' ),
					'add_new_item'       => __( 'Add New Item', 'motors_listing_types' ),
					'edit_item'          => __( 'Edit Item', 'motors_listing_types' ),
					'new_item'           => __( 'New Item', 'motors_listing_types' ),
					'all_items'          => __( 'All Items', 'motors_listing_types' ),
					'view_item'          => __( 'View Item', 'motors_listing_types' ),
					'search_items'       => __( 'Search Items', 'motors_listing_types' ),
					'not_found'          => __( 'No items found', 'motors_listing_types' ),
					'not_found_in_trash' => __( 'No items found in Trash', 'motors_listing_types' ),
					'parent_item_colon'  => '',
					'menu_name'          => __( $listing['label'], 'motors_listing_types' ),
				),
				'menu_icon'          => 'dashicons-location-alt',
				'show_in_nav_menus'  => true,
				'supports'           => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'author', 'revisions' ),
				'rewrite'            => array( 'slug' => $listing['slug'] ),
				'has_archive'        => true,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'hierarchical'       => false,
				'menu_position'      => 4,
			)
		);
	}

	public function stm_insert_post_add_post_type( $post_data ) {
		if ( ! empty( $_REQUEST['post_type'] ) ) {
			$post_data['post_type'] = esc_attr( $_REQUEST['post_type'] );
		}

		return $post_data;
	}

	public function stm_replace_add_listing_notifications_item( $response ) {
		$post_type = esc_attr( $_REQUEST['post_type'] );
		if ( empty( $post_type ) ) {
			return $response;
		}

		if ( ! empty( $post_type ) ) {
			$response['post_type'] = $post_type;
		}

		return $response;
	}

	public function stm_replace_add_listing_notifications_media( $response ) {
		if ( ! empty( $_REQUEST['post_id'] ) ) {
			$post_type = get_post_type( $_REQUEST['post_id'] );
		}
		if ( $post_type == 'listings' ) {
			return $response;
		}

		$redirectType = ( isset( $_POST['redirect_type'] ) ) ? $_POST['redirect_type'] : '';
		if ( empty( $redirectType ) || $redirectType !== 'pay' ) {
			$user_id         = get_current_user_id();
			$response['url'] = esc_url(
				add_query_arg(
					array( 'page' => $post_type ),
					get_author_posts_url( $user_id )
				)
			);
		}
		return $response;
	}

	public function stm_listing_types_similar_listing_query( $query ) {
		$post_type = get_post_type( get_the_id() );

		if ( $post_type !== 'listings' ) {
			$query['post_type'] = $post_type;
		}

		return $query;
	}

	public function stm_filter_listing_link( $listing_link, $filters ) {
		if ( ! empty( $filters['ml_post_type'] ) ) {

			$listing_link = get_post_type_archive_link( $filters['ml_post_type'] );

			unset( $filters['ml_post_type'] );

			if ( ! empty( $listing_link ) ) {
				$qs = array();
				foreach ( $filters as $key => $val ) {
					$info = stm_get_all_by_slug( preg_replace( '/^(min_|max_)/', '', $key ) );
					$val  = ( is_array( $val ) ) ? implode( ',', $val ) : $val;
					$qs[] = $key . ( ! empty( $info['listing_rows_numbers'] ) ? '[]=' : '=' ) . $val;
				}

				if ( count( $qs ) ) {
					$listing_link .= ( strpos( $listing_link, '?' ) ? '&' : '?' ) . join( '&', $qs );
				}
			}
		}

		return $listing_link;
	}

	public function stm_binding_results_return_200( $r ) {
		header( 'HTTP/1.1 200 OK' );
		return $r;
	}

	public function stm_register_perpay() {
		remove_filter( 'woocommerce_data_stores', 'woocommerce_data_stores' );
		add_filter( 'woocommerce_data_stores', array( $this, 'woocommerce_data_stores' ) );
	}

	public function woocommerce_data_stores( $stores ) {
		require_once MULTILISTING_PATH . '/classes/perpay.class.php';
		$stores['product'] = 'STM_Multi_Listing_Data_Store_CPT';
		return $stores;
	}

	public function stm_after_listing_gallery_saved( $post_id ) {
		set_query_var( 'listings_type', get_post_type( $post_id ) );
	}

	public function stm_listings_inventory_page_id( $page_id ) {
		global $wp_query;
		if ( ! empty( $wp_query->get( 'listings_type' ) ) ) {
			$options = get_option( 'stm_motors_listing_types', array() );

			if ( isset( $options['multilisting_repeater'] ) && ! empty( $options['multilisting_repeater'] ) ) {
				foreach ( $options['multilisting_repeater'] as $key => $listing ) {
					if ( $listing['slug'] == $wp_query->get( 'listings_type' ) ) {
						if ( ! empty( $listing['inventory_page'] ) ) {
							return intval( $listing['inventory_page'] );
						}
					}
				}
			}
		}

		return $page_id;
	}
}

new HooksMultiListing();
