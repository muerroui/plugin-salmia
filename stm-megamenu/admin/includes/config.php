<?php
add_filter('stm_nav_menu_item_additional_fields', 'mytheme_menu_item_additional_fields');
function mytheme_menu_item_additional_fields($fields)
{

    $fields['stm_mega'] = array(
        'name' => 'stm_mega',
        'label' => __('Megamenu type', 'stm-megamenu'),
        'wrap' => 'stm_visible_lvl_0',
        'container_class' => 'stm_mega stm_megamenu_select',
        'input_type' => 'select',
        'options' => array(
            'disabled' => __('Disabled', 'stm-megamenu'),
            'boxed' => __('Boxed', 'stm-megamenu'),
            //'wide' => __('Wide', 'stm-megamenu'),
        )
    );

    $fields['stm_menu_logo'] = array(
        'name' => 'stm_menu_logo',
        'label' => __('Megamenu use logo', 'stm-megamenu'),
        'wrap' => 'stm_visible_lvl_0',
        'container_class' => 'stm_menu_logo',
        'input_type' => 'stm_mega_logo',
    );

    $fields['stm_menu_icon'] = array(
        'name' => 'stm_menu_icon',
        'label' => __('Megamenu icon', 'stm-megamenu'),
        'wrap' => 'stm_visible_lvl_1 stm_visible_lvl_2',
        'container_class' => 'stm_mega_icon',
        'input_type' => 'text',
    );

	$fields['stm_child_menu_use_post'] = array(
		'name' => 'stm_child_menu_use_post',
		'label' => __('SubMenu Use PostId', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_0',
		'container_class' => 'stm_child_menu_use_post',
		'input_type' => 'checkbox',
	);

	$fields['stm_menu_as_title'] = array(
		'name' => 'stm_menu_as_title',
		'label' => __('Menu As Title', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_1',
		'container_class' => 'stm_menu_as_title',
		'input_type' => 'checkbox',
	);

	$fields['stm_menu_show_special_offer'] = array(
		'name' => 'stm_menu_show_special_offer',
		'label' => __('Show Special Offers', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_1',
		'container_class' => 'stm_menu_show_special_offer',
		'input_type' => 'checkbox',
	);

    $fields['stm_menu_image'] = array(
        'name' => 'stm_menu_image',
        'label' => __('Megamenu image', 'stm-megamenu'),
        'new' => __('Add image', 'stm-megamenu'),
        'delete' => __('Remove image', 'stm-megamenu'),
        'replace' => __('Replace image', 'stm-megamenu'),
        'wrap' => 'stm_visible_lvl_1 stm_visible_lvl_2',
        'container_class' => 'stm_mega_image',
        'input_type' => 'image',
    );

    $fields['stm_mega_textarea'] = array(
        'name' => 'stm_mega_textarea',
        'label' => __('Megamenu textarea', 'stm-megamenu'),
        'wrap' => 'stm_visible_lvl_2',
        'container_class' => 'stm_mega_textarea',
        'input_type' => 'textarea',
    );

    $fields['stm_menu_bg'] = array(
        'name' => 'stm_menu_bg',
        'label' => __('Megamenu background', 'stm-megamenu'),
        'new' => __('Add image', 'stm-megamenu'),
        'delete' => __('Remove image', 'stm-megamenu'),
        'replace' => __('Replace image', 'stm-megamenu'),
        'wrap' => 'stm_visible_lvl_0',
        'container_class' => 'stm_menu_bg',
        'input_type' => 'image',
    );

	$fields['stm_mega_text_repeater'] = array(
		'name' => 'stm_mega_text_repeater',
		'label' => __('Megamenu text repeater', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_1',
		'container_class' => 'stm_mega_text_repeater',
		'input_type' => 'repeater',
	);

	/*$fields['stm_mega_separator'] = array (
		'name' => 'stm_mega_separator',
		'label' => __(' Post MegaMenu ', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_1',
		'container_class' => 'stm_mega_separator',
		'input_type' => 'separator',
	);

	$fields['stm_mega_use_post'] = array (
		'name' => 'stm_mega_use_post',
		'label' => __('Use MegaMenu Post Id', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_1',
		'container_class' => 'stm_mega_use_post',
		'input_type' => 'text',
	);

	$fields['stm_mega_tips'] = array (
		'name' => 'stm_mega_tips',
		'label' => __('If you choose to load a mega menu or a page, please do not add submenus to this item. The mega menu and mega page menu have to be the top most menu item.', 'stm-megamenu'),
		'wrap' => 'stm_visible_lvl_10',
		'container_class' => 'stm_mega_tips',
		'input_type' => 'tips',
	);*/

    return $fields;
}

if ( ! function_exists( 'stm_mm_get_menu_data' ) ) {
	function stm_mm_get_menu_data() {
		// Get event details
		$json           = array();
		$json['errors'] = array();

		$_POST['postId'] = filter_var( $_POST['postId'], FILTER_VALIDATE_INT );

		if ( empty( $_POST['postId'] ) ) {
			return false;
		}

		$menuIconData = get_post_meta($_POST['postId'], '_menu_item_stm_menu_icon_repeater');
		$menuTextData = get_post_meta($_POST['postId'], '_menu_item_stm_menu_text_repeater');

		$data = array('icons' => json_decode($menuIconData[0]), 'text' => json_decode($menuTextData[0]));

		echo json_encode( $data );
		exit;
	}
}

add_action( 'wp_ajax_stm_mm_get_menu_data', 'stm_mm_get_menu_data' );

add_action('admin_footer', 'setTemplateRepeater');
function setTemplateRepeater() {
	echo '<script id="repItem" type="text/template">
			<div class="mega-repeater-view">
				<input type="text" id="<%= icoId %>" class="widefat code edit-menu-item-stm_menu_icon_repeater" name="<%= icoName %>" value="<%= icoValue %>">
				<input type="text" id="<%= textId %>" class="widefat code edit-menu-item-stm_menu_text_repeater" name="<%= textName %>" value="<%= textValue %>">
				<div class="edit-menu-repeater-controls">
					<i class="fas fa-plus-square mm-plus" aria-hidden="true" data-id="<%= plusPosition %>"></i>
					<i class="fas fa-minus-square mm-minus" aria-hidden="true" data-id="<%= minusPosition %>"></i>
				</div>
			</div>				
		</script>';
}
