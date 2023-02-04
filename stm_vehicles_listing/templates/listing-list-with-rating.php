<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="listing-list-with-rating-loop">

        <div class="meta-top">
            <!--Title-->
            <?php stm_listings_load_template('loop/list/title_with_review'); ?>
        </div>

        <div class="image">
            <a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
                <div class="image-inner">
                    <?php if (has_post_thumbnail()):
                        the_post_thumbnail('stm-img-255-160', array('class' => 'img-responsive'));
                    else:
                    ?>
                        <img src="<?php echo get_template_directory_uri()?>/assets/images/plchldr255_160.jpg" />
                    <?php endif; ?>
                </div>
            </a>
        </div>

        <!--Item rating-->
        <div class="meta-middle">
            <?php stm_listings_load_template('loop/list/price_rating'); ?>
        </div>

        <!--Item exception-->
        <div class="meta-bottom">
            <?php echo get_the_excerpt(); ?>
        </div>
</div>
