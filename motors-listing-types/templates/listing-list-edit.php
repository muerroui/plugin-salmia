<?php
$query = (
	function_exists( 'stm_user_multilistings_query' ) ) ?
	stm_user_multilistings_query( get_current_user_id(), 'any', $post_type ) :
	null;
$queryPPL = (
	function_exists( 'stm_user_pay_per_multilistings_query' ) ) ?
	stm_user_pay_per_multilistings_query( get_current_user_id(), 'any', $post_type ) :
	null;
$tabsActive =
	( $query != null && $query->have_posts() && $queryPPL != null && $queryPPL->have_posts() ) ?
	true :
	false;

if ( $query != null && $query->have_posts() || $queryPPL != null && $queryPPL->have_posts() ): ?>
	<?php
	set_query_var('listings_type', $post_type);
	HooksMultiListing::stm_listings_attributes_filter(['slug' => $post_type]);
	?>
	<?php get_template_part( 'partials/user/private/user', 'inventory' ); ?>
	<div class="archive-listing-page">
		<?php if ( $tabsActive ) : ?>
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item active">
					<a
						href="#pp"
						class="nav-link active heading-font"
						id="pp-tab"
						data-toggle="tab"
						role="tab"
						aria-controls="pp"
						aria-selected="true">
						<?php echo esc_html__( 'Subscription Listings', 'motors_listing_types' ); ?>
					</a>
				</li>
				<li class="nav-item">
					<a
						href="#ppl"
						class="nav-link heading-font"
						id="ppl-tab"
						data-toggle="tab"
						role="tab"
						aria-controls="ppl"
						aria-selected="false">
						<?php echo esc_html__( 'Pay Per Listings', 'motors_listing_types' ); ?>
					</a>
				</li>
			</ul>
		<?php endif; ?>

		<?php if ( $tabsActive ) : ?>
			<div class="tab-content">
				<div
					class="tab-pane active"
					id="pp"
					role="tabpanel"
					aria-labelledby="pp-tab">
					<?php endif; ?>

					<?php while ( $query->have_posts() ): $query->the_post(); ?>
						<?php include( MULTILISTING_PATH . 'templates/listing-list-directory-edit-loop.php' ); ?>
					<?php endwhile; ?>

					<?php if ( $tabsActive ) : ?>
				</div>
			<?php endif; ?>

			<?php if ( $tabsActive ) : ?>
				<div class="tab-pane" id="ppl" role="tabpanel" aria-labelledby="ppl-tab">
			<?php endif; ?>

			<?php
			if ( $queryPPL != null && $queryPPL->have_posts() ):
				while ( $queryPPL->have_posts() ): $queryPPL->the_post();
					include( MULTILISTING_PATH . 'templates/listing-list-directory-edit-loop.php' );
				endwhile;
			endif;
			?>

			<?php if ( $tabsActive ) : ?>
					</div>
				</div>
			<?php endif; ?>
	</div>
<?php else: ?>
	<h4 class="stm-seller-title"><?php esc_html_e( 'No Inventory yet', 'motors_listing_types' ); ?></h4>
<?php endif; ?>
