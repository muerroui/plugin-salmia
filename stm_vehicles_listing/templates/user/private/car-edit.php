<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<div class="stm_listing_edit_controls">

    <?php if (get_post_status($id) != 'pending'): ?>
        <div class="stm_edit_disable_car heading-font">
            <?php if (!empty($user_id)): ?>
                <a href="<?php echo esc_url(stm_get_add_page_url('edit', $id)); ?>">
                    <?php esc_html_e('Edit', 'stm_vehicles_listing'); ?>
                </a>
            <?php endif; ?>
            <?php if (get_post_status($id) == 'draft'): ?>
            <?php else: ?>
                <a href="<?php echo esc_url(add_query_arg(array('stm_disable_user_car' => $id), stm_get_author_link(''))); ?>"
                   class="disable_list"
                   data-id="<?php esc_attr($id); ?>"><?php esc_html_e('Disable', 'stm_vehicles_listing'); ?></a>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="stm_edit_pending_car">
            <h4><?php esc_html_e('Pending', 'stm_vehicles_listing'); ?></h4>
            <a href="<?php echo esc_url(stm_get_add_page_url('edit', $id)); ?>">
                <?php esc_html_e('Edit', 'stm_vehicles_listing'); ?>
            </a>
            <a class="stm-delete-confirmation"
               href="<?php echo esc_url(add_query_arg(array('stm_move_trash_car' => $id), stm_get_author_link(''))); ?>"
               data-title="<?php the_title(); ?>">
                <?php esc_html_e('Delete', 'stm_vehicles_listing'); ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if (get_post_status($id) == 'draft'): ?>
        <a class="stm-delete-confirmation"
           href="<?php echo esc_url(add_query_arg(array('stm_move_trash_car' => $id), stm_get_author_link(''))); ?>"
           data-title="<?php the_title(); ?>">
            <?php esc_html_e('Delete', 'stm_vehicles_listing'); ?>
        </a>
    <?php endif; ?>
</div>

<script type="text/javascript">
    /*Alert user before deleting car*/
    (function ($) {
        "use strict";

        $(document).ready(function () {
            $('.stm-delete-confirmation').click(function (e) {
                e.preventDefault();
                var urlToGo = $(this).attr('href');

                var confirm = window.confirm("<?php esc_html_e('Are you sure?'); ?>");

                if (confirm) {
                    window.location = urlToGo;
                }

            })
        })
    })(jQuery);
</script>