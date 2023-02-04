<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$filter = stm_listings_filter();
?>
<form action="<?php echo stm_listings_current_url() ?>" method="get" data-trigger="filter">
    <div class="filter filter-sidebar ajax-filter">

        <?php do_action('stm_listings_filter_before'); ?>

        <div class="sidebar-entry-header">
            <i class="stm-icon-car_search"></i>
            <span class="h4"><?php _e('Search Options', 'stm_vehicles_listing'); ?></span>
        </div>

        <div class="row row-pad-top-24">

            <?php foreach ($filter['filters'] as $attribute => $config):
                if (!empty($config['slider']) && $config['slider']):
                    stm_listings_load_template('filter/types/slider', array(
                        'taxonomy' => $config,
                        'options' => $filter['options'][$attribute]
                    ));
                else: ?>
                    <div class="col-md-12 col-sm-6 stm-filter_<?php echo esc_attr($attribute) ?>">
                        <div class="form-group">
                            <?php stm_listings_load_template('filter/types/select', array(
                                'options' => $filter['options'][$attribute],
                                'name' => $attribute
                            )); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>

        <!--View type-->
        <input type="hidden" id="stm_view_type" name="view_type"
               value="<?php echo esc_attr(stm_listings_input('view_type')); ?>"/>
        <!--Filter links-->
        <input type="hidden" id="stm-filter-links-input" name="stm_filter_link" value=""/>
        <!--Popular-->
        <input type="hidden" name="popular" value="<?php echo esc_attr(stm_listings_input('popular')); ?>"/>

        <input type="hidden" name="s" value="<?php echo esc_attr(stm_listings_input('s')); ?>"/>
        <input type="hidden" name="sort_order" value="<?php echo esc_attr(stm_listings_input('sort_order')); ?>"/>

        <div class="sidebar-action-units">
            <input id="stm-classic-filter-submit" class="hidden" type="submit"
                   value="<?php _e('Show cars', 'stm_vehicles_listing'); ?>"/>

            <a href="<?php echo esc_url(stm_get_listing_archive_link()); ?>"
               class="button"><span><?php _e('Reset all', 'stm_vehicles_listing'); ?></span></a>
        </div>

        <?php do_action('stm_listings_filter_after'); ?>
    </div>

</form>