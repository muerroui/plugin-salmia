<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="stm-login-register-form">

    <div class="row">

        <div class="col-md-4">
            <h3><?php esc_html_e('Sign In', 'stm_vehicles_listing'); ?></h3>
            <div class="stm-login-form">
                <form method="post">
                    <div class="form-group">
                        <h4><?php esc_html_e('Login or E-mail', 'stm_vehicles_listing'); ?></h4>
                        <input type="text" class="form-control" name="stm_user_login"
                               placeholder="<?php esc_html_e('Enter login or E-mail', 'stm_vehicles_listing') ?>"/>
                    </div>
                    <div class="form-group">
                        <h4><?php esc_html_e('Password', 'stm_vehicles_listing'); ?></h4>
                        <input type="password" class="form-control" name="stm_user_password"
                               placeholder="<?php esc_html_e('Enter password', 'stm_vehicles_listing') ?>"/>
                    </div>
                    <div class="form-group form-checker">
                        <label>
                            <input type="checkbox" name="stm_remember_me"/>
                            <span><?php esc_html_e('Remember me', 'stm_vehicles_listing'); ?></span>
                        </label>
                    </div>
                    <?php if(class_exists('SitePress')) : ?><input type="hidden" name="current_lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/><?php endif; ?>
                    <input type="submit" class="button" value="<?php esc_html_e('Login', 'stm_vehicles_listing'); ?>"/>
                    <span class="stm-listing-loader"><i class="fas fa-spinner"></i></span>
                    <div class="stm-validation-message"></div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <h3><?php esc_html_e('Sign Up', 'stm_vehicles_listing'); ?></h3>
            <div class="stm-register-form">
                <form method="post">

                    <div class="row form-group">
                        <div class="col-md-6">
                            <h4><?php esc_html_e('First Name', 'stm_vehicles_listing'); ?></h4>
                            <input class="user_validated_field form-control" type="text" name="stm_user_first_name"
                                   placeholder="<?php esc_html_e('Enter First name', 'stm_vehicles_listing') ?>"/>
                        </div>
                        <div class="col-md-6">
                            <h4><?php esc_html_e('Last Name', 'stm_vehicles_listing'); ?></h4>
                            <input class="user_validated_field form-control" type="text" name="stm_user_last_name"
                                   placeholder="<?php esc_html_e('Enter Last name', 'stm_vehicles_listing') ?>"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <h4><?php esc_html_e('Phone number', 'stm_vehicles_listing'); ?></h4>
                            <input class="user_validated_field form-control" type="tel" name="stm_user_phone"
                                   placeholder="<?php esc_html_e('Enter Phone', 'stm_vehicles_listing') ?>"/>
                        </div>
                        <div class="col-md-6">
                            <h4><?php esc_html_e('Email *', 'stm_vehicles_listing'); ?></h4>
                            <input class="user_validated_field form-control" type="email" name="stm_user_mail"
                                   placeholder="<?php esc_html_e('Enter E-mail', 'stm_vehicles_listing') ?>"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <h4><?php esc_html_e('Login *', 'stm_vehicles_listing'); ?></h4>
                            <input class="user_validated_field form-control" type="text" name="stm_nickname"
                                   placeholder="<?php esc_html_e('Enter Login', 'stm_vehicles_listing') ?>"/>
                        </div>
                        <div class="col-md-6">
                            <h4><?php esc_html_e('Password *', 'stm_vehicles_listing'); ?></h4>
                            <div class="stm-show-password">
                                <i class="far fa-eye-slash"></i>
                                <input class="user_validated_field form-control" type="password"
                                       name="stm_user_password"
                                       placeholder="<?php esc_html_e('Enter Password', 'stm_vehicles_listing') ?>"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-submit clearfix">
                        <input type="submit" class="button"
                               value="<?php esc_html_e('Sign up now!', 'stm_vehicles_listing'); ?>"/>
                        <span class="stm-listing-loader"><i class="fas fa-spinner"></i></span>
                    </div>

                    <div class="stm-validation-message"></div>

                </form>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        var $ = jQuery;
        $('.stm-show-password .fa').mousedown(function () {
            $(this).closest('.stm-show-password').find('input').attr('type', 'text');
            $(this).addClass('fa-eye');
            $(this).removeClass('fa-eye-slash');
        });

        $(document).mouseup(function () {
            $('.stm-show-password').find('input').attr('type', 'password');
            $('.stm-show-password .fa').addClass('fa-eye-slash');
            $('.stm-show-password .fa').removeClass('fa-eye');
        });

        $("body").on('touchstart', '.stm-show-password .fa', function () {
            $(this).closest('.stm-show-password').find('input').attr('type', 'text');
            $(this).addClass('fa-eye');
            $(this).removeClass('fa-eye-slash');
        });
    });
</script>