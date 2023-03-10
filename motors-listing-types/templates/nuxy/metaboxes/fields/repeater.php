<?php

/**
 * @var $field
 * @var $field_id
 * @var $field_value
 * @var $field_label
 * @var $field_name
 * @var $field_data
 * @var $section_name
 *
 */

wp_enqueue_script('multilisting_repeater', MULTILISTING_PLUGIN_URL . '/assets/js/repeater.js');

?>


<multilisting_repeater v-bind:fields="<?php echo esc_attr($field); ?>"
                 v-bind:parent_repeater="'parent'"
                 v-bind:field_label="<?php echo esc_attr($field_label); ?>"
                 v-bind:field_name="'<?php echo esc_attr($field_name); ?>'"
                 v-bind:field_id="'<?php echo esc_attr($field_id); ?>'"
                 v-bind:field_value="<?php echo esc_attr($field_value); ?>"
                 v-bind:field_data='<?php echo str_replace("'", "", json_encode($field_data)); ?>'
                 @wpcfto-get-value="$set(<?php echo esc_attr($field) ?>, 'value', $event)">
</multilisting_repeater>

<input type="hidden"
       :style="{'width' : '100%'}"
       name="<?php echo esc_attr($field_name); ?>"
       v-bind:id="'<?php echo esc_attr($field_id); ?>'"
       v-model="JSON.stringify(<?php echo esc_attr(wp_unslash($field_value)); ?>)" />