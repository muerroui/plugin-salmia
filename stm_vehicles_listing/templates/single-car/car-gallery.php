<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//Getting gallery list
$gallery = get_post_meta(get_the_id(), 'gallery', true);
$video_preview = get_post_meta(get_the_id(), 'video_preview', true);
$gallery_video = get_post_meta(get_the_id(), 'gallery_video', true);

$asSold = get_post_meta(get_the_ID(), 'car_mark_as_sold', true);
$sold_badge_color = stm_me_get_wpcfto_mod('sold_badge_bg_color');

// remove "special" if the listing is sold
if(!empty($asSold)) {
    delete_post_meta(get_the_ID(), 'special_car');
}

$special_car = get_post_meta(get_the_id(), 'special_car', true);

$badge_text = get_post_meta(get_the_ID(), 'badge_text', true);
$badge_bg_color = get_post_meta(get_the_ID(), 'badge_bg_color', true);

if (empty($badge_text)) {
    $badge_text = esc_html__('Special', 'stm_vehicles_listing');
}

$badge_style = '';
if (!empty($badge_bg_color)) {
    $badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

?>

<div class="stm-car-carousels">

    <?php if (empty($asSold) && !empty($special_car) and $special_car == 'on'): ?>
        <div class="special-label h5" <?php echo esc_attr($badge_style); ?>><?php echo esc_html__($badge_text, 'stm_vehicles_listing'); ?></div>
    <?php elseif(stm_sold_status_enabled() && !empty($asSold)): ?>
        <?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
        <div class="special-label h5" <?php echo esc_attr($badge_style); ?>><?php _e('Sold', 'motors'); ?></div>
    <?php endif; ?>
    
    <div class="stm-big-car-gallery owl-carousel">

        <?php if (has_post_thumbnail()):
            $full_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'full');
            //Post thumbnail first
            ?>
            <div class="stm-single-image"
                 data-id="big-image-<?php echo esc_attr(get_post_thumbnail_id(get_the_id())); ?>">
                <a href="<?php echo esc_url($full_src[0]); ?>" class="stm_light_gallery" rel="stm-car-gallery">
                    <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php if (!empty($video_preview) and !empty($gallery_video)): ?>
            <?php $src = wp_get_attachment_image_src($video_preview, 'full'); ?>
            <?php if (!empty($src[0])): ?>
                <div class="stm-single-image video-preview" data-id="big-image-<?php echo esc_attr($video_preview); ?>">
                    <a class="light_gallery_iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video); ?>">
                        <img src="<?php echo esc_url($src[0]); ?>" class="img-responsive"
                             alt="<?php esc_html_e('Video preview', 'stm_vehicles_listing'); ?>"/>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($gallery)): ?>
            <?php foreach ($gallery as $gallery_image): ?>
                <?php $src = wp_get_attachment_image_src($gallery_image, 'full'); ?>
                <?php $full_src = wp_get_attachment_image_src($gallery_image, 'full'); ?>
                <?php if (!empty($src[0])): ?>
                    <div class="stm-single-image" data-id="big-image-<?php echo esc_attr($gallery_image); ?>">
                        <a href="<?php echo esc_url($full_src[0]); ?>" class="stm_light_gallery" rel="stm-car-gallery">
                            <img src="<?php echo esc_url($src[0]); ?>"
                                 alt="<?php echo get_the_title(get_the_ID()) . ' ' . esc_html__('full', 'stm_vehicles_listing'); ?>"/>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <?php if (has_post_thumbnail() and (!empty($gallery) or (!empty($video_preview) and !empty($gallery_video)))): ?>
        <div class="stm-thumbs-car-gallery owl-carousel">

            <?php if (has_post_thumbnail()):
                //Post thumbnail first ?>
                <div class="stm-single-image"
                     id="big-image-<?php echo esc_attr(get_post_thumbnail_id(get_the_id())); ?>">
                    <?php the_post_thumbnail('thumbnail', array('class' => 'img-responsive')); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($video_preview) and !empty($gallery_video)): ?>
                <?php $src = wp_get_attachment_image_src($video_preview, 'thumbnail'); ?>
                <?php if (!empty($src[0])): ?>
                    <div class="stm-single-image video-preview"
                         data-id="big-image-<?php echo esc_attr($video_preview); ?>">
                        <a class="light_gallery_iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video); ?>">
                            <img src="<?php echo esc_url($src[0]); ?>"
                                 alt="<?php esc_html_e('Video preview', 'stm_vehicles_listing'); ?>"/>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($gallery)): ?>
                <?php foreach ($gallery as $gallery_image): ?>
                    <?php $src = wp_get_attachment_image_src($gallery_image, 'thumbnail'); ?>
                    <?php if (!empty($src[0])): ?>
                        <div class="stm-single-image" id="big-image-<?php echo esc_attr($gallery_image); ?>">
                            <img src="<?php echo esc_url($src[0]); ?>"
                                 alt="<?php echo get_the_title(get_the_ID()) . ' ' . esc_html__('full', 'stm_vehicles_listing'); ?>"/>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</div>


<!--Enable carousel-->
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var big = $('.stm-big-car-gallery');
        var small = $('.stm-thumbs-car-gallery');
        var flag = false;
        var duration = 800;

        var owlRtl = false;
        if ($('body').hasClass('rtl')) {
            owlRtl = true;
        }

        big
            .owlCarousel({
                rtl: owlRtl,
                items: 1,
                smartSpeed: 800,
                dots: false,
                nav: false,
                margin: 0,
                autoplay: false,
                loop: false,
                responsiveRefreshRate: 1000
            })
            .on('changed.owl.carousel', function (e) {
                $('.stm-thumbs-car-gallery .owl-item').removeClass('current');
                $('.stm-thumbs-car-gallery .owl-item').eq(e.item.index).addClass('current');
                if (!flag) {
                    flag = true;
                    small.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
            });

        small
            .owlCarousel({
                rtl: owlRtl,
                items: 5,
                smartSpeed: 800,
                dots: false,
                margin: 22,
                autoplay: false,
                nav: true,
                navElement: 'div',
                loop: false,
                navText: [],
                responsiveRefreshRate: 1000,
                responsive: {
                    0: {
                        items: 2
                    },
                    500: {
                        items: 4
                    },
                    768: {
                        items: 5
                    },
                    1000: {
                        items: 5
                    }
                }
            })
            .on('click', '.owl-item', function (event) {
                big.trigger('to.owl.carousel', [$(this).index(), 400, true]);
            })
            .on('changed.owl.carousel', function (e) {
                if (!flag) {
                    flag = true;
                    big.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
            });

        if ($('.stm-thumbs-car-gallery .stm-single-image').length < 6) {
            $('.stm-single-car-page .owl-controls').hide();
            $('.stm-thumbs-car-gallery').css({'margin-top': '22px'});
        }
    })
</script>