<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$gallery_video = get_post_meta(get_the_ID(), 'gallery_video', true);

if (!empty($gallery_video)): ?>
    <span class="video-preview light_gallery_iframe" data-iframe="true" data-src="<?php echo esc_url($gallery_video); ?>"><i
            class="fas fa-film"></i><?php esc_html_e('Video', 'stm_vehicles_listing'); ?></span>
<?php endif; ?>