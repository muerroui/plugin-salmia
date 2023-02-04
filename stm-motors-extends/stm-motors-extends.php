<?php
/*
 Plugin Name: STM Motors Extends
 Plugin URI:  https://stylemixthemes.com/
 Description: STM Motors Extends WordPress Plugin
 Version:     1.5
 Author:      StylemixThemes
 Author URI:  https://stylemixthemes.com/
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: stm_motors_extends
 Domain Path: /languages
*/

define( 'STM_MOTORS_EXTENDS_PLUGIN_VERSION', '1.5' );
define( 'STM_MOTORS_EXTENDS_DB_VERSION', '1.0.1' );
define( 'STM_MOTORS_EXTENDS_ROOT_FILE', __FILE__ );
define( 'STM_MOTORS_EXTENDS_PATH', dirname( __FILE__ ) );
define( 'STM_MOTORS_EXTENDS_INC_PATH', dirname( __FILE__ ) . '/inc/' );
define( 'STM_MOTORS_EXTENDS_URL', plugins_url( '', __FILE__ ) );

if ( ! is_textdomain_loaded( 'stm_motors_extends' ) ) {
    load_plugin_textdomain( 'stm_motors_extends', false, 'stm-motors-extends/languages' );
}

require_once(dirname(__FILE__) . '/nuxy/NUXY.php');

require_once STM_MOTORS_EXTENDS_INC_PATH . 'wpcfto_conf.php';
require_once STM_MOTORS_EXTENDS_INC_PATH . 'classes/STM_ME_Patcher.php';

require_once STM_MOTORS_EXTENDS_INC_PATH . 'helpers.php';

$widgets_path = STM_MOTORS_EXTENDS_INC_PATH . 'widgets';
//Widgets
require_once( $widgets_path . '/text-widget.php' );
require_once( $widgets_path . '/contacts.php' );


if(!stm_me_is_listing(array('listing_five', 'listing_six'))) {
	require_once( $widgets_path . '/socials.php' );
	require_once( $widgets_path . '/latest-posts.php' );
	require_once( $widgets_path . '/address.php' );
	require_once( $widgets_path . '/dealer_info.php' );
	require_once( $widgets_path . '/car_location.php' );
	require_once( $widgets_path . '/similar_cars.php' );
	require_once( $widgets_path . '/car-contact-form.php' );
	require_once( $widgets_path . '/classified-four-price-view.php' );
	require_once( $widgets_path . '/classified-four-car-data.php' );
	require_once( $widgets_path . '/schedule_showing.php' );
	require_once( $widgets_path . '/car_calculator.php' );
	require_once( $widgets_path . '/car_calculator.php' );
	require_once( $widgets_path . '/car_trade_offer_btns.php' );
}

if(function_exists('stm_is_magazine') && stm_is_magazine()) {
	require_once( $widgets_path . '/recomended_for_you.php' );
}

if(is_admin()) {
    require_once STM_MOTORS_EXTENDS_INC_PATH . 'announcement/main.php';
}
