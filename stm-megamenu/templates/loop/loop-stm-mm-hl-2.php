<?php
$cat = get_the_category(get_the_ID());
?>
<div class="stm-mm-post-wrap">
    <div class="stm-mm-post">
        <a href="<?php the_permalink() ?>">
            <div class="img">
                <?php the_post_thumbnail('medium') ?>
                <?php
                if(get_post_format() == 'video') {
                    echo '<i style="display: none;" class="stm-gm-icon-ico_play_circle"></i>';
                }
                ?>
            </div>
        </a>
        <div class="meta-wrap">
            <a href="<?php the_permalink() ?>">
                <h4 class="<?php echo (stm_mm_layout_name() == 'personal') ? 'heading-font' : 'normal-font'; ?>"><?php the_title(); ?></h4>
            </a>
            <ul class="meta">
                <li class="category normal-font primary-color"><a href="<?php echo esc_url(get_category_link($cat[0]->term_id)); ?>"><?php echo esc_html($cat[0]->name); ?></a></li>
                <li class="normal-font"><?php echo get_the_date(); ?></li>
            </ul>
        </div>
    </div>
</div>