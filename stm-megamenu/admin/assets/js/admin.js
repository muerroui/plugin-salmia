(function ($) {
    "use strict";

    $(document).ready(function () {

        var $el = $(document).find('.edit-menu-item-stm_menu_icon');
        var $elRepeater = $(document).find('.edit-menu-item-stm_menu_icon_repeater');
        stm_init_fontpicker($el);
        stm_init_fontpicker($elRepeater);

        $('body').on('click', '.menu-item', function(){
            var $element = $(this).find('.edit-menu-item-stm_menu_icon');
            var $elementRepeater = $(this).find('.edit-menu-item-stm_menu_icon_repeater');
            stm_init_fontpicker($element);
            stm_init_fontpicker($elementRepeater);
        });

        stm_init_image_picker();
    });

    function stm_init_fontpicker($el) {
        $el.fontIconPicker({
            source: stmIconsSet,
            emptyIcon: true,
            hasSearch: true,
            theme: 'fip-inverted'
        });

        $('.submit-add-to-menu').on('click', function(){
            $el.fontIconPicker({
                source: stmIconsSet,
                emptyIcon: true,
                hasSearch: true,
                theme: 'fip-inverted'
            });
        })
    }

    function stm_init_image_picker() {
        $('body').on('click', '.stm_wrapper_image .add_new, .stm_wrapper_image .replace', function(e){
            e.preventDefault();

            var $btn = $(this);
            var $wrapper = $btn.closest('label');
            var $input = $wrapper.find('input');

            var media_modal = wp.media({
                frame: 'select',
                multiple: false,
                editing: true
            });

            media_modal.open();

            media_modal.on('select', function () {

                // Gets the JSON data for the first selection.
                var media = media_modal.state().get('selection').first().toJSON();
                var image = media.sizes.thumbnail.url;
                var id = media.id;

                $input.val(id);

                $wrapper.find('img').remove();

                $('<img src="'+image+'" />').insertBefore($input);

                $wrapper.addClass('has-image');

            }, this);
        });

        $('body').on('click','.stm_wrapper_image .delete', function(e){
            e.preventDefault();
            var $btn = $(this);
            var $wrapper = $btn.closest('label');
            var $input = $wrapper.find('input');

            $wrapper.removeClass('has-image');

            $wrapper.find('img').remove();
            $input.val('');
        });
    }

})(jQuery);