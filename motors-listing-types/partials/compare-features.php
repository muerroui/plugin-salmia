<?php if ($compares->have_posts()): ?>
    <div class="row row-4 row-compare-features hidden-xs">
        <div class="col-md-3 col-sm-3">
            <h4 class="stm-compare-features"><?php esc_html_e('Additional features', 'motors_listing_types'); ?></h4>
        </div>
        <?php while ( $compares->have_posts() ): $compares->the_post(); ?>
            <?php $features = get_post_meta( get_the_ID(), 'additional_features', true ); ?>
            <div class="col-md-3 col-sm-3 compare-col-stm-<?php echo esc_attr( get_the_ID() ); ?>">
            <?php if ( !empty( $features ) ): ?>
                <?php $features = explode( ',', $features ); ?>
                <ul class="list-style-2">
                    <?php foreach ( $features as $feature ): ?>
                        <li><?php echo esc_attr( $feature ); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>