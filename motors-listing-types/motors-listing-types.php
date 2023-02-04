<?php
/**
 * Plugin Name: Motors Listing Types
 * Plugin URI: http://stylemixthemes.com/
 * Description: Allows to use several listing types in Motors Theme
 * Author: StylemixThemes
 * Author URI: https://stylemixthemes.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: motors_listing_types
 * Version: 1.1.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MULTILISTING_PLUGIN_URL', plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) ) );
define( 'MULTILISTING_PATH', plugin_dir_path( __FILE__ ) );
define( 'MULTILISTING_V', '1.1.7' );

if ( ! is_textdomain_loaded( 'motors_listing_types' ) ) {
	load_plugin_textdomain( 'motors_listing_types', false, 'motors-listing-types/languages' );
}

require_once dirname( __FILE__ ) . '/nuxy/NUXY.php';

require_once MULTILISTING_PATH . '/includes/functions.php';
require_once MULTILISTING_PATH . '/includes/vc_shortcodes.php';
require_once MULTILISTING_PATH . '/classes/multilisting.class.php';
require_once MULTILISTING_PATH . '/classes/categories.class.php';
require_once MULTILISTING_PATH . '/classes/butterbean.class.php';
require_once MULTILISTING_PATH . '/classes/metabox.class.php';
require_once MULTILISTING_PATH . '/classes/templates.class.php';
require_once MULTILISTING_PATH . '/classes/hooks.class.php';
