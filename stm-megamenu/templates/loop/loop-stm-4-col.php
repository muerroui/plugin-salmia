<?php
$cat = get_the_category(get_the_ID());
?>
<div class="stm-mm-post-wrap">
    <div class="stm-mm-post">
        <a href="<?php the_permalink() ?>">
            <div class="img"><?php the_post_thumbnail('medium') ?></div>
            <h4 class="normal-font"><?php the_title() ?></h4>
        </a>
    </div>
</div>