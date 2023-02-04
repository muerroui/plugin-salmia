<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$value = '';
if (!empty($id)) {
    $value = get_the_title($id);
}
?>

<div class="stm-car-listing-data-single stm-border-top-unit ">
    <div class="stm_add_car_title_form">
        <div class="title heading-font"><?php esc_html_e('Car title', 'stm_vehicles_listing'); ?></div>
        <input class="form-control" type="text" name="stm_car_main_title"
               placeholder="<?php esc_html_e('Title', 'stm_vehicles_listing'); ?>"
               value="<?php echo esc_attr($value); ?>">
    </div>
</div>