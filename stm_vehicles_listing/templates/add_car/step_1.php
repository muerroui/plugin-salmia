<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$data = stm_get_single_car_listings();

$terms_args = array(
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,
    'fields' => 'all',
    'pad_counts' => true,
);
?>

<div class="stm_add_car_form_1">
    <div class="stm-car-listing-data-single stm-border-top-unit ">
        <div class="title heading-font"><?php esc_html_e('Car Details', 'stm_vehicles_listing'); ?></div>
        <span class="step_number step_number_1 heading-font"><?php esc_html_e('step', 'stm_vehicles_listing'); ?>1</span>
    </div>

    <div class="stm-form-1-end-unit clearfix">
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $data_key => $data_unit): ?>
                <?php $terms = get_terms($data_unit['slug'], $terms_args); ?>
                <div class="stm-form-1-quarter">
                    <?php if (!empty($data_unit['numeric']) and $data_unit['numeric']): ?>

                        <?php $value = '';
                        if (!empty($id)) {
                            $value = get_post_meta($id, $data_unit['slug'], true);
                        } ?>

                        <input
                            type="text"
                            class="form-control"
                            name="stm_s_s_<?php echo esc_attr($data_unit['slug']); ?>"
                            value="<?php echo esc_attr($value); ?>"
                            placeholder="<?php esc_html_e('Enter', 'stm_vehicles_listing'); ?> <?php esc_html_e($data_unit['single_name'], 'stm_vehicles_listing'); ?> <?php if (!empty($data_unit['number_field_affix'])) {
                                echo '(';
                                esc_html_e($data_unit['number_field_affix'], 'stm_vehicles_listing');
                                echo ')';
                            } ?>"
                        />
                    <?php else: ?>
                        <select name="stm_s_s_<?php echo esc_attr($data_unit['slug']) ?>">
                            <?php $selected = '';
                            if (!empty($id)) {
                                $selected = get_post_meta($id, $data_unit['slug'], true);
                            }
                            ?>
                            <option
                                value=""><?php esc_html_e('Select', 'stm_vehicles_listing') ?><?php esc_html_e($data_unit['single_name']); ?></option>
                            <?php if (!empty($terms)):
                                foreach ($terms as $term): ?>
                                    <?php
                                    $selected_opt = '';
                                    if ($selected == $term->slug) {
                                        $selected_opt = 'selected';
                                    } ?>
                                    <option
                                        value="<?php echo esc_attr($term->slug); ?>" <?php echo esc_attr($selected_opt); ?>><?php echo esc_attr($term->name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    <?php endif; ?>
                    <div class="stm-label">
                        <?php if (!empty($data_unit['font'])): ?>
                            <i class="<?php echo esc_attr($data_unit['font']); ?>"></i>
                        <?php endif; ?>
                        <?php esc_html_e($data_unit['single_name'], 'stm_vehicles_listing'); ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <style type="text/css">
                <?php foreach($data as $data_unit): ?>

                .stm-form-1-end-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'stm_vehicles_listing'); ?> <?php echo esc_html__($data_unit['single_name'], 'stm_vehicles_listing'); ?>"] {
                    background-color: transparent !important;
                    border: 1px solid rgba(255, 255, 255, 0.5);
                    color: #888 !important;
                }

                <?php endforeach; ?>
            </style>

            <?php
            if (!empty($id)) {
                $vars = array(
                    'id' => $id
                );
            } else {
                $vars = array();
            }
            ?>
            <?php stm_listings_load_template('add_car/step_1_additional_fields', $vars); ?>

        <?php endif; ?>
    </div>
</div>