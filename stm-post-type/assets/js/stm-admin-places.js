(function($) {
    "use strict";

    function initialize_stm_places_search() {


        var input = document.getElementById('stm_car_location');

        if(input !== null) {


            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function (e) {
                var place = autocomplete.getPlace();

                var lat = '';
                var lng = '';

                if (typeof(place.geometry) != 'undefined') {
                    lat = place.geometry.location.lat();
                    lng = place.geometry.location.lng();
                } else {
                    lat = '';
                    lng = '';
                }

                $('#stm_lat_car_admin').val(lat);
                $('#stm_lng_car_admin').val(lng);
            });

            google.maps.event.addDomListener(input, 'keydown', function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                }
            });
        }

    }

    google.maps.event.addDomListener(window, 'load', initialize_stm_places_search);

})(jQuery);
