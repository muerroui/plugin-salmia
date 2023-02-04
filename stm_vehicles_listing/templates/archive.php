<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header(); ?>

<?php stm_listings_load_template('filter/inventory/main'); ?>

<?php get_footer();