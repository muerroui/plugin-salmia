<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$histories = 'Carfax, Autocheck';
$get_params = false;

$show_registered = stm_me_get_wpcfto_mod('show_registered', false);
$show_vin = stm_me_get_wpcfto_mod('show_vin', false);
$show_history = stm_me_get_wpcfto_mod('show_history', false);

if (!empty($id)) {
    $post_id = $id;
}

if (empty($post_id)) {
    $post_id = false;
}

if (!$get_params) {
    if ($show_registered) { ?>
        <?php
        $data_value = get_post_meta($post_id, 'registration_date', true);
        ?>
        <div class="stm-form-1-quarter stm_registration_date">
            <input type="text" name="stm_registered"
                   class="form-control stm-years-datepicker<?php if (!empty($data_value)) echo ' stm_has_value'; ?>"
                   placeholder="<?php esc_html_e('Enter date', 'stm_vehicles_listing'); ?>"
                   value="<?php echo esc_attr($data_value); ?>"/>
            <div class="stm-label">
                <i class="stm-icon-key"></i>
                <?php esc_html_e('Registered', 'stm_vehicles_listing'); ?>
            </div>
        </div>
    <?php }
    if ($show_vin) { ?>
        <?php
        $data_value = get_post_meta($post_id, 'vin_number', true);
        ?>
        <div class="stm-form-1-quarter stm_vin">
            <input type="text"
                   name="stm_vin"
                   class="form-control"
                <?php if (!empty($data_value)) { ?> class="stm_has_value" <?php } ?>
                   value="<?php echo esc_attr($data_value); ?>"
                   placeholder="<?php esc_html_e('Enter VIN', 'stm_vehicles_listing'); ?>"/>

            <div class="stm-label">
                <i class="stm-service-icon-vin_check"></i>
                <?php esc_html_e('VIN', 'stm_vehicles_listing'); ?>
            </div>
        </div>
    <?php }
    if ($show_history) { ?>
        <?php
        $data_value = get_post_meta($post_id, 'history', true);
        $data_value_link = get_post_meta($post_id, 'history_link', true);
        ?>
        <div class="stm-form-1-quarter stm_history">
            <input type="text"
                   name="stm_history_label"
                   class="form-control"
                <?php if (!empty($data_value)) { ?> class="stm_has_value" <?php } ?>
                   value="<?php echo esc_attr($data_value) ?>"
                   placeholder="<?php esc_html_e('Vehicle History Report', 'stm_vehicles_listing'); ?>"/>

            <div class="stm-label">
                <i class="stm-icon-time"></i>
                <?php esc_html_e('History', 'stm_vehicles_listing'); ?>
            </div>

            <div class="stm-history-popup stm-invisible">
                <div class="inner">
                    <i class="fas fa-times"></i>
                    <h5><?php esc_html_e('Vehicle history', 'stm_vehicles_listing'); ?></h5>
                    <?php if (!empty($histories)):
                        $histories = explode(',', $histories);
                        if (!empty($histories)):
                            echo '<div class="labels-units">';
                            foreach ($histories as $history): ?>
                                <label>
                                    <input type="radio" name="stm_chosen_history"
                                           value="<?php echo esc_attr($history); ?>"/>
                                    <span><?php echo esc_attr($history); ?></span>
                                </label>
                            <?php endforeach;
                            echo '</div>';
                        endif;
                    endif; ?>
                    <input class="form-control" type="text" name="stm_history_link"
                           placeholder="<?php esc_html_e('Insert link', 'stm_vehicles_listing') ?>"
                           value="<?php echo esc_url($data_value_link); ?>"/>
                    <a href="#" class="button"><?php esc_html_e('Apply', 'stm_vehicles_listing'); ?></a>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var $ = jQuery;
                var $stm_handler = $('.stm-form-1-quarter.stm_history input[name="stm_history_label"]');
                $stm_handler.focus(function () {
                    $('.stm-history-popup').removeClass('stm-invisible');
                });

                $('.stm-history-popup .button').click(function (e) {
                    e.preventDefault();
                    $('.stm-history-popup').addClass('stm-invisible');

                    if ($('input[name=stm_chosen_history]:radio:checked').length > 0) {
                        $stm_checked = $('input[name=stm_chosen_history]:radio:checked').val();
                    } else {
                        $stm_checked = '';
                    }

                    $stm_handler.val($stm_checked);
                })

                $('.stm-history-popup .fa-remove').click(function () {
                    $('.stm-history-popup').addClass('stm-invisible');
                });
            });
        </script>
    <?php }
} else {

    $additional_fields = array();
    if ($show_registered) {
        $additional_fields[] = 'stm_registered';
    }
    if ($show_vin) {
        $additional_fields[] = 'stm_vin';
    }
    if ($show_history) {
        $additional_fields[] = 'stm_history';
    }

    return $additional_fields;
}