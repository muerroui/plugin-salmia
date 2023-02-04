<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$video = '';
if (!empty($id)) {
    $video = get_post_meta($id, 'gallery_video', true);
}
?>

<div class="stm-form-4-videos clearfix">
    <div class="stm-car-listing-data-single stm-border-top-unit ">
        <span class="step_number step_number_4 heading-font"><?php esc_html_e('step', 'stm_vehicles_listing'); ?>
            4</span>
    </div>
    <div class="stm-add-videos-unit">
        <div class="row">
            <div class="col-md-12">
                <div class="stm-video-units">
                    <div class="stm-video-link-unit-wrap">
                        <div class="heading-font">
                            <span class="video-label"><?php esc_html_e('Video link', 'stm_vehicles_listing'); ?></span>
                        </div>
                        <div class="stm-video-link-unit">
                            <input class="form-control" type="text" name="stm_video[]"
                                   value="<?php echo esc_attr($video); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>