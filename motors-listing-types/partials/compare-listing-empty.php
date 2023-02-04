<div class="row row-4 car-listing-row stm-car-compare-row stm-no-cars">
	<div class="col-md-3 col-sm-3">
		<h2 class="compare-title">
			<?php echo esc_html( $title_text ); ?>
		</h2>
		<div class="colored-separator text-left">
			<?php if ( stm_is_boats() ) : ?>
				<div><i class="stm-boats-icon-wave stm-base-color"></i></div>
			<?php else : ?>
				<div class="first-long"></div>
				<div class="last-short"></div>
			<?php endif; ?>
		</div>
	</div>
	<?php for ( $i = 0; $i < 3; $i++ ) { ?>
		<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm-empty">
			<a href="<?php echo esc_url( $empty_add_link ); ?>">
				<div class="image">
					<i class="stm-icon-add_car"></i>
					<img
						class="stm-compare-empty"
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
						alt="<?php esc_attr_e( 'Empty', 'motors_listing_types' ); ?>"
					/>
				</div>
				<div class="h5"><?php echo esc_html( $add_to_text ); ?></div>
			</a>
		</div>
	<?php } ?>
</div> <!--row-->
<div class="row row-4 stm-compare-row hidden-xs">
	<div class="col-md-3 col-sm-3 col-xs-4 hidden-xs">
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
	<?php for ( $i = 0; $i < 3; $i++ ) { ?>
		<?php if ( ! empty( $filter_options ) ) : ?>
			<div class="col-md-3 col-sm-3 col-xs-4">
				<div class="compare-options">
					<table>
						<?php foreach ( $filter_options as $filter_option ) : ?>
							<?php if ( $filter_option['slug'] != 'price' ) { ?>
								<tr>
									<td>&nbsp;</td>
								</tr>
							<?php }; ?>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		<?php endif; ?>
	<?php } ?>
</div> <!--row-->
