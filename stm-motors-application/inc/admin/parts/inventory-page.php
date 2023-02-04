<?php


$inventoryView = get_option('inventory_view', 'inventory_view_grid');
$filterParams = get_option('filter_params', "make,serie,ca-year,price");

$gridView = get_option('grid_view_type', array());
$gridOpt = get_option('grid_view_settings', array());
$gridOptCustom = get_option('grid_view_custom_settings', array());

$listOpt = get_option('list_view_settings', array());
$detailsOpt = get_option('listing_details_settings', array());

$gridView = (empty($gridView)) ? 'grid_one' : $gridView;
$go_one = (empty($gridOpt['go_one'])) ? 'condition,ca-year' : $gridOpt['go_one'];
$go_two = (empty($gridOpt['go_two'])) ? 'make,serie' : $gridOpt['go_two'];
$go_three = (empty($gridOpt['go_three'])) ? 'engine' : $gridOpt['go_three'];

$custom_go_one = (empty($gridOptCustom['custom_go_one'])) ? 'condition,ca-year' : $gridOptCustom['custom_go_one'];
$custom_go_two = (empty($gridOptCustom['custom_go_two'])) ? 'make,serie' : $gridOptCustom['custom_go_two'];
$custom_go_three = (empty($gridOptCustom['custom_go_three'])) ? '' : $gridOptCustom['custom_go_three'];


$listTitle = (empty($listOpt['list_title'])) ? 'condition,ca-year,make,model' : $listOpt['list_title'];
$listInfoOne = (empty($listOpt['list_info_one'])) ? 'mileage' : $listOpt['list_info_one'];
$listInfoTwo = (empty($listOpt['list_info_two'])) ? 'body' : $listOpt['list_info_two'];
$listInfoThree = (empty($listOpt['list_info_three'])) ? 'fuel' : $listOpt['list_info_three'];
$listInfoFour = (empty($listOpt['list_info_four'])) ? 'tonnage' : $listOpt['list_info_four'];

$ldTitle = (empty($detailsOpt['ld_title'])) ? 'make,serie' : $detailsOpt['ld_title'];
$ldSubTitle = (empty($detailsOpt['ld_subtitle'])) ? 'condition,ca-year' : $detailsOpt['ld_subtitle'];
$ldInfo = (empty($detailsOpt['ld_info'])) ? 'mileage,body,fuel' : $detailsOpt['ld_info'];

$options = (function_exists('stm_listings_get_my_options_list')) ? stm_listings_get_my_options_list() : array();
$opt = '';
$filterOpt = '';
foreach ($options as $key => $option) {
	if ($option['slug'] != 'price') {
		$opt .= '<option value=' . $option['slug'] . '>' . $option['single_name'] . '</option>';
	}
	$filterOpt .= '<option value=' . $option['slug'] . '>' . $option['single_name'] . '</option>';
}
$filterOpt .= '<option value="search_radius">Search Radius</option>';
?>
<script>
    var filterParams = <?php echo json_encode(explode(',', $filterParams)); ?>;
</script>
<div class="stm-ma-inventory-settings">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-general-tab" data-toggle="pill" href="#v-pills-general"
                   role="tab"
                   aria-controls="v-pills-general"
                   aria-selected="true"><?php echo esc_html__('General', STM_MOTORS_APP_DOMAIN); ?></a>
                <a class="nav-link" id="v-pills-grid-tab" data-toggle="pill" href="#v-pills-grid" role="tab"
                   aria-controls="v-pills-grid"
                   aria-selected="true"><?php echo esc_html__('Grid Preview', STM_MOTORS_APP_DOMAIN); ?></a>
                <a class="nav-link" id="v-pills-list-tab" data-toggle="pill" href="#v-pills-list" role="tab"
                   aria-controls="v-pills-list"
                   aria-selected="false"><?php echo esc_html__('List Preview', STM_MOTORS_APP_DOMAIN); ?></a>
                <a class="nav-link" id="v-pills-inv-details-tab" data-toggle="pill" href="#v-pills-inv-details"
                   role="tab"
                   aria-controls="v-pills-inv-details"
                   aria-selected="false"><?php echo esc_html__('Single Listing Details', STM_MOTORS_APP_DOMAIN); ?></a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel"
                     aria-labelledby="v-pills-general-tab">
                    <label><?php echo esc_html__('Select Inventory View', STM_MOTORS_APP_DOMAIN); ?></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="radio" id="inventoryList" name="inventory_view"
                                       value="inventory_view_list" <?php if ($inventoryView == 'inventory_view_list') echo 'checked'; ?> />
                                <label for="inventoryList"><?php echo esc_html__('List View', STM_MOTORS_APP_DOMAIN); ?></label>
                            </div>
                            <div class="form-group">
                                <input type="radio" id="inventoryGrid" name="inventory_view"
                                       value="inventory_view_grid" <?php if ($inventoryView == 'inventory_view_grid') echo 'checked'; ?> />
                                <label for="inventoryGrid"><?php echo esc_html__('Grid View', STM_MOTORS_APP_DOMAIN); ?></label>
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                    <label><?php echo esc_html__('Use for Inventory Filter', STM_MOTORS_APP_DOMAIN); ?></label>
                    <div class="row">
                        <div class="sol-md-12">
                            <select multiple="multiple" id="filter-select" name="filter_select[]">
								<?php echo apply_filters('stm_filter_options', $filterOpt); ?>
                            </select>
                            <input type="hidden" name="filter-opt"/>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-grid" role="tabpanel"
                     aria-labelledby="v-pills-grid-tab">
                    <label><?php echo esc_html__('Select Grid View', STM_MOTORS_APP_DOMAIN); ?></label>
                    <div class="row grid-types">
                        <div class="col-md-4">
                            <div class="radio-wrap <?php if ($gridView == 'grid_one') echo 'grid-checked'; ?>">
                                <label>
                                    <input type="radio" name="grid_view"
                                           value="grid_one" <?php if ($gridView == 'grid_one') echo 'checked'; ?> />
                                    <img src="<?php echo STM_MOTORS_APP_URL . '/assets/img/grid_one.jpg' ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio-wrap <?php if ($gridView == 'grid_two') echo 'grid-checked'; ?>">
                                <label>
                                    <input type="radio" name="grid_view"
                                           value="grid_two" <?php if ($gridView == 'grid_two') echo 'checked'; ?>/>
                                    <img src="<?php echo STM_MOTORS_APP_URL . '/assets/img/grid_two.jpg' ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio-wrap <?php if ($gridView == 'grid_three') echo 'grid-checked'; ?>">
                                <label>
                                    <input type="radio" name="grid_view"
                                           value="grid_three" <?php if ($gridView == 'grid_three') echo 'checked'; ?>/>
                                    <img src="<?php echo STM_MOTORS_APP_URL . '/assets/img/grid_three.jpg' ?>"/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="preview-wrap grid_four <?php if ($gridView == 'grid_four') echo 'grid-checked'; ?>">

                                 <input type="radio"  class="grid_view" name="grid_view" style="display: none"
                                           value="grid_four" <?php if ($gridView == 'grid_four' || $gridView == '') echo 'checked'; ?> />
                                <div class="img">
                                    <img src="<?php echo STM_MOTORS_APP_URL . '/assets/img/grid_one_img.jpg' ?>"/>
                                </div>
                                <table>
                                    <tr>
                                        <td>
                                            <div class="price">$21 900</div>
                                        </td>
                                        <td>
                                            <div class="input-wrap">
                                                <div class="select-wrap">
                                                    <select name="custom_go_one" id="go_one" multiple>
														<?php echo apply_filters('stm_go_one_filter_opt', $opt); ?>
                                                    </select>
                                                </div>

                                                <div name="custom_go_two" class="select-wrap">
                                                    <select id="go_two" multiple>
														<?php echo apply_filters('stm_go_two_filter_opt', $opt); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="photo_count">
                                                <div  class="select-wrap">
                                                    <select name="custom_go_three" id="go_three" multiple>
														<?php echo apply_filters('stm_go_three_filter_opt', $opt); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <input type="hidden" name="grid_one_text" value="<?php echo esc_attr($go_one); ?>"/>
                            <input type="hidden" name="grid_two_text" value="<?php echo esc_attr($go_two); ?>"/>
                            <input type="hidden" name="grid_three_text" value="<?php echo esc_attr($go_three); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-list" role="tabpanel" aria-labelledby="v-pills-list-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="preview-wrap">
                                <div class="img">
                                    <img src="<?php echo STM_MOTORS_APP_URL . '/assets/img/list_view.jpg' ?>"/>
                                </div>
                                <table>
                                    <tr>
                                        <td colspan="2">

                                            <div class="select-wrap">
                                                <select id="list_title" multiple>
													<?php echo apply_filters('stm_list_aa_filter_opt', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="select-wrap">
                                                <select id="list_info_one" multiple>
													<?php echo apply_filters('stm_list_ab_filter_opt', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="select-wrap">
                                                <select id="list_info_two" multiple>
													<?php echo apply_filters('stm_list_ac_filter_opt', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="select-wrap">
                                                <select id="list_info_three" multiple>
													<?php echo apply_filters('stm_list_ad_filter_opt', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="select-wrap">
                                                <select id="list_info_four" multiple>
													<?php echo apply_filters('stm_list_ae_filter_opt', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <input type="hidden" name="list_info_one" value="<?php echo esc_attr($listInfoOne); ?>"/>
                            <input type="hidden" name="list_info_two" value="<?php echo esc_attr($listInfoTwo); ?>"/>
                            <input type="hidden" name="list_info_three"
                                   value="<?php echo esc_attr($listInfoThree); ?>"/>
                            <input type="hidden" name="list_info_four" value="<?php echo esc_attr($listInfoFour); ?>"/>
                            <input type="hidden" name="list_title" value="<?php echo esc_attr($listTitle); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-inv-details" role="tabpanel"
                     aria-labelledby="v-pills-inv-details-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="preview-wrap">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="select-wrap">
                                                <label><?php echo esc_html__('Title', STM_MOTORS_APP_DOMAIN); ?></label>
                                                <select id="ld_title" multiple>
													<?php echo apply_filters('stm_ma_inv_title', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="select-wrap">
                                                <label><?php echo esc_html__('Subtitle', STM_MOTORS_APP_DOMAIN); ?></label>
                                                <select id="ld_subtitle" multiple>
													<?php echo apply_filters('stm_ma_inv_subtitle', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="select-wrap">
                                                <label><?php echo esc_html__('Listing Info', STM_MOTORS_APP_DOMAIN); ?></label>
                                                <select id="ld_info" multiple>
													<?php echo apply_filters('stm_ma_inv_l_i', $opt); ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <input type="hidden" name="listing_details_title"
                                   value="<?php echo esc_attr($ldTitle); ?>"/>
                            <input type="hidden" name="listing_details_subtitle"
                                   value="<?php echo esc_attr($ldSubTitle); ?>"/>
                            <input type="hidden" name="listing_details_info" value="<?php echo esc_attr($ldInfo); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {
            $('#go_one').select2({
                maximumSelectionLength: 2,
                multiple: true,
                placeholder: 'Select category'
            });

            $('#go_two').select2({
                maximumSelectionLength: 2,
                placeholder: 'Select category'
            });

            $('#go_three').select2({
                maximumSelectionLength: 1,
                placeholder: 'Select category'
            });

            $('#list_title').select2({
                maximumSelectionLength: 4,
                placeholder: 'Select category'
            });

            $('#list_info_one, #list_info_two, #list_info_three, #list_info_four').select2({
                maximumSelectionLength: 1,
                placeholder: 'Select category'
            });

            $('#ld_title, #ld_subtitle, #ld_info').select2({
                placeholder: 'Select category'
            });

            $('#go_one').on('select2:select', function (e) {
                $('input[name="grid_one_text"]').val($(this).select2('val'));
            });
            $('#go_two').on('select2:select', function (e) {
                $('input[name="grid_two_text"]').val($(this).select2('val'));
            });
            $('#go_three').on('select2:select', function (e) {
                $('input[name="grid_three_text"]').val($(this).select2('val'));
            });
            $('#go_one').on('select2:unselect', function (e) {
                $('input[name="grid_one_text"]').val($(this).select2('val'));
            });
            $('#go_two').on('select2:unselect', function (e) {
                $('input[name="grid_two_text"]').val($(this).select2('val'));
            });
            $('#go_three').on('select2:unselect', function (e) {
                $('input[name="grid_three_text"]').val($(this).select2('val'));
            });

            $('#list_title').on('select2:select', function (e) {
                $('input[name="list_title"]').val($(this).select2('val'));
            });
            $('#list_info_one').on('select2:select', function (e) {
                $('input[name="list_info_one"]').val($(this).select2('val'));
            });
            $('#list_info_two').on('select2:select', function (e) {
                $('input[name="list_info_two"]').val($(this).select2('val'));
            });
            $('#list_info_three').on('select2:select', function (e) {
                $('input[name="list_info_three"]').val($(this).select2('val'));
            });
            $('#list_info_four').on('select2:select', function (e) {
                $('input[name="list_info_four"]').val($(this).select2('val'));
            });
            $('#list_title').on('select2:unselect', function (e) {
                $('input[name="list_title"]').val($(this).select2('val'));
            });
            $('#list_info_one').on('select2:unselect', function (e) {
                $('input[name="list_info_one"]').val($(this).select2('val'));
            });
            $('#list_info_two').on('select2:unselect', function (e) {
                $('input[name="list_info_two"]').val($(this).select2('val'));
            });
            $('#list_info_three').on('select2:unselect', function (e) {
                $('input[name="list_info_three"]').val($(this).select2('val'));
            });
            $('#list_info_four').on('select2:unselect', function (e) {
                $('input[name="list_info_four"]').val($(this).select2('val'));
            });

            $('#ld_title').on('select2:select', function (e) {
                $('input[name="listing_details_title"]').val($(this).select2('val'));
            });
            $('#ld_subtitle').on('select2:select', function (e) {
                $('input[name="listing_details_subtitle"]').val($(this).select2('val'));
            });
            $('#ld_info').on('select2:select', function (e) {
                $('input[name="listing_details_info"]').val($(this).select2('val'));
            });
            $('#ld_title').on('select2:unselect', function (e) {
                $('input[name="listing_details_title"]').val($(this).select2('val'));
            });
            $('#ld_subtitle').on('select2:unselect', function (e) {
                $('input[name="listing_details_subtitle"]').val($(this).select2('val'));
            });
            $('#ld_info').on('select2:unselect', function (e) {
                $('input[name="listing_details_info"]').val($(this).select2('val'));
            });

            $('#go_one').val( <?php echo json_encode(explode(',', $custom_go_one )); ?>).trigger("change");
            $('#go_two').val( <?php echo json_encode(explode(',', $custom_go_two )); ?>).trigger("change");
            $('#go_three').val(<?php echo json_encode(explode(',', $custom_go_three )); ?>).trigger("change");

            $('#list_title').val(<?php echo json_encode(explode(',', $listTitle)); ?>).trigger("change");
            $('#list_info_one').val(<?php echo json_encode(explode(',', $listInfoOne)); ?>).trigger("change");
            $('#list_info_two').val(<?php echo json_encode(explode(',', $listInfoTwo)); ?>).trigger("change");
            $('#list_info_three').val(<?php echo json_encode(explode(',', $listInfoThree)); ?>).trigger("change");
            $('#list_info_four').val(<?php echo json_encode(explode(',', $listInfoFour)); ?>).trigger("change");

            $('#ld_title').val(<?php echo json_encode(explode(',', $ldTitle)); ?>).trigger("change");
            $('#ld_subtitle').val(<?php echo json_encode(explode(',', $ldSubTitle)); ?>).trigger("change");
            $('#ld_info').val(<?php echo json_encode(explode(',', $ldInfo)); ?>).trigger("change");
        });
    })(jQuery);
</script>
