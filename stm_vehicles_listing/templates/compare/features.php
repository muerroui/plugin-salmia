<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!empty($compares)): ?>
    <?php if ($compares->have_posts()): ?>
        <div class="row row-4 row-compare-features hidden-xs">
            <div class="col-md-3 col-sm-3">
                <h4 class="stm-compare-features"><?php echo esc_attr('Additional features', 'stm_vehicles_listing'); ?></h4>
            </div>
            <?php while ($compares->have_posts()): $compares->the_post(); ?>
                <?php $features = get_post_meta(get_the_ID(), 'additional_features', true); ?>
                <?php if (!empty($features)): ?>
                    <div class="col-md-3 col-sm-3 compare-col-stm-<?php echo esc_attr(get_the_ID()); ?>">
                        <?php $features = explode(',', $features); ?>
                        <ul class="list-style-2">
                            <?php foreach ($features as $feature): ?>
                                <li><?php echo esc_attr($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>

        </div>
    <?php endif; ?>
<?php endif; ?>