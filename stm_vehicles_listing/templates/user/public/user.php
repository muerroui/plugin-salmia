<?php
$user_page = get_queried_object();
$user_id = $user_page->data->ID;
$user_image = get_the_author_meta('stm_user_avatar', $user_id);
$image = '';
$user_show_mail = '';
$user_show_mail = get_the_author_meta('stm_show_email', $user_id);
$user_phone = get_the_author_meta('stm_phone', $user_id);

if(!empty($user_image)) {
	$image = $user_image;
}

$posts_per_page = get_option( 'posts_per_page' );
$page = ( !empty($_GET['page']) ) ? intval($_GET['page']) : 1;
$offset = $posts_per_page * ($page - 1);

$query = stm_user_listings_query($user_id, 'publish', $posts_per_page, false, $offset);
?>

<div class="container stm-user-public-profile">
	<div class="row">
		<div class="col-md-9">
			<div class="clearfix stm-user-public-profile-top">
				<div class="stm-user-name">
					<div class="image">
						<?php if(!empty($image)): ?>
							<img src="<?php echo esc_url($image) ?>" />
						<?php else: ?>
							<i class="stm-service-icon-user"></i>
						<?php endif; ?>
					</div>
					<div class="title">
						<h4><?php echo esc_attr(stm_display_user_name($user_page->ID)); ?></h4>
						<div class="stm-title-desc"><?php esc_html_e('Private Seller', 'stm_vehicles_listing'); ?></div>
					</div>
				</div>
				<div class="stm-user-data-right">
					<?php if(!empty($user_page->data->user_email) and $user_show_mail=='show'): ?>
						<div class="stm-user-email">
							<i class="fas fa-envelope-open"></i>
							<div class="mail-label"><?php esc_html_e('Seller email', 'stm_vehicles_listing'); ?></div>
							<a href="mailto:<?php echo esc_attr($user_page->data->user_email); ?>" class="mail h4"><?php echo esc_attr($user_page->data->user_email); ?></a>
						</div>
					<?php endif; ?>

					<?php if(!empty($user_phone)): ?>
						<div class="stm-user-phone">
							<i class="fas fa-phone"></i>
							<div class="phone h3"><?php echo esc_attr($user_phone); ?></div>
							<div class="phone-label"><?php esc_html_e('Seller phone', 'stm_vehicles_listing'); ?></div>
						</div>
					<?php endif; ?>

				</div>
			</div> <!-- top profile -->

			<div class="stm-user-public-listing">
				<?php if($query->have_posts()): ?>
					<h3 class="stm-seller-title"><?php esc_html_e('Sellers Inventory', 'stm_vehicles_listing'); ?></h3>
					<div class="archive-listing-page">
						<?php while($query->have_posts()): $query->the_post(); ?>
							<?php stm_listings_load_template('listing-list'); ?>
						<?php endwhile; ?>
					</div>
					<?php

						echo paginate_links( array(
							'type'           => 'list',
							'format'         => '?page=%#%',
							'current'        => $page,
							'total'          => $query->max_num_pages,
							'posts_per_page' => $posts_per_page,
							'prev_text'      => '<i class="fas fa-angle-left"></i>',
							'next_text'      => '<i class="fas fa-angle-right"></i>',
						) );
					?>
				<?php else: ?>
					<h4 class="stm-seller-title" style="color:#aaa;"><?php esc_html_e('No Inventory added yet.', 'stm_vehicles_listing'); ?></h4>
				<?php endif; ?>
			</div>

		</div>

		<div class="col-md-3">
			<?php stm_listings_load_template('user/public/sidebar', array( 'user_id' => $user_id)); ?>
		</div>
	</div>
</div>