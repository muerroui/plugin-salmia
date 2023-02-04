<?php
/*
 Plugin Name: Motors Application
 Plugin URI:  https://stylemixthemes.com/plugins/
 Description: Motors Application - Car Dealership & Classified Listings Mobile App for Android & iOS Integration
 Version:     2.0.2
 Author:      StylemixThemes
 Author URI:  https://stylemixthemes.com/
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: stm-motors-application
 Domain Path: /languages
*/

define( 'STM_MOTORS_APP_VERSION', '2.0.2' );
define( 'STM_MOTORS_APP_ROOT_FILE', __FILE__ );
define( 'STM_MOTORS_APP_PATH', dirname( __FILE__ ) );
define( 'STM_MOTORS_APP_INC_PATH', dirname( __FILE__ ) . '/inc/' );
define( 'STM_MOTORS_APP_URL', plugins_url( '', __FILE__ ) );
define( 'STM_MOTORS_APP_ID', 'stm-mra/v1' );
define( 'STM_MOTORS_APP_DOMAIN', 'stm-motors-application' );

require_once STM_MOTORS_APP_INC_PATH . 'scripts_styles.php';
require_once STM_MOTORS_APP_INC_PATH . 'validate.php';
require_once STM_MOTORS_APP_INC_PATH . 'helpers.php';
require_once STM_MOTORS_APP_INC_PATH . 'rest_api.php';