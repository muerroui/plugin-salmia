<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!empty($id)) {
    $item_id = $id;
} ?>

<div class="stm-form-3-photos clearfix">
    <div class="stm-car-listing-data-single stm-border-top-unit ">
        <div class="title heading-font"><?php esc_html_e('Upload photo', 'stm_vehicles_listing'); ?></div>
        <span class="step_number step_number_3 heading-font"><?php esc_html_e('step', 'stm_vehicles_listing'); ?>
            3</span>
    </div>

    <div class="row">

        <div class="col-md-12">
            <!--Check if user not editing existing images-->
            <?php if (empty($item_id)): ?>
                <div class="stm-add-media-car">
                    <div class="stm-media-car-main-input">
                        <input type="file" name="stm_car_gallery_add[]" multiple/>

                        <div class="stm-placeholder">
                            <i class="fas fa-camera"></i>
                            <a href="#"
                               class="button stm_fake_button"><?php esc_html_e('Choose files', 'stm_vehicles_listing'); ?></a>
                        </div>
                    </div>
                    <div class="stm-media-car-gallery clearfix">
                        <?php $i = 1;
                        while ($i <= 5): $i++; ?>
                            <div class="stm-placeholder stm-placeholder-native">
                                <div class="inner">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="stm-add-media-car">
                    <div class="stm-media-car-main-input">
                        <input type="file" name="stm_car_gallery_add[]" multiple/>

                        <div class="stm-placeholder hasPreviews">
                            <?php
                            $featured_image = get_post_thumbnail_id($item_id);
                            $image = '';
                            if (!empty($featured_image)) {
                                $image = wp_get_attachment_image_src($featured_image, 'stm-img-796-466');
                                if (!empty($image[0])) {
                                    $image = 'style=background:url("' . $image[0] . '")';
                                }
                            }
                            ?>

                            <i class="stm-service-icon-photos"></i>
                            <a href="#"
                               class="button stm_fake_button"><?php esc_html_e('Choose files', 'stm_vehicles_listing'); ?></a>
                        </div>
                        <?php if (!empty($image)): ?>
                            <div
                                class="stm-image-preview stm-placeholder-generated-php" <?php echo esc_attr($image); ?>></div>
                        <?php endif; ?>
                    </div>

                    <?php $images_js = array(); ?>
                    <?php if (!empty($image)): $images_js[] = $featured_image; ?>
                        <div class="stm-media-car-gallery clearfix">
                            <div class="stm-placeholder stm-placeholder-generated stm-placeholder-generated-php">
                                <div class="inner">
                                    <div class="stm-image-preview" data-media="<?php echo esc_attr($featured_image); ?>"
                                         data-id="0" <?php echo esc_attr($image); ?>>
                                        <i class="fas fa-times fa-remove-loaded" data-id="0"
                                           data-media="<?php echo esc_attr($featured_image); ?>"></i>
                                    </div>
                                </div>
                            </div>
                            <?php $gallery = get_post_meta($item_id, 'gallery', true); ?>
                            <?php if (!empty($gallery)): ?>
                                <?php foreach ($gallery as $gallery_key => $gallery_id): ?>
                                    <?php $image = '';
                                    if (!empty($gallery_id)) {
                                        $image = wp_get_attachment_image_src($gallery_id, 'stm-img-796-466');
                                        if (!empty($image[0])) {
                                            $image = 'style=background:url("' . $image[0] . '")';
                                            $images_js[] = intval($gallery_id);
                                        }
                                    }

                                    if (!empty($image)): ?>
                                        <div
                                            class="stm-placeholder stm-placeholder-generated stm-placeholder-generated-php">
                                            <div class="inner">
                                                <div class="stm-image-preview"
                                                     data-media="<?php echo intval($gallery_id); ?>"
                                                     data-id="<?php echo esc_attr($gallery_key + 1); ?>" <?php echo esc_attr($image); ?>>
                                                    <i class="fas fa-times fa-remove-loaded"
                                                       data-id="<?php echo esc_attr($gallery_key + 1); ?>"
                                                       data-media="<?php echo intval($gallery_id); ?>"></i>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <script type="text/javascript">
                            var stmUserFilesLoaded = [
                                <?php foreach ($images_js as $image_js) {
                                echo intval($image_js) . ',';
                            } ?>
                            ]
                        </script>
                    <?php else: ?>
                        <div class="stm-media-car-gallery clearfix">
                            <?php $i = 1;
                            while ($i <= 5): $i++; ?>
                                <div class="stm-placeholder stm-placeholder-native">
                                    <div class="inner">
                                        <i class="stm-service-icon-photos"></i>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>