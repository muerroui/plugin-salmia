<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="stm-user-private-sidebar">

    <div class="clearfix stm-user-top">

        <div class="stm-user-avatar">
            <?php
            $link = add_query_arg(array('page_admin' => 'settings'), stm_get_author_link(''));

            $hide_empty = '';
            if (!empty($user_fields['image'])) {
                $hide_empty = 'hide-empty';
            } else {
                $hide_empty = 'hide-photo';
            } ?>

            <a href="<?php echo esc_url($link); ?>" class="stm-image-avatar image <?php echo esc_attr($hide_empty); ?>">
                <img class="img-responsive img-avatar" src="<?php echo esc_url($user_fields['image']); ?>"/>
                <div class="stm-empty-avatar-icon"><i class="fas fa-camera"></i></div>
            </a>
        </div>

        <div class="stm-user-profile-information">
            <a href="<?php echo esc_url($link); ?>"
               class="title heading-font"><?php echo esc_attr(stm_display_user_name($user->ID)); ?></a>
            <div class="title-sub"><?php esc_html_e('Private Seller', 'stm_vehicles_listing'); ?></div>
            <?php if (!empty($user_fields['socials'])): ?>
                <div class="socials clearfix">
                    <?php foreach ($user_fields['socials'] as $social_key => $social): ?>
                        <a href="<?php echo esc_url($social); ?>">
                            <i class="fab fa-<?php echo esc_attr($social_key); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <div class="stm-actions-list heading-font">

        <a
            href="<?php echo esc_url(stm_get_author_link('')); ?>">
            <i class="fas fa-car"></i><?php esc_html_e('My Inventory', 'stm_vehicles_listing') ?>
        </a>

        <a
            href="<?php echo esc_url(add_query_arg(array('page_admin' => 'settings'), stm_get_author_link(''))); ?>">
            <i class="fas fa-cog"></i>
            <?php esc_html_e('Profile Settings', 'stm_vehicles_listing') ?>
        </a>

    </div>

    <?php if (!empty($user_fields['phone'])): ?>
        <div class="stm-dealer-phone">
            <i class="fas fa-phone"></i>
            <div
                class="phone-label heading-font"><?php esc_html_e('Seller Contact Phone', 'stm_vehicles_listing'); ?></div>
            <div class="phone"><?php echo esc_attr($user_fields['phone']); ?></div>
        </div>
    <?php endif; ?>

    <div class="stm-dealer-mail">
        <i class="fas fa-envelope-open"></i>
        <div class="mail-label heading-font"><?php esc_html_e('Seller Email', 'stm_vehicles_listing'); ?></div>
        <div class="mail"><a href="mailto:<?php echo esc_attr($user->data->user_email); ?>">
                <?php echo esc_attr($user->data->user_email); ?>
            </a></div>
    </div>


    <div class="show-my-profile">
        <a href="<?php echo esc_url(stm_get_author_link('myself-view')); ?>" target="_blank"><i
                class="fas fa-external-link-alt"></i><?php esc_html_e('Show my Public Profile', 'stm_vehicles_listing'); ?>
        </a>
    </div>

</div>