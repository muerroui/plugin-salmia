<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<select name="<?php echo esc_attr($name) ?>" class="form-control">
    <?php if(!empty($options)): ?>
        <?php foreach ($options as $value => $option) : ?>
            <option
                value="<?php echo esc_attr($value) ?>" <?php selected($option['selected']) ?> <?php // disabled($option['disabled']) ?>><?php echo esc_html($option['label']); ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>