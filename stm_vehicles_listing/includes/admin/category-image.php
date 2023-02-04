<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$stm_get_car_modern_filter_view_images = stm_get_car_modern_filter_view_images();

if (!empty($stm_get_car_modern_filter_view_images)) {
    foreach ($stm_get_car_modern_filter_view_images as $stm_get_car_modern_filter_view_image) {
        /** Add Custom Field To Form */
        add_action($stm_get_car_modern_filter_view_image['slug'] . '_add_form_fields', 'stm_taxonomy_listing_add_field', 10);
        add_action($stm_get_car_modern_filter_view_image['slug'] . '_edit_form_fields', 'stm_taxonomy_listing_edit_field', 10, 2);
        /** Save Custom Field Of Form */
        add_action('created_' . $stm_get_car_modern_filter_view_image['slug'], 'stm_taxonomy_listing_image_save', 10, 2);
        add_action('edited_' . $stm_get_car_modern_filter_view_image['slug'], 'stm_taxonomy_listing_image_save', 10, 2);
    }
}

/*Add field*/
if (!function_exists('stm_taxonomy_listing_add_field')) {
    function stm_taxonomy_listing_add_field($taxonomy)
    {
        $default_image = plugin_dir_url(__FILE__) . '../../assets/images/default_170x50.gif';
        ?>
        <div class="form-field">
            <label for="stm_taxonomy_listing_image"><?php esc_html_e('Category Image'); ?></label>
            <div class="stm-choose-listing-image">
                <input
                    type="hidden"
                    name="stm_taxonomy_listing_image"
                    id="stm_taxonomy_listing_image"
                    value=""
                    size="40"
                    aria-required="true"/>

                <img class="stm_taxonomy_listing_image_chosen" src="<?php echo esc_url($default_image); ?>"/>

                <input type="button" class="button-primary" value="Choose image"/>
            </div>
            <script type="text/javascript">
                jQuery(function ($) {
                    $(".stm-choose-listing-image .button-primary").click(function () {
                        var custom_uploader = wp.media({
                            title: "Select image",
                            button: {
                                text: "Attach"
                            },
                            multiple: false
                        }).on("select", function () {
                            var attachment = custom_uploader.state().get("selection").first().toJSON();
                            $('#stm_taxonomy_listing_image').val(attachment.id);
                            $('.stm_taxonomy_listing_image_chosen').attr('src', attachment.url);
                        }).open();
                    });
                });
            </script>
        </div>
    <?php }
}

/*Edit field*/
if (!function_exists('stm_taxonomy_listing_edit_field')) {
	function stm_taxonomy_listing_edit_field( $tag, $taxonomy ) {
	    $current_image = get_term_meta($tag->term_id, 'stm_image', true);
        $default_image_placeholder = plugin_dir_url(__FILE__) . '../../assets/images/default_170x50.gif';
        $default_image = plugin_dir_url(__FILE__) . '../../assets/images/default_170x50.gif';
        if (!empty($current_image)) {
            $default_image = wp_get_attachment_image_src($current_image, 'thumbnail');
            if (!empty($default_image[0])) {
                $default_image = $default_image[0];
            }
        }

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label
                    for="stm_taxonomy_listing_image"><?php esc_html_e('Category Image'); ?></label></th>
            <td>
                <div class="stm-choose-listing-image">
                    <input
                        type="hidden"
                        name="stm_taxonomy_listing_image"
                        id="stm_taxonomy_listing_image"
                        value="<?php echo esc_attr($current_image); ?>"
                        size="40"
                        aria-required="true"/>

                    <img class="stm_taxonomy_listing_image_chosen" src="<?php echo esc_url($default_image); ?>"/>

                    <input type="button" class="button-primary" value="Choose image"/>
                    <input type="button" class="button-primary-delete" value="Remove image"/>
                </div>
            </td>
            <script type="text/javascript">
                jQuery(function ($) {
                    $(".stm-choose-listing-image .button-primary").click(function () {
                        var custom_uploader = wp.media({
                            title: "Select image",
                            button: {
                                text: "Attach"
                            },
                            multiple: false
                        }).on("select", function () {
                            var attachment = custom_uploader.state().get("selection").first().toJSON();
                            $('#stm_taxonomy_listing_image').val(attachment.id);
                            $('.stm_taxonomy_listing_image_chosen').attr('src', attachment.url);
                        }).open();
                    });

                    $(".stm-choose-listing-image .button-primary-delete").click(function () {
                        $('#stm_taxonomy_listing_image').val('');
                        $('.stm_taxonomy_listing_image_chosen').attr('src', '<?php echo esc_url($default_image_placeholder); ?>');
                    })
                });
            </script>
        </tr>
    <?php }
}

/*Save value*/
if (!function_exists('stm_taxonomy_listing_image_save')) {
    function stm_taxonomy_listing_image_save($term_id, $tt_id)
    {
        if (isset($_POST['stm_taxonomy_listing_image'])) {
            update_term_meta($term_id, 'stm_image', $_POST['stm_taxonomy_listing_image']);
        }
    }
}

/*Parent tax*/
$stm_get_car_parent_exist = stm_get_car_parent_exist();

if (!empty($stm_get_car_parent_exist)) {
    foreach ($stm_get_car_parent_exist as $stm_get_car_parent_exist_single) {
        /** Add Custom Field To Form */
        add_action($stm_get_car_parent_exist_single['slug'] . '_add_form_fields', 'stm_taxonomy_listing_add_field_parent', 10);
        add_action($stm_get_car_parent_exist_single['slug'] . '_edit_form_fields', 'stm_taxonomy_listing_edit_field_parent', 10, 2);
        /** Save Custom Field Of Form */
        add_action('created_' . $stm_get_car_parent_exist_single['slug'], 'stm_taxonomy_listing_parent_save', 10, 2);
        add_action('edited_' . $stm_get_car_parent_exist_single['slug'], 'stm_taxonomy_listing_parent_save', 10, 2);
    }
}

/*Add field*/
if (!function_exists('stm_taxonomy_listing_add_field_parent')) {
    function stm_taxonomy_listing_add_field_parent($taxonomy)
    {
        $taxonomy = stm_get_all_by_slug($taxonomy);
        $taxonomy_parent_slug = $taxonomy['listing_taxonomy_parent'];
        $taxonomy_parent = stm_get_category_by_slug_all($taxonomy_parent_slug, true);
        ?>
        <div class="form-field">
            <label for="stm_parent_taxonomy"><?php esc_html_e('Choose parent taxonomy'); ?></label>
            <select multiple name="stm_parent_taxonomy[]" size="10">
                <option value=""><?php esc_html_e('No parent'); ?></option>
                <?php if (!empty($taxonomy_parent)): ?>
                    <?php foreach ($taxonomy_parent as $term): ?>
                        <option value="<?php echo esc_attr($term->slug) ?>">
                            <?php echo apply_filters('stm_parent_taxonomy_option', $term->name, $term, $taxonomy); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    <?php }
}

if (!function_exists('stm_taxonomy_listing_edit_field_parent')) {
    function stm_taxonomy_listing_edit_field_parent($tag, $taxonomy)
    {
        $values = get_term_meta( $tag->term_id, 'stm_parent' );
        $taxonomy = stm_get_all_by_slug($taxonomy);
	    $parents = stm_get_category_by_slug_all( $taxonomy['listing_taxonomy_parent'], true );
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label
                    for="stm_parent_taxonomy"><?php esc_html_e('Parent category'); ?></label>
            </th>
            <td>
                <select multiple name="stm_parent_taxonomy[]" size="10">
                    <option value=""><?php esc_html_e('No parent'); ?></option>
                    <?php if (!empty($parents)): ?>
                        <?php foreach ($parents as $term): ?>
                            <option value="<?php echo esc_attr( $term->slug ) ?>"
								<?php selected( in_array( $term->slug, $values ) ); ?>>
                                <?php echo apply_filters('stm_parent_taxonomy_option', $term->name, $term, $taxonomy); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        <?php
    }
}

if ( ! function_exists( 'stm_taxonomy_listing_parent_save' ) ) {
	function stm_taxonomy_listing_parent_save( $term_id, $tt_id ) {
		if ( array_key_exists( 'stm_parent_taxonomy', $_POST ) ) {
			delete_term_meta( $term_id, 'stm_parent' );
			foreach ( (array) $_POST['stm_parent_taxonomy'] as $slug ) {
				add_term_meta( $term_id, 'stm_parent', $slug );
			}
		}
	}
}