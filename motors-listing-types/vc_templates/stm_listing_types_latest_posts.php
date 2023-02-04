<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';

wp_enqueue_style( 'stm_c_f_latest_posts', MULTILISTING_PLUGIN_URL . '/assets/css/stm_c_f_latest_posts.css' );

if ( empty( $posts_per_page ) ) {
	$posts_per_page = 3;
}

$posts = new WP_Query(array(
	'post_type' => 'post',
	'posts_per_page' => $posts_per_page,
	'post_status' => 'publish',
	'order' => 'DESC'
));

?>

<div class="stm-c-f-latest-posts-wrap">
	<h2>
		<?php echo esc_html( $title );?>
	</h2>
	<div class="subcontent heading-font">
		<?php echo $content; ?>
	</div>
	<div class="latest-posts">
		<?php
		if($posts->have_posts()) {
			while ($posts->have_posts()) {
				$posts->the_post();
				$date = get_the_date('d - M');
				$dateParse = explode(' - ', $date);
				?>

				<div class="recent-post-item">
					<div class="img">
						<a href="<?php echo esc_url( get_the_permalink() );?>">
							<?php the_post_thumbnail('full'); ?>
						</a>
						<div class="date">
							<span class="day heading-font"><?php echo esc_html( $dateParse[0] );?></span>
							<span class="month heading-font"><?php echo esc_html( $dateParse[1] );?></span>
						</div>
					</div>
					<h4><a href="<?php echo esc_url( get_the_permalink() );?>"><?php the_title(); ?></a></h4>
					<div class="excerpt">
						<?php the_excerpt(); ?>
					</div>
				</div>

			<?php }
		}
		wp_reset_postdata();
		?>
	</div>
</div>

