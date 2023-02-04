<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="container">
    <div class="archive-listing-page">
        <div class="archive-listing-page_row">

            <div class="archive-listing-page_side">
                <?php stm_listings_load_template('filter/sidebar'); ?>
            </div>

            <div class="archive-listing-page_content">
                <?php stm_listings_load_template('filter/actions'); ?>

                <div id="listings-result">
                    <?php stm_listings_load_results(); ?>
                </div>

            </div>
        </div>
    </div>
</div>