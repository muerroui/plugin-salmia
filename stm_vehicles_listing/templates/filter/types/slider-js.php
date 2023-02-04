<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<script type="text/javascript">
    var stmOptions_<?php echo esc_attr($js_slug); ?>;
    (function ($) {
        $(document).ready(function () {
            var affix = "<?php echo esc_js($affix); ?>";
            var stmMinValue = <?php echo esc_js($start_value); ?>;
            var stmMaxValue = <?php echo esc_js($end_value); ?>;
            stmOptions_<?php echo esc_attr($js_slug); ?> = {
                range: true,
                min: <?php echo esc_js($start_value); ?>,
                max: <?php echo esc_js($end_value); ?>,
                values: [<?php echo esc_js($min_value); ?>, <?php echo esc_js($max_value); ?>],
                step: <?php echo esc_js($slider_step); ?>,
                slide: function (event, ui) {
                    $("#stm_filter_min_<?php echo esc_attr($slug); ?>").val(ui.values[0]);
                    $("#stm_filter_max_<?php echo esc_attr($slug); ?>").val(ui.values[1]);
                    var stmText = ui.values[0] + affix + ' — ' + ui.values[1] + affix;
                    $('.filter-<?php echo($slug) ?> .stm-current-slider-labels').html(stmText);
                }
            }
            $(".stm-<?php echo esc_attr($slug); ?>-range").slider(stmOptions_<?php echo esc_attr($js_slug); ?>);


            $("#stm_filter_min_<?php echo esc_attr($slug); ?>").val($(".stm-<?php echo esc_attr($slug); ?>-range").slider("values", 0));
            $("#stm_filter_max_<?php echo esc_attr($slug); ?>").val($(".stm-<?php echo esc_attr($slug); ?>-range").slider("values", 1));

            $("#stm_filter_min_<?php echo esc_attr($slug); ?>").keyup(function () {
                $(".stm-<?php echo esc_attr($slug); ?>-range").slider("values", 0, $(this).val());
            });

            $("#stm_filter_min_<?php echo esc_attr($slug); ?>").focusout(function () {
                if ($(this).val() < stmMinValue) {
                    $(".stm-<?php echo esc_attr($slug); ?>-range").slider("values", 0, stmMinValue);
                    $(this).val(stmMinValue);
                }
            });

            $("#stm_filter_max_<?php echo esc_attr($slug); ?>").keyup(function () {
                $(".stm-<?php echo esc_attr($slug); ?>-range").slider("values", 1, $(this).val());
            });

            $("#stm_filter_max_<?php echo esc_attr($slug); ?>").focusout(function () {
                if ($(this).val() > stmMaxValue) {
                    $(".stm-<?php echo esc_attr($slug); ?>-range").slider("values", 1, stmMaxValue);
                    $(this).val(stmMaxValue);
                }
            });
        })
    })(jQuery);
</script>