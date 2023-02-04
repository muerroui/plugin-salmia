<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$show_compare = stm_me_get_wpcfto_mod('show_listing_compare', false);
?>

<div v-if="posts.length > 0" class="stm-listing-with-rating-wrap" :class="{opacity: loadListings == true}">
    <div class="listing-list-with-rating-loop" v-for="(post, index) in posts"><!--template Start-->
        <div class="meta-top">
            <div class="title heading-font">
                <a v-bind:href="post.url" class="rmv_txt_drctn" v-html="post.title"></a>
            </div>
        </div>
        <div class="image">
            <a v-bind:href="post.url" class="rmv_txt_drctn">
                <div class="image-inner">
                    <img width="255" height="160" v-bind:src="post.img_url" class="img-responsive wp-post-image" alt="" />

                    <div class='fa-round'><i class='fa fa-share'></i></div>
                </div>
            </a>
            <?php if(!empty($show_compare) and $show_compare): ?>
                <div
                        class="stm-listing-compare" :class="post.car_already_added"
                        v-bind:data-id="post.id"
                        v-bind:data-title="post.generate_title"
                        data-toggle="tooltip"
                        data-placement="left"
                        v-bind:title="post.car_compare_status"
                >
                    <i class="stm-service-icon-compare-new"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="meta-middle">
            <div v-if="post.ratingSumm" class="middle_info middle-rating">
                <div class="car_info">
                    <div class="price heading-font">{{post.price}}</div>
                    <div class="mpg normal-font">{{post.hwy}}Hwy / {{post.cwy}}City</div>
                </div>
                <div class="rating">
                    <div class="rating-stars">
                        <i class="rating-empty"></i>
                        <i class="rating-color" v-bind:style="{width: post.ratingP + '%'}"></i>
                    </div>
                    <div class="rating-text heading-font">{{post.ratingSumm}} <?php esc_html_e('out of 5.0', 'stm_vehicles_listing'); ?></div>
                    <div class="rating-details-popup">
                        <ul class="rating-params">
                            <li>
                                <span class="normal-font">Performance</span>
                                <div class="rating-stars">
                                    <i class="rating-empty"></i>
                                    <i class="rating-color" v-bind:style="{width: post.performanceP + '%'}"></i>
                                </div>
                            </li>
                            <li>
                                <span class="normal-font">Comfort</span>
                                <div class="rating-stars">
                                    <i class="rating-empty"></i>
                                    <i class="rating-color" v-bind:style="{width: post.comfortP + '%'}"></i>
                                </div>
                            </li>
                            <li>
                                <span class="normal-font">Interior</span>
                                <div class="rating-stars">
                                    <i class="rating-empty"></i>
                                    <i class="rating-color" v-bind:style="{width: post.interiorP + '%'}"></i>
                                </div>
                            </li>
                            <li>
                                <span class="normal-font">Exterior</span>
                                <div class="rating-stars">
                                    <i class="rating-empty"></i>
                                    <i class="rating-color" v-bind:style="{width: post.exteriorP + '%'}"></i>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div v-else class="middle_info">
                <div class="car_info">
                    <div class="price heading-font">{{post.price}}</div>
                    <div class="mpg normal-font">{{post.hwy}}Hwy / {{post.cwy}}City</div>
                </div>
                <div class="no-review normal-font">No reviews for this Vehicle</div>
            </div>

        </div>
        <div class="meta-bottom" v-html="post.excerpt"></div>
    </div><!--template End-->
</div>
<h3 v-else><?php esc_html_e('Sorry, No results', 'stm_vehicles_listing') ?></h3>