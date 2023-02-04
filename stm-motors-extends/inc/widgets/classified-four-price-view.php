<?php
class STM_WP_ClassifiedFourPriceView extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'stm_wp_classified_four_price_view', 'description' => __('STM Classified Four Price widget', 'stm_motors_extends'));
        $control_ops = array('width' => 400, 'height' => 350);
        parent::__construct('stm_classified_four_price_view', __('STM Classified Four Price View', 'stm_motors_extends'), $widget_ops, $control_ops);
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $price = get_post_meta(get_the_ID(), 'price', true);
        $sale_price = get_post_meta(get_the_ID(), 'sale_price', true);
        $regular_price_description = get_post_meta(get_the_ID(), 'regular_price_description', true);

        if(!stm_is_aircrafts()) {
            $regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
            $special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );
            $instant_savings_label = get_post_meta( get_the_ID(), 'instant_savings_label', true );

//Get text price field
            $car_price_form = get_post_meta( get_the_ID(), 'car_price_form', true );
            $car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );


            $show_price = true;
            $show_sale_price = true;

            if ( empty( $price ) ) {
                $show_price = false;
            }

            if ( empty( $sale_price ) ) {
                $show_sale_price = false;
            }

            if ( !empty( $price ) and empty( $sale_price ) ) {
                $show_sale_price = false;
            }

            if ( !empty( $price ) and !empty( $sale_price ) ) {
                if ( intval( $price ) == intval( $sale_price ) ) {
                    $show_sale_price = false;
                }
            }

            if ( empty( $price ) and !empty( $sale_price ) ) {
                $price = $sale_price;
                $show_price = true;
                $show_sale_price = false;
            }

			if(stm_is_dealer_two()) {
				$sellOnline = stm_me_get_wpcfto_mod( 'enable_woo_online', false );
				$isSellOnline = ( $sellOnline ) ? !empty( get_post_meta( get_the_ID(), 'car_mark_woo_online', true ) ) : false;
			}
            ?>

            <?php //SINGLE REGULAR PRICE ?>
            <?php if ( $show_price and !$show_sale_price ) { ?>

                <?php if(stm_is_dealer_two() && $isSellOnline): ?>
                    <a id="buy-car-online" class="buy-car-online-btn" href="#" data-id="<?php echo esc_attr(get_the_ID()); ?>" data-price="<?php echo esc_attr($price); ?>" >
                <?php else: ?>
                    <?php if ( !empty( $car_price_form ) and $car_price_form == 'on' ): ?>
                        <a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
                    <?php endif; ?>
                <?php endif; ?>

                <div class="single-car-prices">
                    <div class="single-regular-price text-center">

                        <?php if ( !empty( $car_price_form_label ) ): ?>
                            <span class="h3"><?php echo esc_attr( $car_price_form_label ); ?></span>
                        <?php else: ?>
                            <?php if(stm_is_dealer_two() && $isSellOnline): ?>
                                <span class="labeled"><?php esc_html_e('BUY CAR ONLINE:', 'motors'); ?></span>
                            <?php else: ?>
                                <?php if ( !empty( $regular_price_label ) ): ?>
                                    <span class="labeled"><?php printf( esc_html__( '%s', 'motors' ), $regular_price_label ); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="h3"><?php echo esc_attr( stm_listing_price_view( $price ) ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

				<?php if(stm_is_dealer_two() && $isSellOnline): ?>
                    </a>
				<?php else: ?>
                    <?php if ( !empty( $car_price_form ) and $car_price_form == 'on' ): ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>


                <?php if ( !empty( $regular_price_description ) ): ?>
                    <div
                            class="price-description-single"><?php printf( esc_html__( '%s', 'motors' ), $regular_price_description ); ?></div>
                <?php endif; ?>

            <?php } ?>
            <?php //SINGLE REGULAR && SALE PRICE ?>
            <?php if ( $show_price and $show_sale_price ) { ?>

                <div class="single-car-prices">
                    <?php if ( !empty( $car_price_form ) and $car_price_form == 'on' ): ?>
                        <a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
                            <div class="single-regular-price text-center">
                                <?php if ( !empty( $car_price_form_label ) ): ?>
                                    <span class="h3"><?php echo esc_attr( $car_price_form_label ); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php else: ?>
					<?php if(stm_is_dealer_two() && $isSellOnline): ?>
                        <a id="buy-car-online" class="buy-car-online-btn" href="#" data-id="<?php echo esc_attr(get_the_ID()); ?>" data-price="<?php echo esc_attr($sale_price); ?>" >
                    <?php endif; ?>
                        <div class="single-regular-sale-price">
                            <table>
								<?php if(stm_is_dealer_two() && $isSellOnline): ?>
                                    <tr>
                                        <td colspan="2" style="border: 0; padding-bottom: 5px;" align="center">
                                            <span class="labeled"><?php esc_html_e('BUY CAR ONLINE', 'motors'); ?></span>
                                        </td>
                                    </tr>
								<?php endif; ?>
                                <tr>
                                    <td>
                                        <div class="regular-price-with-sale">
                                            <?php if ( !empty( $regular_price_label ) ):
                                                printf( esc_html__( '%s', 'motors' ), $regular_price_label );
                                            endif; ?>
                                            <strong>
                                                <?php echo esc_attr( stm_listing_price_view( $price ) ); ?>
                                            </strong>

                                        </div>
                                    </td>
                                    <td>
                                        <?php if ( !empty( $special_price_label ) ): ?>
                                            <?php printf( esc_html__( '%s', 'motors' ), $special_price_label );
                                            $mg_bt = '';
                                        else:
                                            $mg_bt = 'style=margin-top:0';
                                        endif; ?>
                                        <div class="h4" <?php echo esc_attr( $mg_bt ); ?>><?php echo esc_attr( stm_listing_price_view( $sale_price ) ); ?></div>
                                    </td>
                                </tr>
                            </table>
                        </div>

						<?php if(stm_is_dealer_two() && $isSellOnline): ?>
                            </a>
						<?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if ( $car_price_form == '' && !empty( $instant_savings_label ) ): ?>
                    <?php $savings = intval( $price ) - intval( $sale_price ); ?>
                    <div class="sale-price-description-single">
                        <?php printf( esc_html__( '%s', 'motors' ), $instant_savings_label ); ?>
                        <strong> <?php echo esc_attr( stm_listing_price_view( $savings ) ); ?></strong>
                    </div>
                <?php endif; ?>
            <?php } ?>

            <?php if ( !$show_price and !$show_sale_price && !empty( $car_price_form_label ) ) { ?>
                <?php if ( !empty( $car_price_form ) and $car_price_form == 'on' ): ?>
                    <a href="#" class="rmv_txt_drctn" data-toggle="modal" data-target="#get-car-price">
                <?php endif; ?>

                <div class="single-car-prices">
                    <div class="single-regular-price text-center">
                        <span class="h3"><?php echo esc_attr( $car_price_form_label ); ?></span>
                    </div>
                </div>

                <?php if ( !empty( $car_price_form ) and $car_price_form == 'on' ): ?>
                    </a>
                <?php endif; ?>

                <?php if ( !empty( $regular_price_description ) ): ?>
                    <div class="price-description-single"><?php printf( esc_html__( '%s', 'motors' ), $regular_price_description ); ?></div>
                <?php endif; ?>
            <?php }
        } else {
            ?>
            <div class="aircraft-price-wrap">
                <div class="left">
                    <?php if(empty($sale_price)): ?>
                        <span class="h3"><?php echo esc_attr(stm_listing_price_view($price)); ?></span>
                    <?php else: ?>
                        <span class="h4"><?php echo esc_attr(stm_listing_price_view($price)); ?></span>
                        <span class="h3"><?php echo esc_attr(stm_listing_price_view($sale_price)); ?></span>
                    <?php endif;?>
                </div>
                <div class="right">
                    <?php if (!empty($regular_price_description)): ?>
                        <div class="price-description-single"><?php stm_dynamic_string_translation_e('Regular Price Description', $regular_price_description); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        return $old_instance;
    }

    public function form( $instance ) {}
}

function register_stm_classified_four_price_view() {
    register_widget( 'STM_WP_ClassifiedFourPriceView' );
}
add_action( 'widgets_init', 'register_stm_classified_four_price_view' );