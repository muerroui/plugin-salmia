<?php
$mpSettings = get_option( 'main_page_settings', '' );

$mpRecom = ( !empty( $mpSettings ) ) ? $mpSettings['main_page_recom_ppp'] : '10';
$mpRecent = ( !empty( $mpSettings ) ) ? $mpSettings['main_page_recent_add_ppp'] : '10';
$mpViewType = ( !empty( $mpSettings ) ) ? $mpSettings['main_page_ra_view'] : 'main_ra_list_view';

$gsapAndroid = get_option( 'gsap_android', '' );
$gsapIos = get_option( 'gsap_ios', '' );
$appType = get_option( 'app_type', 'classified' );
$mainColor = get_option( 'main_color', '#1bc744' );
$secondColor = get_option( 'second_color', '#2d60f3' );

wp_enqueue_media();

$my_saved_attachment_post_id = get_option( 'plchldr_attachment_id', 0 );

/*Add A Car*/
$stepOne = get_option( 'add_car_step_one', "add_media,make,serie,ca-year,mileage,exterior-color" );
$stepTwo = get_option( 'add_car_step_two', "engine,fuel,transmission,drive,body,location,price" );
$stepThree = get_option( 'add_car_step_three', "seller_notes,additional_features" );

$options = ( function_exists( 'stm_listings_get_my_options_list' ) ) ? stm_listings_get_my_options_list() : array();
$opt = '';
$filterOpt = '';
$filterOpt .= '<option value="add_media">Add Media</option>';
$filterOpt .= '<option value="location">Location</option>';
foreach ( $options as $key => $option ) {
    if ( $option['slug'] != 'price' ) {
        $opt .= '<option value=' . $option['slug'] . '>' . $option['single_name'] . '</option>';
    }
    $filterOpt .= '<option value=' . $option['slug'] . '>' . $option['single_name'] . '</option>';
}
$filterOpt .= '<option value="seller_notes">Seller notes</option>';
$filterOpt .= '<option value="stm_additional_features">Car Features</option>';
/*Add A Car*/

/*Ad Mob settings*/
$adsSettings = get_option('ads_settings', array());

$showAd = (!empty($adsSettings['show_ads'])) ? 'checked' : '';
$adType = (!empty($adsSettings['ad_type'])) ? $adsSettings['ad_type'] : '';
$adTypeBanner = (!empty($adsSettings['ad_type']) && $adsSettings['ad_type'] == 'banner') ? 'checked' : '';
$adTypeInterst = (!empty($adsSettings['ad_type']) && $adsSettings['ad_type'] == 'interstitial') ? 'checked' : '';
$adPositionTop = (!empty($adsSettings['banner_position']) && $adsSettings['banner_position'] == 'top') ? 'checked' : '';
$adPositionBottom = (!empty($adsSettings['banner_position']) && $adsSettings['banner_position'] == 'bottom') ? 'checked' : '';
$adFirstTime = (!empty($adsSettings['ad_first_time'])) ? $adsSettings['ad_first_time'] : '';
$adNextTime = (!empty($adsSettings['ad_interval'])) ? $adsSettings['ad_interval'] : '';
$adBannerAndrId = (!empty($adsSettings['android_banner_id'])) ? $adsSettings['android_banner_id'] : '';
$adBannerIosId = (!empty($adsSettings['ios_banner_id'])) ? $adsSettings['ios_banner_id'] : '';
$adInterstAndrId = (!empty($adsSettings['android_interstitial_id'])) ? $adsSettings['android_interstitial_id'] : '';
$adInterstIosId = (!empty($adsSettings['ios_interstitial_id'])) ? $adsSettings['ios_interstitial_id'] : '';


/*Ad Mob settings*/
?>
<script>
    var stepOne = <?php echo ( !empty( $stepOne ) ) ? json_encode( explode( ',', $stepOne ) ) : '""'; ?>;
    var stepTwo = <?php echo ( !empty( $stepTwo ) ) ? json_encode( explode( ',', $stepTwo ) ) : '""'; ?>;
    var stepThree = <?php echo ( !empty( $stepThree ) ) ? json_encode( explode( ',', $stepThree ) ) : '""'; ?>;
</script>

<div class="stm-ma-inventory-settings">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-global-tab" data-toggle="pill" href="#v-pills-global"
                   role="tab"
                   aria-controls="v-pills-global"
                   aria-selected="true"><?php echo esc_html__( 'Global Settings', STM_MOTORS_APP_DOMAIN ); ?></a>
                <a class="nav-link" id="v-pills-main-page-tab" data-toggle="pill" href="#v-pills-main-page" role="tab"
                   aria-controls="v-pills-main-page"
                   aria-selected="true"><?php echo esc_html__( 'Main Page', STM_MOTORS_APP_DOMAIN ); ?></a>
                <a class="nav-link" id="v-pills-add-a-car-tab" data-toggle="pill" href="#v-pills-add-a-car" role="tab"
                   aria-controls="v-pills-add-a-car"
                   aria-selected="true"><?php echo esc_html__( 'Add A Car', STM_MOTORS_APP_DOMAIN ); ?></a>
                <a class="nav-link" id="v-pills-ad-mob-tab" data-toggle="pill" href="#v-pills-ad-mob" role="tab"
                   aria-controls="v-pills-ad-mob"
                   aria-selected="true"><?php echo esc_html__( 'Ad Mob', STM_MOTORS_APP_DOMAIN ); ?></a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-global" role="tabpanel"
                     aria-labelledby="v-pills-global-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <label><?php echo esc_html__( 'Application Type', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <div class="row app_type_wrap">
                                <div class="col-md-3">
                                    <div class="radio-wrap">
                                        <label>
                                            <input type="radio" name="app_type"
                                                   value="dealership" <?php if ( $appType == 'dealership' ) echo 'checked'; ?> />
                                            Dealership
                                        </label>
                                    </div>
                                    <div class="radio-wrap">
                                        <label>
                                            <input type="radio" name="app_type"
                                                   value="classified" <?php if ( $appType == 'classified' ) echo 'checked'; ?>/>
                                            Classified
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label><?php echo esc_html__( 'Main Color', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <div class="main-color-wrap">
                                <input name="main_color" type="text" value="<?php echo apply_filters('stm_ma_main_color_filter', $mainColor); ?>"/>
                            </div>
                            <label><?php echo esc_html__( 'Secondary Color', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <div class="second-color-wrap">
                                <input name="second_color" type="text" value="<?php echo apply_filters('stm_ma_second_color_filter', $secondColor); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label><?php echo esc_html__( 'Google Services Api Key Android', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <input type="text" name="gsap_android" value="<?php echo esc_attr( $gsapAndroid ); ?>"/>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label><?php echo esc_html__( 'Google Services Api Key IOs', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <input type="text" name="gsap_ios" value="<?php echo esc_attr( $gsapIos ); ?>"/>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label><?php echo esc_html__( 'Placeholder', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <div class='image-preview-wrapper'>
                                <?php if ( $my_saved_attachment_post_id != 0 ) : ?>
                                    <img id='image-preview'
                                         src='<?php echo esc_url( wp_get_attachment_image_url( $my_saved_attachment_post_id ) ) ?>'
                                         width='100' height='100' style='max-height: 100px; width: 100px;'>
                                <?php endif; ?>
                            </div>
                            <input id="upload_image_button" type="button" class="button"
                                   value="<?php _e( 'Upload image' ); ?>"/>
                            <input type='hidden' name='plchldr_image_attachment_id' id='plchldr_image_attachment_id'
                                   value=''>
                        </div>
                        <div class="col-md-6">
                            <script type='text/javascript'>
                                jQuery(document).ready(function ($) {
                                    // Uploading files
                                    var file_frame;
                                    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                                    var set_to_post_id = <?php echo esc_html($my_saved_attachment_post_id); ?>; // Set this
                                    jQuery('#upload_image_button').on('click', function (event) {
                                        event.preventDefault();
                                        // If the media frame already exists, reopen it.
                                        if (file_frame) {
                                            // Set the post ID to what we want
                                            file_frame.uploader.uploader.param('post_id', set_to_post_id);
                                            // Open frame
                                            file_frame.open();
                                            return;
                                        } else {
                                            // Set the wp.media post id so the uploader grabs the ID we want when initialised
                                            wp.media.model.settings.post.id = set_to_post_id;
                                        }
                                        // Create the media frame.
                                        file_frame = wp.media.frames.file_frame = wp.media({
                                            title: 'Select a image to upload',
                                            button: {
                                                text: 'Use this image',
                                            },
                                            multiple: false	// Set to true to allow multiple files to be selected
                                        });
                                        // When an image is selected, run a callback.
                                        file_frame.on('select', function () {
                                            // We set multiple to false so only get one image from the uploader
                                            attachment = file_frame.state().get('selection').first().toJSON();
                                            // Do something with attachment.id and/or attachment.url here
                                            $('#image-preview').attr('src', attachment.url).css('width', 'auto');
                                            $('#plchldr_image_attachment_id').val(attachment.id);
                                            // Restore the main post ID
                                            wp.media.model.settings.post.id = wp_media_post_id;
                                        });
                                        // Finally, open the modal
                                        file_frame.open();
                                    });
                                    // Restore the main ID when the add media button is pressed
                                    jQuery('a.add_media').on('click', function () {
                                        wp.media.model.settings.post.id = wp_media_post_id;
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-main-page" role="tabpanel"
                     aria-labelledby="v-pills-main-page-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <label><?php echo esc_html__( 'Recomended Listings Per Page', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <input type="text" name="main_page_recom_ppp"
                                   value="<?php echo esc_attr( $mpRecom ); ?>"/>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label><?php echo esc_html__( 'Recently Added Listings Per Page', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <input type="text" name="main_page_recent_add_ppp"
                                   value="<?php echo esc_attr( $mpRecent ); ?>"/>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label><?php echo esc_html__( 'View Type for Recently Added section', STM_MOTORS_APP_DOMAIN ); ?></label>
                            <select name="main_page_ra_view">
                                <option value="main_ra_list_view" <?php if ( $mpViewType == 'main_ra_list_view' ) echo 'selected'; ?>>
                                    List View
                                </option>
                                <option value="main_ra_grid_view" <?php if ( $mpViewType == 'main_ra_grid_view' ) echo 'selected'; ?>>
                                    Grid View
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-add-a-car" role="tabpanel"
                     aria-labelledby="v-pills-add-a-car-tab">
                    <div class="add_a_car_steps_wrap">
                        <div class="step-one">
                            <label>Step One</label>
                            <select multiple="multiple" id="step_one-select" name="step_one[]">
                                <?php echo apply_filters('stm_ma_add_car_step_one_filter', $filterOpt); ?>
                            </select>
                        </div>
                        <div class="step-two">
                            <label>Step Two</label>
                            <select multiple="multiple" id="step_two-select" name="step_two[]">
                                <?php echo apply_filters('stm_ma_add_car_step_two_filter', $filterOpt); ?>
                            </select>
                        </div>
                        <div class="step-three">
                            <label>Step Three</label>
                            <select multiple="multiple" id="step_three-select" name="step_three[]">
                                <?php echo apply_filters('stm_ma_add_car_step_three_filter', $filterOpt); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <input type="hidden" name="step_one-opt"/>
                            <input type="hidden" name="step_two-opt"/>
                            <input type="hidden" name="step_three-opt"/>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-ad-mob" role="tabpanel"
                     aria-labelledby="v-pills-ad-mob-tab">
                    <div class="firebase-admob-keys">
                        <div class="main-ads-wrap">
                            <table>
                                <tr>
                                    <th><?php echo esc_html__( 'Show Ads', STM_MOTORS_APP_DOMAIN ); ?></th>
                                    <td><input type="checkbox" name="show_ads" value="yes" <?php echo esc_attr($showAd); ?>/></td>
                                </tr>
                                <tr>
                                    <th><?php echo esc_html__( 'Ads Type', STM_MOTORS_APP_DOMAIN ); ?></th>
                                    <td>
                                        <label>
                                            <input type="radio" name="ad_type" value="banner" <?php echo esc_attr($adTypeBanner); ?>/>Banner
                                        </label>
                                        <label>
                                            <input type="radio" name="ad_type" value="interstitial" <?php echo esc_attr($adTypeInterst); ?>/>Interstitial
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="banner-ids-wrap <?php if($adType == 'banner') echo esc_attr('visible'); ?>">
                            <table>
                                <tr>
                                    <th>Banner position</th>
                                    <td>
                                        <label>
                                            <input type="radio" name="banner_position" value="top" <?php echo esc_attr($adPositionTop); ?>/>Top
                                        </label>
                                        <label>
                                            <input type="radio" name="banner_position" value="bottom" <?php echo esc_attr($adPositionBottom); ?>/>Bottom
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ios banner Id</th>
                                    <td><input type="text" name="ios_banner_id"
                                               placeholder="ca-app-pub-0123456789/012345678" value="<?php echo esc_attr($adBannerIosId); ?>"/></td>
                                </tr>
                                <tr>
                                    <th>Android banner Id</th>
                                    <td><input type="text" name="android_banner_id"
                                               placeholder="ca-app-pub-0123456789/012345678" value="<?php echo esc_attr($adBannerAndrId); ?>"/></td>
                                </tr>
                            </table>
                        </div>
                        <div class="interstitial-ids-wrap <?php if($adType == 'interstitial') echo esc_attr('visible'); ?>">
                            <table>
                                <tr>
                                    <th>Ios interstitial Id</th>
                                    <td><input type="text" name="ios_interstitial_id"
                                               placeholder="ca-app-pub-0123456789/012345678" value="<?php echo esc_attr($adInterstIosId); ?>"/></td>
                                </tr>
                                <tr>
                                    <th>Android interstitial Id</th>
                                    <td><input type="text" name="android_interstitial_id"
                                               placeholder="ca-app-pub-0123456789/012345678" value="<?php echo esc_attr($adInterstAndrId); ?>"/></td>
                                </tr>
                                <tr>
                                    <th>Show first Ad</th>
                                    <td><input type="text" name="ad_first_time"
                                               placeholder="10" value="<?php echo esc_attr($adFirstTime); ?>"/>
                                        <small><?php echo esc_html__( 'Show first ad after specific time.', STM_MOTORS_APP_DOMAIN ); ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Show next Ad interval</th>
                                    <td><input type="text" name="ad_interval"
                                               placeholder="10" value="<?php echo esc_attr($adNextTime); ?>"/>
                                        <small><?php echo esc_html__( 'Set interval for show next ads.', STM_MOTORS_APP_DOMAIN ); ?></small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
