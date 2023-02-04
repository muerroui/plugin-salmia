<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$user = wp_get_current_user();

$vars = get_queried_object();

if (!empty($_GET['view-myself'])) {
    stm_listings_load_template('user/public/user');
} else {
    if ($user->ID !== $vars->ID) {
        stm_listings_load_template('user/public/user');
    } else {
        stm_listings_load_template('user/private/user');
    }
}
?>