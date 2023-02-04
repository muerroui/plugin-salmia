<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$asSold = get_post_meta(get_the_ID(), 'car_mark_as_sold', true);
$sold_badge_color = stm_me_get_wpcfto_mod('sold_badge_bg_color');

// remove "special" if the listing is sold
if(!empty($asSold)) {
    delete_post_meta(get_the_ID(), 'special_car');
}

$badge_text = get_post_meta(get_the_ID(), 'badge_text', true);
$special_car = get_post_meta(get_the_ID(),'special_car', true);

$badge_style = '';
$badge_bg_color = get_post_meta(get_the_ID(), 'badge_bg_color', true);
if (!empty($badge_bg_color)) {
    $badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

if ( empty($asSold) && !empty($special_car) && $special_car == 'on' && !empty($badge_text)): ?>
    <div class="special-label special-label-small h6" <?php echo esc_attr($badge_style); ?>>
        <?php echo esc_html__($badge_text, 'stm_vehicles_listing'); ?>
    </div>
<?php elseif(stm_sold_status_enabled() && !empty($asSold)): ?>
    <?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
    <div class="special-label special-label-small h6" <?php echo esc_attr($badge_style); ?>>
        <?php _e('Sold', 'motors'); ?>
    </div>
<?php endif; ?>