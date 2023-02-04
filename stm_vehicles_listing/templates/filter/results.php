<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (have_posts()): ?>
    <div class="stm-isotope-sorting">
        <?php while (have_posts()) : the_post();
            stm_listings_load_template('listing-list');
        endwhile; ?>
    </div>
<?php else: ?>
    <h3><?php esc_html_e('Sorry, No results', 'stm_vehicles_listing') ?></h3>
<?php endif; ?>

<?php stm_listings_load_pagination() ?>