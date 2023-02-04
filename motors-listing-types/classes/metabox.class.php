<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MetaboxMultiListing extends STMMultiListing
{
	public function __construct()
	{
		parent::__construct();
		add_action('plugins_loaded', array($this, 'register_metabox'));
	}

	public function register_metabox() {
		if( !class_exists('STM_PostType') ) return;

		$args = array(
			'fields' => array(
				'page_bg_color' => array(
					'label' => __( 'Page Background Color', STM_POST_TYPE ),
					'type'  => 'color_picker'
				),
				'transparent_header' => array(
					'label'   => __( 'Transparent Header', STM_POST_TYPE ),
					'type'    => 'checkbox'
				),
				'separator_title_box' => array(
					'label'   => __( 'Title Box', STM_POST_TYPE ),
					'type'    => 'separator'
				),
				'alignment' => array(
					'label'   => __( 'Alignment', STM_POST_TYPE ),
					'type'    => 'select',
					'options' => array(
						'left' => __( 'Left', STM_POST_TYPE ),
						'center' => __( 'Center', STM_POST_TYPE ),
						'right' => __( 'Right', STM_POST_TYPE )
					)
				),
				'title' => array(
					'label'   => __( 'Title', STM_POST_TYPE ),
					'type'    => 'select',
					'options' => array(
						'show' => __( 'Show', STM_POST_TYPE ),
						'hide' => __( 'Hide', STM_POST_TYPE )
					)
				),
				'sub_title' => array(
					'label'   => __( 'Sub Title', STM_POST_TYPE ),
					'type'    => 'text'
				),
				'title_box_bg_color' => array(
					'label' => __( 'Background Color', STM_POST_TYPE ),
					'type'  => 'color_picker'
				),
				'title_box_font_color' => array(
					'label' => __( 'Font Color', STM_POST_TYPE ),
					'type'  => 'color_picker'
				),
				'title_box_line_color' => array(
					'label' => __( 'Line Color', STM_POST_TYPE ),
					'type'  => 'color_picker'
				),
				'title_box_subtitle_font_color' => array(
					'label' => __( 'Sub Title Font Color', STM_POST_TYPE ),
					'type'  => 'color_picker'
				),
				'title_box_custom_bg_image' => array(
					'label' => __( 'Custom Background Image', STM_POST_TYPE ),
					'type'  => 'image'
				),
				'separator_breadcrumbs' => array(
					'label'   => __( 'Breadcrumbs', STM_POST_TYPE ),
					'type'    => 'separator'
				),
				'breadcrumbs' => array(
					'label'   => __( 'Breadcrumbs', STM_POST_TYPE ),
					'type'    => 'select',
					'options' => array(
						'show' => __( 'Show', STM_POST_TYPE ),
						'hide' => __( 'Hide', STM_POST_TYPE )
					)
				),
				'breadcrumbs_font_color' => array(
					'label' => __( 'Breadcrumbs Color', STM_POST_TYPE ),
					'type'  => 'color_picker'
				),
			)
		);

		STM_PostType::addMetaBox(
			'listing_seller_note',
			esc_html__( "Seller's note", STM_POST_TYPE ),
			$this->stm_get_listing_type_slugs(),
			'',
			'normal',
			'high',
			array(
				'fields' => array(
					'listing_seller_note' => array(
						'label' => '',
						'type'  => 'textarea',
						'class' => 'fullwidth listing_seller_note'
					)
				)
			)
		);

		STM_PostType::addMetaBox(
			'page_options', __( 'Page Options', STM_POST_TYPE ),
			$this->stm_get_listing_type_slugs(),
			'',
			'',
			'',
			apply_filters('stm_multilisting_metabox', $args)
		);
	}
}

new MetaboxMultiListing;
