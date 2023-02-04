(function ($) {
    $(document).ready(function () {
        //$('#stmMATabs li:nth-child(1) a').tab('show');


        $('input[name="ad_type"]').on('change', function () {
            $('.banner-ids-wrap').removeClass('visible');
            $('.interstitial-ids-wrap').removeClass('visible');

            if ($(this).val() == 'banner') {
                $('.banner-ids-wrap').addClass('visible');
            } else {
                $('.interstitial-ids-wrap').addClass('visible');
            }
        });

        $('input[name="stm-ma-str-placeholder"]').on('change', function () {
            var key = $(this).attr('data-key');
            $('input[data-key="hidden-' + key + '"]').val($(this).val());
        });

        $('input[name="grid_view"]').on('change', function () {
            remove_grid_check();
            $(this).parent().parent().addClass('grid-checked');
            let change_view = $(this).val();
            change_view_set(change_view);
        });

        $('.grid_four').on('click', function () {
            remove_grid_check();
            $(this).addClass('grid-checked');
            $(this).find('.grid_view').prop( "checked", true );
        });

        $('input[name="main_color"]').wpColorPicker({
            defaultColor: '#1bc744',
            change: function (event, ui) {
            },
            clear: function () {
            },
            hide: true,
            palettes: false
        });

        $('input[name="second_color"]').wpColorPicker({
            defaultColor: '#2d60f3',
            change: function (event, ui) {
            },
            clear: function () {
            },
            hide: true,
            palettes: false
        });

        $('#filter-select').multiSelect({
            keepOrder: true,
            afterSelect: function (val) {
                var get_val = $('input[name="filter-opt"]').val();
                var hidden_val = (get_val != "") ? get_val + "," : get_val;

                $('input[name="filter-opt"]').val(hidden_val + "" + val);
            },
            afterDeselect: function (val) {
                var get_val = $('input[name="filter-opt"]').val();
                var new_val = get_val.replace(val + ',', "");
                new_val = new_val.replace(val, "");

                $('input[name="filter-opt"]').val(new_val);
            }
        });

        $('#step_one-select').multiSelect({
            keepOrder: true,
            afterSelect: function (val) {
                var get_val = $('input[name="step_one-opt"]').val();
                var hidden_val = (get_val != "") ? get_val + "," : get_val;

                $('#step_two-select').multiSelect('deselect', val[0]);
                $('#step_three-select').multiSelect('deselect', val[0]);

                $('input[name="step_one-opt"]').val(hidden_val + "" + val);
            },
            afterDeselect: function (val) {
                var get_val = $('input[name="step_one-opt"]').val();
                var new_val = get_val.replace(val + ',', "");
                new_val = new_val.replace(val, "");

                $('input[name="step_one-opt"]').val(new_val);

            }
        });

        // $('#step_one-select').multiSelect('deselect', 'location');
        //$('#step_one-select').multiSelect('deselect', 'make');


        $('#step_two-select').multiSelect({
            keepOrder: true,
            afterSelect: function (val) {
                var get_val = $('input[name="step_two-opt"]').val();
                var hidden_val = (get_val != "") ? get_val + "," : get_val;

                $('input[name="step_two-opt"]').val(hidden_val + "" + val);
                $('#step_one-select').multiSelect('deselect', val[0]);
                $('#step_three-select').multiSelect('deselect', val[0]);
            },
            afterDeselect: function (val) {
                var get_val = $('input[name="step_two-opt"]').val();
                var new_val = get_val.replace(val + ',', "");
                new_val = new_val.replace(val, "");

                $('input[name="step_two-opt"]').val(new_val);
            }
        });

        $('#step_three-select').multiSelect({
            keepOrder: true,
            afterSelect: function (val) {
                var get_val = $('input[name="step_three-opt"]').val();
                var hidden_val = (get_val != "") ? get_val + "," : get_val;

                $('input[name="step_three-opt"]').val(hidden_val + "" + val);
                $('#step_one-select').multiSelect('deselect', val[0]);
                $('#step_two-select').multiSelect('deselect', val[0]);
            },
            afterDeselect: function (val) {
                var get_val = $('input[name="step_three-opt"]').val();
                var new_val = get_val.replace(val + ',', "");
                new_val = new_val.replace(val, "");

                $('input[name="step_three-opt"]').val(new_val);
            }
        });

        if (filterParams.length > 0) {
            filterParams.forEach(function (item, i, filterParams) {
                $('#filter-select').multiSelect('select', item);
            });
        }

        if (stepOne.length > 0) {
            stepOne.forEach(function (item, i, stepOne) {
                $('#step_one-select').multiSelect('select', item);
            });
        }
        if (stepTwo.length > 0) {
            stepTwo.forEach(function (item, i, stepTwo) {
                $('#step_two-select').multiSelect('select', item);
            });
        }
        if (stepThree.length > 0) {
            stepThree.forEach(function (item, i, stepThree) {
                $('#step_three-select').multiSelect('select', item);
            });
        }

        function remove_grid_check() {

            $('.radio-wrap').removeClass('grid-checked');
            $('.grid_four').removeClass('grid-checked');
        }

        function change_view_set(change_view) {

            let grid_one = {
                'go_one': ['condition', 'ca-year'],
                'go_two': ['make', 'serie'],
                'go_three': []
            };
            let grid_two = {
                'go_one': ['condition', 'ca-year'],
                'go_two': ['make', 'serie'],
                'go_three': ['mileage']
            };

            let grid_three = {
                'go_one': ['condition', 'ca-year'],
                'go_two': ['make', 'serie'],
                'go_three': ['transmission']
            };

            switch (change_view) {
                case 'grid_one':
                    set_val(grid_one);
                    break;
                case 'grid_two':
                    set_val(grid_two)
                    break;
                case 'grid_three':
                    set_val(grid_three)
                    break;

            }
        }

        function set_val(val) {
            //$('#go_one').val(val['go_one']).change();
            //$('#go_two').val(val['go_two']).change();
            //$('#go_three').val(val['go_three']).change();

            let loop_val = ['one', 'two', 'three'];
            $.each(loop_val, function (index, v) {
                let value = val['go_' + v];
                value = value.toString();
                $('input[name="grid_' + v + '_text"]').val(value)
            });
        }
    });
})(jQuery);