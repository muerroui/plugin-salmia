<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

stm_motors_enqueue_scripts_styles('stm_image_filter_by_type');

$randId = 'owl-' . rand(1, 10000000);
// $items = vc_param_group_parse_atts($atts['items']);

$imgSizes = array(
    'items_3' => array('stm-img-635-255', 'stm-img-635-255', 'stm-img-445-540'),
    'items_4' => array('stm-img-445-255', 'stm-img-635-255', 'stm-img-635-255', 'stm-img-445-255'),
    'items_5' => array('stm-img-635-255', 'stm-img-445-255', 'stm-img-350-255', 'stm-img-350-255', 'stm-img-350-255')
);

$listings = STMMultiListing::stm_get_listings();

if ( empty( $limit ) ) $limit = -1;

if($post_type == 'all') {
    $post_type = [stm_listings_post_type()];
	if(!empty(STMMultiListing::stm_get_listing_type_slugs())) {
		$post_type = array_merge($post_type, STMMultiListing::stm_get_listing_type_slugs());
	}
}

$args = [
	'post_type' => $post_type,
	'posts_per_page' => intval( $limit ),
	'post_status' => 'publish',
	'meta_query' => [
		[
			'key'     => 'special_car',
			'value'   => 'on',
			'compare' => '='
		]
	]
];

if(isset($order_by) && !empty($order_by)) {
	if($order_by == 'popular') {
		$args[] = [
			"orderby" => 'meta_value_num',
            "meta_key" => 'stm_car_views',
            "order" => 'DESC'
		];
	}
}

$autoplay = $autoplay ? 'true' : 'false';

$listings = new WP_Query($args);

$num = 0;
$i = 0;

?>

<div class="stm-image-filter-wrap multilisting-masonry-carousel-wrap <?php echo esc_attr( $css_class ); ?>">
    <?php if(!empty($title)): ?>
        <div class="title">
            <h2><?php echo stm_do_lmth($title); ?></h2>
        </div>
    <?php endif; ?>

    <?php if($listings->have_posts()): ?>

        <div id="<?php echo esc_attr($randId);?>" class="owl-carousel stm-img-filter-container stm-img-<?php echo esc_attr($row_number);?>">

            <?php while($listings->have_posts()) : $listings->the_post(); ?>

                <?php
                    if($num == 0) echo '<div class="carousel-container">';

                    if(has_post_thumbnail(get_the_ID())) {
                        $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), $imgSizes['items_' . $row_number][$num]);
                    } else {
                        $featured_image_url = MULTILISTING_PLUGIN_URL . '/assets/img/placeholder-'.$imgSizes['items_' . $row_number][$num].'.jpg';
                    }

                    if($row_number == 3 && ($num == 0 || $num == 2)) echo '<div class="col-wrap">';
                ?>

                <div class="img-filter-item template-<?php echo esc_attr($row_number) . '-' . ($num%$row_number); ?>">
                    <a href="<?php the_permalink(); ?>">
                        <div class="img-wrap">
                            <img src="<?php echo esc_url($featured_image_url); ?>" />
                        </div>
                    </a>
                    <div class="body-type-data">
                        <div class="bt-title heading-font"><?php the_title(); ?></div>
                        <!-- <div class="bt-count normal_font">()</div> -->
                    </div>
                </div>

                <?php
                    if($row_number == 3 && ($num == 1 || $num == 2)) echo '</div>';
                    $num = ($row_number-1 > $num) ? $num + 1 : 0;
                    if($num == 0 || ($listings->found_posts - 1) == $i) echo '</div>';
                    $i++;
                ?>
            
            <?php endwhile; ?>

            <?php wp_reset_postdata(); ?>

        </div>

    <?php endif; ?>
</div>

<?php if ( $limit =! -1 && $limit <= $row_number ): ?>
<style>
    #<?php echo esc_html($randId); ?> .owl-controls {
        display: none!important;
    }
</style>
<?php endif; ?>

<script>
    (function($) {
        $(document).ready(function () {
            var owlIcon = $('#<?php echo esc_attr($randId); ?>');
            var owlRtl = false;
            if( $('body').hasClass('rtl') ) {
                owlRtl = true;
            }

            owlIcon.on('initialized.owl.carousel', function(e){
				setTimeout(function () {
					owlIcon.find('.owl-nav, .owl-dots').wrapAll("<div class='owl-controls'></div>");
					owlIcon.find('.owl-dots').remove();
				}, 500);
			});

            owlIcon.owlCarousel({
                items: 1,
                smartSpeed: 800,
                dots: false,
                margin: 0,
                autoplay: <?php echo $autoplay; ?>,
                nav: true,
                navElement: 'div',
                loop: true,
                responsiveRefreshRate: 1000,
            })
        });
    })(jQuery);
</script>