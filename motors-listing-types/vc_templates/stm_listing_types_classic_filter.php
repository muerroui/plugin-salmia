<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$quant_listing_on_grid = ( ! empty( $quant_listing_on_grid ) ) ? $quant_listing_on_grid : 3;

?>

<div class="archive-listing-page multilisting-page <?php echo esc_attr( $css_class ); ?>">
	<div class="container">
		<div class="row">

			<?php
			set_query_var( 'listings_type', $post_type );
			HooksMultiListing::stm_listings_attributes_filter( array( 'slug' => $post_type ) );
			$filter = stm_listings_filter( array( 'post_type' => $post_type ) );

			$sidebar_pos = stm_get_sidebar_position();
			$sidebar_id  = stm_me_get_wpcfto_mod( 'listing_sidebar', 'default' );

			if ( ! empty( $sidebar_id ) ) {
				$blog_sidebar = get_post( $sidebar_id );
			}

			if ( ! is_numeric( $sidebar_id ) && ( $sidebar_id == 'no_sidebar' || ! is_active_sidebar( $sidebar_id ) ) ) {
				$sidebar_id = false;
			}

			if ( is_numeric( $sidebar_id ) && empty( $blog_sidebar->post_content ) ) {
				$sidebar_id = false;
			}
			?>

			<div class="col-md-3 col-sm-12 classic-filter-row sidebar-sm-mg-bt <?php echo esc_attr( $sidebar_pos['sidebar'] ); ?>">
				<?php if ( function_exists( 'stm_is_motorcycle' ) && stm_is_motorcycle() ) : ?>
					<?php stm_listings_load_template( 'motorcycles/filter/sidebar', array( 'filter' => $filter ) ); ?>
				<?php else : ?>
					<?php stm_listings_load_template( 'classified/filter/sidebar', array( 'filter' => $filter ) ); ?>
				<?php endif; ?>

				<!--Sidebar-->
				<div class="stm-inventory-sidebar">
					<?php
					if ( 'default' === $sidebar_id ) {
						get_sidebar();
					} elseif ( ! empty( $sidebar_id ) ) {
						echo apply_filters( 'the_content', $blog_sidebar->post_content );
						?>
						<style type="text/css">
							<?php echo get_post_meta( $sidebar_id, '_wpb_shortcodes_custom_css', true ); ?>
						</style>
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-md-9 col-sm-12 <?php echo esc_attr( $sidebar_pos['content'] ); ?>">
				<div class="stm-ajax-row">
					<?php stm_listings_load_template( 'classified/filter/actions', array( 'filter' => $filter ) ); ?>
					<div id="listings-result">
						<?php stm_listings_load_results(); ?>
					</div>
				</div>
			</div> <!--col-md-9-->

		</div>

		<?php wp_reset_postdata(); ?>
	</div>
</div>
