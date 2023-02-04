<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$post_id = get_the_id();
$user_added_by = get_post_meta($post_id, 'stm_car_user', true);

if (!empty($user_added_by)):

    $user_data = get_userdata($user_added_by);

    if ($user_data):

        $user_fields = stm_get_user_custom_fields($user_added_by); ?>

        <div class="stm-listing-car-dealer-info stm-common-user">

            <div class="clearfix stm-user-main-info-c">
                <div class="image">
                    <a href="<?php echo esc_url(stm_get_author_link($user_added_by)); ?>">
                        <?php if (!empty($user_fields['image'])): ?>
                            <img src="<?php echo esc_url($user_fields['image']); ?>"/>
                        <?php else: ?>
                            <div class="no-avatar">
                                <i class="fas fa-camera"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
                <a class="stm-no-text-decoration" href="<?php echo esc_url(stm_get_author_link($user_added_by)); ?>">
                    <h3 class="title"><?php stm_display_user_name($user_added_by); ?></h3>
                    <div class="stm-label"><?php esc_html_e('Private Seller', 'stm_vehicles_listing'); ?></div>
                </a>
            </div>

            <div class="dealer-contacts">
                <?php if (!empty($user_fields['phone'])): ?>
                    <div class="dealer-contact-unit phone">
                        <i class="fas fa-phone"></i>
                        <div class="phone heading-font"><?php echo esc_attr($user_fields['phone']); ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($user_fields['email']) and !empty($user_fields['show_mail']) and $user_fields['show_mail'] == 'show'): ?>
                    <div class="dealer-contact-unit mail">
                        <i class="fas fa-envelope-open"></i>
                        <div class="stm-label"><?php esc_html_e('Seller Email', 'stm_vehicles_listing'); ?></div>
                        <div class="address"><a class="heading-font"
                                                href="mailto:<?php echo esc_attr($user_fields['email']); ?>"><?php echo esc_attr($user_fields['email']); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
<?php endif; ?>