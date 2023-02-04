<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

stm_motors_enqueue_scripts_styles( 'stm_listing_tabs_2' );

$multilisting = new STMMultiListing();

if ( empty( $per_page ) ) {
	$per_page = 8;
}

$filter_cats = array();
if ( ! empty( $taxonomy ) ) {
	$taxonomy   = str_replace( ' ', '', $taxonomy );
	$taxonomies = explode( ',', $taxonomy );
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $categories ) {
			if ( ! empty( $categories ) ) {
				$filter_cats[] = explode( '|', $categories );
			}
		}
	}
}

// Set active from category if no recent and popular included
$set_active_category = true;

$set_recent_active      = '';
$set_recent_active_fade = '';

$set_popular_active      = '';
$set_popular_active_fade = '';

if ( ! empty( $recent ) and $recent == 'yes' or ! empty( $popular ) and $popular == 'yes' or ! empty( $featured ) and $featured == 'yes' ) {
	$set_active_category = false;
}

$active_category = 0;

$listings   = $multilisting::stm_get_listings();
$post_types = array( stm_listings_post_type() );
foreach ( $listings as $key => $listing ) {
	$post_types[] = $listing['slug'];
}

$items = vc_param_group_parse_atts( $atts['items'] );

$featured_types = array();
// featured items in selected listing types
if ( ! empty( $items ) ) {
	foreach ( $items as $key => $item ) {
		$featured_types[] = $item['tab_listing_type'];
	}
}

$args = array(
	'post_type'      => $featured_types,
	'post_status'    => 'publish',
	'posts_per_page' => 8,
	'order'          => 'rand',
	'meta_query'     => array(
		array(
			'key'     => 'special_car',
			'value'   => 'on',
			'compare' => '=',
		),
		array(
			'relation' => 'OR',
			array(
				'key'     => 'car_mark_as_sold',
				'value'   => '',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'car_mark_as_sold',
				'value'   => '',
				'compare' => '=',
			),
		),
	),
);

$featured_query = new WP_Query( $args );

?>

<div class="stm_listing_tabs_style_2 <?php echo esc_attr( $css_class ); ?>">

	<div class="clearfix">

		<?php if ( ! empty( $title ) ) : ?>
			<h3 class="hidden-md hidden-lg"><?php echo esc_attr( $title ); ?></h3>
		<?php endif; ?>

		<!-- Nav tabs -->
		<ul class="stm_listing_nav_list heading-font" role="tablist">

			<?php foreach ( $items as $key => $item ) : ?>

				<?php
				if ( empty( $item['tab_listing_type'] ) ) {
					continue;
				}
					$type_title = ( empty( $item['tab_title_single'] ) ) ? $multilisting->stm_get_listing_name_by_slug( $item['tab_listing_type'] ) : $item['tab_title_single'];
				if ( stm_listings_post_type() === $item['tab_listing_type'] && empty( $item['tab_title_single'] ) ) {
					$type_title = esc_html__( 'Listings', 'motors_listing_types' );
				}
				?>

				<li role="presentation" class="
				<?php
				if ( ! $key && ! $featured ) {
					echo 'active';}
				?>
				">
					<a
						href="#ml_tab2_<?php echo $key; ?>_<?php echo $item['tab_listing_type']; ?>"
						aria-controls="ml_tab2_<?php echo $key; ?>_<?php echo $item['tab_listing_type']; ?>"
						role="tab"
						data-toggle="tab">
						<span><?php echo esc_attr( $type_title ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>

			<?php if ( ! empty( $featured ) && $featured == 'yes' && $featured_query->have_posts() ) : ?>
				<?php
				if ( empty( $featured_label ) ) {
					$featured_label = __( 'Featured', 'motors_listing_types' );
				}
				?>

				<li role="presentation" class="active">
					<a
						href="#ml_tab2_<?php echo $key; ?>_featured"
						aria-controls="ml_tab2_<?php echo $key; ?>_featured_"
						role="tab" data-toggle="tab">
						<span><?php echo esc_attr( $featured_label ); ?></span>
					</a>
				</li>
			<?php endif; ?>

		</ul>

		<?php if ( ! empty( $title ) ) : ?>
			<h3 class="hidden-xs hidden-sm"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>

	</div>

	<!-- Tab panes -->
	<div class="tab-content multilisting-tab-content">
		<?php
			$active_category = 0;
			$per_row         = 4;
			$template        = 'partials/listing-cars/listing-grid-directory-loop-4';
		if ( stm_is_motorcycle() ) {
			$per_row  = 3;
			$template = 'partials/listing-cars/motos/moto-single-grid';
		}
		?>
		<?php foreach ( $items as $key => $item ) : ?>

			<?php
			if ( empty( $item['tab_listing_type'] ) ) {
				continue;
			}
				$ppp = ( empty( $item['tab_limit'] ) ) ? 8 : intval( $item['tab_limit'] );
			?>

			<div
				role="tabpanel"
				class="tab-pane fade 
				<?php
				if ( ! $key && ! $featured ) {
					echo 'active in';}
				?>
				 <?php echo esc_attr( $set_recent_active_fade . ' ' . $set_recent_active ); ?>"
				id="ml_tab2_<?php echo $key; ?>_<?php echo $item['tab_listing_type']; ?>">
				<?php

				$args = array(
					'post_type'      => $item['tab_listing_type'],
					'post_status'    => 'publish',
					'posts_per_page' => $ppp,
				);

				if ( isset( $order_by ) && ! empty( $order_by ) ) {
					if ( $order_by == 'popular' ) {
						$args[] = array(
							'orderby'  => 'meta_value_num',
							'meta_key' => 'stm_car_views',
							'order'    => 'DESC',
						);
					}
				}

				$recent_query = new WP_Query( $args );

				if ( $recent_query->have_posts() ) :
					?>
					<div class="row row-<?php echo intval( $per_row ); ?> car-listing-row">
							<?php
							while ( $recent_query->have_posts() ) :
								$recent_query->the_post();
								?>
								<?php get_template_part( $template ); ?>
							<?php endwhile; ?>
						</div>

						<?php if ( ! empty( $show_more ) and $show_more == 'yes' ) : ?>
						<div class="row">
								<div class="col-xs-12 text-center">
									<div class="dp-in">
										<a class="load-more-btn" href="<?php echo esc_url( get_post_type_archive_link( $item['tab_listing_type'] ) ); ?>">
											<?php esc_html_e( 'Show all', 'motors_listing_types' ); ?>
										</a>
									</div>
								</div>
							</div>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>

			</div>
		<?php endforeach; ?>

		<?php if ( ! empty( $featured ) and $featured == 'yes' ) : ?>
			<div role="tabpanel" class="tab-pane fade active in" id="ml_tab2_<?php echo $key; ?>_featured">
				<?php
				if ( $featured_query->have_posts() ) :
					?>
					<div class="row row-<?php echo intval( $per_row ); ?> car-listing-row">
						<?php
						while ( $featured_query->have_posts() ) :
							$featured_query->the_post();
							?>
							<?php get_template_part( $template ); ?>
						<?php endwhile; ?>
					</div>
					<?php if ( ! empty( $show_more ) and $show_more == 'yes' ) : ?>
						<div class="row">
							<div class="col-xs-12 text-center">
								<div class="dp-in">
									<a class="load-more-btn" href="<?php echo esc_url( get_post_type_archive_link( $featured_types[0] ) . '?featured_top=true' ); ?>">
										<?php esc_html_e( 'Show all', 'motors_listing_types' ); ?>
									</a>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>
</div>
