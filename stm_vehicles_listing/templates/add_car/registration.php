<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$user_title = '';
$user_text = '';
$link = array();
?>

<div class="stm-user-registration-unit">
    <div class="clearfix stm_register_title">
        <h3><?php esc_html_e('Sign Up', 'stm_vehicles_listing'); ?></h3>
        <div class="stm_login_me"><?php esc_html_e('Already Registered? Members', 'stm_vehicles_listing'); ?>
            <a href="#"><?php esc_html_e('Login Here', 'stm_vehicles_listing'); ?></a>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="stm-login-register-form">
                <div class="stm-register-form">
                    <form method="post">
                        <input type="hidden" name="redirect" value="disable">

                        <div class="row form-group">
                            <div class="col-md-6">
                                <h4><?php esc_html_e('First Name', 'stm_vehicles_listing'); ?></h4>
                                <input class="form-control user_validated_field" type="text" name="stm_user_first_name"
                                       placeholder="<?php esc_html_e('Enter First name', 'stm_vehicles_listing') ?>"/>
                            </div>
                            <div class="col-md-6">
                                <h4><?php esc_html_e('Last Name', 'stm_vehicles_listing'); ?></h4>
                                <input class="form-control user_validated_field" type="text" name="stm_user_last_name"
                                       placeholder="<?php esc_html_e('Enter Last name', 'stm_vehicles_listing') ?>"/>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <h4><?php esc_html_e('Phone number', 'stm_vehicles_listing'); ?></h4>
                                <input class="form-control user_validated_field" type="tel" name="stm_user_phone"
                                       placeholder="<?php esc_html_e('Enter Phone', 'stm_vehicles_listing') ?>"/>
                            </div>
                            <div class="col-md-6">
                                <h4><?php esc_html_e('Email *', 'stm_vehicles_listing'); ?></h4>
                                <input class="form-control user_validated_field" type="email" name="stm_user_mail"
                                       placeholder="<?php esc_html_e('Enter E-mail', 'stm_vehicles_listing') ?>"/>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <h4><?php esc_html_e('Login *', 'stm_vehicles_listing'); ?></h4>
                                <input class="form-control user_validated_field" type="text" name="stm_nickname"
                                       placeholder="<?php esc_html_e('Enter Login', 'stm_vehicles_listing') ?>"/>
                            </div>
                            <div class="col-md-6">
                                <h4><?php esc_html_e('Password *', 'stm_vehicles_listing'); ?></h4>
                                <div class="stm-show-password">
                                    <i class="far fa-eye-slash"></i>
                                    <input class="form-control user_validated_field" type="password"
                                           name="stm_user_password"
                                           placeholder="<?php esc_html_e('Enter Password', 'stm_vehicles_listing') ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-submit">
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