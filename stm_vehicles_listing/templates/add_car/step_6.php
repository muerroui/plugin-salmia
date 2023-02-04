<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$price = $sale_price = $label = '';
if (!empty($id)) {
    $price = get_post_meta($id, 'price', true);
    $sale_price = get_post_meta($id, 'sale_price', true);
    $label = get_post_meta($id, 'car_price_form_label', true);
}
?>


<div class="stm-form-price-edit">
    <div class="stm-car-listing-data-single stm-border-top-unit ">
        <div class="title heading-font"><?php esc_html_e('Set Your Asking Price', 'stm_vehicles_listing'); ?></div>
        <span class="step_number step_number_6 heading-font"><?php esc_html_e('step', 'stm_vehicles_listing'); ?>
            6</span>
    </div>

    <div class="row stm-relative">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="stm_price_input">
                        <div class="stm_label heading-font"><?php esc_html_e('Price', 'stm_vehicles_listing'); ?>*
                            (<?php echo stm_get_price_currency(); ?>)
                        </div>
                        <input class="form-control" type="number" min="0" class="heading-font" name="stm_car_price"
                               value="<?php echo esc_attr($price); ?>" required/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="stm_price_input">
                        <div class="stm_label heading-font"><?php esc_html_e('Sale Price', 'stm_vehicles_listing'); ?>
                            (<?php echo stm_get_price_currency(); ?>)
                        </div>
                        <input class="form-control" type="number" min="0" class="heading-font" name="stm_car_sale_price"
                               value="<?php echo esc_attr($sale_price); ?>"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="stm_price_input">
                        <div
                            class="stm_label heading-font"><?php esc_html_e('Custom label instead of price', 'stm_vehicles_listing'); ?></div>
                        <input class="form-control" type="text" class="heading-font" name="car_price_form_label"
                               value="<?php echo esc_attr($label); ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>