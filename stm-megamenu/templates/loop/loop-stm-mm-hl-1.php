<?php
$cat = get_the_category(get_the_ID());
?>
<div class="stm-mm-post-wrap">
    <div class="stm-mm-post">
        <a href="<?php the_permalink() ?>">
            <div class="img"><?php the_post_thumbnail('medium') ?></div>
        </a>
        <?php if(stm_mm_layout_name() != 'personal') : ?>
        <div class="meta-wrap">
            <ul class="meta">
                <li class="category normal-font primary-color"><a href="<?php echo esc_url(get_category_link($cat[0]->term_id)); ?>"><?php echo esc_html($cat[0]->name); ?></a></li>
                <li><?php echo get_the_date(); ?></li>
            </ul>
        </div>
        <a href="<?php the_permalink() ?>">
            <h4 class="normal-font"><?php the_title() ?></h4>
        </a>
        <?php else: ?>
        <div class="meta-wrap">
            <div class="category">
                <a href="<?php echo esc_url(get_category_link($cat[0]->term_id)); ?>"><?php echo stmt_gutenmag_print_lmth($cat[0]->name); ?></a>
            </div>
            <div class="meta-middle">
                <h3 class="heading-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </div>
            <div class="meta-bottom">
                <ul class="meta">
                    <li><?php echo get_the_date(); ?></li>
                </ul>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>