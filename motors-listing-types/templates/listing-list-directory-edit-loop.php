<?php
$badge_text     = get_post_meta( get_the_ID(), 'badge_text', true );
$badge_bg_color = get_post_meta( get_the_ID(), 'badge_bg_color', true );
$special_car    = get_post_meta( get_the_ID(), 'special_car', true );
$taxonomies     = stm_get_taxonomies();
$categories     = wp_get_post_terms( get_the_ID(), array_values( $taxonomies ) );
$classes        = array( 'listing-list-loop-edit', get_post_status( get_the_ID() ) );
$car_media      = stm_get_car_medias( get_the_id() );
$show_compare   = stm_me_get_wpcfto_mod( 'show_listing_compare', false );
$show_favorite  = stm_me_get_wpcfto_mod( 'enable_favorite_items', false );
$hide_labels    = stm_me_get_wpcfto_mod( 'hide_price_labels', false );

foreach ( $categories as $category ) {
	$classes[] = $category->slug . '-' . $category->term_id;
}

if ( $hide_labels ) {
	$classes[] = 'stm-listing-no-price-labels';
}

$car_views = get_post_meta( get_the_ID(), 'stm_car_views', true );
if ( empty( $car_views ) ) {
	$car_views = 0;
}

$phone_reveals = get_post_meta( get_the_ID(), 'stm_phone_reveals', true );
if ( empty( $phone_reveals ) ) {
	$phone_reveals = 0;
}

$asSold = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
if ( ! empty( $asSold ) ) {
	$classes[] = 'as_sold';
}

$sell_online = false;

stm_listings_load_template(
	'loop/start',
	array(
		'modern'          => true,
		'listing_classes' => $classes,
	)
);
?>
		<div class="image">

			<!--Hover blocks-->
			<!---Media-->
			<div class="stm-car-medias">
				<?php if ( ! empty( $car_media['car_photos_count'] ) ) : ?>
					<div class="stm-listing-photos-unit stm-car-photos-<?php echo get_the_id(); ?>">
						<i class="stm-service-icon-photo"></i>
						<span><?php echo esc_html( $car_media['car_photos_count'] ); ?></span>
					</div>

					<script>
						jQuery(document).ready(function(){

							jQuery(".stm-car-photos-<?php echo get_the_id(); ?>").on('click', function() {
								jQuery(this).lightGallery({
									dynamic: true,
									dynamicEl: [
										<?php foreach ( $car_media['car_photos'] as $car_photo ) : ?>
										{
											src  : "<?php echo esc_url( $car_photo ); ?>",
											thumb: "<?php echo esc_url( $car_photo ); ?>",
										},
										<?php endforeach; ?>
									],
									download: false,
									mode: 'lg-fade',
								})
							});
						});

					</script>
				<?php endif; ?>
				<?php if ( ! empty( $car_media['car_videos_count'] ) ) : ?>
					<div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_id(); ?>">
						<i class="fas fa-film"></i>
						<span><?php echo esc_html( $car_media['car_videos_count'] ); ?></span>
					</div>

					<script>
						jQuery(document).ready(function(){

							jQuery(".stm-car-videos-<?php echo get_the_id(); ?>").on('click', function() {
								jQuery(this).lightGallery({
									dynamic: true,
									dynamicEl: [
										<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
										{
											src  : "<?php echo esc_url( $car_video ); ?>"
										},
										<?php endforeach; ?>
									],
									download: false,
									mode: 'lg-fade',
								})
							}); //click
						}); //ready

					</script>
				<?php endif; ?>
			</div>
			<!--Compare-->
			<?php if ( ! empty( $show_compare ) and $show_compare ) : ?>
				<div class="stm-listing-compare" data-id="<?php echo esc_attr( get_the_id() ); ?>" data-title="<?php echo stm_generate_title_from_slugs( get_the_id(), false ); ?>" data-post-type="<?php echo get_post_type( get_the_ID() ); ?>">
					<i class="stm-service-icon-compare-new"></i>
				</div>
			<?php endif; ?>

			<!--Favorite-->
			<?php if ( ! empty( $show_favorite ) and $show_favorite ) : ?>
				<div class="stm-listing-favorite" data-id="<?php echo esc_attr( get_the_id() ); ?>">
					<i class="stm-service-icon-staricon"></i>
				</div>
			<?php endif; ?>

			<div class="listing_stats_wrap">
				<div class="stm-phone-reveals" data-type="phone" data-id="<?php echo esc_attr( get_the_id() ); ?>" data-title="<?php echo stm_generate_title_from_slugs( get_the_id(), false ); ?>">
					<i class="fas fa-phone"></i>
					<?php echo esc_attr( $phone_reveals ); ?>
				</div>
				<div class="stm-car-views" data-type="listing" data-id="<?php echo esc_attr( get_the_id() ); ?>" data-title="<?php echo stm_generate_title_from_slugs( get_the_id(), false ); ?>">
					<i class="fas fa-eye"></i>
					<?php echo esc_attr( $car_views ); ?>
				</div>
			</div>

			<?php if ( get_post_status( get_the_id() ) != 'pending' ) : ?>
				<div class="stm_edit_disable_car heading-font">
					<div class="stm_sold_sell_wrap">
					<?php if ( $asSold == 'on' ) : ?>
						<a href="<?php echo esc_url( add_query_arg( array( 'stm_unmark_as_sold_car' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>" class="as_sold">
							<?php esc_html_e( 'Unmark as sold', 'motors_listing_types' ); ?><i class="far fa-check-square" aria-hidden="true"></i>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( add_query_arg( array( 'stm_mark_as_sold_car' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>">
							<?php esc_html_e( 'Mark as sold', 'motors_listing_types' ); ?><i class="far fa-check-square" aria-hidden="true"></i>
						</a>
					<?php endif; ?>

					<?php if ( $sell_online ) : ?>
						<?php if ( $issell_online == 'on' ) : ?>
							<a href="
							<?php
							echo esc_url(
								add_query_arg(
									array(
										'stm_unmark_woo_online' => get_the_ID(),
										'nonce' => $sell_onlineNonce,
									),
									stm_get_author_link( '' )
								)
							);
							?>
										" class="as_sold">
								<?php esc_html_e( 'Don\'t sell online', 'motors_listing_types' ); ?><i class="far fa-check-square" aria-hidden="true"></i>
							</a>
						<?php else : ?>
							<a href="
							<?php
							echo esc_url(
								add_query_arg(
									array(
										'stm_mark_woo_online' => get_the_ID(),
										'nonce' => $sell_onlineNonce,
									),
									stm_get_author_link( '' )
								)
							);
							?>
										">
								<?php esc_html_e( 'Sell online', 'motors_listing_types' ); ?><i class="far fa-check-square" aria-hidden="true"></i>
							</a>
						<?php endif; ?>
					<?php endif; ?>
					</div>
					<a href="<?php echo esc_url( stm_get_add_page_url( 'edit', get_the_ID() ) ); ?>"
					   data-toggle="tooltip"
					   data-placement="top"
					   title="<?php esc_html_e( 'Edit', 'motors_listing_types' ); ?>"
						><i class="fas fa-pencil-alt"></i>
					</a>
					<?php if ( get_post_status( get_the_id() ) == 'draft' ) : ?>
						<a href="<?php echo esc_url( add_query_arg( array( 'stm_enable_user_car' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>" class="enable_list"
						   data-toggle="tooltip"
						   data-placement="top"
						   title="<?php esc_html_e( 'Enable', 'motors_listing_types' ); ?>"
						><i class="fas fa-eye"></i></a>
					<?php else : ?>
						<a href="<?php echo esc_url( add_query_arg( array( 'stm_disable_user_car' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>" class="disable_list"
						   data-id="<?php esc_attr( get_the_ID() ); ?>"
						   data-toggle="tooltip"
						   data-placement="top"
						   title="<?php esc_html_e( 'Disable', 'motors_listing_types' ); ?>"
						><i class="fas fa-eye-slash"></i></a>
					<?php endif; ?>
					<?php
					if ( stm_me_get_wpcfto_mod( 'dealer_payments_for_featured_listing', false ) ) :
						?>
							<?php
							$featuredStatus = get_post_meta( get_the_ID(), 'car_make_featured_status', true );
							if ( ! $special_car && ( empty( $featuredStatus ) || ! in_array( $featuredStatus, array( 'completed', 'processing' ) ) ) ) :
								?>
								<a href="<?php echo esc_url( add_query_arg( array( 'stm_make_featured' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>" class="make_featured"
								   data-toggle="tooltip"
								   data-placement="top"
								   title="<?php esc_html_e( 'Make Featured', 'motors_listing_types' ); ?>"
								>
									<i class="fas fa-star" aria-hidden="true"></i>
								</a>
								<?php
							else :
								$featuredText  = ( ( $special_car && ( $featuredStatus == 'completed' || $featuredStatus == 'processing' ) ) || $special_car && empty( $featuredClass ) ) ? esc_html__( 'Featured', 'motors_listing_types' ) : esc_html__( 'Featured (pending)', 'motors_listing_types' );
								$featuredClass = ( ( $special_car && ( $featuredStatus == 'completed' || $featuredStatus == 'processing' ) ) || $special_car && empty( $featuredClass ) ) ? 'featured' : 'featured_pending';
								?>
								<span class="<?php echo esc_attr( $featuredClass ); ?>"
									  data-toggle="tooltip"
									  data-placement="top"
									  title="<?php echo esc_attr( $featuredText ); ?>"
								><i class="fas fa-star" aria-hidden="true"></i></span>
							<?php endif; ?>
					<?php endif; ?>
					<?php if ( get_post_status( get_the_id() ) == 'draft' ) : ?>
						<div class="stm_car_move_to_trash">
							<a class="stm-delete-confirmation" href="<?php echo esc_url( add_query_arg( array( 'stm_move_trash_car' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>" data-title="<?php the_title(); ?>">
								<i class="fas fa-trash-alt"></i>
							</a>
						</div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="stm_edit_pending_car">
					<h4><?php esc_html_e( 'Pending', 'motors_listing_types' ); ?></h4>
					<div class="stm-dots"><span></span><span></span><span></span></div>
					<a href="<?php echo esc_url( stm_get_add_page_url( 'edit', get_the_ID() ) ); ?>">
						<?php esc_html_e( 'Edit', 'motors_listing_types' ); ?>
						<i class="fas fa-pencil-alt"></i>
					</a>
					<a class="stm-delete-confirmation" href="<?php echo esc_url( add_query_arg( array( 'stm_move_trash_car' => get_the_ID() ), stm_get_author_link( '' ) ) ); ?>" data-title="<?php the_title(); ?>">
						<?php esc_html_e( 'Delete', 'motors_listing_types' ); ?>
						<i class="fas fa-trash-alt"></i>
					</a>
				</div>
			<?php endif; ?>
   
			<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
				<div class="image-inner">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php
							$sizeImg    = 'stm-img-280-165';
							$sizeRetina = 'stm-img-280-165-x-2';
							$img        = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $sizeImg );
							$imgRetina  = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $sizeRetina );
						?>
						<img
							data-src="<?php echo esc_url( $img[0] ); ?>"
							src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/plchldr350.png' ); ?>"
							class="lazy img-responsive"
							srcset="<?php echo esc_url( ! empty( $img[0] ) ? $img[0] : get_stylesheet_directory_uri() . '/assets/images/plchldr350.png' ); ?> 1x, <?php echo esc_url( ! empty( $imgRetina[0] ) ? $imgRetina[0] : get_stylesheet_directory_uri() . '/assets/images/plchldr350.png' ); ?> 2x"
							alt="<?php the_title(); ?>"
						/>

					<?php else : ?>
						<img
							src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/plchldr350.png' ); ?>"
							class="img-responsive"
							alt="<?php esc_attr_e( 'Placeholder', 'motors_listing_types' ); ?>"
						/>
					<?php endif; ?>
				</div>
			</a>
		</div>


		<div class="content">

			<?php stm_listings_load_template( 'loop/classified/list/title_price', array( 'hide_labels' => $hide_labels ) ); ?>

			<?php stm_listings_load_template( 'loop/classified/list/options' ); ?>

			<div class="meta-bottom">
				<?php get_template_part( 'partials/listing-cars/listing-directive-list-loop', 'actions' ); ?>
			</div>

			<a href="<?php the_permalink(); ?>" class="stm-car-view-more button visible-xs"><?php esc_html_e( 'View more', 'motors_listing_types' ); ?></a>
		</div>

</div>
