<?php


$update = false;

if ( isset( $_POST['global_save'] ) ) {

    $cacheVersion = get_option('app_cache_version', 0);

    update_option('app_cache_version', $cacheVersion + 1);

    update_option('main_color', (!empty($_POST['main_color'])) ? $_POST['main_color'] : '#1bc744' );
    update_option('second_color', (!empty($_POST['second_color'])) ? $_POST['second_color'] : '#2d60f3' );

    $mainPageSettings = array();

    $mainPageSettings['main_page_recom_ppp'] = ( !empty( $_POST['main_page_recom_ppp'] ) ) ? $_POST['main_page_recom_ppp'] : '';
    $mainPageSettings['main_page_recent_add_ppp'] = ( !empty( $_POST['main_page_recent_add_ppp'] ) ) ? $_POST['main_page_recent_add_ppp'] : '';
    $mainPageSettings['main_page_ra_view'] = ( !empty( $_POST['main_page_ra_view'] ) ) ? $_POST['main_page_ra_view'] : '';

    update_option( 'main_page_settings', $mainPageSettings );
    update_option('app_type', (!empty($_POST['app_type'])) ? $_POST['app_type'] : 'dealership');

    update_option( 'gsap_android', ( !empty( $_POST['gsap_android'] ) ) ? $_POST['gsap_android'] : '' );
    update_option( 'gsap_ios', ( !empty( $_POST['gsap_ios'] ) ) ? $_POST['gsap_ios'] : '' );
    if ( !empty( $_POST['plchldr_image_attachment_id'] ) ) update_option( 'plchldr_attachment_id', absint( $_POST['plchldr_image_attachment_id'] ) );
    update_option( 'inventory_view', ( !empty( $_POST['inventory_view'] ) ) ? $_POST['inventory_view'] : '' );
    update_option( 'filter_params', ( !empty( $_POST['filter-opt'] ) ) ? str_replace(',,', ',', $_POST['filter-opt']) : '' );
    update_option( 'add_car_step_one', ( !empty( $_POST['step_one-opt'] ) ) ? str_replace(',,', ',', $_POST['step_one-opt']) : '' );
    update_option( 'add_car_step_two', ( !empty( $_POST['step_two-opt'] ) ) ? str_replace(',,', ',', $_POST['step_two-opt']) : '' );
    update_option( 'add_car_step_three', ( !empty( $_POST['step_three-opt'] ) ) ? str_replace(',,', ',', $_POST['step_three-opt']) : '' );

    $newOpt = array(
        "go_one" => ( !empty( $_POST['grid_one_text'] ) ) ? $_POST['grid_one_text'] : '',
        "go_two" => ( !empty( $_POST['grid_two_text'] ) ) ? $_POST['grid_two_text'] : '',
        "go_three" => ( !empty( $_POST['grid_three_text'] ) ) ? $_POST['grid_three_text'] : '',
    );

    update_option( 'grid_view_type', ( !empty( $_POST['grid_view'] ) ) ? $_POST['grid_view'] : '' );
    update_option( 'grid_view_settings', $newOpt );

    $newOpt = array(
        "list_title" => ( !empty( $_POST['list_title'] ) ) ? $_POST['list_title'] : '',
        "list_info_one" => ( !empty( $_POST['list_info_one'] ) ) ? $_POST['list_info_one'] : '',
        "list_info_two" => ( !empty( $_POST['list_info_two'] ) ) ? $_POST['list_info_two'] : '',
        "list_info_three" => ( !empty( $_POST['list_info_three'] ) ) ? $_POST['list_info_three'] : '',
        "list_info_four" => ( !empty( $_POST['list_info_four'] ) ) ? $_POST['list_info_four'] : '',
    );

    $newOptCustom = array(
        "custom_go_one" => ( !empty( $_POST['custom_go_one'] ) ) ? $_POST['custom_go_one'] : '',
        "custom_go_two" => ( !empty( $_POST['custom_go_two'] ) ) ? $_POST['custom_go_two'] : '',
        "custom_go_three" => ( !empty( $_POST['custom_go_three'] ) ) ? $_POST['custom_go_three'] : '',
    );

    update_option( 'grid_view_custom_settings', $newOptCustom );

    update_option( 'list_view_settings', $newOpt );

    $newOpt = array(
        "ld_title" => ( !empty( $_POST['listing_details_title'] ) ) ? $_POST['listing_details_title'] : '',
        "ld_subtitle" => ( !empty( $_POST['listing_details_subtitle'] ) ) ? $_POST['listing_details_subtitle'] : '',
        "ld_info" => ( !empty( $_POST['listing_details_info'] ) ) ? $_POST['listing_details_info'] : '',
    );

    update_option( 'listing_details_settings', $newOpt );

    update_option('translations', (!empty($_POST['strings'])) ? $_POST['strings'] : '');

    if(isset($_POST['show_ads']) && $_POST['show_ads'] == 'yes') {
        $adsSettings = array(
                'show_ads' => $_POST['show_ads'],
                'ad_type' => $_POST['ad_type'],
                'banner_position' => $_POST['banner_position'],
                'ios_banner_id' => $_POST['ios_banner_id'],
                'android_banner_id' => $_POST['android_banner_id'],
                'ios_interstitial_id' => $_POST['ios_interstitial_id'],
                'android_interstitial_id' => $_POST['android_interstitial_id'],
                'ad_first_time' => $_POST['ad_first_time'],
                'ad_interval' => $_POST['ad_interval'],
        );

        update_option('ads_settings', $adsSettings);
    } else {
        delete_option('ads_settings');
    }

    $update = true;
}

?>
<div class="wrap">
    <?php if ( $update ) : ?>
        <div class="notice notice-large notice-success">Application Settings Updated</div>
    <?php endif; ?>
    <h1><?php echo esc_html__( 'Motors Application Settings', STM_MOTORS_APP_DOMAIN ); ?></h1>
    <form method="post">
        <div class="tabs-wrapper">
            <ul class="nav nav-tabs" id="stmMATabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab"
                       aria-controls="general"
                       aria-selected="true"><?php echo esc_html__( 'General', STM_MOTORS_APP_DOMAIN ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="inventory-tab" data-toggle="tab" href="#inventory" role="tab"
                       aria-controls="inventory"
                       aria-selected="false"><?php echo esc_html__( 'Inventory', STM_MOTORS_APP_DOMAIN ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="translations-tab" data-toggle="tab" href="#translations" role="tab"
                       aria-controls="translations"
                       aria-selected="false"><?php echo esc_html__( 'Translations', STM_MOTORS_APP_DOMAIN ); ?></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <?php require_once __DIR__ . '/general-page.php'; ?>
                </div>
                <div class="tab-pane" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                    <?php require_once __DIR__ . '/inventory-page.php'; ?>
                </div>
                <div class="tab-pane" id="translations" role="tabpanel" aria-labelledby="translations-tab">
                    <?php require_once __DIR__ . '/translations-page.php'; ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="global_save" value="global_save"/>
        <input type="submit" class="button-primary" value="Save changes"/>
    </form>
</div>
