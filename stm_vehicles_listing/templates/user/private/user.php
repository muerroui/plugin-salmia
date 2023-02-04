<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$user = wp_get_current_user();
$user_id = $user->ID;
$user_fields = stm_get_user_custom_fields($user_id);

$tpl = 'inventory';
if (!empty($_GET['page_admin'])) {
    $tpl = sanitize_file_name($_GET['page_admin']);
}

?>

<div class="stm-user-private">
    <div class="container">
        <div class="row">

            <div class="col-md-3 col-sm-3 hidden-sm hidden-xs stm-sticky-user-sidebar">
                <?php stm_listings_load_template('user/private/sidebar', array('user' => $user, 'user_fields' => $user_fields)); ?>
            </div>

            <div class="col-md-9 col-sm-12">
                <div class="stm-user-private-main">
                    <?php stm_listings_load_template('user/private/' . $tpl, array('user_id' => $user_id)); ?>
                </div>
            </div>

        </div>
    </div>
</div>