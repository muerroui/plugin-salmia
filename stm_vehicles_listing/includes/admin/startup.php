<?php

function stm_listings_startup_styles()
{
    wp_enqueue_style('stm-startup_css', STM_LISTINGS_URL . '/assets/css/plugin.css', null, 1.6, 'all');
}

add_action('admin_enqueue_scripts', 'stm_listings_startup_styles');

function stm_listings_activation_redirect( $plugin ) {
    if($plugin == 'stm_vehicles_listing/stm_vehicles_listing.php') {
        exit(wp_redirect(esc_url_raw(admin_url('admin.php?page=stm_startup_vehicles_listing'))));
    }

}
add_action( 'activated_plugin', 'stm_listings_activation_redirect' );


//Register Startup page in admin menu
function stm_listings_register_startup_screen()
{
    $stm_admin_menu_page_creation_method = 'add_menu_page';

    /*Item Registration*/
    $stm_admin_menu_page_creation_method(
        'Motors',
        'Motors',
        'manage_options',
        'stm_startup_vehicles_listing',
        'stm_listings_plugin_startup',
        'http://motors.stylemixthemes.com/wp-content/uploads/2015/12/cropped-favicon-150x150.png',
        '100'
    );

}
add_action('admin_menu', 'stm_listings_register_startup_screen');

function stm_listings_plugin_startup() { ?>
    <div class="wrap about-wrap stm-admin-wrap  stm-admin-support-screen">
        <div class="clearfix">
            <div class="stm_theme_info"></div>
            <div class="stm-about-text-wrap">
                <h1><?php esc_html_e('Welcome to Motors', 'stm_vehicles_listing'); ?></h1>
                <div class="stm-about-text about-text">
                    <?php esc_html_e('Motors plugin is now installed and ready to use!', 'stm_vehicles_listing'); ?>
                </div>
            </div>
        </div>

        <div class="stm_listings_banner">
            <a href="https://themeforest.net/item/motors-automotive-cars-vehicle-boat-dealership-classifieds-wordpress-theme/13987211?s_rank=5" target="_blank">
                <img src="<?php echo esc_url(STM_LISTINGS_URL . '/assets/images/motors_banner.jpg'); ?>" />
            </a>
        </div>

        <div class="stm-admin-row">
            <div class="stm-admin-two-third">

                <div class="stm-admin-row">

                    <div class="stm-admin-one-half">
                        <div class="stm-admin-one-half-inner">
                            <h3>
                                <span><img src="<?php echo esc_url(STM_LISTINGS_URL . '/assets/images/ico-1.svg'); ?>"></span>
                                <?php esc_html_e('Learn more', 'stm_vehicles_listing'); ?>
                            </h3>
                            <p>
                                <?php esc_html_e('Detailed documentation of the plugin which makes easy working with Motors - Car Dealership plugin.', 'stm_vehicles_listing'); ?>
                            </p>
                            <a href="https://plugins.svn.wordpress.org/motors-car-dealership-classified-listings/trunk/documentation/Motors%20Documentation.pdf" target="_blank">Documentation</a>
                        </div>
                    </div>

                    <div class="stm-admin-one-half">
                        <div class="stm-admin-one-half-inner">
                            <h3>
                                <span><img src="<?php echo esc_url(STM_LISTINGS_URL . '/assets/images/ico-2.svg'); ?>"></span>
                                <?php esc_html_e('Plugin Options', 'stm_vehicles_listing'); ?>
                            </h3>
                            <p>
                                <?php esc_html_e('All plugin settings can be set up under Wordpress Customize options. These settings don\'t depend on using Theme.', 'stm_vehicles_listing'); ?>
                            </p>
                            <a href="<?php echo esc_url(admin_url('customize.php')); ?>" target="_blank"><?php esc_html_e('Setup plugin', 'stm_vehicles_listing'); ?></a>
                        </div>
                    </div>
                </div>

                <div class="stm-admin-row">

                    <div class="stm-admin-one-half">
                        <div class="stm-admin-one-half-inner">
                            <h3>
                                <span><img src="<?php echo esc_url(STM_LISTINGS_URL . '/assets/images/ico-3.svg'); ?>"></span>
                                <?php esc_html_e('Demo Site', 'stm_vehicles_listing'); ?>
                            </h3>
                            <p>
                                <?php esc_html_e('Example Listings with public access are located in this Demo Site. Feel free going ahead and try everything with demo Listings.', 'stm_vehicles_listing'); ?>
                            </p>
                            <a href="http://motorsplugin.stylemixthemes.com/" target="_blank"><?php esc_html_e('Motors demo', 'stm_vehicles_listing'); ?></a>
                        </div>
                    </div>

                    <div class="stm-admin-one-half">
                        <div class="stm-admin-one-half-inner">
                            <h3>
                                <span><img src="<?php echo esc_url(STM_LISTINGS_URL . '/assets/images/ico-4.svg'); ?>"></span>
                                <?php esc_html_e('Plugin support', 'stm_vehicles_listing'); ?>
                            </h3>
                            <p>
                                <?php esc_html_e('Use WordPress forum to find answers to your questions. This information is more specific and unique to various versions or aspects of Motors.', 'stm_vehicles_listing'); ?>
                            </p>
                            <a href="https://wordpress.org/support/plugin/motors-car-dealership-classified-listings" target="_blank"><?php esc_html_e('Forum', 'stm_vehicles_listing'); ?></a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="stm-admin-one-third">
                <a href="https://stylemix.net/?utm_source=dashboard&amp;utm_medium=banner&amp;utm_campaign=motorsplugin" target="_blank">
                    <img src="http://motors.stylemixthemes.com/wp-content/themes/motors/assets/admin/images/banner-1.png">
                </a>
            </div>
        </div>

    </div>
<?php }