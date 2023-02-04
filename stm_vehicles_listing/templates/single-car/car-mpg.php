<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$city_mpg = get_post_meta(get_the_ID(), 'city_mpg', true);
$highway_mpg = get_post_meta(get_the_ID(), 'highway_mpg', true);

if (!empty($city_mpg) || !empty($highway_mpg)): ?>

    <div class="single-car-mpg heading-font">
        <div class="text-center">
            <div class="clearfix dp-in text-left mpg-mobile-selector">
                <div class="mpg-unit">
                    <div class="mpg-value"><?php echo (!empty($city_mpg)) ? esc_attr($city_mpg) : "-"; ?></div>
                    <div class="mpg-label"><?php esc_html_e('city mpg', 'stm_vehicles_listing'); ?></div>
                </div>
                <div class="mpg-icon">

                </div>
                <div class="mpg-unit">
                    <div class="mpg-value"><?php echo (!empty($highway_mpg)) ? esc_attr($highway_mpg) : "-"; ?></div>
                    <div class="mpg-label"><?php esc_html_e('hwy mpg', 'stm_vehicles_listing'); ?></div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

