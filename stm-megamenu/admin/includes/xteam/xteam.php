<?php
/**
 * Proof of concept for how to add new fields to nav_menu_item posts in the WordPress menu editor.
 * @author Weston Ruter (@westonruter), X-Team
 */
add_action('init', array('STM_Nav_Menu_Item_Custom_Fields', 'setup'));

class STM_Nav_Menu_Item_Custom_Fields
{
    static $options = array();

    static function view_control($field) {
        $type = $field['input_type'];

        $view = '<div class="stm_wrapper_{input_type}"><p class="additional-menu-field-{name} description description-thin {wrap}">';

        switch ($type) {
            case 'select':
                if(empty($field['options'])) break;
                $options = '';
                $selected = '';
                foreach($field['options'] as $key => $value) {
                    $selected = ($key == $field['value']) ? 'selected' : '';
                    $options .= '<option value="' . $key .'" '.$selected.'>'.$value.'</option>';
                }
                $view .= '<label for="edit-menu-item-{name}-{id}">
                        {label}<br>
                        <select 
                            id="edit-menu-item-{name}-{id}" 
                            class="widefat code edit-menu-item-{name}"
                            name="menu-item-{name}[{id}]">'
                            . $options .
                        '</select>
                    </label>';
                break;
            case 'textarea':
                $textarea = $field['value'];
                $view .= '<label for="edit-menu-item-{name}-{id}">
                    {label}<br>
                    <textarea
                        id="froala-editor edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]">'.$textarea.'</textarea>
                    </label>';
                break;
            case 'image':
                $image = '';
                $img = (!empty($field['value'])) ? 'has-image' : '';
                if(!empty($img)) {
                    $image = wp_get_attachment_image_src($field['value']);
                    if(!empty($image[0])) $image = '<img src="' . $image[0] . '" />';
                }

                $view .= '<label class="image ' . $img . '" for="edit-menu-item-{name}-{id}">
                        {label}<br>'.
                        $image
                        .'<input
                            type="hidden"
                            id="edit-menu-item-{name}-{id}"
                            class="widefat code edit-menu-item-{name}"
                            name="menu-item-{name}[{id}]"
                            value="{value}">
                        <a href="#" class="add_new">{new}</a>
                        <a href="#" class="replace">{replace}</a>
                        <a href="#" class="delete">{delete}</a>
                    </label>';
                break;
			case 'repeater':
				$view .= '<label for="edit-menu-item-{name}-{id}">
                    {label}<br>
                    <div id="app{id}" class="mega-repeater-box stm_visible_lvl_1 stm_visible_lvl_2" data-id="{id}"></div>
                </label>';
				break;
			case 'stm_mega_logo':
				$view .= '<label for="edit-menu-item-{name}-{id}">
                    <input type="checkbox" name="menu-item-{name}[{id}]" id="edit-menu-item-{name}-{id}" class="stm-mega-use-logo" value="checked" {checked}/>
                    {label}
                </label>';
				break;
			case 'checkbox':
				$view .= '<label for="edit-menu-item-{name}-{id}">
                    <input type="checkbox" name="menu-item-{name}[{id}]" id="edit-menu-item-{name}-{id}" class="stm-mega-checkbox" value="checked" {checked}/>
                    {label}
                </label>';
				break;
            case 'separator':
                $view .= '<h4 class="stm-separator stm_visible_lvl_1">
                    {label}
                </h4>';
                break;
            case 'tips':
                $view .= '<span class="stm_visible_lvl_0">
                    {label}
                </span>';
                break;
            default:
                $view .= '<label for="edit-menu-item-{name}-{id}">
                    {label}<br>
                    <input
                        type="{input_type}"
                        id="edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]"
                        value="{value}">
                </label>';
                break;
        }

        $view .= '</p></div>';

        return $view;
    }

    static function setup()
    {
        $new_fields = apply_filters('stm_nav_menu_item_additional_fields', array());
        if (empty($new_fields))
            return;
        self::$options['fields'] = self::get_fields_schema($new_fields);
        add_filter('wp_edit_nav_menu_walker', function () {
            return 'STM_Walker_Nav_Menu_Edit';
        });
        add_action('save_post', array(__CLASS__, '_save_post'), 10, 2);
    }

    static function get_fields_schema($new_fields)
    {
        $schema = array();
        foreach ($new_fields as $name => $field) {
            if (empty($field['name'])) {
                $field['name'] = $name;
            }
            $schema[] = $field;
        }
        return $schema;
    }

    static function get_menu_item_postmeta_key($name)
    {
        return '_menu_item_' . $name;
    }

    static function get_field($item, $depth, $args)
    {
        $new_fields = '';
        foreach (self::$options['fields'] as $field) {

        	if($field['name'] == 'stm_mega_text_repeater'){
				$field['valueIcon'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key('stm_menu_icon_repeater'), true);
				$field['idIcon'] = $item->ID;
			}

			$field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
        	if($field['name'] == 'stm_menu_logo' && get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true) == "checked") $field['checked'] = "checked";
        	if($field['name'] == 'stm_menu_show_special_offer' && get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true) == "checked") $field['checked'] = "checked";
        	if($field['name'] == 'stm_menu_as_title' && get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true) == "checked") $field['checked'] = "checked";
        	if($field['name'] == 'stm_child_menu_use_post' && get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true) == "checked") $field['checked'] = "checked";
        	$field['id'] = $item->ID;

            $view = self::view_control($field);
            if(!empty($field['options'])) unset($field['options']);

            $new_fields .= str_replace(
                array_map(function ($key) {
                    return '{' . $key . '}';
                }, array_keys($field)),
                array_values(array_map('esc_attr', $field)),
                $view
            );
        }

        return $new_fields;
    }

    static function _save_post($post_id, $post)
    {
        if ($post->post_type !== 'nav_menu_item') {
            return $post_id; // prevent weird things from happening
        }

        foreach (self::$options['fields'] as $field_schema) {

            $form_field_name = 'menu-item-' . $field_schema['name'];

            // @todo FALSE should always be used as the default $value, otherwise we wouldn't be able to clear checkboxes
			if ($form_field_name == 'menu-item-stm_mega_text_repeater' && isset($_POST["menu-item-stm_menu_icon_repeater"][$post_id])) {
				if($_POST["menu-item-stm_menu_icon_repeater"][$post_id][0] != "") {
					unset($_POST['menu-item-stm_menu_icon_repeater'][$post_id][""]);
					unset($_POST['menu-item-stm_mega_text_repeater'][$post_id][""]);

					$key = self::get_menu_item_postmeta_key('stm_menu_icon_repeater');
					$value = stripslashes(json_encode($_POST['menu-item-stm_menu_icon_repeater'][$post_id]));
					update_post_meta($post_id, $key, $value);

					$key = self::get_menu_item_postmeta_key('stm_menu_text_repeater');
					$value = stripslashes(json_encode($_POST['menu-item-stm_mega_text_repeater'][$post_id]));
					update_post_meta($post_id, $key, $value);
				} else {
					$key = self::get_menu_item_postmeta_key('stm_menu_icon_repeater');
					delete_post_meta($post_id, $key, '');

					$key = self::get_menu_item_postmeta_key('stm_menu_text_repeater');
					delete_post_meta($post_id, $key, '');
				}
			} else {
				if (isset($_POST[$form_field_name][$post_id])) {
				    $key = self::get_menu_item_postmeta_key($field_schema['name']);
					$value = stripslashes($_POST[$form_field_name][$post_id]);
					update_post_meta($post_id, $key, $value);
				} else {
                    $key = self::get_menu_item_postmeta_key($field_schema['name']);
                    $value = '';
                    update_post_meta($post_id, $key, $value);
                }
			}
        }
    }
}

// @todo This class needs to be in it's own file so we can include id J.I.T.
// requiring the nav-menu.php file on every page load is not so wise
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

class STM_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit
{
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $item_output = '';
        parent::start_el($item_output, $item, $depth, $args);
        if ($new_fields = STM_Nav_Menu_Item_Custom_Fields::get_field($item, $depth, $args)) {
            $item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output);
        }
        $output .= $item_output;
    }
}