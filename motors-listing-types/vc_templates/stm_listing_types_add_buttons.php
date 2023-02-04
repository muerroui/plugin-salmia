<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

// default values.
if ( empty( $stm_listings_icon ) ) {
	$stm_listings_icon = 'fas fa-car';
}
if ( empty( $add_page_id ) ) {
	$add_page_id = null;
}

if ( empty( $stm_listings_label ) ) {
	$stm_listings_label = __( 'Listings', 'motors_listing_types' );
}

$default_types = array(
	'label'          => esc_html( $stm_listings_label ),
	'slug'           => stm_listings_post_type(),
	'inventory_page' => 0,
	'add_page'       => $add_page_id,
	'icon'           => array(
		'icon' => $stm_listings_icon,
	),
);

$listings = STMMultiListing::stm_get_listings();

array_unshift( $listings, $default_types );

$stm_custom_icon_class = 'stm_custom_icon_class_' . wp_rand( 111, 999 );

?>

<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
		.<?php echo esc_attr( $stm_custom_icon_class ); ?>::before {
			color: <?php echo esc_attr( $icon_color ); ?>;
		}
	<?php endif; ?>

	.card-body:hover i::before {
		color: #fff!important;
	}
</style>

<div class="listing-type-list multilisting-buttons-wrap">
	<?php if ( ! empty( $listings ) ) : ?>
		<div class="stm-row">
			<?php foreach ( $listings as $key => $listing ) : ?>
				<div class="stm-col-3 m-b-15">
					<div class="card">
						<div class="card-body">
							<div class="card-icon">
								<?php if ( empty( $listing['icon']['icon'] ) ) : ?>
									<i class="fas fa-car <?php echo ( stm_listings_post_type() === $listing['slug'] ) ? esc_attr( $stm_custom_icon_class ) : ''; ?>"></i>
								<?php else :
									$icon_color = '';
									if ( ! empty( $listing['icon']['color'] ) && '#000' !== $listing['icon']['color'] ) {
										$icon_color = 'color: ' . esc_attr( $listing['icon']['color'] ) . '!important;';
									}
									?>
									<i class="<?php echo esc_attr( $listing['icon']['icon'] ); ?> <?php echo ( stm_listings_post_type() === $listing['slug'] ) ? esc_attr( $stm_custom_icon_class ) : ''; ?>" style="<?php echo esc_attr( $icon_color ); ?>"></i>
								<?php endif; ?>
							</div>

							<h6 class="card-title"><?php echo esc_html( $listing['label'] ); ?></h6>

							<a href="<?php echo ( ! empty( $listing['add_page'] ) && is_numeric( $listing['add_page'] ) ) ? get_permalink( $listing['add_page'] ) : '#!'; ?>" class="btn btn-primary">
								<i class="fas fa-arrow-right"></i>
							</a>

						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>

		<p><?php esc_html_e( 'Sorry, no listing types found', 'motors_listing_types' ); ?></p>

	<?php endif ?>

	<?php wp_reset_postdata(); ?>
</div>
