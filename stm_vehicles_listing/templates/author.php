<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$user = wp_get_current_user();

$vars = get_queried_object();

get_header();

$user = get_queried_object();
$current_user = wp_get_current_user();

if ($user->ID === $current_user->ID and empty($_GET['view-myself'])) { ?>

    <script>
        jQuery(document).ready(function () {
            <?php if(!empty($_GET['stm_disable_user_car'])): ?>
            window.history.pushState('', '', '<?php echo esc_url(stm_get_author_link('')); ?>');
            <?php endif; ?>

            <?php if(!empty($_GET['stm_enable_user_car'])): ?>
            window.history.pushState('', '', '<?php echo esc_url(stm_get_author_link('')); ?>');
            <?php endif; ?>

            <?php if(!empty($_GET['stm_move_trash_car'])): ?>
            window.history.pushState('', '', '<?php echo esc_url(stm_get_author_link('')); ?>');
            <?php endif; ?>
        });
    </script>
<?php }

if (is_user_logged_in()) {
    stm_listings_load_template('user/private-route');
} else {
    stm_listings_load_template('user/public/user');
}
?>


<?php get_footer(); ?>
