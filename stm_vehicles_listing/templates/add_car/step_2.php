<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!empty($id)) {
    $post_id = $id;
}
$user_features = array(
    array(
        'tab_title_single' => 'Comfort',
        'tab_title_labels' => 'A/C: Front,A/C: Rear,Backup Camera,Cruise Control,Navigation,Power Locks,Power Steering'
    ),
    array(
        'tab_title_single' => 'Entertainment',
        'tab_title_labels' => 'AM/FM Stereo,CD Player,DVD System,MP3 Player,Portable Audio,Premium Audio'
    )
);
?>

<div class="stm-form-2-features clearfix">
    <div class="stm-car-listing-data-single stm-border-top-unit ">
        <div class="title heading-font"><?php esc_html_e('Select Your Car Features', 'stm_vehicles_listing'); ?></div>
        <span class="step_number step_number_2 heading-font"><?php esc_html_e('step', 'stm_vehicles_listing'); ?>
            2</span>
    </div>


    <?php if (!empty($user_features)) {
        if (!empty($post_id)) {
            $features_car = get_post_meta($post_id, 'additional_features', true);
            $features_car = explode(',', $features_car);
        } else {
            $features_car = array();
        }
        foreach ($user_features as $user_feature) { ?>
            <div class="stm-single-feature">
                <div class="heading-font"><?php echo apply_filters('stm_vl_user_feat_tab_title_filter', $user_feature['tab_title_single']); ?></div>
                <?php $features = explode(',', $user_feature['tab_title_labels']); ?>
                <?php if (!empty($features)): ?>
                    <?php foreach ($features as $feature): ?>
                        <?php
                        $checked = '';

                        if (in_array($feature, $features_car)) {
                            $checked = 'checked';
                        };
                        ?>
                        <div class="feature-single">
                            <label>
                                <input type="checkbox" value="<?php echo esc_attr($feature); ?>"
                                       name="stm_car_features_labels[]" <?php echo esc_attr($checked); ?>/>
                                <span><?php echo esc_attr($feature); ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php }
    }
    ?>
</div>