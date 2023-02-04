<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (empty($_COOKIE['compare_ids'])) {
    $compare_ids = array();
} else {
    $compare_ids = $_COOKIE['compare_ids'];
}

$filter_options = stm_get_single_car_listings();

$empty_cars = 3 - count($compare_ids);
$counter = 0;
?>

<div class="stm_compare_cars_archive">

    <?php if (!empty($compare_ids) or count($compare_ids) != 0): ?>
        <?php $args = array(
            'post_type' => 'listings',
            'post_status' => 'publish',
            'posts_per_page' => 3,

            'post__in' => $compare_ids,
        );
        $compares = new WP_Query($args);
        ?>

        <?php if ($compares->have_posts()): ?>
            <div class="row row-4 stm-compare-row">

                <?php stm_listings_load_template('compare/side', array('filter_options' => $filter_options)); ?>

                <?php stm_listings_load_template('compare/car-loop', array('compares' => $compares, 'filter_options' => $filter_options)); ?>
            </div> <!--row-->
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>

        <!--Additional features-->
        <?php stm_listings_load_template('compare/features', array('compares' => $compares)); ?>
    <?php else: ?>
        <h4><?php esc_html_e('No items in compare', 'stm_vehicles_listing'); ?></h4>
    <?php endif; ?>

</div> <!--container-->

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        stm_equal_cols();

        $('.compare-value-hover').hover(function () {
            var dataValue = $(this).data('value');
            $('.compare-value-hover[data-value = ' + dataValue + ']').addClass('hovered');
        }, function () {
            $('.compare-value-hover').removeClass('hovered');
        })

        $(window).load(function () {
            stm_equal_cols();
        })

        function stm_equal_cols() {
            var colHeight = 0;
            $('.stm_compare_col_top').each(function () {
                var currentColHeight = $(this).outerHeight();

                if (currentColHeight > colHeight) {
                    colHeight = currentColHeight;
                }
            });

            $('.stm_compare_col_top').css({
                'min-height': colHeight + 'px'
            });

            $('.compare-options').css({
                'margin-top': colHeight + 20 + 'px'
            });

        }

    })
</script>