<?php
/**
Plugin Name: Motors – Car Dealer, Classifieds & Listing
Plugin URI: http://stylemixthemes.com/
Description: Manage classified listings from the WordPress admin panel, and allow users to post classified listings directly to your website.
Author: StylemixThemes
Author URI: https://stylemixthemes.com/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: stm_vehicles_listing
Version: 6.8.6
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'STM_LISTINGS_PATH', dirname( __FILE__ ) );
define( 'STM_LISTINGS_URL', plugins_url( '', __FILE__ ) );
define( 'STM_LISTINGS', 'stm_vehicles_listing' );

define( 'STM_LISTINGS_IMAGES', STM_LISTINGS_URL . '/includes/admin/butterbean/images/' );

if ( ! is_textdomain_loaded( 'stm_vehicles_listing' ) ) {
	load_plugin_textdomain( 'stm_vehicles_listing', false, 'stm_vehicles_listing/languages' );
}

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/query.php';
require_once __DIR__ . '/includes/options.php';
require_once __DIR__ . '/includes/actions.php';
require_once __DIR__ . '/includes/fix-image-orientation.php';

if ( is_admin() ) {
    require_once __DIR__ . '/includes/admin/categories.php';
    require_once __DIR__ . '/includes/admin/enqueue.php';
    require_once __DIR__ . '/includes/admin/butterbean_metaboxes.php';
    require_once __DIR__ . '/includes/admin/category-image.php';
    require_once __DIR__ . '/includes/admin/admin_sort.php';

    /*For plugin only*/
    //require_once __DIR__ . '/includes/admin/startup.php';
}