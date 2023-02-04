(function ($) {

    //Body background color
    wp.customize('bg_color', function (value) {
        value.bind(function (newval) {
            $('body').css('background-color', newval);
        });
    });

    //Text logo color
    wp.customize('logo_color', function (value) {
        value.bind(function (newval) {
            $('#header .logo-main .blogname h1').css('color', newval);
        });
    });

    //Text body color
    wp.customize('typography_body_color', function (value) {
        value.bind(function (newval) {
            $('body, .normal_font').css('color', newval);
        });
    });

    //Top bar bg color
    wp.customize('top_bar_bg_color', function (value) {
        value.bind(function (newval) {
            $('#top-bar').css('background-color', newval);
        });
    });
    
    //Top bar bg color
    wp.customize('header_bg_color', function (value) {
        value.bind(function (newval) {
            $('.header-main').css('background-color', newval);
        });
    });
    
    //Footer bar bg color
    wp.customize('footer_bg_color', function (value) {
        value.bind(function (newval) {
            $('#footer-main').css('background-color', newval);
        });
    });

    //Footer copyright bg color
    wp.customize('footer_copyright_color', function (value) {
        value.bind(function (newval) {
            $('#footer-copyright').css('background-color', newval);
        });
    });

    //Text menu color
    wp.customize('typography_menu_color', function (value) {
        value.bind(function (newval) {
            $('.header-menu li a').css('color', newval);
        });
    });

    //Text heading color
    wp.customize('typography_heading_color', function (value) {
        value.bind(function (newval) {
            $('h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6,.heading-font,.button,.load-more-btn,.vc_tta-panel-title,.page-numbers li > a,.page-numbers li > span,.vc_tta-tabs .vc_tta-tabs-container .vc_tta-tabs-list .vc_tta-tab a span,.stm_auto_loan_calculator input').css('color', newval);
        });
    });

    //Slider jquery ui
    wp.customize('jqueryui_slider', function (value) {
        value.bind(function (newval) {
            $('p').css('font-size', newval + 'px');
        });
    });

})(jQuery);