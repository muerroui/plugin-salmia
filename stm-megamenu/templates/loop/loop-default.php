<div class="stm-mm-post-wrap stm-mm-<?php echo stm_mm_layout_name();?>-layout-item">
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
            <h4 class="<?php echo (stm_mm_layout_name() == 'personal') ? 'heading-font' : 'normal-font'; ?>"><?php the_title() ?></h4>
            <span class="date normal-font" style="display: none;"><?php echo get_the_date(); ?></span>
        </a>
    </div>
</div>