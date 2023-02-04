<?php
$postId = get_the_ID();

$price = stm_listing_price_view(get_post_meta($postId, 'stm_genuine_price', true));
$hwy = get_post_meta($postId, 'highway_mpg', true);
$cwy = get_post_meta($postId, 'sity_mpg', true);

$reviewId = get_post_id_by_meta_k_v('review_car', $postId);

if(!is_null($reviewId)) {

    $performance = get_post_meta($reviewId, 'performance', true);
    $comfort = get_post_meta($reviewId, 'comfort', true);
    $interior = get_post_meta($reviewId, 'interior', true);
    $exterior = get_post_meta($reviewId, 'exterior', true);

    $ratingSumm = (($performance + $comfort + $interior + $exterior) / 4);

}

?>
<div class="middle_info <?php if(!is_null($reviewId)) echo 'middle-rating'; ?>">
    <div class="car_info">
        <?php if(!empty($startAt)): ?>
            <div class="starting-at normal-font">
                <?php echo esc_html__('Starting at', 'stm_vehicles_listing'); ?>
            </div>
        <?php endif; ?>
        <div class="price heading-font">
            <?php echo esc_html($price); ?>
        </div>
        <?php if(empty($startAt)): ?>
            <div class="mpg normal-font">
                <?php echo esc_html($hwy) . esc_html__('Hwy', 'stm_vehicles_listing') . ' / ' . esc_html($cwy) . esc_html__('City', 'stm_vehicles_listing'); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if(!is_null($reviewId)) :?>
        <div class="rating">
            <div class="rating-stars">
                <i class="rating-empty"></i>
                <?php $rateSumm = $ratingSumm * 20; ?>
                <i class="rating-color" style="width: <?php echo esc_attr($rateSumm); ?>%;"></i>
            </div>
            <div class="rating-text heading-font">
                <?php echo sprintf(esc_html__('%s out of 5.0', 'stm_vehicles_listing'), $ratingSumm); ?>
            </div>
            <div class="rating-details-popup">
                <ul class="rating-params">
                    <li>
                        <span class="normal-font"><?php echo esc_html__('Performance', 'stm_vehicles_listing')?></span>
                        <div class="rating-stars">
                            <i class="rating-empty"></i>
                            <?php $perf = $performance * 20; ?>
                            <i class="rating-color" style="width: <?php echo esc_attr($perf); ?>%;"></i>
                        </div>
                    </li>
                    <li>
                        <span class="normal-font"><?php echo esc_html__('Comfort', 'stm_vehicles_listing')?></span>
                        <div class="rating-stars">
                            <i class="rating-empty"></i>
                            <?php $comf = $comfort * 20; ?>
                            <i class="rating-color" style="width: <?php echo esc_attr($comf); ?>%;"></i>
                        </div>
                    </li>
                    <li>
                        <span class="normal-font"><?php echo esc_html__('Interior', 'stm_vehicles_listing')?></span>
                        <div class="rating-stars">
                            <i class="rating-empty"></i>
                            <?php $inter = $interior * 20;?>
                            <i class="rating-color" style="width: <?php echo esc_attr($inter); ?>%;"></i>
                        </div>
                    </li>
                    <li>
                        <span class="normal-font"><?php echo esc_html__('Exterior', 'stm_vehicles_listing')?></span>
                        <div class="rating-stars">
                            <i class="rating-empty"></i>
                            <?php $exter = $exterior * 20 ?>
                            <i class="rating-color" style="width: <?php echo esc_attr($exter); ?>%;"></i>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    <?php else: ?>
        <div class="no-review normal-font">
            <?php echo esc_html__('No reviews for this Vehicle', 'stm_vehicles_listing'); ?>
        </div>
    <?php endif; ?>
</div>
