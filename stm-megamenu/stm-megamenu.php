<?php
/*
Plugin Name: MegaMenu
Plugin URI: https://stylemixthemes.com
Description: MegaMenu
Author: StylemixThemes
Author URI: https://stylemixthemes.com
Text Domain: stm-megamenu
Version: 2.3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('STM_MM_VER', '1.0');
define('STM_MM_DIR', plugin_dir_path(__FILE__));
define('STM_MM_URL', plugins_url('/', __FILE__));
define('STM_MM_PATH', plugin_basename(__FILE__));
define('STM_MM_DIR_NAME', dirname( __FILE__ ));

if (!is_textdomain_loaded('mega_menu')) {
    load_plugin_textdomain('mega_menu', false, 'megamenu/languages');
}

function stm_mm_is_activate () {
	return true;
}

add_filter('stm_mm_is_active', 'stm_mm_is_activate');

add_action( 'after_setup_theme', 'stm_mm_setup' );
function stm_mm_setup(){
	add_image_size('stm-img-380-240', 380, 240, true);
	add_image_size('stm-img-120-120', 120, 120, true);
}

if(get_theme_mod('mega_menu', true)){
    require_once(STM_MM_DIR . '/includes/post-types.php');
    require_once(STM_MM_DIR . '/includes/helpers.php');
    require_once(STM_MM_DIR . '/includes/stm-mm-ajax.php');

    if(is_admin()) {
        require_once(STM_MM_DIR . '/admin/includes/helpers.php');
        require_once(STM_MM_DIR . '/admin/includes/xteam/xteam.php');
        require_once(STM_MM_DIR . '/admin/includes/config.php');
        require_once(STM_MM_DIR . '/admin/includes/enqueue.php');
        require_once(STM_MM_DIR . '/admin/includes/fontawesome.php');
    } else {
        require_once(STM_MM_DIR . '/includes/walker.php');
        require_once(STM_MM_DIR . '/includes/enqueue.php');
    }
}
