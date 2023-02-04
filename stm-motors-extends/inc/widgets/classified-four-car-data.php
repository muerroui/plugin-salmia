<?php

class STM_WP_ClassifiedFourCarData extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array( 'classname' => 'stm_wp_classified_four_car_data', 'description' => __( 'STM Classified Four Car Data widget', 'stm_motors_extends' ) );
        $control_ops = array( 'width' => 400, 'height' => 350 );
        parent::__construct( 'stm_classified_four_car_data', __( 'STM Classified Four Car Data', 'stm_motors_extends' ), $widget_ops, $control_ops );
    }

    public function widget( $args, $instance )
    {
        echo $args['before_widget'];
        $show_trade_in = stm_me_get_wpcfto_mod('show_trade_in', false);
        $show_offer_price = stm_me_get_wpcfto_mod('show_offer_price', false);

        if($show_offer_price or $show_trade_in): ?>


            <div class="stm-car_dealer-buttons heading-font">

                <?php if($show_trade_in): ?>
                    <a href="#trade-in" data-toggle="modal" data-target="#trade-in">
                        <?php esc_html_e('Trade in form', 'motors'); ?>
                        <i class="stm-moto-icon-trade"></i>
                    </a>
                <?php endif; ?>

                <?php if($show_offer_price): ?>
                    <a href="#trade-offer" data-toggle="modal" data-target="#trade-offer">
                        <?php esc_html_e('Make an offer price', 'motors'); ?>
                        <i class="stm-moto-icon-cash"></i>
                    </a>
                <?php endif; ?>

            </div>

        <?php endif;

        $data = apply_filters( 'stm_single_car_data', stm_get_single_car_listings() );
        $post_id = get_the_ID();
        $vin_num = get_post_meta( get_the_id(), 'vin_number', true );
        ?>

        <?php if ( !empty( $data ) ): ?>
        <div class="single-car-data">
            <?php
			$show_certified_logo_1 = stm_me_get_wpcfto_mod( 'show_certified_logo_1', false );
			$show_certified_logo_2 = stm_me_get_wpcfto_mod('show_certified_logo_2', false);
            
            /*If automanager, and no image in admin, set default image carfax*/
            $history_link_1 = get_post_meta( get_the_ID(), 'history_link', true );
            $certified_logo_1 = get_post_meta( get_the_ID(), 'certified_logo_1', true );
            if ( stm_check_if_car_imported( get_the_ID() ) and empty( $certified_logo_1 ) and !empty( $history_link_1 ) ) {
                $certified_logo_1 = 'automanager_default';
            }

            if ( !empty( $certified_logo_1 ) && $show_certified_logo_1):
                if ( $certified_logo_1 == 'automanager_default' ) {
                    $certified_logo_1 = array();
                    $certified_logo_1[0] = get_template_directory_uri() . '/assets/images/carfax.png';
                } else {
                    $certified_logo_1 = wp_get_attachment_image_src( $certified_logo_1, 'stm-img-255-135' );
                }
                if ( !empty( $certified_logo_1[0] ) ) {
                    $certified_logo_1 = $certified_logo_1[0]; ?>
                    <div class="text-center stm-single-car-history-image">
                        <a href="<?php echo esc_url( $history_link_1 ); ?>" target="_blank">
                            <img src="<?php echo esc_url( $certified_logo_1 ); ?>" class="img-responsive dp-in"/>
                        </a>
                    </div>
                <?php }
            endif;

			$history_link_2 = get_post_meta(get_the_ID(), 'certified_logo_2_link', true);
			$certified_logo_2 = get_post_meta(get_the_ID(), 'certified_logo_2', true);
			if (stm_check_if_car_imported(get_the_ID()) and empty($certified_logo_2) and !empty($history_link_2)) {
				$certified_logo_2 = 'automanager_default';
			}

			if (!empty($certified_logo_2) && $show_certified_logo_2):
				if ($certified_logo_2 == 'automanager_default') {
					$certified_logo_2 = array();
					$certified_logo_2[0] = get_stylesheet_directory_uri() . '/assets/images/carfax.png';
				} else {
					$certified_logo_2 = wp_get_attachment_image_src($certified_logo_2, 'full');
				}
				if (!empty($certified_logo_2[0])) {
					$certified_logo_2 = $certified_logo_2[0]; ?>
                    <div class="text-center stm-single-car-history-image">
                        <a href="<?php echo esc_url($history_link_2); ?>" target="_blank">
                            <img src="<?php echo esc_url($certified_logo_2); ?>" class="img-responsive dp-in"/>
                        </a>
                    </div>
				<?php }
			endif;
			?>
            <?php if ( strpos( stm_me_get_wpcfto_mod( "carguru_style", "STYLE1" ), "STYLE" ) !== false ) get_template_part( 'partials/single-car/car', 'gurus' ); ?>

            <table>
                <?php foreach ( $data as $data_value ): ?>
                    <?php
                    $affix = '';
                    if ( !empty( $data_value['number_field_affix'] ) ) {
                        $affix = $data_value['number_field_affix'];
                    }
                    ?>

                    <?php if ( $data_value['slug'] != 'price' ): ?>
                        <?php $data_meta = get_post_meta( $post_id, $data_value['slug'], true ); ?>
                        <?php if ( $data_meta !== '' and $data_meta !== 'none' ): ?>
                            <tr>
                                <td class="t-label"><?php esc_html_e( $data_value['single_name'], 'motors' ); ?></td>
                                <?php if ( !empty( $data_value['numeric'] ) and $data_value['numeric'] ): ?>
                                    <td class="t-value h6"><?php echo esc_attr( ucfirst( $data_meta . $affix ) ); ?></td>
                                <?php else: ?>
                                    <?php
                                    $data_meta_array = explode( ',', $data_meta );
                                    $datas = array();

                                    if ( !empty( $data_meta_array ) ) {
                                        foreach ( $data_meta_array as $data_meta_single ) {
                                            $data_meta = get_term_by( 'slug', $data_meta_single, $data_value['slug'] );
                                            if ( !empty( $data_meta->name ) ) {
                                                $datas[] = esc_attr( $data_meta->name ) . $affix;
                                            }
                                        }
                                    }
                                    ?>
                                    <td class="t-value h6"><?php echo implode( ', ', $datas ); ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!--VIN NUMBER-->
                <?php if ( !empty( $vin_num ) ): ?>
                    <tr>
                        <td class="t-label"><?php esc_html_e( 'Vin', 'motors' ); ?></td>
                        <td class="t-value t-vin h6"><?php echo esc_attr( $vin_num ); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    <?php endif;
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance )
    {
        return $old_instance;
    }

    public function form( $instance )
    {
    }
}

function register_stm_classified_four_car_data()
{
    register_widget( 'STM_WP_ClassifiedFourCarData' );
}

add_action( 'widgets_init', 'register_stm_classified_four_car_data' );