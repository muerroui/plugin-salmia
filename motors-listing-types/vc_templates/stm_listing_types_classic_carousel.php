<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

$random_id = 'owl' . wp_rand( 1, 99999 );

$listings = STMMultiListing::stm_get_listings();

if(empty($limit)) $limit = -1;

if($post_type == 'all') {
    $post_type = [stm_listings_post_type()];
	if(!empty(STMMultiListing::stm_get_listing_type_slugs())) {
		$post_type = array_merge($post_type, STMMultiListing::stm_get_listing_type_slugs());
	}
}

$args = [
	'post_type' => $post_type,
	'posts_per_page' => intval($limit),
	'post_status' => 'publish'
];

if(isset($sort_by) && !empty($sort_by)) {
	if( $sort_by == 'popular' ) {
		$args[] = [
			"orderby" => 'meta_value_num',
            "meta_key" => 'stm_car_views',
            "order" => 'DESC'
		];
	} elseif( $sort_by == 'featured' ) {
		$args['meta_query'] = [
			[
				'key'     => 'special_car',
				'value'   => 'on',
				'compare' => '='
			]
		];
	}
}

$autoplay = $autoplay ? 'true' : 'false';

$listings = new WP_Query($args);
?>

<div class="wrap_multilisting_carousel <?php echo esc_attr($css_class); ?> visible_items_<?php echo esc_attr( intval( $vis_limit ) ); ?>">
	<div class="wrap_multilisting_carousel_inner car-listing-row owl-carousel" id="<?php echo esc_attr($random_id); ?>">
		<?php while($listings->have_posts()): $listings->the_post(); ?>
			<?php
                $regular_price_label = get_post_meta(get_the_ID(), 'regular_price_label', true);
                $special_price_label = get_post_meta(get_the_ID(),'special_price_label',true);

                $price = get_post_meta(get_the_id(),'price',true);
                $sale_price = get_post_meta(get_the_id(),'sale_price',true);

                $car_price_form_label = get_post_meta(get_the_ID(), 'car_price_form_label', true);

                $data = array(
                    'data_price' => 0,
                    'data_mileage' => 0,
                );

                if(!empty($price)) {
                    $data['data_price'] = $price;
                }

                if(!empty($sale_price)) {
                    $data['data_price'] = $sale_price;
                }

                if(empty($price) and !empty($sale_price)) {
                    $price = $sale_price;
                }

                $mileage = get_post_meta(get_the_id(),'mileage',true);

                if(!empty($mileage)) {
                    $data['data_mileage'] = $mileage;
                }

                $data['class'] = array('stm-directory-grid-loop stm-isotope-listing-item all');
                $sold = get_post_meta(get_the_ID(), 'car_mark_as_sold', true);
			?>

			<div class="stm-directory-grid-loop stm-isotope-listing-item all stm-directory-grid-loop stm-isotope-listing-item all <?php if (!empty($sold)) echo esc_attr('car-as-sold'); ?>">
				<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
					<?php stm_listings_load_template('loop/classified/grid/image', $data); ?>

					<div class="listing-car-item-meta">
						<?php stm_listings_load_template(
							'loop/default/grid/title_price',
							array(
								'price' => $price,
								'sale_price' => $sale_price,
								'car_price_form_label' => $car_price_form_label
							)); ?>

						<?php stm_multilisting_load_template('templates/grid-listing-data'); ?>
					</div>
				</a>
			</div>
		<?php endwhile; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</div>

<script>
(function($) {
	$(window).on('load', function () {
		var multilistingCarousel = $('#<?php echo esc_attr($random_id); ?>');
		var owlRtl = false;
		if( $('body').hasClass('rtl') ) {
			owlRtl = true;
		}

		multilistingCarousel.on('initialized.owl.carousel', function (e) {
			setTimeout(function () {
				multilistingCarousel.find('.owl-dots').before('<div class="stm-owl-prev"><i class="fas fa-angle-left"></i></div>');
				multilistingCarousel.find('.owl-dots').after('<div class="stm-owl-next"><i class="fas fa-angle-right"></i></div>');
				multilistingCarousel.find('.owl-dots, .stm-owl-prev, .stm-owl-next').wrapAll("<div class='owl-controls'></div>");
				multilistingCarousel.find('.owl-nav').remove();
			}, 500);
		});

		multilistingCarousel.on('click', '.stm-owl-prev', function () {
			multilistingCarousel.trigger('prev.owl.carousel');
		});

		multilistingCarousel.on('click', '.stm-owl-next', function () {
			multilistingCarousel.trigger('next.owl.carousel');
		});

		multilistingCarousel.owlCarousel({
			items: <?php echo esc_js( intval( $vis_limit ) ); ?>,
			smartSpeed: 800,
			dots: true,
			margin: 20,
			autoplay: <?php echo $autoplay; ?>,
			nav: false,
			navElement: 'div',
			loop: false,
			responsiveRefreshRate: 1000,
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items: <?php echo esc_js( intval( $vis_limit ) ); ?>
				}
			}
		})
	});
})(jQuery);
</script>
