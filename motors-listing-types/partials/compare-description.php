<!--Compare car description-->
<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>">
	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
		<div class="compare-col-stm-empty">
			<div class="image">
				<?php if ( has_post_thumbnail() ) { ?>
					<div class="stm-compare-car-img">
						<?php the_post_thumbnail( 'stm-img-255-135', array( 'class' => 'img-responsive ' ) ); ?>
					</div>
				<?php } else { ?>
					<i class="stm-icon-add_car"></i>
					<img class="stm-compare-empty"
							src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/' . $compare_empty_placeholder ); ?>"
							alt="<?php esc_attr_e( 'Empty', 'motors_listing_types' ); ?>"/>
				<?php }; ?>
			</div>
		</div>
	</a>

	<div class="remove-compare-unlinkable">
		<span
			class="remove-from-compare"
			data-id="<?php echo esc_attr( get_the_ID() ); ?>"
			data-action="remove"
			data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
			>
			<i class="stm-icon-remove"></i>
			<span><?php esc_html_e( 'Remove from list', 'motors_listing_types' ); ?></span>
		</span>
	</div>

	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
		<div class="listing-car-item-meta">
			<div class="car-meta-top heading-font clearfix">
				<?php $price = get_post_meta( get_the_id(), 'price', true ); ?>
				<?php $sale_price = get_post_meta( get_the_id(), 'sale_price', true ); ?>
				<?php $car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true ); ?>
				<?php if ( empty( $car_price_form_label ) ) : ?>
					<?php if ( ! empty( $price ) and ! empty( $sale_price ) ) : ?>
						<div class="price discounted-price">
							<div class="regular-price">
								<?php echo esc_attr( stm_listing_price_view( $price ) ); ?>
							</div>
							<div class="sale-price">
								<?php echo esc_attr( stm_listing_price_view( $sale_price ) ); ?>
							</div>
						</div>
					<?php elseif ( ! empty( $price ) ) : ?>
						<div class="price">
							<div class="normal-price">
								<?php echo esc_attr( stm_listing_price_view( $price ) ); ?>
							</div>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<div class="price">
						<div
							class="normal-price"><?php echo esc_attr( $car_price_form_label ); ?></div>
					</div>
				<?php endif; ?>
				<div class="car-title"><?php the_title(); ?></div>
			</div>
		</div>
	</a>

	<span class="btn btn-default add-to-compare hidden" data-action="remove" data-id="<?php echo esc_js( get_the_ID() ); ?>">
		<?php esc_html_e( 'Remove from compare', 'motors_listing_types' ); ?>
	</span>
</div> <!--md-3-->
