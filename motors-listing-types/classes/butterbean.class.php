<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class ButterBeanMultiListing extends STMMultiListing {

	public function __construct() {
		parent::__construct();
		add_action( 'butterbean_register', array( $this, 'register_manager' ), 9, 2 );
		add_action( 'butterbean_enqueue_scripts', array( $this, 'enqueue' ), 100 );
	}

	public function enqueue() {
		wp_enqueue_script(
			'stm-theme-multiselect-multilisting',
			STM_LISTINGS_URL . '/assets/js/jquery.multi-select.js',
			array( 'jquery' )
		);

		/*Google places*/
		$google_api_key = get_theme_mod( 'google_api_key', '' );
		$google_api_map = 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=places';

		if ( ! wp_script_is( 'stm_gmap', 'registered' ) ) {
			wp_register_script( 'stm_gmap', $google_api_map, array( 'jquery' ), null, true );
		}

		if ( ! wp_script_is( 'stm-google-places', 'enqueued' ) ) {
			wp_enqueue_script( 'stm-google-places', STM_LISTINGS_URL . '/assets/js/stm-google-places.js', 'stm_gmap', null, true );
		}

	}

	public function fields() {
		$fields = array();
		if ( is_array( $this->listings ) ) {
			foreach ( $this->listings as $key => $listing ) {
				$fields[ $listing['slug'] ] = array(
					'manager'  => array(
						'label'     => esc_html__( "{$listing['label']} manager", 'motors_listing_types' ),
						'post_type' => $listing['slug'],
						'context'   => 'normal',
						'priority'  => 'high',
					),
					'sections' => array(
						'options'             => array(
							'label' => esc_html__( 'Details', 'motors_listing_types' ),
							'icon'  => 'fas fa-list-ul',
						),
						'features'            => array(
							'label' => esc_html__( 'Options', 'motors_listing_types' ),
							'icon'  => 'fa fa-dashboard',
						),
						'additional_features' => array(
							'label' => esc_html__( 'Features', 'motors_listing_types' ),
							'icon'  => 'fa fa-check-square-o',
						),
						'price'               => array(
							'label' => esc_html__( 'Prices', 'motors_listing_types' ),
							'icon'  => 'fa fa-dollar',
						),
						'offers'              => array(
							'label' => esc_html__( 'Specials', 'motors_listing_types' ),
							'icon'  => 'fa fa-bookmark',
						),
						'media'               => array(
							'label' => esc_html__( 'Images', 'motors_listing_types' ),
							'icon'  => 'fa fa-image',
						),
						'video'               => array(
							'label' => esc_html__( 'Video', 'motors_listing_types' ),
							'icon'  => 'fa fa-video-camera',
						),
					),
					'controls' => array(
						/*Special Cars*/
						'special_car'               => array(
							'type'        => 'checkbox',
							'section'     => 'offers',
							'value'       => 'on',
							'label'       => esc_html__( 'Special offer', 'motors_listing_types' ),
							'preview'     => 'special',
							'description' => esc_html__( 'Show this item in \'special offers carousel\' module and Featured Listing on Classified layout', 'motors_listing_types' ),
							'attr'        => array( 'class' => 'widefat' ),
						),
						'badge_text'                => array(
							'type'    => 'text',
							'section' => 'offers',
							'preview' => 'special_label',
							'label'   => esc_html__( 'Enable badge', 'motors_listing_types' ),
							'attr'    => array(
								'data-dep'    => 'special_car',
								'data-value'  => 'true',
								'placeholder' => esc_html__( 'Enter badge text', 'motors_listing_types' ),
								'class'       => 'widefat',
							),
						),
						'badge_bg_color'            => array(
							'type'    => 'color',
							'section' => 'offers',
							'label'   => 'Badge background color',
						),
						'special_text'              => array(
							'type'    => 'text',
							'section' => 'offers',
							'preview' => 'special-txt',
							'label'   => esc_html__( 'Special offer text', 'motors_listing_types' ),
							'attr'    => array(
								'class'      => 'widefat',
								'data-dep'   => 'special_car',
								'data-value' => 'true',
							),
						),
						'special_image'             => array(
							'type'        => 'image',
							'section'     => 'offers',
							'preview'     => 'special-bnr',
							'label'       => 'Special Offer Banner',
							'description' => esc_html__( 'Banner will appear instead of listing image under \'special offers carousel\' module.', 'motors_listing_types' ),
							'size'        => 'thumbnail',
							'attr'        => array(
								'data-dep'   => 'special_car',
								'data-value' => 'true',
							),
						),
						/*Media*/
						'gallery'                   => array(
							'type'        => 'gallery',
							'section'     => 'media',
							'label'       => 'Image Gallery',
							'description' => esc_html__( 'Create photo gallery for listing item here', 'motors_listing_types' ),
							'size'        => 'stm-img-796-466',
						),
						/*Video*/
						'video_preview'             => array(
							'type'        => 'image',
							'section'     => 'video',
							'label'       => 'Video Preview',
							'description' => esc_html__( 'Image for video preview. Please note that video will start playing in a pop-up window.', 'motors_listing_types' ),
							'size'        => 'stm-img-796-466',
						),
						'gallery_video'             => array(
							'type'    => 'text',
							'section' => 'video',
							'label'   => 'Gallery Video (Embed video URL)',
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'gallery_videos'            => array(
							'type'    => 'repeater',
							'section' => 'video',
							'label'   => 'Additional videos (Embed video URL)',
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'gallery_videos_posters'    => array(
							'type'        => 'gallery',
							'section'     => 'video',
							'label'       => 'Additional video posters',
							'description' => esc_html__( 'Used in STM Boat Videos module', 'motors_listing_types' ),
							'size'        => 'stm-img-796-466',
						),
						/*Additional features*/
						'additional_features'       => array(
							'type'    => 'checkbox_repeater',
							'section' => 'additional_features',
							'label'   => 'Additional features',
							'preview' => 'features',
						),
						/*Price*/
						'price'                     => array(
							'type'    => 'text',
							'section' => 'price',
							'label'   => esc_html__( 'Price', 'motors_listing_types' ),
							'preview' => 'price_msrp',
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'sale_price'                => array(
							'type'    => 'text',
							'section' => 'price',
							'preview' => 'price',
							'label'   => esc_html__( 'Sale Price', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'stm_genuine_price'         => array(
							'type'    => 'hidden',
							'section' => 'price',
							'preview' => 'price',
							'label'   => 'Genuine Price',
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'regular_price_label'       => array(
							'type'    => 'text',
							'section' => 'price',
							'label'   => esc_html__( 'Regular price label', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'regular_price_description' => array(
							'type'    => 'text',
							'section' => 'price',
							'label'   => esc_html__( 'Regular price description', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'special_price_label'       => array(
							'type'    => 'text',
							'section' => 'price',
							'label'   => esc_html__( 'Special price label', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'instant_savings_label'     => array(
							'type'    => 'text',
							'section' => 'price',
							'label'   => esc_html__( 'Instant savings label', 'motors_listing_types' ),
							'preview' => 'price_instant',
							'attr'    => array(
								'class' => 'widefat',
							),
						),
						'car_price_form_label'      => array(
							'type'        => 'text',
							'section'     => 'price',
							'label'       => esc_html__( 'Custom label', 'motors_listing_types' ),
							'preview'     => 'price_request',
							'description' => esc_html__( 'This text will appear instead of price', 'motors_listing_types' ),
							'attr'        => array(
								'class' => 'widefat',
							),
						),
						'car_price_form'            => array(
							'type'        => 'checkbox',
							'section'     => 'price',
							'value'       => 'on',
							'label'       => esc_html__( 'Listing price form', 'motors_listing_types' ),
							'description' => esc_html__( 'Enable/Disable \'Request a price\' form', 'motors_listing_types' ),
							'attr'        => array( 'class' => 'widefat' ),
						),
						'car_mark_as_sold'          => array(
							'type'        => 'checkbox',
							'section'     => 'price',
							'value'       => 'on',
							'label'       => esc_html__( 'Mark as sold', 'motors_listing_types' ),
							'description' => esc_html__( 'Enable/Disable \'Car sold\'', 'motors_listing_types' ),
							'attr'        => array( 'class' => 'widefat' ),
						),
						/*Options*/
						'automanager_id'            => array(
							'type'    => 'hidden',
							'section' => 'options',
							'label'   => esc_html__( 'Listing ID', 'motors_listing_types' ),
							'attr'    => array( 'class' => 'widefat' ),
						),
						'stock_number'              => array(
							'type'    => 'text',
							'section' => 'options',
							'preview' => 'stockid',
							'label'   => esc_html__( 'Stock number', 'motors_listing_types' ),
							'attr'    => array( 'class' => 'widefat' ),
						),
						'vin_number'                => array(
							'type'    => 'text',
							'section' => 'options',
							'preview' => 'vin',
							'label'   => esc_html__( 'VIN number', 'motors_listing_types' ),
							'attr'    => array( 'class' => 'widefat' ),
						),
						'stm_car_location'          => array(
							'type'    => 'location',
							'section' => 'options',
							'label'   => esc_html__( 'Listing location', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
								'id'    => 'stm_car_location',
							),
						),
						'stm_lat_car_admin'         => array(
							'type'    => 'text',
							'section' => 'options',
							'label'   => esc_html__( 'Latitude', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
								'id'    => 'stm_lat_car_admin',
							),
						),
						'stm_lng_car_admin'         => array(
							'type'    => 'text',
							'section' => 'options',
							'label'   => esc_html__( 'Longitude', 'motors_listing_types' ),
							'attr'    => array(
								'class' => 'widefat',
								'id'    => 'stm_lng_car_admin',
							),
						),
						'registration_date'         => array(
							'type'        => 'datepicker',
							'section'     => 'options',
							'label'       => esc_html__( 'Registration date', 'motors_listing_types' ),
							'preview'     => 'regist',
							'description' => esc_html__( 'Only in classified layout', 'motors_listing_types' ),
							'attr'        => array( 'class' => 'widefat' ),
						),
						'history'                   => array(
							'type'        => 'text',
							'section'     => 'options',
							'label'       => esc_html__( 'Certificate name', 'motors_listing_types' ),
							'description' => esc_html__( 'Only in classified layout', 'motors_listing_types' ),
							'attr'        => array( 'class' => 'widefat' ),
							'preview'     => 'history-txt',
						),
						'history_link'              => array(
							'type'    => 'text',
							'section' => 'options',
							'label'   => esc_html__( 'Certificate 1 Link', 'motors_listing_types' ),
							'attr'    => array( 'class' => 'widefat' ),
						),
						'certified_logo_1'          => array(
							'type'    => 'image',
							'section' => 'options',
							'label'   => 'Certified 1 Logo',
							'size'    => 'thumbnail',
							'preview' => 'CERT1',
						),
						'certified_logo_2_link'     => array(
							'type'    => 'text',
							'section' => 'options',
							'label'   => esc_html__( 'Certificate 2 Link', 'motors_listing_types' ),
							'attr'    => array( 'class' => 'widefat' ),
						),
						'certified_logo_2'          => array(
							'type'    => 'image',
							'section' => 'options',
							'label'   => 'Certified 2 Logo',
							'size'    => 'thumbnail',
							'preview' => 'CERT2',
						),
						'car_brochure'              => array(
							'type'    => 'file',
							'section' => 'options',
							'label'   => esc_html__( 'Brochure (.pdf)', 'motors_listing_types' ),
							'preview' => 'pdf',
							'attr'    => array(
								'class'     => 'widefat',
								'data-type' => 'application/pdf',
							),
						),
						'stm_car_user'              => array(
							'type'    => 'select',
							'section' => 'options',
							'label'   => __( 'Created by', 'motors_listing_types' ),
							'choices' => $this->get_user_list(),
							'attr'    => array( 'class' => 'widefat' ),
						),
						'stm_car_views'             => array(
							'type'        => 'text',
							'section'     => 'options',
							'label'       => esc_html__( 'Amount of Views', 'motors_listing_types' ),
							'description' => __( 'Visible for item author in classified layout', 'motors_listing_types' ),
							'attr'        => array(
								'class'    => 'widefat',
								'readonly' => 'readonly',
								'reset'    => 'all',
							),
						),
						'stm_phone_reveals'         => array(
							'type'        => 'text',
							'section'     => 'options',
							'label'       => esc_html__( 'Amount of Phone Views', 'motors_listing_types' ),
							'description' => __( 'Visible for item author in classified layout', 'motors_listing_types' ),
							'attr'        => array(
								'class'    => 'widefat',
								'readonly' => 'readonly',
								'reset'    => 'all',
							),
						),
					),
					'settings' => array(
						/*Special Cars*/
						'special_car'               => array(
							'sanitize_callback' => 'stm_listings_validate_checkbox',
						),
						'special_text'              => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'special_image'             => array(
							'sanitize_callback' => 'stm_listings_validate_image',
						),
						'badge_text'                => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'badge_bg_color'            => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						/*Media*/
						'gallery'                   => array(
							'sanitize_callback' => 'stm_listings_validate_gallery',
						),
						/*Video*/
						'video_preview'             => array(
							'sanitize_callback' => 'stm_listings_validate_image',
						),
						'gallery_video'             => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'gallery_videos'            => array(
							'sanitize_callback' => 'stm_listings_validate_repeater_videos',
						),
						'gallery_videos_posters'    => array(
							'sanitize_callback' => 'stm_gallery_videos_posters',
						),
						/*Price*/
						'price'                     => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'sale_price'                => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'stm_genuine_price'         => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'regular_price_label'       => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'regular_price_description' => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'special_price_label'       => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'instant_savings_label'     => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'car_price_form'            => array(
							'sanitize_callback' => 'stm_listings_validate_checkbox',
						),
						'car_mark_as_sold'          => array(
							'sanitize_callback' => 'stm_listings_validate_checkbox',
						),
						'car_price_form_label'      => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						/*Options*/
						'automanager_id'            => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'vin_number'                => array(
							'label' => __( 'VIN number', 'stm_vehicles_listing' ),
							'type'  => 'text',
						),
						'stm_car_user'              => array(
							'sanitize_callback' => 'sanitize_key',
						),
						'stm_car_views'             => array(
							'sanitize_callback' => 'sanitize_key',
						),
						'stm_phone_reveals'         => array(
							'sanitize_callback' => 'sanitize_key',
						),
						'stock_number'              => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'serial_number'             => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'registration_number'       => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'stm_car_location'          => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'stm_lat_car_admin'         => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'stm_lng_car_admin'         => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'vin_number'                => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'city_mpg'                  => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'highway_mpg'               => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'registration_date'         => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'history'                   => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'history_link'              => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'certified_logo_1'          => array(
							'sanitize_callback' => 'stm_listings_validate_image',
						),
						'certified_logo_2_link'     => array(
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						),
						'certified_logo_2'          => array(
							'sanitize_callback' => 'stm_listings_validate_image',
						),
						'car_brochure'              => array(
							'sanitize_callback' => 'stm_listings_validate_image',
						),
						'additional_features'       => array(
							'sanitize_callback' => 'stm_listings_validate_repeater',
						),
					),
				);
			}
		}

		return apply_filters( 'stm_multilisting_butterbean_fields', $fields );
	}

	public function register_manager( $butterbean, $post_type ) {
		if ( is_array( $this->listings ) ) {
			foreach ( $this->listings as $key => $listing ) {

				$slug   = $listing['slug'];
				$fields = $this->fields();
				if ( empty( $fields [ $slug ] ) ) {
					continue;
				}

				$butterbean->register_manager( "{$slug}_manager", $fields[ $slug ]['manager'] );
				$manager = $butterbean->get_manager( "{$slug}_manager" );
				if ( ! $manager ) {
					return;
				}

				/*Register sections*/
				if ( ! empty( $fields[ $slug ]['sections'] ) ) {
					foreach ( $fields[ $slug ]['sections'] as $opt_name => $option ) {
						$manager->register_section( $opt_name, $option );
					}
				}

				/*Registering controls*/
				if ( ! empty( $fields[ $slug ]['controls'] ) ) {
					foreach ( $fields[ $slug ]['controls'] as $ctrl_name => $option ) {
						$manager->register_control( $ctrl_name, $option );
					}
				}

				/*Registering Setting*/
				if ( ! empty( $fields[ $slug ]['settings'] ) ) {
					foreach ( $fields[ $slug ]['settings'] as $set_name => $option ) {
						$manager->register_setting( $set_name, $option );
					}
				}

				/*Features*/
				$options = get_option( "stm_{$listing['slug']}_options" );

				if ( ! empty( $options ) ) {
					$args = array(
						'orderby'    => 'name',
						'order'      => 'ASC',
						'hide_empty' => false,
						'fields'     => 'all',
						'pad_counts' => false,
					);

					/*Add multiselects*/
					foreach ( $options as $key => $option ) {

						if ( $option['slug'] == 'price' || ( isset( $option['listing_price_field'] ) && $option['listing_price_field'] == true ) ) {
							continue;
						}

						$terms = get_terms( $option['slug'], $args );

						$single_term = array(
							'' => 'None',
						);

						if ( $terms ) {
							foreach ( $terms as $tax_key => $taxonomy ) {
								if ( ! empty( $taxonomy ) ) {
									$single_term[ $taxonomy->slug ] = $taxonomy->name;
								}
							}
						}

						if ( empty( $option['numeric'] ) ) {
							$manager->register_control(
								$option['slug'],
								array(
									'type'    => 'multiselect',
									'section' => 'features',
									'label'   => $option['plural_name'],
									'choices' => $single_term,
								)
							);

							$manager->register_setting(
								$option['slug'],
								array(
									'sanitize_callback' => 'stm_listings_multiselect',
								)
							);
						} else { /*Add number fields*/
							$manager->register_control(
								$option['slug'],
								array(
									'type'    => 'text',
									'section' => 'features',
									'label'   => esc_html__( $option['single_name'], 'motors_listing_types' ),
									'attr'    => array( 'class' => 'widefat' ),
								)
							);

							$manager->register_setting(
								$option['slug'],
								array(
									'sanitize_callback' => 'wp_filter_nohtml_kses',
								)
							);
						}
					}
				}
			}
		}
	}

	private function get_user_list() {
		$users_args     = array(
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
			'who'          => '',
		);
		$users          = get_users( $users_args );
		$users_dropdown = array(
			'' => esc_html__( 'Not assigned', 'stm_vehicles_listing' ),
		);
		if ( ! is_wp_error( $users ) ) {
			foreach ( $users as $user ) {
				$users_dropdown[ $user->data->ID ] = $user->data->user_login;
			}
		}

		return $users_dropdown;
	}
}

new ButterBeanMultiListing();
