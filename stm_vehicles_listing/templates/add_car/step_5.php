<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$note = '';
if (!empty($id)) {
    $item_id = $id;
    $note = get_post_meta($id,'listing_seller_note', true);
}
?>

<div class="stm-form-5-notes clearfix">
    <div class="stm-car-listing-data-single stm-border-top-unit ">
        <div class="title heading-font"><?php esc_html_e('Enter Seller\'s notes', 'stm_vehicles_listing'); ?></div>
        <span class="step_number step_number_5 heading-font"><?php esc_html_e('step', 'stm_vehicles_listing'); ?>
            5</span>
    </div>
    <div class="row stm-relative">
        <div class="col-md-12">
            <div class="stm-phrases-unit">
                <?php if (!empty($stm_phrases)): $stm_phrases = explode(',', $stm_phrases); ?>
                    <div class="stm_phrases">
                        <div class="inner">
                            <i class="fas fa-times"></i>
                            <h5><?php esc_html_e('Select all the phrases that apply to your vehicle.', 'stm_vehicles_listing'); ?></h5>
                            <?php if (!empty($stm_phrases)): ?>
                                <div class="clearfix">
                                    <?php foreach ($stm_phrases as $phrase): ?>
                                        <label>
                                            <input type="checkbox" name="stm_phrase"
                                                   value="<?php echo esc_attr($phrase); ?>"/>
                                            <span><?php echo esc_attr($phrase); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <a href="#" class="button"><?php esc_html_e('Apply', 'stm_vehicles_listing'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <textarea class="form-control"
                          placeholder="<?php esc_html_e('Enter Seller\'s notes', 'stm_vehicles_listing'); ?>"
                          name="stm_seller_notes"><?php echo esc_attr($note); ?></textarea>
            </div>
        </div>
    </div>
</div>