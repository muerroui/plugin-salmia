jQuery(document).ready(function ($) {
    "use strict";

    $(".stm-icons-wrapper label").on("click", function () {
        $(this).closest("ul").find("li.active").removeClass("active");
        $(this).closest("li").addClass("active");
    });

    $(".stm-color-selector").wpColorPicker({
        change: _.throttle(function () {
            $(this).trigger('change');
        })
    });

    $(".stm_iconpicker").fontIconPicker({
        theme: "fip-bootstrap",
        emptyIcon: false,
        source: stm_icons_array
    });

    $(".stm-multiple-checkbox-wrapper input[type='checkbox']").on("change", function () {

        var checkbox_values = jQuery(this).parents(".customize-control").find("input[type='checkbox']:checked").map(function () {
            return this.value;
        }).get().join(",");

        $(this).parents(".stm-multiple-checkbox-wrapper").find("input[type='hidden']").val(checkbox_values).trigger("change");
    });

    $(".stm-socials-wrapper input[type='text']").on("change, keyup", function () {

        var data = $(this).closest("form").serialize();

        $(this).parents('.stm-socials-wrapper').find('input[type="hidden"]').val(data).trigger('change');
    });

    var bg_image = $("#customize-control-bg_image input");
    var site_layout_checked = $("#customize-control-site_boxed input:checked");

    var colorCustom = $('#site_style').val();
    if(colorCustom=='site_style_custom') {
        $('#customize-control-site_style_secondary_color_listing,#customize-control-site_style_base_color_listing,#customize-control-site_style_base_color,#customize-control-site_style_secondary_color')
            .addClass('active');
    }

    $('#site_style').on('change', function(){
       if($(this).val()=='site_style_custom') {
           $('#customize-control-site_style_secondary_color_listing,#customize-control-site_style_base_color_listing,#customize-control-site_style_base_color,#customize-control-site_style_secondary_color')
               .addClass('active');
       } else {
           $('#customize-control-site_style_secondary_color_listing,#customize-control-site_style_base_color_listing,#customize-control-site_style_base_color,#customize-control-site_style_secondary_color')
               .removeClass('active');
       }
    });

    wp.customize('site_boxed', function (value) {
        value.bind(function (to) {
            if (to) {
                $("#customize-control-bg_image").show();
                $("#customize-control-custom_bg_image").show();
            } else {
                $("#customize-control-bg_image").hide();
                $("#customize-control-custom_bg_image").hide();
            }
        });
    });

    if (site_layout_checked.val()) {
        $("#customize-control-bg_image").show();
        $("#customize-control-custom_bg_image").show();
    } else {
        $("#customize-control-bg_image").hide();
        $("#customize-control-custom_bg_image").hide();
    }

    bg_image.on('change', function () {
        $(".theme_bg li.active").removeClass('active');
        $(this).closest('li').addClass('active');
    });

    $("#customize-control-bg_image input[name='bg_image']:checked").closest('li').addClass('active');

    function changeHeaderEl() {
        var curVal = $('#motorcycle_header_layout').val();

        if(curVal == 'car_dealer') {
            changing_elements.show();
        } else {
            changing_elements.hide();
        }
    }

    /*HIDE SETTINGS CUSTOMIZER*/
    var changing_elements = $('#customize-control-header_main_phone_label,' +
        '#customize-control-header_secondary_phone_label_1,' +
        '#customize-control-header_secondary_phone_1,' +
        '#customize-control-header_secondary_phone_label_2,' +
        '#customize-control-header_secondary_phone_2,' +
        '#customize-control-header_address,' +
        '#customize-control-header_address_url,' +
        '#customize-control-header_layout_break_1,' +
        '#customize-control-header_sticky,' +
        '#customize-control-header_style,' +
        '#customize-control-header_break_1,' +
        '#customize-control-header_layout_break_2');

    if($('#motorcycle_header_layout').length) {
        changeHeaderEl();

        $('#motorcycle_header_layout').on('change', function(){
            changeHeaderEl();
        });
    }
});