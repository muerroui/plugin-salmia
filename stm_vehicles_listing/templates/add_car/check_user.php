<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="stm-form-checking-user">
    <div class="stm-form-inner">
        <i class="stm-icon-load1"></i>
        <?php $restricted = false; ?>
        <?php if (is_user_logged_in()):
            $disabled = 'enabled';
            ?>
            <div id="stm_user_info">
                <?php stm_listings_load_template('add_car/registered'); ?>
            </div>
        <?php else:
            $disabled = 'disabled'; ?>
            <div id="stm_user_info" style="display:none;"></div>
            <?php
        endif; ?>

        <div class="stm-not-<?php echo esc_attr($disabled); ?>">

            <?php stm_listings_load_template('add_car/registration'); ?>

            <div class="stm-add-a-car-login-overlay"></div>
            <div class="stm-add-a-car-login">
                <div class="stm-login-form">
                    <form method="post">
                        <input type="hidden" name="redirect" value="disable">
                        <div class="form-group">
                            <h4><?php esc_html_e('Login or E-mail', 'stm_vehicles_listing'); ?></h4>
                            <input class="form-control" type="text" name="stm_user_login"
                                   placeholder="<?php esc_html_e('Enter login or E-mail', 'stm_vehicles_listing'); ?>">
                        </div>

                        <div class="form-group">
                            <h4><?php esc_html_e('Password', 'stm_vehicles_listing'); ?></h4>
                            <input class="form-control" type="password" name="stm_user_password"
                                   placeholder="<?php esc_html_e('Enter password', 'stm_vehicles_listing'); ?>">
                        </div>

                        <div class="form-group form-checker">
                            <label>
                                <input type="checkbox" name="stm_remember_me">
                                <span><?php esc_html_e('Remember me', 'stm_vehicles_listing'); ?></span>
                            </label>
                        </div>
                        <input type="submit" class="button" value="Login">
                        <span class="stm-listing-loader"><i class="fas fa-spinner"></i></span>
                        <div class="stm-validation-message"></div>
                    </form>
                </div>
            </div>
        </div>

        <button type="submit" class="button <?php echo esc_attr($disabled); ?>">
            <i class="stm-service-icon-add_check"></i><?php esc_html_e('Submit listing', 'stm_vehicles_listing'); ?>
        </button>
        <span class="stm-add-a-car-loader"><i class="fas fa-spinner"></i></span>

        <div class="stm-add-a-car-message heading-font"></div>
    </div>
</div>