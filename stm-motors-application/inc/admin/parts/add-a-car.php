<?php
$mpSettings = get_option( 'add_a_car_settings', '' );

$stepOne = get_option( 'add_car_step_one', "add_media,make,serie,ca-year,mileage,exterior-color" );
$stepTwo = get_option( 'add_car_step_two', "engine,fuel,transmission,drive,body,location,price" );
$stepThree = get_option( 'add_car_step_three', "seller_notes,additional_features" );

$options = (function_exists('stm_listings_get_my_options_list')) ? stm_listings_get_my_options_list() : array();
$opt = '';
$filterOpt = '';
$filterOpt .= '<option value="add_media">Add Media</option>';
$filterOpt .= '<option value="location">Location</option>';
foreach ( $options as $key => $option ) {
    if ( $option['slug'] != 'price' ) {
        $opt .= '<option value=' . $option['slug'] . '>' . $option['single_name'] . '</option>';
    }
    $filterOpt .= '<option value=' . $option['slug'] . '>' . $option['single_name'] . '</option>';
}
$filterOpt .= '<option value="seller_notes">Seller notes</option>';
$filterOpt .= '<option value="stm_additional_features">Car Features</option>';

?>
<script>
    var stepOne = <?php echo ( !empty( $stepOne ) ) ? json_encode( explode( ',', $stepOne ) ) : '""'; ?>;
    var stepTwo = <?php echo ( !empty( $stepTwo ) ) ? json_encode( explode( ',', $stepTwo ) ) : '""'; ?>;
    var stepThree = <?php echo ( !empty( $stepThree ) ) ? json_encode( explode( ',', $stepThree ) ) : '""'; ?>;
</script>
<div class="stm-ma-add_a_car-settings">
    <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-add_a_car" role="tabpanel"
             aria-labelledby="v-pills-add_a_car-tab">
            <div class="add_a_car_steps_wrap">
                <div class="step-one">
                    <label>Step One</label>
                    <select multiple="multiple" id="step_one-select" name="step_one[]">
                        <?php echo apply_filters('stm_ma_add_car_step_one_filter', $filterOpt); ?>
                    </select>
                </div>
                <div class="step-two">
                    <label>Step Two</label>
                    <select multiple="multiple" id="step_two-select" name="step_two[]">
                        <?php echo apply_filters('stm_ma_add_car_step_two_filter', $filterOpt); ?>
                    </select>
                </div>
                <div class="step-three">
                    <label>Step Three</label>
                    <select multiple="multiple" id="step_three-select" name="step_three[]">
                        <?php echo apply_filters('stm_ma_add_car_step_three_filter', $filterOpt); ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <input type="hidden" name="step_one-opt"/>
                    <input type="hidden" name="step_two-opt"/>
                    <input type="hidden" name="step_three-opt"/>
                </div>
            </div>
        </div>
    </div>
</div>
