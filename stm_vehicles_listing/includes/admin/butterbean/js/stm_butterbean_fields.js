/*Butterbean fields*/
( function() {

    /*Multiselect*/
    butterbean.views.register_control( 'multiselect', {

        // Adds custom events.
        events : {
            'keydown .stm-multiselect-wrapper .stm_add_new_optionale input'    : 'preventsubmit',
            'click .stm-multiselect-wrapper .fa-plus'    : 'addfield',
            'click .stm-multiselect-wrapper .fa-plus'    : 'addfield',
        },

        ready: function() {
            jQuery("#butterbean-control-stm_rental_office").find('.stm_add_new_optionale').hide();
        },

        preventsubmit: function(e) {
            if( (event.keyCode == 13) ) {
                event.preventDefault();
                this.addfield(e);

                jQuery('.stm_checkbox_adder').focus();

                return false;
            }
        },

        addfield: function(m) {
            var $ = jQuery;
            var $input = $(m.currentTarget).closest('.stm_add_new_inner').find('input');
            var inputVal = $input.val();
            var $preloader = $input.closest('.stm_add_new_inner').find('i');

		    if(inputVal !== '') {
                $.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    context: this,
                    data: 'term=' + inputVal + '&category=' + this.model.attributes.name + '&action=stm_listings_add_category_in',
                    beforeSend: function () {
                        $input.closest('.stm-multiselect-wrapper').addClass('stm_loading');
                        $preloader.addClass('fa-pulse fa-spinner');
                    },
                    complete: $.proxy(function(data) {
                        data = data.responseJSON;
                        $input.closest('.stm-multiselect-wrapper').removeClass('stm_loading');
                        $preloader.removeClass('fa-pulse fa-spinner');
						jQuery(this.el).find('select').multiSelect('addOption', { value: data.slug, text: data.name});
						jQuery(this.el).find('select').multiSelect('select', [data.slug]);
                    })
                })
            }
        }

    } );

    /*Repeater checks*/
    butterbean.views.register_control( 'checkbox_repeater', {

        // Adds custom events.
        events : {
            'click .butterbean-add-checkbox'    : 'addfield',
            'click .stm_repeater_checkbox .fa-remove'    : 'deletefield',
            'click .stm_repeater_checkboxes input'    : 'changedata',
            'keydown .stm_checkbox_adder' : 'preventsubmit'
        },

        preventsubmit: function(e) {
            if( (event.keyCode == 13) ) {
                event.preventDefault();
                this.addfield(e);

                jQuery('.stm_checkbox_adder').focus();

                return false;
            }
        },

        updatemodel: function() {
            var currentValues = this.model.attributes.values;
            var value = [];

            _.each(currentValues, function(check){
                if(check['checked']) {
                    value.push(check.val);
                }
            });

            this.model.set({
                value: value.join()
            }).trigger( 'change', this.model );
        },

        changedata : function(m) {
            var $ = jQuery;
            var $addC = $(m.currentTarget);
            var currentValues = this.model.attributes.values;
            var currentValue = $addC.prop('checked');
            var currentKey = $addC.data('key');

            currentValues[currentKey]['checked'] = currentValue;

            this.updatemodel();
        },

        addfield : function(m) {
            var $ = jQuery;
            var $addB = $(m.currentTarget);
            var currentValues = this.model.attributes.values;

            var currentValue = $addB.closest('.stm_checkbox_repeater').find('.stm_checkbox_adder').val();

            if(currentValue !== '') {
                currentValues.unshift({
                    val : currentValue,
                    checked : true
                })
            }

            this.model.set({
                values: currentValues
            }).trigger( 'change', this.model );
            this.updatemodel();
        },

        deletefield : function(m) {
            var index = m.currentTarget.dataset.key;

            var currentValues = this.model.attributes.values;

            currentValues.splice(index, 1);

            this.model.set({
                values: currentValues
            }).trigger( 'change', this.model );

            this.updatemodel();
        }

    } );

    /*Repeater*/
    butterbean.views.register_control( 'repeater', {

        // Adds custom events.
        events : {
            'click .butterbean-add-field'    : 'addfield',
            'click .butterbean-delete-field'    : 'deletefield',
            'change .stm_repeater_inputs input' : 'valueadded'
        },

        valueadded: function(m) {
            var $ = jQuery;
            var key = m.currentTarget.dataset.key;
            var value = $(m.currentTarget).val();
            var currentValues = this.model.attributes.values;
            currentValues[key] = value;

            this.model.set({
                values: currentValues
            }).trigger( 'change', this.model );
        },

        addfield : function() {
            var currentValues = this.model.attributes.values;
            currentValues.push('');
            this.model.set({
                values: currentValues
            }).trigger( 'change', this.model );
        },

        deletefield : function(m) {
            var index = m.currentTarget.dataset.delete;

            var currentValues = this.model.attributes.values;

            currentValues.splice(index, 1);

            this.model.set({
                values: currentValues
            }).trigger( 'change', this.model );
        }

    } );

    /*Location*/
    butterbean.views.register_control( 'location', {

        ready: function(){
            var $ = jQuery;
            var location_id = this.model.attributes.name;
            var input = document.getElementById(location_id);

            var autocomplete = new google.maps.places.Autocomplete(input, {types: ['geocode']});

            //Place changed hook
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();

                var lat = 0;
                var lng = 0;

                if(typeof(place.geometry) !== 'undefined') {
                    lat = place.geometry.location.lat();
                    lng = place.geometry.location.lng();
                }

                $('#stm_lat_car_admin').val(lat);
                $('#stm_lng_car_admin').val(lng);
            });
        }
    } );

    /*File*/
    butterbean.views.register_control( 'file', {

        // Adds custom events
        events : {
            'click .butterbean-add-media'    : 'showmodal',
            'click .butterbean-change-media' : 'showmodal',
            'click .butterbean-remove-media' : 'removemedia'
        },

        // Executed when the show modal button is clicked.
        showmodal : function() {


            // If we already have a media modal, open it.
            if ( ! _.isUndefined( this.media_modal ) ) {

                this.media_modal.open();
                return;
            }

            // Create a new media modal.
            var format = this.model.attributes.format;

            this.media_modal = wp.media( {
                frame    : 'select',
                multiple : false,
                editing  : true,
                title    : this.model.get( 'l10n' ).choose,
                library  : { type : format },
                button   : { text:  this.model.get( 'l10n' ).set }
            } );

            // Runs when an image is selected in the media modal.
            this.media_modal.on( 'select', function() {

                // Gets the JSON data for the first selection.
                var media = this.media_modal.state().get( 'selection' ).first().toJSON();

                // Updates the model for the view.
                this.model.set( {
                    src   : media.filename,
                    value : media.id
                } );

            }, this );

            // Opens the media modal.
            this.media_modal.open();
        },

        // Executed when the remove media button is clicked.
        removemedia : function() {

            // Updates the model for the view.
            this.model.set( { src : '', value : '' } );
        }
    } );

    /*Datepicker*/

    butterbean.views.register_control( 'datepicker', {
        events : {
            'click .butterbean-datepicker': 'initDatepicker',
        },

        ready: function(){
            jQuery( '.butterbean-datepicker' ).datepicker({
                dateFormat: "m/yy"
            });
        },

        initDatepicker: function(m){
            jQuery( m.currentTarget ).datepicker();
        }
    } );

    /*Gallery*/
    butterbean.views.register_control( 'gallery', {

        // Adds custom events.
        events : {
            'click .butterbean-add-media'    : 'showmodal',
            'click .butterbean-change-media' : 'showmodal',
            'click .butterbean-remove-media' : 'removemedia',
            'click .stm_mini_thumbs .thumbs .fa-times' : 'removemedia_single'
        },

        // Executed when the show modal button is clicked.
        showmodal : function() {


            // If we already have a media modal, open it.
            if ( ! _.isUndefined( this.media_modal ) ) {

                this.media_modal.open();
                return;
            }

            // Create a new media modal.
            this.media_modal = wp.media( {
                frame    : 'select',
                multiple : true,
                editing  : true,
                title    : this.model.get( 'l10n' ).choose,
                library  : { type : 'image' },
                button   : { text:  this.model.get( 'l10n' ).set }
            } );

            // Runs when an image is selected in the media modal.
            this.media_modal.on( 'select', function() {

                // Gets the JSON data for the first selection.
                var media = this.media_modal.state().get( 'selection' ).toJSON();

                var size = this.model.attributes.size;

                var medias = this.model.attributes.values;
                var ids = this.model.attributes.value.split(',');

                _.each(media, function(img){
                    ids.push(img.id);
                    medias.push({
                        id : img.id,
                        src : img.sizes[ size ] ? img.sizes[ size ]['url'] : img.url,
                        thumb : img.sizes[ 'stm-img-350-205' ] ? img.sizes[ 'stm-img-350-205' ]['url'] : img.url,
                    })
                });

                this.model.set(medias);
                this.model.set({
                    value: ids.join()
                });

            }, this );

            // Opens the media modal.
            this.media_modal.open();
        },

        // Executed when the remove media button is clicked.
        removemedia : function() {

            this.model.set({
                value: '',
                values: []
            });

        },

        removemedia_single : function(m) {

            var index = m.currentTarget.dataset.delete;

            var medias = this.model.attributes.values;
            var ids = this.model.attributes.value.split(',');

            if(typeof medias[index] !== 'undefined' && typeof ids[index] !== 'undefined') {
                medias.splice(index, 1);
                ids.splice(index, 1);
            }

            this.model.set(medias);
            this.model.set({
                value: ids.join()
            });

            // empty gallery if last element has been deleted
            if(medias.length == 0) {
                this.removemedia();
            }

        },

        initSwap: function() {
            var $ = jQuery;

            var medias = this.model.attributes.values;

            if(typeof(medias[0]) !== 'undefined' && this.model.attributes.name == 'gallery') {
                $('#_thumbnail_id').val(medias[0].id);
            } else if(typeof(medias[0]) === 'undefined' && this.model.attributes.name == 'gallery') {
                $('#remove-post-thumbnail').click();
            }

            var moduleId = '#butterbean-control-' + this.model.attributes.name + ' ';

            $(document).on("mouseenter", moduleId + '.stm_mini_thumbs .thumbs .inner', function(e){
                var item = $(this);
                item.draggable({
                    revert: 'invalid',
                    helper: "clone",
                    start: function() {
                        item.closest('.thumbs').addClass('main-target');
                        $(moduleId + '.main_image .main_image_droppable').addClass('drop-here');
                    },
                    stop: function() {
                        item.closest('.thumbs').addClass('main-target');
                        $(moduleId + '.main_image .main_image_droppable').removeClass('drop-here');
                    }
                })
            });


            $(moduleId + '.stm_mini_thumbs .thumbs').droppable({
                drop: $.proxy(stmDroppableEvent, this),
            });

            $(moduleId + '.main_image').droppable({
                drop: $.proxy(stmDropFeatured, this),
            });

            $( moduleId + ".stm_mini_thumbs .thumbs" ).on( "dropover", function( event, ui ) {
                $(event.target).addClass('targets-here');
            } );

            $( moduleId + ".stm_mini_thumbs .thumbs" ).on( "dropout", function( event, ui ) {
                $(event.target).removeClass('targets-here');
            } );

            function stmDropFeatured(event, ui) {
                var ids = [];

                var dragFromIndex = ui.draggable[0].dataset.thumb;
                var dragToIndex = 0;

                var swapFrom = medias[dragFromIndex];
                var swapTo = medias[dragToIndex];

                medias[dragToIndex] = swapFrom;
                medias[dragFromIndex] = swapTo;

                _.each(medias, function(img){
                    ids.push(img.id);
                });

                this.model.set({
                    values: medias,
                    value: ids.join()
                });

                $(moduleId + '.stm_mini_thumbs .thumbs').removeClass('targets-here main-target');

                $(moduleId + '.main_image .main_image_droppable').removeClass('drop-here');
            }

            function stmDroppableEvent(event, ui) {
                var ids = [];

                var dragFromIndex = ui.draggable[0].dataset.thumb;
                var dragToIndex = $(event.target).find('.inner').data('thumb');

                var swapFrom = medias[dragFromIndex];
                var swapTo = medias[dragToIndex];

                medias[dragToIndex] = swapFrom;
                medias[dragFromIndex] = swapTo;

                _.each(medias, function(img){
                    ids.push(img.id);
                });

                this.model.set({
                    values: medias,
                    value: ids.join()
                })
            }
        },

        ready: function(){
            this.model.on( 'change', this.onchange, this );
            this.initSwap()
        },

        onchange: function(){
            this.initSwap()
        }

    } );

}() );

(function($) {

    $(document).ready(function () {
        var elements = '.stm_checkbox_adder,' +
            '.butterbean-datepicker,' +
            'select,input[type="text"].widefat,' +
            '.stm_repeater_inputs input[type="text"]';
        $(elements).each(function () {
            if ($(this).val()) {
                $(this).addClass('has-value');
            }
            $(document).on('change', elements, function () {
                if ($(this).val()) {
                    $(this).addClass('has-value');
                } else {
                    $(this).removeClass('has-value');
                }
            })
        });


        /*PREVIEW*/
        $(document).on('click', '.image_preview', function(){
            var stmImage = $(this).find('span').data('preview');

            $('.image-preview').addClass('visible').append('<img src="' + stmImage + '" />');
        });

        $(document).on('click', '.image-preview .overlay', function(){
            $('.image-preview').removeClass('visible').find('img').remove();
        });

        /*Reset amount*/
        $(document).on('click', '.reset_field', function(e){
            e.preventDefault();

            if($(this).data('type') == 'stm_car_views') {
                $('input[name="butterbean_stm_car_manager_setting_stm_car_views"]').val('');
            } else if($(this).data('type') == 'stm_phone_reveals') {
                $('input[name="butterbean_stm_car_manager_setting_stm_phone_reveals"]').val('');
            }
        });

        $('input[name="butterbean_stm_car_manager_setting_car_mark_woo_online"]').on('change', function(){
            $('input[name="butterbean_stm_car_manager_setting_stm_car_stock"]').val(1);
        });

        $('input[name="butterbean_stm_car_manager_setting_car_mark_as_sold"]').on('change', function(){
            if($(this).is(':checked')) {
                $('input[name="butterbean_stm_car_manager_setting_stm_car_stock"]').val(1);
                $('input[name="butterbean_stm_car_manager_setting_car_mark_woo_online"]').prop('checked', false);
                $('#butterbean-control-stm_car_stock').hide();
            }
        });
    });

    $(window).load(function(){

        $('[data-dep]').each(function(){
            var $stmThis = $(this);

            var managerName = 'stm_car_manager_setting_';

            var elementDepended = $stmThis.data('dep');

            $(document).on('change', 'input[name="butterbean_' + managerName + elementDepended + '"]', function(){
                stmHideUseless(managerName, elementDepended, $stmThis);
            });

            stmHideUseless(managerName, elementDepended, $stmThis);
        });

        function stmHideUseless(managerName, elementDepended, stm_this) {

            var depValue = stm_this.data('value').toString();

            var $elementDepended = stm_this.closest('.butterbean-control');

            var $elementDependsInput = $('input[name="butterbean_' + managerName + elementDepended + '"]');

            var elementDependsValue = '';
            if($elementDependsInput.attr('type') == 'checkbox') {
                elementDependsValue = $elementDependsInput.prop('checked');
            } else {
                elementDependsValue = $elementDependsInput.val();
            }
            elementDependsValue = elementDependsValue.toString();

            if(depValue !== elementDependsValue) {
                $elementDepended.slideUp();
            } else {
                $elementDepended.slideDown();
            }
        }

        if($('input[name="butterbean_stm_car_manager_setting_car_mark_as_sold"]').is(':checked')) {
            $('input[name="butterbean_stm_car_manager_setting_stm_car_stock"]').val(1);
            $('input[name="butterbean_stm_car_manager_setting_car_mark_woo_online"]').prop('checked', false);
            $('#butterbean-control-stm_car_stock').hide();
        }

    });
})(jQuery);