<?php
	if(empty($options_list)) $options_list = [];
	if(empty($options)) $options = [];
	if(empty($listing)) $listing = [
		'label' => 'UNDEFINED',
		'slug' => 'undefined'
	];
?>

<?php if(defined('STM_LISTINGS_URL')): // if STM Vehicle Listings plugin active ?>

	<div class="stm_vehicles_listing_categories stm_<?php echo $listing['slug'] ?>_listing_categories">
		<div class="image-preview">
			<div class="overlay"></div>
		</div>
		<div class="stm_start">
			<?php esc_html_e("{$listing['label']} listing Settings", 'stm_vehicles_listing'); ?>
		</div>
		<?php if(!empty($_GET['after_patch'])) : ?>
			<div class="stm-admin-message" style="padding: 20px; margin-bottom: 20px; clear: both;">
				<p style="font-size: 20px;">NOTE!!!</p>
				Listing Categories list has been recreated, but you have to set Options manually such:<br />
				<ul style="margin: 0 0 20px 0; padding: 0; line-height: 20px;">
					<li style="margin: 0; padding: 0;">- Icon;</li>
					<li style="margin: 0; padding: 0;">- Use on Inventory or Single pages;</li>
					<li style="margin: 0; padding: 0;">- Numeric field;</li>
					<li style="margin: 0; padding: 0;">- Display options; etc.</li>
				</ul>

				You can follow this setup manual -
				<a
					href="https://support.stylemixthemes.com/manuals/motors/#inventory_categories"
					style="background: #fff; padding: 3px;">
					https://support.stylemixthemes.com/manuals/motors/#inventory_categories
				</a><br />

				So sorry for inconveniences!<br />
			</div>
		<?php endif; ?>
		<div class="stm_import_export">
			<div class="export_settings">

			</div>
		</div>

		<div class="stm_vehicles_listing_content">

			<table class="wp-list-table widefat listing_categories <?php echo $listing['slug'] ?>_categories_edit">
				<thead>
					<tr>
						<th><?php esc_html_e('Singular', 'stm_vehicles_listing'); ?></th>
						<th><?php esc_html_e('Plural', 'stm_vehicles_listing'); ?></th>
						<th><?php esc_html_e('Slug', 'stm_vehicles_listing'); ?></th>
						<th><?php esc_html_e('Numeric', 'stm_vehicles_listing'); ?></th>
						<th><?php esc_html_e('Manage', 'stm_vehicles_listing'); ?></th>
						<th><?php esc_html_e('Edit', 'stm_vehicles_listing'); ?></th>
					</tr>
				</thead>
				<tbody class="<?php echo $listing['slug'] ?>" id="<?php echo $listing['slug'] ?>ID">
					<?php if(!empty($options)): ?>
						<?php foreach($options as $option_key => $option): ?>
							<tr class="stm_listings_settings_head" data-tr="<?php echo esc_attr($option_key) ?>">
								<td class="highlited">
									<?php printf(
										__('%s', 'stm_vehicles_listing'),
										esc_html($option['single_name'])); ?>
								</td>
								<td>
									<?php printf(
										__('%s', 'stm_vehicles_listing'),
										esc_html($option['plural_name'])); ?>
								</td>
								<td>
									<?php printf(
										__('%s', 'stm_vehicles_listing'),
										esc_html($option['slug'])); ?>
								</td>
								<td>
									<?php $option['numeric']
										? esc_html_e('Yes', 'stm_vehicles_listing')
										: esc_html_e('No', 'stm_vehicles_listing'); ?>
								</td>
								<td class="manage">
									<i
										class="fas fa-list-ul"
										data-url="<?php
										echo get_site_url() . "/wp-admin/edit-tags.php?taxonomy=" .
											esc_attr($option['slug']) . "&post_type={$listing['slug']}"; ?>">

									</i>
								</td>
								<td><i class="fas fa-pencil-alt"></i></td>
							</tr>
							<tr class="stm_listings_settings_tr" data-tr="<?php echo esc_attr($option_key); ?>">
								<td colspan="7">
									<form action="" method="post">
										<div class="stm_vehicles_listing_option_meta">
											<div class="stm_vehicles_listing_row_options">
												<div class="stm_listings_col_4">
													<div class="inner">
														<input name="stm_vehicle_listing_row_position" type="hidden" value="<?php echo esc_attr($option_key); ?>" />
														<?php foreach($options_list as $option_name => $option_settings): ?>
															<?php if($option_name == 'listing_taxonomy_parent'): ?>
																<?php $option_settings['choices'] = CategoriesMultiListing::stm_get_taxes_by_listing($listing) ?>
															<?php endif ?>

															<?php stm_vehicles_listings_show_field($option_name, $option_settings, $option); ?>

														<?php endforeach; ?>
													</div>
												</div>
											</div>
											<div class="stm_vehicles_listing_row_actions">
												<a
												href="#save_<?php echo $listing['slug'] ?>"
												class="button button-primary button-large">
													<?php esc_html_e('Save', 'stm_vehicles_listing'); ?>
												</a>
												<div class="stm_response_message"></div>

												<a
												href="#cancel"
												class="button button-secondary button-large">
													<?php esc_html_e('Cancel', 'stm_vehicles_listing'); ?>
												</a>
												<a
												href="#delete_<?php echo $listing['slug'] ?>"
												style="float: right;"
												class="button button-secondary button-large">
													<i class="fas fa-trash"></i>
													<?php esc_html_e('Delete', 'stm_vehicles_listing'); ?>
												</a>
											</div>
										</div>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>

			<div class="stm_vehicles_add_new">
				<div class="stm_vehicles_listings_add_new_row">
					<i class="fa fa-plus"></i><?php esc_html_e('Add new', 'stm_vehicles_listing'); ?>
				</div>
				<table class="wp-list-table widefat listing_categories listing_categories_add_new">
					<tbody>
						<tr class="stm_listings_settings_tr">
							<td colspan="7">
								<form action="" method="post">
									<div class="stm_vehicles_listing_option_meta">
										<div class="stm_vehicles_listing_row_options">
											<div class="stm_listings_col_4">
												<div class="inner">
													<?php foreach($options_list as $option_name => $option_settings): ?>
														<?php if($option_name == 'listing_taxonomy_parent'): ?>
															<?php $option_settings['choices'] = CategoriesMultiListing::stm_get_taxes_by_listing($listing) ?>
														<?php endif ?>

														<?php stm_vehicles_listings_show_field($option_name, $option_settings, array()); ?>

													<?php endforeach; ?>
												</div>
											</div>
										</div>
										<div class="stm_vehicles_listing_row_actions">
											<a
												href="#add_new_<?php echo $listing['slug'] ?>"
												class="button button-primary button-large">
												<?php esc_html_e('Save', 'stm_vehicles_listing'); ?>
											</a>
											<div class="stm_response_message"></div>

											<a
												href="#delete"
												class="button button-secondary button-large">
												<i class="fas fa-trash"></i>
												<?php esc_html_e('Delete', 'stm_vehicles_listing'); ?>
											</a>
										</div>
									</div>
								</form>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<?php
		if( function_exists( 'stm_vehicles_listing_get_icons_html' ) ) {
			stm_vehicles_listing_get_icons_html();
		}
	?>

<?php else: ?>

	<div class="notice notice-warning" style="margin-top:30px;">
        <p><?php _e( 'Motors Listing Types plugin requires Motors – Car Dealer, Classifieds & Listing plugin installed and activated.', 'motors_listing_types' ); ?></p>
    </div>

<?php endif; ?>