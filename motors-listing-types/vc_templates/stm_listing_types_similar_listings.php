<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';
?>

<div class="<?php echo esc_attr($css_class); ?>">
	<div class="stm-border-bottom-unit"><div class="title heading-font"><?php esc_html_e('Similar Listing' , 'motors_listing_types'); ?></div></div>
	<?php get_template_part('partials/single-car-listing/car-similar'); ?>
</div>

<?php wp_reset_postdata(); ?>