<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$reviewId = get_post_id_by_meta_k_v('review_car', get_the_ID());
$title = get_the_title();

if(!is_null($reviewId)) {
    $title = '<span>' . $title . '</span> ' . string_max_charlength(get_the_title($reviewId), 55);
}
?>

<div class="title heading-font">
    <a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
        <?php echo apply_filters('stm_vl_title_filter', $title); ?>
    </a>
</div>