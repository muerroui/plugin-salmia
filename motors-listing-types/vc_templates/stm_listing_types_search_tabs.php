<?php
wp_enqueue_script(
	'stm-cascadingdropdown',
	get_template_directory_uri() . '/assets/js/jquery.cascadingdropdown.js',
	array( 'jquery' ),
	STM_THEME_VERSION,
	true
);

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( isset( $atts['items'] ) && strlen( $atts['items'] ) > 0 ) {
	$items = vc_param_group_parse_atts( $atts['items'] );
	if ( ! is_array( $items ) ) {
		$temp         = explode( ',', $atts['items'] );
		$param_values = array();
		foreach ( $temp as $value ) {
			$data                  = explode( '|', $value );
			$new_line              = array();
			$new_line['title']     = isset( $data[0] ) ? $data[0] : 0;
			$new_line['sub_title'] = isset( $data[1] ) ? $data[1] : '';
			if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
				$new_line['title']     = (float) str_replace( '%', '', $data[1] );
				$new_line['sub_title'] = isset( $data[2] ) ? $data[2] : '';
			}
			$param_values[] = $new_line;
		}
		$atts['items'] = rawurlencode( wp_json_encode( $param_values ) );
	}
}

$multilisting = new STMMultiListing();

$active_taxonomy_tab         = true;
$active_taxonomy_tab_active  = 'active';
$active_taxonomy_tab_content = 'in active';

if ( empty( $filter_all ) ) {
	$active_taxonomy_tab        = true;
	$active_taxonomy_tab_active = 'active';
}

$stm_post_types = array();
$listings       = $multilisting::stm_get_listings();
if ( ! empty( $listings ) ) {
	foreach ( $listings as $key => $listing ) {
		$stm_post_types[] = $listing['slug'];
	}
}
$args = array(
	'post_type'        => $stm_post_types,
	'post_status'      => 'publish',
	'posts_per_page'   => 1,
	'suppress_filters' => 0,
);

$args['meta_query'][] = array(
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
);

$all = new WP_Query( $args );
$all = $all->found_posts;

if ( empty( $show_amount ) ) {
	$show_amount = 'no';
}

$words = array();

if ( ! empty( $select_prefix ) ) {
	$words['select_prefix'] = $select_prefix;
}

if ( ! empty( $select_affix ) ) {
	$words['select_affix'] = $select_affix;
}

if ( ! empty( $number_prefix ) ) {
	$words['number_prefix'] = $number_prefix;
}

if ( ! empty( $number_affix ) ) {
	$words['number_affix'] = $number_affix;
}

$pt_tax_arr = array();

?>
<div class="stm-c-f-search-form-wrap multilisting-search-tabs-wrap filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr( $css_class ); ?>">

	<?php if ( is_array( $items ) && ! empty( $items ) ) : ?>
		<ul class="nav nav-tabs" role="tablist">

			<?php
			$i = 1;

			foreach ( $items as $key => $item ) :
				$stm_post_type = $item['tab_listing_type'];
				if ( ! empty( $stm_post_type ) ) :
					$stm_title = ( empty( $item['tab_title_single'] ) ) ? $multilisting->stm_get_listing_name_by_slug( $stm_post_type ) : $item['tab_title_single'];
					$slug      = ( isset( $item['tab_id_single'] ) ) ? sanitize_title( $item['tab_id_single'] ) : sanitize_title( $stm_title );

					if ( stm_listings_post_type() === $stm_post_type && empty( $item['tab_title_single'] ) ) {
						$stm_title = esc_html__( 'Listings', 'motors_listing_types' );
					}
					?>
					<li class="nav-item 
					<?php
					if ( 1 === $i ) {
						echo 'active';}
					?>
					">
						<?php $icon = stm_multilisting_get_type_icon_by_slug( $stm_post_type ); ?>
						<a href="#<?php echo esc_attr( $stm_post_type ); ?>"
							class="nav-link stm-cursor-pointer heading-font 
							<?php
							if ( 1 === $i ) {
								echo 'active';}
							?>
							"
							aria-controls="<?php echo esc_attr( $stm_post_type ); ?>"
							role="tab"
							data-toggle="tab"
							data-slug="<?php echo esc_attr( $slug ); ?>"
							>
							<?php if ( ! empty( $icon ) ) : ?>
								<i class="<?php echo esc_attr( $icon ); ?>"></i>
							<?php endif; ?>
							<?php echo esc_attr( $stm_title ); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php $i++; ?>
			<?php endforeach; ?>
		</ul>

		<div class="tab-content">
			<?php $i = 1; ?>
			<?php
			foreach ( $items as $key => $item ) :
				$stm_post_type                = $item['tab_listing_type'];
				$pt_tax_arr[ $stm_post_type ] = $stm_post_type;
				?>
				<?php
				if ( ! empty( $stm_post_type ) ) :
					$stm_title = ( empty( $item['tab_title_single'] ) ) ? $multilisting->stm_get_listing_name_by_slug( $stm_post_type ) : $item['tab_title_single'];
					$slug      = ( isset( $item['tab_id_single'] ) ) ? sanitize_title( $item['tab_id_single'] ) : sanitize_title( $stm_title );
					?>
					<div role="tabpanel" class="tab-pane fade 
					<?php
					if ( 1 === $i ) {
						echo 'active in';}
					?>
					" id="<?php echo esc_attr( $stm_post_type ); ?>">
						<?php
						if ( ! empty( $item['taxonomy_tab'] ) ) {
							$tax_term       = explode( ',', $item['taxonomy_tab'] );
							$tax_term       = explode( ' | ', $tax_term[0] );
							$taxonomy_count = stm_get_custom_taxonomy_pt_count(
								$tax_term[0],
								$tax_term[1],
								$stm_post_type
							);
						} elseif ( ! empty( $item['filter_selected'] ) ) {
							$tax_term       = explode( ',', $item['filter_selected'] );
							$taxonomy_count = stm_get_custom_taxonomy_pt_count(
								'',
								$tax_term,
								$stm_post_type
							);
						} else {
							$taxonomy_count = stm_get_custom_taxonomy_pt_count(
								'',
								'',
								$stm_post_type
							);
						}
						if ( ! empty( $stm_post_type ) ) {
							if ( stm_listings_post_type() === $stm_post_type ) {
								$stm_post_type_link = esc_url( stm_get_listing_archive_link() );
							} else {
								$stm_post_type_link = home_url( '/' . $stm_post_type );
							}
						} else {
							$stm_post_type_link = esc_url( stm_get_listing_archive_link() );
						}
						?>

						<?php if ( isset( $item['filter_selected'] ) && ! empty( $item['filter_selected'] ) ) : ?>

							<form action="<?php echo esc_url( $stm_post_type_link ); ?>" method="GET">
								<div class="row">
									<div class="col-md-10">
										<div class="stm-filter-tab-selects filter stm-vc-ajax-filter">
											<input type="hidden" name="posttype" value="<?php echo esc_attr( $stm_post_type ); ?>">
											<?php
												set_query_var( 'listings_type', $stm_post_type );
												HooksMultiListing::stm_listings_attributes_filter( array( 'slug' => $stm_post_type ) );

												stm_listing_filter_get_selects(
													$item['filter_selected'],
													$slug,
													$words,
													$show_amount
												);
											?>
										</div>
									</div>
									<div class="col-md-2">
										<button type="submit" class="heading-font">
											<?php
											if ( empty( $search_button_label ) ) {
												$search_button_label = __( 'Search', 'motors_listing_types' );
											}

											echo esc_html( $search_button_label );
											?>
										</button>
									</div>
								</div>
							</form>

						<?php endif; ?>

					</div>

				<?php endif; ?>
				<?php $i++; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
</div>

<?php
$bind_tax = array();
foreach ( $pt_tax_arr as $item ) {
	set_query_var( 'listings_type', $item );
	HooksMultiListing::stm_listings_attributes_filter( array( 'slug' => $item ) );

	$bind_tax = array_merge( $bind_tax, stm_data_binding( true ) );
}


if ( ! empty( $bind_tax ) ) :
	?>

	<script>
		jQuery(function ($) {
			var options = <?php echo wp_json_encode( $bind_tax ); ?>,
				show_amount = <?php echo wp_json_encode( 'no' !== $show_amount ); ?>;

			if (show_amount) {
				$.each(options, function (tax, data) {
					$.each(data.options, function (val, option) {
						option.label += ' (' + option.count + ')';
					});
				});
			}

			$('.stm-filter-tab-selects.filter').each(function () {
				new STMCascadingSelect(this, options);
			});

			$("select[data-class='stm_select_overflowed']").on("change", function () {
				var sel = $(this);
				var selValue = sel.val();
				var selType = sel.attr("data-sel-type");
				var min = 'min_' + selType;
				var max = 'max_' + selType;
				if (selValue.includes("<")) {
					var str = selValue.replace("<", "").trim();
					$("input[name='" + min + "']").val("");
					$("input[name='" + max + "']").val(str);
				} else if (selValue.includes("-")) {
					var strSplit = selValue.split("-");
					$("input[name='" + min + "']").val(strSplit[0]);
					$("input[name='" + max + "']").val(strSplit[1]);
				} else {
					var str = selValue.replace(">", "").trim();
					$("input[name='" + min + "']").val(str);
					$("input[name='" + max + "']").val("");
				}
			});
		});
	</script>
	<?php
endif;

// reset everyting to original listings post type.
set_query_var( 'listings_type', stm_listings_post_type() );
remove_all_filters( 'stm_listings_attributes' );
