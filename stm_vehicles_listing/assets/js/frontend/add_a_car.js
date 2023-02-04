(function($) {
    "use strict";

    $(document).ready(function () {

        //window.hasOwnProperty = window.hasOwnProperty || Object.prototype.hasOwnProperty;

        /*Sell a car*/
        var userFiles = [];
        if(typeof stmUserFilesLoaded !== 'undefined') {
            userFiles = stmUserFilesLoaded;
        }
        var stmFeaturedID = 0;
        var orderChanged = false;
        var stm_timeout;
        $('.stm_add_car_form input[type="file"]').on('change', function(){

            var stmDontChangeFeatured = false;
            $('.stm-image-preview, .stm-placeholder-generated').not('.stm-placeholder-generated-php, .stm-placeholder-generated-php .stm-image-preview, .stm-placeholder-generated-php').remove();

            var files = $(this)[0].files;

            for(var key in files) {
                if(typeof(files[key]) === 'object') {
                    userFiles.push(files[key]);
                }
            }

            var stmFeaturedSet = 0;
            if (userFiles) {
                [].forEach.call(userFiles, readAndPreview);
            }

            function readAndPreview(file, index) {
                if(typeof(userFiles[0]) === 'number') {
                    stmDontChangeFeatured = true;
                }

                var currentFiles = index;

                // Make sure `file.name` matches our extensions criteria
                if (/\.(jpe?g|png)$/i.test(file.name)) {
                    var reader = new FileReader();

                    reader.addEventListener("load", function () {

                        stmFeaturedSet++;

                        if (userFiles[currentFiles]) {
                            var $stm_append_placeholder_start = '<div class="stm-placeholder stm-placeholder-generated"><div class="inner">';
                            var $stm_append_placeholder_end = '</div></div>';

                            var stmDelete = '<i class="fas fa-times" data-id="' + currentFiles + '"></i>'

                            if (stmFeaturedSet == 1 && !stmDontChangeFeatured) {
                                stmFeaturedID = currentFiles;
                                $('.stm-media-car-main-input').append(
                                    '<div class="stm-image-preview" data-id="' + currentFiles + '" style="background:url(' + this.result + ')"></div>'
                                );
                            }

                            $('.stm-placeholder-native').remove();
                            $('.stm-media-car-gallery')
                                .append($stm_append_placeholder_start +
                                    '<div class="stm-image-preview" data-id="' + currentFiles + '" style="background:url(' + this.result + ')">' + stmDelete + '</div>' +
                                    $stm_append_placeholder_end);

                        }

                        /*Enable droppadble on new elements again*/
                        $('.stm-media-car-gallery .stm-placeholder').droppable({
                            drop: stmDroppableEvent
                        });
                    }, false);


                    reader.readAsDataURL(file);

                    if(userFiles.length>0) {
                        $('.stm-media-car-main-input .stm-placeholder').addClass('hasPreviews');
                    } else {
                        $('.stm-media-car-main-input .stm-placeholder').removeClass('hasPreviews');
                    }
                }
            }

        });

        $(document).on('mouseenter', '.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview .fas', function(){
            $(this).closest('.inner').addClass('deleting');
        });

        $(document).on('mouseleave', '.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview .fas', function(){
            $(this).closest('.inner').removeClass('deleting');
        });

        $(document).on('click', '.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview .fas', function(){
            var stm_id = $(this).attr('data-id');
            var stm_length = 0;
            delete userFiles[stm_id];
            $('.stm-placeholder .inner').removeClass('deleting');

            $(this).closest('.stm-placeholder').remove();

            $(userFiles).each(function(i,e){
                if(typeof(e) !== 'undefined') {
                    stm_length++;
                }
            });

            if(stm_length == 0) {
                $('.stm-media-car-main-input .stm-image-preview').remove();
                $('.stm-media-car-main-input .stm-placeholder').removeClass('hasPreviews');
                var defaultPlaceholders = '';
                for(var i=0; i<5; i++) {
                    defaultPlaceholders += '<div class="stm-placeholder stm-placeholder-native"><div class="inner"><i class="stm-service-icon-photos"></i></div></div>';
                }

                $('.stm-media-car-gallery').append(defaultPlaceholders);
            }

            if(stmFeaturedID == stm_id) {
                var changeFeatured = $('.stm-media-car-gallery .stm-placeholder:nth-child(1)');
                stmFeaturedID = changeFeatured.find('.stm-image-preview').attr('data-id');

                $('.stm-media-car-main-input .stm-image-preview').remove();
                $(changeFeatured).find('.stm-image-preview').clone().appendTo('.stm-media-car-main-input');
            }

            stm_resort_array();

        });

        /*Droppable*/
        $(document).on("mouseenter", '.stm-media-car-gallery .stm-placeholder .inner .stm-image-preview', function(e){
            var item = $(this);
            item.draggable({
                revert: 'invalid',
                helper: "clone"
            })
        });

        function stm_resort_array() {
            stm_timeout = setTimeout(function(){
                var tmpArr = [];
                $('.stm-placeholder.stm-placeholder-generated').each(function (i, e) {
                    /*Get old id*/
                    var oldId = $(this).find('.stm-image-preview').attr('data-id');

                    /*Set new ids to preview and to delete icon*/
                    $(this).find('.stm-image-preview').attr('data-id', i);
                    $(this).find('.stm-image-preview .fas').attr('data-id', i);

                    if(typeof(userFiles[oldId]) !== 'undefined') {
                        tmpArr[i] = userFiles[oldId];
                    }

                });

                stmFeaturedID = 0;

                userFiles = tmpArr;
            }, 100);
        }

        $('.stm-media-car-gallery .stm-placeholder').droppable({
            drop: stmDroppableEvent
        });

        function stmDroppableEvent(event, ui) {

            var dragFromPreview = ui.draggable;
            var dragFrom = dragFromPreview.closest('.inner');

            var dragTo = $(this).find('.inner');
            var dragToPreview = dragTo.find('.stm-image-preview');


            if(dragFromPreview.length > 0 && dragToPreview.length > 0 && dragTo.length > 0 && dragFrom.length > 0) {

                if(dragFrom[0] != dragTo[0]) {

                    var dragFromId = dragFromPreview.data('id');
                    var dragToId = dragToPreview.data('id');

                    dragFromPreview.clone().appendTo(dragTo);
                    dragToPreview.clone().appendTo(dragFrom);


                    var droppingInIndex = dragTo.closest('.stm-placeholder').index();

                    var draggingIndex = dragFromPreview.closest('.stm-placeholder').index();


                    /*If placed in first pos*/
                    if (droppingInIndex === 0) {
                        $('.stm-media-car-main-input .stm-image-preview').remove();

                        dragFromPreview.clone().appendTo('.stm-media-car-main-input');

                        stmFeaturedID = dragFromPreview.data('id');
                    }

                    /*If moving from first place*/
                    if(draggingIndex === 0) {
                        $('.stm-media-car-main-input .stm-image-preview').remove();

                        dragToPreview.clone().appendTo('.stm-media-car-main-input');

                        stmFeaturedID = dragToPreview.data('id');
                    }

                    orderChanged = true;

                    dragFromPreview.remove();
                    dragToPreview.remove();

                    stm_resort_array();

                    orderChanged = true;
                }
            }
        }


        $('.stm-form-checking-user button[type="submit"]').click(function(e){
            e.preventDefault();

            var loadType = $(this).data("load");
            var gdpr = '';
            if(typeof $('input[name="motors-gdpr-agree"]')[0] !== 'undefined') {
                var gdprAgree = ($('input[name="motors-gdpr-agree"]')[0].checked) ? 'agree' : 'not_agree';
                gdpr = '&motors-gdpr-agree=' + gdprAgree;
            }

            if(!$(this).hasClass('disabled')) {
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    dataType: 'json',
                    context: this,
                    data: $( '.stm_add_car_form form' ).serialize() + gdpr + '&action=stm_ajax_add_a_car&security=' + addACar,
                    beforeSend: function(){
                        $('.stm-add-a-car-loader.' + loadType).addClass('activated');
                        $('.stm-add-a-car-message').slideUp();
                    },
                    success: function (data) {

                        $('.stm-add-a-car-loader.' + loadType).removeClass('activated');
                        if(data.message) {
                            $('.stm-add-a-car-message').html(data.message).slideDown();
                        }

                        if(data.post_id) {
                            $('.stm-add-a-car-message').text(data.message).slideDown();
                            $('.stm-add-a-car-loader.' + loadType).addClass('activated');

                            /*Photos*/
                            if(typeof(userFiles) !== 'undefined') {
                                if (!orderChanged) {
                                    stm_resort_array();
                                }

                                var fd = new FormData();
                                $.each(userFiles, function (i, file) {
                                    if (typeof(file) !== undefined) {
                                        if(typeof(file) !== 'number') {
                                            fd.append('files[' + i + ']', file);
                                        } else {
                                            fd.append('media_position_' + i, file);
                                        }
                                    }
                                });

                                if($('.stm_add_car_form').hasClass('stm_edit_car_form')) {
                                    fd.append('stm_edit', 'update');
                                }

                                fd.append('action', 'stm_ajax_add_a_car_media');
                                fd.append('post_id', data.post_id);

                                $.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: fd,
                                    contentType: false,
                                    processData: false,
                                    success: function (response) {
                                        if(typeof(response) != 'object') {
                                            var responseObj = JSON.parse(response);
                                        } else {
                                            var responseObj = response;
                                        }
                                        if(responseObj.allowed_posts) {
                                            $('.stm-posts-available-number span').text(responseObj.allowed_posts);
                                        }
                                        $('.stm-add-a-car-loader.' + loadType).removeClass('activated');
                                        if(responseObj.message) {
                                            $('.stm-add-a-car-message').html(responseObj.message).slideDown();
                                        }
                                        if(responseObj.url) {
                                            window.location = responseObj.url;
                                        }
                                    }
                                });
                            }
                        }
                    }
                });

            }

        });

    });

})(jQuery);