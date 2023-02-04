<?php for ( $i = 0; $i < $empty_cars; $i++ ) { ?>
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
<?php } ?>
