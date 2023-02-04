(function ($) {
    "use strict";

    var onHoverMap = [];

    $(document).ready(function () {
        stretch_child();
        setActive();
        wrapMenuElement();

        var windowW = $(document).width();

        if(windowW < 450) {
            moveMenuContent();
        }
    });

    $(window).on('load', function(){
        stretch_child();
    });

    $(window).resize(function(){
        stretch_child();
    });

    function moveMenuContent() {}

    function setActive() {
        $('.stm_menu_child_use_post').each( function () {
            var stmMenuChild = $(this);

            stmMenuChild.find('.sub-menu li.menu-item').first().addClass('active');

            stmMenuChild.find('.sub-menu li.menu-item').on('mouseover', function () {
                //$(this).parent().parent().addClass('active');
                $('.sub-menu li.menu-item').removeClass('active').removeClass('remove_before');
                $(this).addClass('active').addClass('remove_before');
            })

            stmMenuChild.find('.sub-menu li.menu-item').on('mouseout', function () {
                //$(this).parent().parent().removeClass('active');
                $(this).removeClass('active');
                $(this).first().addClass('active');
            })
        });
    }

    function wrapMenuElement () {
        $('.stm_menu_item_has_filter_posts').each( function () {

            var bg = $(this).find('a[data-megabg]').attr('data-megabg');

            var style = '';
            var hasBg = '';
            if( typeof(bg) != 'undefined' ) {
                style = 'style="background-image: url(' + bg + ');"';
                hasBg = 'stm-mm-has_bg'
            }

            if($(this).find('.sub-menu').length > 0) {
                $(this).find('.stm-mm-category-filter-wrap, .sub-menu').wrapAll('<div class="stm-mm-container ' + hasBg + '" ' + style + '></div>');
            } else {
                $(this).find('.stm-mm-category-filter-wrap').wrap('<div class="stm-mm-container ' + hasBg + '" ' + style + '></div>');
            }

        } );
    }

    function stretch_child() {
        // Wide
        var $wide = $('.stm_megamenu__wide > ul.sub-menu');
        var windowW = $(document).width();

        if ($wide.length) {
            var $containerWide = $wide.closest('.header_top .container, .top_nav .container');
            var containerWideW = $containerWide.width();

            // -15 due to global style left 15px
            var xPos = ((windowW - containerWideW) / 2 ) - 15;

            $wide.each(function () {

                $(this).css({
                    width: windowW + 'px',
                    'margin-left': '-' + xPos + 'px'
                })
            })
        }

        // Boxed
        var $boxed = $('.stm_megamenu__boxed > ul.sub-menu');
        if ($boxed.length) {
            var $container = $boxed.closest('.header_top .container, .top_nav .container');
            var containerW = $container.width();


            $boxed.each(function () {
                $(this).css({
                    width: containerW + 'px'
                })
            })
        }


        /*GET BG*/
        var $mega_menu = $('.stm_megamenu');
        $mega_menu.each(function(){
            var bg = $(this).find('a[data-megabg]').attr('data-megabg');
            if(typeof bg !== 'undefined') {
                $(this).find(' > ul.sub-menu').css({
                    'background-image' : 'url("' + bg + '")'
                })
            }
        })
    }


})(jQuery);