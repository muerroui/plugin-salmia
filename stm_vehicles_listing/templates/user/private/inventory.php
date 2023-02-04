<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$posts_per_page = get_option('posts_per_page');
$page = (!empty($_GET['page'])) ? intval($_GET['page']) : 1;
$offset = $posts_per_page * ($page - 1);

$query = stm_user_listings_query($user_id, 'any', $posts_per_page, false, $offset);

if ($query->have_posts()): ?>
    <div class="archive-listing-page">
        <h1><?php esc_html_e('My inventory', 'stm_vehicles_listing'); ?></h1>
        <?php while ($query->have_posts()): $query->the_post(); ?>
            <div class="stm_listing_edit_car <?php echo esc_attr(get_post_status(get_the_id())); ?>">
                <?php stm_listings_load_template('user/private/car-edit', array('id' => get_the_id(), 'user_id' => $user_id)); ?>
                <?php stm_listings_load_template('listing-list'); ?>
            </div>
        <?php endwhile; ?>

        <?php

        echo paginate_links(array(
            'type' => 'list',
            'format' => '?page=%#%',
            'current' => $page,
            'total' => $query->max_num_pages,
            'posts_per_page' => $posts_per_page,
            'prev_text' => '<i class="fas fa-angle-left"></i>',
            'next_text' => '<i class="fas fa-angle-right"></i>',
        ));
        ?>
    </div>
<?php else: ?>
    <h4 class="stm-seller-title"><?php esc_html_e('No Inventory yet', 'stm_vehicles_listing'); ?></h4>
<?php endif; ?>
