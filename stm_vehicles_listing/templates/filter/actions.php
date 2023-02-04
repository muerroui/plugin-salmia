<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="stm-sort-by-options clearfix">
    <span><?php esc_html_e('Sort by:', 'stm_vehicles_listing'); ?></span>

    <select name="sort_order">
        <?php //todo: make this as array & apply_filters ?>
        <?php //todo: mark current as selected ?>
        <option value="date_high" selected><?php esc_html_e('Date: newest first', 'stm_vehicles_listing'); ?></option>
        <option value="date_low"><?php esc_html_e('Date: oldest first', 'stm_vehicles_listing'); ?></option>
        <option value="price_low"><?php esc_html_e('Price: lower first', 'stm_vehicles_listing'); ?></option>
        <option value="price_high"><?php esc_html_e('Price: highest first', 'stm_vehicles_listing'); ?></option>
        <option value="mileage_low"><?php esc_html_e('Mileage: lowest first', 'stm_vehicles_listing'); ?></option>
        <option value="mileage_high"><?php esc_html_e('Mileage: highest first', 'stm_vehicles_listing'); ?></option>
    </select>

</div>