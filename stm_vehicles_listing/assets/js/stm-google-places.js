(function($) {
    "use strict";

    function addGoogleAutocomplete(location_id) {
        var input = document.getElementById(location_id);

        var autocomplete = new google.maps.places.Autocomplete(input,{types: ['geocode']});

        //Place changed hook
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();

            var lat = 0;
            var lng = 0;

            if(typeof place.geometry !== 'undefined') {
                lat = place.geometry.location.lat();
                lng = place.geometry.location.lng();
            }

            $('#' + location_id).closest('.stm-location-search-unit').find('input[name="stm_lat"]').val(lat);
            $('#' + location_id).closest('.stm-location-search-unit').find('input[name="stm_lng"]').val(lng);
        });

        //If user just entered some text, without getting prediction, geocode it
        google.maps.event.addDomListener(input, 'keydown', function(e) {

            var places = autocomplete.getPlace();

            if(typeof(places) == 'undefined') {
                geocoder_by_input(location_id);
            } else {
                if(typeof(places.geometry) == 'undefined' || places.name != $('#' + location_id )) {
                    geocoder_by_input(location_id);
                }
            }

            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });
    }

    function geocoder_by_input(location_id) {

        var address_search = $('#' + location_id).val();

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address_search}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var lat = results[0].geometry.location.lat();
                var lng = results[0].geometry.location.lng();

                $('#' + location_id).closest('.stm-location-search-unit').find('input[name="stm_lat"]').val(lat);
                $('#' + location_id).closest('.stm-location-search-unit').find('input[name="stm_lng"]').val(lng);
            }
        });
    }



    function initialize_stm_places_search() {
        $('.stm_listing_search_location').each(function () {
            var location_id = $(this).attr('id');
            if (typeof(location_id) != 'undefined') {
                addGoogleAutocomplete(location_id);
            }
        });

        if($('#ca_location_listing_filter').length >0) {
            addGoogleAutocomplete('ca_location_listing_filter');
        }

        if($('#stm-add-car-location').length >0) {
            addGoogleAutocomplete('stm-add-car-location');
        }

        if($('#stm_google_user_location_entry').length >0) {
            addGoogleAutocomplete('stm_google_user_location_entry');
        }


    }

    function init(){
        google.maps.event.addDomListener(window, 'load', initialize_stm_places_search);
    }

    $(document).ready(function(){
        init();

        var cur_val = $('#ca_location_listing_filter').val();
        if(cur_val == '') {
            $('#ca_location_listing_filter').addClass('empty');
        } else {
            $('#ca_location_listing_filter').removeClass('empty');
        }

        $('#ca_location_listing_filter').on('keyup', function(){
            if($(this).val() == '') {
                $(this).addClass('empty');
            } else {
                $(this).removeClass('empty');
            }
        })
    })

})(jQuery);