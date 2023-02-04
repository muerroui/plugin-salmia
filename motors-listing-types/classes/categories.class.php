<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class CategoriesMultiListing extends STMMultiListing {
	public function __construct() {
		parent::__construct();
		$this->register_categories();
	}

	public function register_categories() {
		if(is_array($this->listings) && count($this->listings)) {
			foreach ($this->listings as $key => $listing) {
				if (empty($listing['slug']) || empty($listing['label'])) {
					continue;
				}
				
				if (post_type_exists($listing['slug'])) {
					continue;
				}

				$this->stm_create_post_type_category($listing);

				add_action("admin_menu", function () use ($listing) {
					$options = get_option("stm_{$listing['slug']}_options", []);
					$options_list = [];
					if (function_exists('stm_listings_page_options')) {
						$options_list = stm_listings_page_options();
					}
					
					add_submenu_page(
						"edit.php?post_type={$listing['slug']}",
						__("{$listing['label']} Categories", 'stm_vehicles_listing'),
						__("{$listing['label']} Categories", 'stm_vehicles_listing'),
						'manage_options',
						"{$listing['slug']}_categories",
						function () use ($listing, $options, $options_list) {
							extract([
								'listing' => $listing,
								'options' => $options,
								'options_list' => $options_list
							]);

							
							if ( stm_is_listing_five() ) {
								unset( $options_list['use_in_footer_search'] );
							}
							
							include_once(MULTILISTING_PATH . '/templates/listing-category.php');
						}
					);
				});

				add_action("wp_ajax_stm_{$listing['slug']}_add_new_option",
					function () use ($listing) {
						if (!function_exists('stm_listings_reserved_terms')) {
							return;
						}
						$data = [
							'error' => false,
							'message' => ''
						];

						$options = get_option("stm_{$listing['slug']}_options", []);

						/*Get reserved terms*/
						$reserved_terms = stm_listings_reserved_terms();

						$new_option = $_POST;

						if (empty($new_option['slug']) and !empty($new_option['single_name'])) {
							$new_option['slug'] = sanitize_title($new_option['single_name']);
						}

						if (empty($new_option['single_name']) or empty($new_option['plural_name'])
							or empty($new_option['slug'])) {
							$data['error'] = true;
							$data['message'] = esc_html__(
								'Singular, Plural names and Slug are required',
								'stm_vehicles_listing');
						}
						else {
							$new_option['slug'] = sanitize_title($new_option['slug']);

							if (in_array($new_option['slug'], $reserved_terms)
								or taxonomy_exists($new_option['slug'])) {
								$data['error'] = true;
								$data['message'] = esc_html__(
									'Slug name is already in use. Please choose another slug name.',
									'stm_vehicles_listing');
							}
						}

						if (!$data['error']) {
							$settings = stm_listings_page_options();

							foreach ($settings as $setting_name => $setting) {
								if (!empty($new_option[$setting_name])) {
									$current_option[$setting_name] =
										sanitize_text_field($new_option[$setting_name]);
								}
								else {
									if (strpos($setting_name, 'divider') === false) {
										$current_option[$setting_name] = '';
									}
								}
							}

							if (empty($current_option['listing_rows_numbers_enable'])) {
								$current_option['enable_checkbox_button'] =
								$current_option['listing_rows_numbers'] = '';
							}

							$numeric =
								($new_option['numeric']) ? esc_html__('Yes') : esc_html__('No');
							$link = get_site_url() .
								"/wp-admin/edit-tags.php?taxonomy=" .
								esc_attr($new_option['slug']) .
								"&post_type={$listing['slug']}";

							$options[] = $current_option;

							$data['option'] = [
								'key' => max(array_keys($options)),
								'name' => $new_option['single_name'],
								'plural' => $new_option['plural_name'],
								'slug' => $new_option['slug'],
								'numeric' => $numeric,
								'link' => $link
							];

							$this->stm_save_post_type_options($listing, $options);
						}

						wp_send_json($data);
						exit;
					});

				add_action("wp_ajax_stm_{$listing['slug']}_save_option_row",
					function () use ($listing) {
						$data = [
							'error' => false,
							'message' => ''
						];

						$options = get_option("stm_{$listing['slug']}_options", []);

						/*Check number of setting*/
						if (!isset($_POST['stm_vehicle_listing_row_position'])) {
							$data['error'] = true;
							$data['message'] =
								esc_html__('Some error occurred 1', 'stm_vehicles_listing');
						}
						else {
							$option_key = intval($_POST['stm_vehicle_listing_row_position']);
						}

						/*Check if setting exists*/
						if (empty($options[$option_key])) {
							$data['error'] = true;
							$data['message'] =
								esc_html__('Some error occurred 2', 'stm_vehicles_listing');
						}
						else {
							$current_option = $options[$option_key];
						}

						/*Check POST*/
						if (empty($_POST)) {
							$data['error'] = true;
							$data['message'] =
								esc_html__('Some error occurred 3', 'stm_vehicles_listing');
						}
						else {
							$user_choice = $_POST;
						}

						if (!$data['error']) {
							$settings = stm_listings_page_options();

							foreach ($settings as $setting_name => $setting) {
								if (strpos($setting_name, 'divider') === false) {
									if (!empty($user_choice[$setting_name])) {
										$current_option[$setting_name] =
											($setting_name == 'number_field_affix')
												? esc_html($user_choice[$setting_name])
												: sanitize_text_field($user_choice[$setting_name]);
									}
									else {
										$current_option[$setting_name] = '';
									}
								}
							}

							if (empty($current_option['listing_rows_numbers_enable'])) {
								$current_option['enable_checkbox_button'] =
								$current_option['listing_rows_numbers'] = '';
							}

							$options[$option_key] = $current_option;

							$this->stm_save_post_type_options($listing, $options);

							$data['error'] = false;
							$data['message'] = esc_html__('Settings saved', 'stm_vehicles_listing');
							$data['data'] = $current_option;
						}

						wp_send_json($data);
						exit;
					});

				add_action("wp_ajax_stm_{$listing['slug']}_delete_option_row",
					function () use ($listing) {
						if (isset($_POST['number'])) {
							$options = get_option("stm_{$listing['slug']}_options", []);
							$option_key = intval($_POST['number']);
							if (!empty($options[$option_key])) {
								unset($options[$option_key]);
								$this->stm_save_post_type_options($listing, $options);
							}
						}
						wp_send_json_success();
					});

				add_action("wp_ajax_stm_{$listing['slug']}_save_option_order",
					function () use ($listing) {
						if (isset($_POST['order'])) {
							$options = get_option("stm_{$listing['slug']}_options", []);
							$new_options = explode(',', sanitize_text_field($_POST['order']));
							$new_order = array();
							foreach ($new_options as $option) {
								if (!empty($options[$option])) {
									$new_order[] = $options[$option];
								}
							}
							$this->stm_save_post_type_options($listing, $new_order);
						}
						wp_send_json_success();
					});

				$stm_get_car_parent_exist = $this->stm_get_car_parent_exists($listing);

				if (!empty($stm_get_car_parent_exist)) {
					foreach ($stm_get_car_parent_exist as $stm_get_car_parent_exist_single) {
						/** Add Custom Field To Form */
						add_action(	$stm_get_car_parent_exist_single['slug'] . '_add_form_fields',
							function ($taxonomy) use ($listing) {
								set_query_var('listings_type', $listing['slug']);
								HooksMultiListing::stm_listings_attributes_filter($listing);
								$taxonomy = stm_get_all_by_slug($taxonomy);
								$taxonomy_parent_slug = $taxonomy['listing_taxonomy_parent'];
								$parents = stm_get_category_by_slug_all($taxonomy_parent_slug, true);
								?>
								<div class="form-field">
									<label for="stm_parent_taxonomy">
										<?php esc_html_e('Choose parent taxonomy'); ?>
									</label>
									<select multiple name="stm_parent_taxonomy[]" size="10">
										<option value=""><?php esc_html_e('No parent', 'motors_listing_types'); ?></option>
										<?php if (!empty($parents)): ?>
											<?php foreach ($parents as $term): ?>
												<option value="<?php echo esc_attr($term->slug) ?>">
													<?php
													echo apply_filters(
														'stm_parent_taxonomy_option',
														$term->name,
														$term, $taxonomy
													); ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
								<?php
							}, 10);

						add_action(	$stm_get_car_parent_exist_single['slug'] . '_edit_form_fields',
							function ($tag, $taxonomy) use ($listing) {
								set_query_var('listings_type', $listing['slug']);
								HooksMultiListing::stm_listings_attributes_filter($listing);
								$values = get_term_meta( $tag->term_id, 'stm_parent' );
								$taxonomy = stm_get_all_by_slug($taxonomy);
								$taxonomy_parent_slug = $taxonomy['listing_taxonomy_parent'];
								$parents = stm_get_category_by_slug_all($taxonomy_parent_slug, true);
								?>
								<tr class="form-field">
									<th scope="row" valign="top"><label
											for="stm_parent_taxonomy"><?php esc_html_e('Parent category'); ?></label>
									</th>
									<td>
										<select multiple name="stm_parent_taxonomy[]" size="10">
											<option value=""><?php esc_html_e('No parent', 'motors_listing_types'); ?></option>
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
							}, 10, 2);
						/** Save Custom Field Of Form */
						add_action(
							'created_' . $stm_get_car_parent_exist_single['slug'],
							'stm_taxonomy_listing_parent_save', 10, 2);
						add_action(
							'edited_' . $stm_get_car_parent_exist_single['slug'],
							'stm_taxonomy_listing_parent_save', 10, 2);
					}
				}
			}
		}
	}


	protected function stm_get_car_parent_exists($listing){
		$car_listing = array();
		$options = get_option("stm_{$listing['slug']}_options", []);
		if (!empty($options)) {
			foreach ($options as $key => $option) {
				if (!empty($options[$key]['listing_taxonomy_parent'])) {
					$car_listing[] = $option;
				}
			}
		}

		return $car_listing;
	}

	protected function stm_create_post_type_category($listing) {
		$options = get_option("stm_{$listing['slug']}_options");
		if (!empty($options)) {
			foreach ($options as $option) {
				$show_admin_table = false;
				if (!empty($option['show_in_admin_column']) and $option['show_in_admin_column']) {
					$show_admin_table = true;
				}

				if (empty($option['numeric'])) {
					$numeric = true;
				} else {
					$numeric = false;
					$show_admin_table = false;
				}

				register_taxonomy(
					$option['slug'],
					$listing['slug'],
					array(
						'labels' => array(
							'name' => $option['plural_name'],
							'singular_name' => $option['single_name'],
							'search_items' => __('Search ' . $option['plural_name']),
							'popular_items' => __('Popular ' . $option['plural_name']),
							'all_items' => __('All ' . $option['plural_name']),
							'parent_item' => null,
							'parent_item_colon' => null,
							'edit_item' => __('Edit ' . $option['single_name']),
							'update_item' => __('Update ' . $option['single_name']),
							'add_new_item' => __('Add New ' . $option['single_name']),
							'new_item_name' => __('New ' . $option['single_name'] . ' Name'),
							'separate_items_with_commas' => __('Separate ' . $option['plural_name'] . ' with commas'),
							'add_or_remove_items' => __('Add or remove ' . $option['plural_name']),
							'choose_from_most_used' => __('Choose from the most used ' . $option['plural_name']),
							'not_found' => __('No ' . $option['plural_name'] . ' found.'),
							'menu_name' => __($option['plural_name']),
						),
						'public' => true,
						'hierarchical' => $numeric,
						'show_ui' => true,
						'show_in_menu' => false,
						'show_admin_column' => $show_admin_table,
						'show_in_nav_menus' => false,
						'show_in_quick_edit' => false,
						'query_var' => false,
						'rewrite' => false,
						'meta_box_cb' => false
					)
				);
			};
		}

		/*Register additional features hidden taxonomy*/
		// register_taxonomy(
		// 	'stm_additional_features',
		// 	$listing['slug'],
		// 	array(
		// 		'labels' => array(
		// 			'name' => esc_html__('Additional features', 'stm_vehicles_listing'),
		// 			'singular_name' => esc_html__('Additional features', 'stm_vehicles_listing'),
		// 			'search_items' => esc_html__('Search Additional features', 'stm_vehicles_listing'),
		// 			'popular_items' => esc_html__('Popular Additional features', 'stm_vehicles_listing'),
		// 			'all_items' => esc_html__('All  Additional features', 'stm_vehicles_listing'),
		// 			'parent_item' => null,
		// 			'parent_item_colon' => null,
		// 			'edit_item' => esc_html__('Edit Additional features', 'stm_vehicles_listing'),
		// 			'update_item' => esc_html__('Update Additional features', 'stm_vehicles_listing'),
		// 			'add_new_item' => esc_html__('Add New Additional features', 'stm_vehicles_listing'),
		// 			'new_item_name' => esc_html__('New Additional features', 'stm_vehicles_listing' . ' Name'),
		// 			'separate_items_with_commas' => esc_html__('Separate Additional features', 'stm_vehicles_listing'),
		// 			'add_or_remove_items' => esc_html__('Add or remove Additional features', 'stm_vehicles_listing'),
		// 			'choose_from_most_used' => esc_html__('Choose from the most used  Additional features', 'stm_vehicles_listing'),
		// 			'not_found' => esc_html__('No Additional features found', 'stm_vehicles_listing'),
		// 			'menu_name' => esc_html__('Additional features', 'stm_vehicles_listing'),
		// 		),
		// 		'public' => true,
		// 		'hierarchical' => false,
		// 		'show_ui' => true,
		// 		'show_in_menu' => false,
		// 		'show_admin_column' => false,
		// 		'show_in_nav_menus' => false,
		// 		'show_in_quick_edit' => false,
		// 		'query_var' => false,
		// 		'rewrite' => false,
		// 	)
		// );
	}

	public static function stm_get_taxes_by_listing($listing) {
		$options[] = __("No parent");
		if(!empty($listing['slug'])){
			$temp = get_object_taxonomies($listing['slug'], 'objects');
			if(!empty($temp)){
				foreach ($temp as $item) {
					$labels = $item->labels;
					$options[$item->name] = $labels->singular_name;
				}
			}
		}

		return $options;
	}

}

new CategoriesMultiListing;
