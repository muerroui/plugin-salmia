<div class="row row-4 stm-compare-row">
	<div class="col-md-3 col-sm-3 hidden-xs">
		<?php if ( ! empty( $filter_options ) ) : ?>
			<div class="compare-options">
				<table>
					<?php foreach ( $filter_options as $filter_option ) : ?>
						<?php if ( $filter_option['slug'] != 'price' ) { ?>
							<tr>
								<?php $compare_option = get_post_meta( get_the_id(), $filter_option['slug'], true ); ?>
								<td class="compare-value-hover <?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>"
									data-value="<?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>">
									<?php esc_html_e( $filter_option['single_name'], 'motors_listing_types' ); ?>
								</td>
							</tr>
						<?php }; ?>
					<?php endforeach; ?>
				</table>
			</div>
		<?php endif; ?>
	</div>
	<?php
	while ( $compares->have_posts() ) :
		$compares->the_post();
		?>
		<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>">
			<?php if ( ! empty( $filter_options ) ) : ?>
				<div class="compare-values">
					<?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
						<div class="compare-car-visible">
							<?php the_post_thumbnail( 'stm-img-796-466', array( 'class' => 'img-responsive stm-img-mobile-compare' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="remove-compare-unlinkable">
						<span class="remove-from-compare"
								data-id="<?php echo esc_attr( get_the_ID() ); ?>"
								data-action="remove"
								data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
								>
							<i class="stm-icon-remove"></i>
							<span><?php esc_html_e( 'Remove from list', 'motors_listing_types' ); ?></span>
						</span>
					</div>
					<h4 class="text-transform compare-car-visible"><?php the_title(); ?></h4>
					<table>
						<?php if ( wp_is_mobile() ) : ?>
							<tr>
							<td class="compare-value-hover">
								<div class="h5" data-option="<?php esc_html_e( 'Price', 'motors_listing_types' ); ?>">&nbsp;
									<?php $price = get_post_meta( get_the_id(), 'price', true ); ?>
									<?php $sale_price = get_post_meta( get_the_id(), 'sale_price', true ); ?>
									<?php $car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true ); ?>
									<?php if ( empty( $car_price_form_label ) ) : ?>
										<?php if ( ! empty( $price ) and ! empty( $sale_price ) ) : ?>
											<span class="regular-price"><?php echo esc_attr( stm_listing_price_view( $price ) ); ?></span>
											<span class="sale-price"><?php echo esc_attr( stm_listing_price_view( $sale_price ) ); ?></span>
										<?php elseif ( ! empty( $price ) ) : ?>
											<span class="normal-price"><?php echo esc_attr( stm_listing_price_view( $price ) ); ?></span>
										<?php endif; ?>
									<?php else : ?>
										<span class="normal-price"><?php echo esc_attr( $car_price_form_label ); ?></span>
									<?php endif; ?>
								</div>
							</td>
						</tr>
						<?php endif; ?>
						<?php foreach ( $filter_options as $filter_option ) : ?>
							<?php if ( $filter_option['slug'] != 'price' ) { ?>
								<tr>
									<?php $compare_option = get_post_meta( get_the_id(), $filter_option['slug'], true ); ?>
									<td class="compare-value-hover <?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>"
										data-value="<?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>">
										<div class="h5" data-option="<?php esc_html_e( $filter_option['single_name'], 'motors_listing_types' ); ?>">
											<?php
											if ( ! empty( $compare_option ) ) {
												// if numeric get value from meta
												if ( ! empty( $filter_option['numeric'] ) and $filter_option['numeric'] ) {
													echo esc_attr( $compare_option );
												} else {
													// not numeric, get category name by meta
													$data_meta_array = explode( ',', $compare_option );
													$datas           = array();

													if ( ! empty( $data_meta_array ) ) {
														foreach ( $data_meta_array as $data_meta_single ) {
															$data_meta = get_term_by( 'slug', $data_meta_single, $filter_option['slug'] );
															if ( ! empty( $data_meta->name ) ) {
																$datas[] = esc_attr( $data_meta->name );
															}
														}
													}
													if ( ! empty( $datas ) ) {
														echo implode( ', ', $datas );

													} else {
														esc_html_e( 'None', 'motors_listing_types' );
													}
												}
											} else {
												esc_html_e( 'None', 'motors_listing_types' );
											}
											?>
										</div>
									</td>
								</tr>
							<?php } ?>
						<?php endforeach; ?>
					</table>
				</div>
			<?php endif; ?>
		</div> <!--md-3-->
	<?php endwhile; ?>
	<?php for ( $i = 0; $i < $empty_cars; $i++ ) { ?>
		<?php if ( ! empty( $filter_options ) ) : ?>
			<div class="col-md-3 col-sm-3 hidden-xs">
				<div class="compare-options">
					<table>
						<?php foreach ( $filter_options as $filter_option ) : ?>
							<?php if ( $filter_option['slug'] != 'price' ) { ?>
								<tr>
									<td class="compare-value-hover">&nbsp;</td>
								</tr>
							<?php }; ?>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		<?php endif; ?>
	<?php } ?>
</div> <!--row-->
