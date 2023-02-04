<?php


function stm_ma_enqueue_admin_ss() {
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );

    wp_register_style( 'ma-bootstrap', STM_MOTORS_APP_URL . '/assets/css/bootstrap.min.css', false, STM_MOTORS_APP_VERSION );
    wp_register_style( 'ma-multi-select', STM_MOTORS_APP_URL . '/assets/css/multi-select.css', false, STM_MOTORS_APP_VERSION );
    wp_register_style( 'ma-select2', STM_MOTORS_APP_URL . '/assets/css/select2.css', false, STM_MOTORS_APP_VERSION );
    wp_register_style( 'ma-styles', STM_MOTORS_APP_URL . '/assets/css/styles.css', false, STM_MOTORS_APP_VERSION );
    wp_register_script( 'ma-bootstrap', STM_MOTORS_APP_URL . '/assets/js/bootstrap.min.js', false, STM_MOTORS_APP_VERSION );
    wp_register_script( 'ma-multi-select', STM_MOTORS_APP_URL . '/assets/js/jquery.multi-select.js', false, STM_MOTORS_APP_VERSION );
    wp_register_script( 'ma-select2', STM_MOTORS_APP_URL . '/assets/js/select2.js', 'jquery', STM_MOTORS_APP_VERSION );
    wp_register_script( 'ma-app', STM_MOTORS_APP_URL . '/assets/js/app.js', 'jquery', STM_MOTORS_APP_VERSION );
}

add_action( 'admin_enqueue_scripts', 'stm_ma_enqueue_admin_ss' );