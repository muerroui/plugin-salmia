<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once STM_LISTINGS_PATH . '/includes/admin/butterbean_helpers.php';
do_action('stm_add_new_auto_complete');
add_action( 'butterbean_register', 'stm_listings_register_manager', 10, 2 );

function stm_listings_register_manager( $butterbean, $post_type )
{

    $listings = stm_listings_post_type();

    // Register managers, sections, controls, and settings here.
    if ( $post_type !== $listings ) {
        return;
    }

    $butterbean->register_manager(
        'stm_car_manager',
        array(
            'label' => esc_html__( 'Listing manager', 'stm_vehicles_listing' ),
            'post_type' => $listings,
            'context' => 'normal',
            'priority' => 'high'
        )
    );

    $manager = $butterbean->get_manager( 'stm_car_manager' );

    /*Register sections*/
    $manager->register_section(
        'stm_options',
        array(
            'label' => esc_html__( 'Details', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-list-ul'
        )
    );

    $manager->register_section(
        'stm_features',
        array(
            'label' => esc_html__( 'Options', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-dashboard'
        )
    );

    $manager->register_section(
        'stm_additional_features',
        array(
            'label' => esc_html__( 'Features', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-check-square-o'
        )
    );

    $manager->register_section(
        'stm_price',
        array(
            'label' => esc_html__( 'Prices', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-dollar'
        )
    );

    $manager->register_section(
        'special_offers',
        array(
            'label' => esc_html__( 'Specials', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-bookmark'
        )
    );

    $manager->register_section(
        'stm_media',
        array(
            'label' => esc_html__( 'Images', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-image'
        )
    );

    $manager->register_section(
        'stm_video',
        array(
            'label' => esc_html__( 'Video', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-video-camera'
        )
    );

    /*Registering controls*/

    /*Special Cars*/

    $manager->register_control(
        'special_car',
        array(
            'type' => 'checkbox',
            'section' => 'special_offers',
            'value' => 'on',
            'label' => esc_html__( 'Special offer', 'stm_vehicles_listing' ),
            'preview' => 'special',
            'description' => esc_html__( 'Show this item in \'special offers carousel\' module and Featured Listing on Classified layout', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    $manager->register_control(
        'badge_text',
        array(
            'type' => 'text',
            'section' => 'special_offers',
            'preview' => 'special_label',
            'label' => esc_html__( 'Enable badge', 'stm_vehicles_listing' ),
            'attr' => array(
                'data-dep' => 'special_car',
                'data-value' => 'true',
                'placeholder' => esc_html__( 'Enter badge text', 'stm_vehicles_listing' ),
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'badge_bg_color',
        array(
            'type' => 'color',
            'section' => 'special_offers',
            'label' => 'Badge background color'
        )
    );

    $manager->register_control(
        'special_text',
        array(
            'type' => 'text',
            'section' => 'special_offers',
            'preview' => 'special-txt',
            'label' => esc_html__( 'Special offer text', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
                'data-dep' => 'special_car',
                'data-value' => 'true'
            )
        )
    );

    $manager->register_control(
        'special_image',
        array(
            'type' => 'image',
            'section' => 'special_offers',
            'preview' => 'special-bnr',
            'label' => 'Special Offer Banner',
            'description' => esc_html__( 'Banner will appear instead of listing image under \'special offers carousel\' module.', 'stm_vehicles_listing' ),
            'size' => 'thumbnail',
            'attr' => array(
                'data-dep' => 'special_car',
                'data-value' => 'true'
            )
        )
    );


    /*Media*/
    $manager->register_control(
        'gallery',
        array(
            'type' => 'gallery',
            'section' => 'stm_media',
            'label' => 'Image Gallery',
            'description' => esc_html__( 'Create photo gallery for listing item here', 'stm_vehicles_listing' ),
            'size' => 'stm-img-796-466'
        )
    );

    /*Video*/

    $manager->register_control(
        'video_preview',
        array(
            'type' => 'image',
            'section' => 'stm_video',
            'label' => 'Video Preview',
            'description' => esc_html__( 'Image for video preview. Please note that video will start playing in a pop-up window.', 'stm_vehicles_listing' ),
            'size' => 'stm-img-796-466',
        )
    );

    $manager->register_control(
        'gallery_video',
        array(
            'type' => 'text',
            'section' => 'stm_video',
            'label' => 'Gallery Video (Embed video URL)',
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'gallery_videos',
        array(
            'type' => 'repeater',
            'section' => 'stm_video',
            'label' => 'Additional videos (Embed video URL)',
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'gallery_videos_posters',
        array(
            'type' => 'gallery',
            'section' => 'stm_video',
            'label' => 'Additional video posters',
            'description' => esc_html__( 'Used in STM Boat Videos module', 'stm_vehicles_listing' ),
            'size' => 'stm-img-796-466'
        )
    );

    /*Additional features*/
    $manager->register_control(
        'additional_features',
        array(
            'type' => 'checkbox_repeater',
            'section' => 'stm_additional_features',
            'label' => 'Additional features',
            'preview' => 'features'
        )
    );

    /*Price*/
    $manager->register_control(
        'price',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'label' => esc_html__( 'Price', 'stm_vehicles_listing' ),
            'preview' => 'price_msrp',
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'sale_price',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'preview' => 'price',
            'label' => esc_html__( 'Sale Price', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    if(function_exists('stm_is_equipment') && stm_is_equipment()) {
		/*Price*/
		$manager->register_control(
			'rent_price',
			array(
				'type' => 'text',
				'section' => 'stm_price',
				'label' => esc_html__( 'Rent Price', 'stm_vehicles_listing' ),
				'preview' => '',
				'attr' => array(
					'class' => 'widefat',
				)
			)
		);

		$manager->register_control(
			'sale_rent_price',
			array(
				'type' => 'text',
				'section' => 'stm_price',
				'preview' => '',
				'label' => esc_html__( 'Sale Rent Price', 'stm_vehicles_listing' ),
				'attr' => array(
					'class' => 'widefat',
				)
			)
		);

		$manager->register_control(
			'stm_genuine_rent_price',
			array(
				'type' => 'hidden',
				'section' => 'stm_price',
				'preview' => '',
				'label' => 'Genuine Rent Price',
				'attr' => array(
					'class' => 'widefat',
				)
			)
		);
	}

    $manager->register_control(
        'stm_genuine_price',
        array(
            'type' => 'hidden',
            'section' => 'stm_price',
            'preview' => 'price',
            'label' => 'Genuine Price',
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'regular_price_label',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'label' => esc_html__( 'Regular price label', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'regular_price_description',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'label' => esc_html__( 'Regular price description', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'special_price_label',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'label' => esc_html__( 'Special price label', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'instant_savings_label',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'label' => esc_html__( 'Instant savings label', 'stm_vehicles_listing' ),
            'preview' => 'price_instant',
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'car_price_form_label',
        array(
            'type' => 'text',
            'section' => 'stm_price',
            'label' => esc_html__( 'Custom label', 'stm_vehicles_listing' ),
            'preview' => 'price_request',
            'description' => esc_html__( 'This text will appear instead of price', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'car_price_form',
        array(
            'type' => 'checkbox',
            'section' => 'stm_price',
            'value' => 'on',
            'label' => esc_html__( 'Listing price form', 'stm_vehicles_listing' ),
            'description' => esc_html__( 'Enable/Disable \'Request a price\' form', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    if(stm_is_dealer_two()) {
		if(stm_me_get_wpcfto_mod('enable_woo_online', false)) {
			$manager->register_control(
				'car_mark_woo_online',
				array(
					'type' => 'checkbox',
					'section' => 'stm_price',
					'value' => 'on',
					'label' => esc_html__( 'Sell a car Online', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Enable/Disable Sell a car Online', 'stm_vehicles_listing' ),
					'attr' => array(
                        'data-dep' => 'car_mark_as_sold',
                        'data-value' => 'false',
                        'class' => 'widefat'
                    )
				)
			);

            $manager->register_control(
				'stm_car_stock',
				array(
					'type' => 'number',
					'section' => 'stm_price',
					'value' => '1',
					'label' => esc_html__( 'Car Stock', 'stm_vehicles_listing' ),
					'attr' => array(
                        'data-dep' => 'car_mark_woo_online',
                        'data-value' => 'true',
                        'placeholder' => esc_html__( 'Enter amount in stock', 'stm_vehicles_listing' ),
                        'class' => 'widefat'
                    )
				)
			);
		}
	}

    if ( function_exists('stm_sold_status_enabled') && stm_sold_status_enabled() ) {
        $manager->register_control(
            'car_mark_as_sold',
            array(
                'type' => 'checkbox',
                'section' => 'stm_price',
                'value' => 'on',
                'label' => esc_html__( 'Mark as sold', 'stm_vehicles_listing' ),
                'description' => esc_html__( 'Enable/Disable \'Car sold\'', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );
    }

    /*Options*/
    $manager->register_control(
        'automanager_id',
        array(
            'type' => 'hidden',
            'section' => 'stm_options',
            'label' => esc_html__( 'Listing ID', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    if ( stm_is_aircrafts() ) {
        $manager->register_control(
            'serial_number',
            array(
                'type' => 'text',
                'section' => 'stm_options',
                'label' => esc_html__( 'Serial number', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );
        $manager->register_control(
            'registration_number',
            array(
                'type' => 'text',
                'section' => 'stm_options',
                'label' => esc_html__( 'Registration number', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );
    } else {
        $manager->register_control(
            'stock_number',
            array(
                'type' => 'text',
                'section' => 'stm_options',
                'preview' => 'stockid',
                'label' => esc_html__( 'Stock number', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );
    }


    $manager->register_control(
        'stm_car_location',
        array(
            'type' => 'location',
            'section' => 'stm_options',
            'label' => esc_html__( 'Listing location', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
                'id' => 'stm_car_location'
            )
        )
    );

    $manager->register_control(
        'stm_lat_car_admin',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Latitude', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
                'id' => 'stm_lat_car_admin'
            )
        )
    );

    $manager->register_control(
        'stm_lng_car_admin',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Longtitude', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
                'id' => 'stm_lng_car_admin'
            )
        )
    );

    if ( !stm_is_aircrafts() ) {
        $manager->register_control(
            'vin_number',
            array(
                'type' => 'text',
                'section' => 'stm_options',
                'preview' => 'vin',
                'label' => esc_html__( 'VIN number', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );

        $manager->register_control(
            'city_mpg',
            array(
                'type' => 'text',
                'section' => 'stm_options',
                'label' => esc_html__( 'City MPG', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' ),
                'preview' => 'mpg'
            )
        );

        $manager->register_control(
            'highway_mpg',
            array(
                'type' => 'text',
                'section' => 'stm_options',
                'label' => esc_html__( 'Highway MPG', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' ),
                'preview' => 'mpg'
            )
        );
    }

    $manager->register_control(
        'registration_date',
        array(
            'type' => 'datepicker',
            'section' => 'stm_options',
            'label' => esc_html__( 'Registration date', 'stm_vehicles_listing' ),
            'preview' => 'regist',
            'description' => esc_html__( 'Only in classified layout', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    $manager->register_control(
        'history',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Certificate name', 'stm_vehicles_listing' ),
            'description' => esc_html__( 'Only in classified layout', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' ),
            'preview' => 'history-txt'
        )
    );

    $manager->register_control(
        'history_link',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Certificate 1 Link', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    $manager->register_control(
        'certified_logo_1',
        array(
            'type' => 'image',
            'section' => 'stm_options',
            'label' => 'Certified 1 Logo',
            'size' => 'thumbnail',
            'preview' => 'CERT1'
        )
    );

    $manager->register_control(
        'certified_logo_2_link',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Certificate 2 Link', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    $manager->register_control(
        'certified_logo_2',
        array(
            'type' => 'image',
            'section' => 'stm_options',
            'label' => 'Certified 2 Logo',
            'size' => 'thumbnail',
            'preview' => 'CERT2'
        )
    );

    $manager->register_control(
        'car_brochure',
        array(
            'type' => 'file',
            'section' => 'stm_options',
            'label' => esc_html__( 'Brochure (.pdf)', 'stm_vehicles_listing' ),
            'preview' => 'pdf',
            'attr' => array(
                'class' => 'widefat',
                'data-type' => 'application/pdf',
            )
        )
    );

    $manager->register_control(
        'stm_car_user',
        array(
            'type' => 'select',
            'section' => 'stm_options',
            'label' => __( 'Created by', 'stm_vehicles_listing' ),
            'choices' => stm_listings_get_user_list(),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    $manager->register_control(
        'stm_car_views',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Amount of Car Views', 'stm_vehicles_listing' ),
            'description' => __( 'Visible for item author in classified layout', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
                'readonly' => 'readonly',
                'reset' => 'all'
            )
        )
    );

    $manager->register_control(
        'stm_phone_reveals',
        array(
            'type' => 'text',
            'section' => 'stm_options',
            'label' => esc_html__( 'Amount of Phone Views', 'stm_vehicles_listing' ),
            'description' => __( 'Visible for item author in classified layout', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
                'readonly' => 'readonly',
                'reset' => 'all'
            )
        )
    );

    /*Registering Setting*/

    /*Special Cars*/

    $manager->register_setting(
        'special_car',
        array(
            'sanitize_callback' => 'stm_listings_validate_checkbox'
        )
    );

    $manager->register_setting(
        'special_text',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'special_image',
        array( 'sanitize_callback' => 'stm_listings_validate_image' )
    );

    $manager->register_setting(
        'badge_text',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'badge_bg_color',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );


    /*Media*/

    $manager->register_setting(
        'gallery',
        array( 'sanitize_callback' => 'stm_listings_validate_gallery' )
    );

    /*Video*/
    $manager->register_setting(
        'video_preview',
        array( 'sanitize_callback' => 'stm_listings_validate_image' )
    );

    $manager->register_setting(
        'gallery_video',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'gallery_videos',
        array( 'sanitize_callback' => 'stm_listings_validate_repeater_videos' )
    );

    $manager->register_setting(
        'gallery_videos_posters',
        array( 'sanitize_callback' => 'stm_gallery_videos_posters' )
    );

    /*Price*/
    $manager->register_setting(
        'price',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'sale_price',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'stm_genuine_price',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    if(stm_is_equipment()) {
		$manager->register_setting( 'rent_price', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );

		$manager->register_setting( 'sale_rent_price', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );

		$manager->register_setting( 'stm_genuine_rent_price', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );
	}

    $manager->register_setting(
        'regular_price_label',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'regular_price_description',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'special_price_label',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'instant_savings_label',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'car_price_form',
        array(
            'sanitize_callback' => 'stm_listings_validate_checkbox'
        )
    );

    $manager->register_setting(
        'car_mark_woo_online',
        array(
            'sanitize_callback' => 'stm_listings_validate_checkbox'
        )
    );
    
    $manager->register_setting(
        'stm_car_stock',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'car_mark_as_sold',
        array(
            'sanitize_callback' => 'stm_listings_validate_checkbox'
        )
    );

    $manager->register_setting(
        'car_price_form_label',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    /*Options*/
    $manager->register_setting(
        'automanager_id',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'stm_car_user',
        array( 'sanitize_callback' => 'sanitize_key' )
    );

    $manager->register_setting(
        'stm_car_views',
        array( 'sanitize_callback' => 'sanitize_key' )
    );

    $manager->register_setting(
        'stm_phone_reveals',
        array( 'sanitize_callback' => 'sanitize_key' )
    );

    $manager->register_setting(
        'stock_number',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'serial_number',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'registration_number',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'stm_car_location',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'stm_lat_car_admin',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'stm_lng_car_admin',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'vin_number',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'city_mpg',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'highway_mpg',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'registration_date',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'history',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'history_link',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'certified_logo_1',
        array( 'sanitize_callback' => 'stm_listings_validate_image' )
    );

    $manager->register_setting(
        'certified_logo_2_link',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        )
    );

    $manager->register_setting(
        'certified_logo_2',
        array( 'sanitize_callback' => 'stm_listings_validate_image' )
    );

    $manager->register_setting(
        'car_brochure',
        array( 'sanitize_callback' => 'stm_listings_validate_image' )
    );

    $manager->register_setting(
        'additional_features',
        array( 'sanitize_callback' => 'stm_listings_validate_repeater' )
    );


    /*Features*/
    $options = get_option( 'stm_vehicle_listing_options' );

    if ( !empty( $options ) ) {
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'fields' => 'all',
            'pad_counts' => false,
        );

        /*Add multiselects*/
        foreach ( $options as $key => $option ) {

            if ( $option["slug"] == "price" ) {
                continue;
            }

            $terms = get_terms( $option['slug'], $args );

            $single_term = array(
                '' => 'None'
            );

            foreach ( $terms as $tax_key => $taxonomy ) {
                if ( !empty( $taxonomy ) ) {
                    $single_term[$taxonomy->slug] = $taxonomy->name;
                }
            }

            if ( empty( $option['numeric'] ) ) {
                $manager->register_control(
                    $option['slug'],
                    array(
                        'type' => 'multiselect',
                        'section' => 'stm_features',
                        'label' => $option['plural_name'],
                        'choices' => $single_term
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
                        'type' => 'text',
                        'section' => 'stm_features',
                        'label' => esc_html__( $option['single_name'], 'stm_vehicles_listing' ),
                        'attr' => array( 'class' => 'widefat' )
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

add_action( 'butterbean_register', 'stm_listings_register_manager_rental', 10, 2 );

function stm_listings_register_manager_rental( $butterbean, $post_type )
{

    $offices = 'stm_office';
    // Register managers, sections, controls, and settings here.
    if ( $post_type !== $offices ) {
        return;
    }

    $butterbean->register_manager(
        'stm_rent_manager',
        array(
            'label' => esc_html__( 'Office Info', 'stm_vehicles_listing' ),
            'post_type' => $offices,
            'context' => 'normal',
            'priority' => 'high'
        )
    );

    $manager = $butterbean->get_manager( 'stm_rent_manager' );

    /*Register sections*/
    $manager->register_section(
        'stm_info',
        array(
            'label' => esc_html__( 'Details', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-reorder'
        )
    );

    /*Register controls*/
    $manager->register_control(
        'address',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Office address', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'latitude',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Office latitude', 'stm_vehicles_listing' ),
            'description' => esc_html__( 'You can find latitude on http://www.latlong.net/', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'longitude',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Office longitude', 'stm_vehicles_listing' ),
            'description' => esc_html__( 'You can find longitude on http://www.latlong.net/', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'phone',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Phone', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'fax',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Fax', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    $manager->register_control(
        'work_hours',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Work hours', 'stm_vehicles_listing' ),
            'attr' => array(
                'class' => 'widefat',
            )
        )
    );

    /*Registering Setting*/
    $manager->register_setting(
        'address',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'latitude',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'longitude',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'phone',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'fax',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_setting(
        'work_hours',
        array(
            'sanitize_callback' => 'stm_listings_no_validate'
        )
    );
}

add_action( 'butterbean_register', 'stm_listings_register_manager_product', 10, 2 );

function stm_listings_register_manager_product( $butterbean, $post_type )
{

    $offices = 'product';
    // Register managers, sections, controls, and settings here.
    if ( $post_type !== $offices ) {
        return;
    }


    $butterbean->register_manager(
        'stm_product_manager',
        array(
            'label' => esc_html__( 'Car rent Info', 'stm_vehicles_listing' ),
            'post_type' => $offices,
            'context' => 'normal',
            'priority' => 'high'
        )
    );


    $manager = $butterbean->get_manager( 'stm_product_manager' );

    /*Register sections*/
    $manager->register_section(
        'stm_info',
        array(
            'label' => esc_html__( 'Details', 'stm_vehicles_listing' ),
            'icon' => 'fa fa-reorder'
        )
    );


    /*Control*/
    $manager->register_control(
        'cars_qty',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Stock quantity', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    $manager->register_control(
        'cars_info',
        array(
            'type' => 'text',
            'section' => 'stm_info',
            'label' => esc_html__( 'Cars included', 'stm_vehicles_listing' ),
            'attr' => array( 'class' => 'widefat' )
        )
    );

    /*Settings*/
    $manager->register_setting(
        'cars_info',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );

    $manager->register_setting(
        'cars_qty',
        array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
    );


    /*Features*/
    $options = get_option( 'stm_vehicle_listing_options' );

    if ( !empty( $options ) ) {
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'fields' => 'all',
            'pad_counts' => false,
        );

        /*Add multiselects*/
        foreach ( $options as $key => $option ) {
            $terms = get_terms( $option['slug'], $args );

            $single_term = array(
                '' => 'None'
            );

            foreach ( $terms as $tax_key => $taxonomy ) {
                if ( !empty( $taxonomy ) ) {
                    $single_term[$taxonomy->name] = $taxonomy->name;
                }
            }

            if ( empty( $option['numeric'] ) ) {
                $manager->register_control(
                    $option['slug'],
                    array(
                        'type' => 'multiselect',
                        'section' => 'stm_info',
                        'label' => $option['plural_name'],
                        'choices' => $single_term
                    )
                );

                $manager->register_setting(
                    $option['slug'],
                    array(
                        'sanitize_callback' => 'stm_listings_multiselect',
                    )
                );

            }
        }

        /*Add number fields*/
        foreach ( $options as $key => $option ) {
            if ( !empty( $option['numeric'] ) ) {
                $manager->register_control(
                    $option['slug'],
                    array(
                        'type' => 'text',
                        'section' => 'stm_info',
                        'label' => esc_html__( $option['single_name'], 'stm_vehicles_listing' ),
                        'attr' => array( 'class' => 'widefat' )
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

    do_action( "stm_add_rental_offices", $manager );
}

add_action( 'butterbean_register', 'stm_listings_register_manager_order', 10, 2 );

function stm_listings_register_manager_order( $butterbean, $post_type )
{

    $offices = 'shop_order';
    // Register managers, sections, controls, and settings here.
    if ( $post_type !== $offices ) {
        return;
    }

    if ( stm_is_rental() ) {
        $butterbean->register_manager(
            'stm_order_manager',
            array(
                'label' => esc_html__( 'Car rent Info', 'stm_vehicles_listing' ),
                'post_type' => $offices,
                'context' => 'normal',
                'priority' => 'high'
            )
        );

        $manager = $butterbean->get_manager( 'stm_order_manager' );

        /*Register sections*/
        $manager->register_section(
            'stm_info',
            array(
                'label' => esc_html__( 'Details', 'stm_vehicles_listing' ),
                'icon' => 'fa fa-reorder'
            )
        );


        /*Control*/
        $manager->register_control(
            'order_car',
            array(
                'type' => 'hidden',
                'section' => 'stm_info',
                'label' => esc_html__( 'Car ordered', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );

        $manager->register_control(
            'order_car_date',
            array(
                'type' => 'hidden',
                'section' => 'stm_info',
                'label' => esc_html__( 'Options ordered', 'stm_vehicles_listing' ),
                'attr' => array( 'class' => 'widefat' )
            )
        );

        $manager->register_control(
            'order_pickup_date',
            array(
                'type' => 'text',
                'section' => 'stm_info',
                'label' => esc_html__( 'Pickup Date', 'stm_vehicles_listing' ),
                'attr' => array(
                    'class' => 'widefat',
                    'readonly' => 'readonly'
                )
            )
        );

        $manager->register_control(
            'order_pickup_location',
            array(
                'type' => 'text',
                'section' => 'stm_info',
                'label' => esc_html__( 'Pickup location', 'stm_vehicles_listing' ),
                'attr' => array(
                    'class' => 'widefat',
                    'readonly' => 'readonly'
                )
            )
        );

        $manager->register_control(
            'order_drop_date',
            array(
                'type' => 'text',
                'section' => 'stm_info',
                'label' => esc_html__( 'Return date', 'stm_vehicles_listing' ),
                'attr' => array(
                    'class' => 'widefat',
                    'readonly' => 'readonly'
                )
            )
        );

        $manager->register_control(
            'order_drop_location',
            array(
                'type' => 'text',
                'section' => 'stm_info',
                'label' => esc_html__( 'Return location', 'stm_vehicles_listing' ),
                'attr' => array(
                    'class' => 'widefat',
                    'readonly' => 'readonly'
                )
            )
        );

        /*Settings*/
        $manager->register_setting(
            'order_car',
            array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
        );

        $manager->register_setting(
            'order_car_date',
            array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
        );

        $manager->register_setting(
            'order_pickup_date',
            array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
        );

        $manager->register_setting(
            'order_pickup_location',
            array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
        );

        $manager->register_setting(
            'order_drop_date',
            array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
        );

        $manager->register_setting(
            'order_drop_location',
            array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
        );
    }
}