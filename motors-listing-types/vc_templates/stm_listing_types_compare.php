<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class    = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$multilisting = new STMMultiListing();

$title_text  = esc_html__( 'Compare Listings', 'motors_listing_types' );
$add_to_text = esc_html__( 'Add Listings to Compare', 'motors_listing_types' );

if ( isset( $_GET['compare_type'] ) && stm_listings_post_type() !== $_GET['compare_type'] ) {
	$title = $multilisting->stm_get_listing_name_by_slug( $_GET['compare_type'] );
	if ( ! empty( $title ) ) {
		$title_text  = sprintf( __( 'Compare %s', 'motors_listing_types' ), $title );
		$add_to_text = sprintf( __( 'Add %s to Compare', 'motors_listing_types' ), $title );
	}
}

$compare_empty_placeholder = ( stm_is_aircrafts() ) ? 'ac_empty_compare.jpg' : 'compare-empty.jpg';

$slugs = array( stm_listings_post_type() );

$multilisting_slugs = $multilisting::stm_get_listing_type_slugs();
if ( ! empty( $multilisting_slugs ) ) {
	$slugs = array_merge( $slugs, $multilisting_slugs );
}

?>

<div class="multilisting-compare-wrap <?php echo esc_attr( $css_class ); ?>">

	<?php if ( ( isset( $_GET['compare_type'] ) && stm_listings_post_type() === $_GET['compare_type'] ) || ( ! isset( $_GET['compare_type'] ) || empty( $_GET['compare_type'] ) ) ) :

		$compared_items = stm_get_compared_items( stm_listings_post_type() );

		$args = array(
			'post_type'      => stm_listings_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__in'       => $compared_items,
		);

		$compares = new WP_Query( $args );

		$filter_options = stm_get_single_car_listings();

		$empty_add_link = get_post_type_archive_link( stm_listings_post_type() );

	else :

		if ( empty( $_GET['compare_type'] ) ) {
			$post_type = stm_listings_post_type();
		} else {
			$post_type = $_GET['compare_type'];
		}

		$compared_items = stm_get_compared_items( $post_type );
		$listings       = STMMultiListing::stm_get_listings();
		$f_options      = array();
		$temp           = array();

		if ( ! empty( $listings ) ) {
			foreach ( $listings as $key => $listing ) {
				if ( $post_type !== $listing['slug'] ) {
					continue;
				}

				$temp = stm_get_single_car_multilisting( $listing['slug'] );

				foreach ( $temp as $item ) {
					$f_options[ $item['single_name'] ] = $item;
				}
			}
		}

			$filter_options = $f_options; // stm_get_single_car_listings();

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__in'       => $compared_items,
		);

		$compares = new WP_Query( $args );

		$empty_add_link = get_post_type_archive_link( $_GET['compare_type'] );

	endif;

	$empty_cars = 3;

	if ( $compares->have_posts() ) {
		$empty_cars = 3 - $compares->post_count;
	}

	$counter = 0;
	?>

	<div class="multilisting_compare_type_buttons">
		<?php foreach ( $slugs as $slug ) : ?>
			<?php
				$type_label = ( stm_listings_post_type() === $slug ) ? __( 'Listings', 'motors_listing_types' ) : $multilisting->stm_get_listing_name_by_slug( $slug );
				$btn_class  = ( isset( $_GET['compare_type'] ) && $_GET['compare_type'] === $slug ) ? 'btn-primary' : 'btn-default';

			if ( ( ! isset( $_GET['compare_type'] ) || empty( $_GET['compare_type'] ) ) && stm_listings_post_type() === $slug ) {
				$btn_class = 'btn-primary';
			}
			?>
			<a class="btn btn-lg <?php echo $btn_class; ?>" href="?compare_type=<?php echo $slug; ?>">
				<?php echo $type_label; ?> <span class="badge"><?php echo count( stm_get_compared_items( $slug ) ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $compared_items ) > 0 && $compares->have_posts() ) : ?>
		<div class="row row-4 car-listing-row stm-car-compare-row">
			<?php require_once MULTILISTING_PATH . 'partials/compare-title.php'; ?>
			<?php
			while ( $compares->have_posts() ) :
				$compares->the_post();
				$counter++;
				stm_multilisting_load_template( 'partials/compare-description' );
			endwhile;

			stm_multilisting_load_template(
				'partials/compare-description-empty',
				array(
					'empty_cars'                => $empty_cars,
					'add_to_text'               => $add_to_text,
					'compare_empty_placeholder' => $compare_empty_placeholder,
					'empty_add_link'            => $empty_add_link,
				)
			);
			?>

		</div> <!--row-->

		<?php
		stm_multilisting_load_template(
			'partials/compare-listing',
			array(
				'compares'                  => $compares,
				'empty_cars'                => $empty_cars,
				'add_to_text'               => $add_to_text,
				'compare_empty_placeholder' => $compare_empty_placeholder,
				'filter_options'            => $filter_options,
			)
		);
		?>

		<!--Additional features-->
		<?php stm_multilisting_load_template( 'partials/compare-features', array( 'compares' => $compares ) ); ?>

		<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<?php
		stm_multilisting_load_template(
			'partials/compare-listing-empty',
			array(
				'empty_cars'                => $empty_cars,
				'add_to_text'               => $add_to_text,
				'compare_empty_placeholder' => $compare_empty_placeholder,
				'title_text'                => $title_text,
				'filter_options'            => $filter_options,
				'empty_add_link'            => $empty_add_link,
			)
		);
		?>
	<?php endif; ?>

</div> <!--container-->

<div class="compare-empty-car-top">
	<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-empty">
		<a href="<?php echo esc_url( $empty_add_link ); ?>">
			<div class="image">
				<i class="stm-icon-add_car"></i>
				<img class="stm-compare-empty"
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
					alt="<?php esc_attr_e( 'Empty', 'motors_listing_types' ); ?>"/>
			</div>
			<div class="h5"><?php echo esc_html( $add_to_text ); ?></div>
		</a>
	</div>
</div>

<div class="compare-empty-car-bottom">
	<?php if ( ! empty( $filter_options ) ) : ?>
		<div class="col-md-3 col-sm-3 col-xs-4">
			<div class="compare-options">
				<table>
					<?php foreach ( $filter_options as $filter_option ) : ?>
						<?php if ( false === stm_is_listing_price_field( $filter_option['slug'] ) ) { ?>
							<tr>
								<td class="compare-value-hover">&nbsp;</td>
							</tr>
						<?php }; ?>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>

<script>
	jQuery(document).ready(function ($) {

		var heigth = 0;
		$('.compare-values table tbody tr td').each(function(){
			heigth = $(this).height();

			$('.' + $(this).attr('data-value')).each(function () {
				if($(this).height() > heigth || heigth > 0) {
					heigth = $(this).height() + 18;
					$('.' + $(this).attr('data-value')).css('height', heigth + 'px');
				}
			});
		});

		$('.compare-value-hover').on('hover', function () {
			var dataValue = $(this).data('value');
			$('.compare-value-hover[data-value = ' + dataValue + ']').addClass('hovered');
		}, function () {
			$('.compare-value-hover').removeClass('hovered');
		});
	});
</script>
