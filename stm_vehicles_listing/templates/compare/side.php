<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="col-md-3 col-sm-3 hidden-xs">
    <?php if (!empty($filter_options)): ?>
        <div class="compare-options">
            <table>
                <?php foreach ($filter_options as $filter_option): ?>
                    <?php if ($filter_option['slug'] != 'price') { ?>
                        <tr>
                            <?php $compare_option = get_post_meta(get_the_id(), $filter_option['slug'], true); ?>
                            <td class="compare-value-hover"
                                data-value="<?php echo esc_attr('compare-value-' . $filter_option['slug']) ?>">
                                <div
                                    class="h5"><?php esc_html_e($filter_option['single_name'], 'stm_vehicles_listing'); ?></div>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>