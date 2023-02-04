<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'STM_Customizer_Vehicle_Font_Weight_Control' ) ) {

	class STM_Customizer_Vehicle_Font_Weight_Control extends WP_Customize_Control {

		public $type = 'stm-font-weight';

		public function render_content() {


			$weights = array(
				'100'       => __( 'Ultra Light', 'stm_vehicles_listing' ),
				'100italic' => __( 'Ultra Light Italic', 'stm_vehicles_listing' ),
				'200'       => __( 'Light', 'stm_vehicles_listing' ),
				'200italic' => __( 'Light Italic', 'stm_vehicles_listing' ),
				'300'       => __( 'Book', 'stm_vehicles_listing' ),
				'300italic' => __( 'Book Italic', 'stm_vehicles_listing' ),
				'400'       => __( 'Regular', 'stm_vehicles_listing' ),
				'400italic' => __( 'Regular Italic', 'stm_vehicles_listing' ),
				'500'       => __( 'Medium', 'stm_vehicles_listing' ),
				'500italic' => __( 'Medium Italic', 'stm_vehicles_listing' ),
				'600'       => __( 'Semi-Bold', 'stm_vehicles_listing' ),
				'600italic' => __( 'Semi-Bold Italic', 'stm_vehicles_listing' ),
				'700'       => __( 'Bold', 'stm_vehicles_listing' ),
				'700italic' => __( 'Bold Italic', 'stm_vehicles_listing' ),
				'800'       => __( 'Extra Bold', 'stm_vehicles_listing' ),
				'800italic' => __( 'Extra Bold Italic', 'stm_vehicles_listing' ),
				'900'       => __( 'Ultra Bold', 'stm_vehicles_listing' ),
				'900italic' => __( 'Ultra Bold Italic', 'stm_vehicles_listing' )
			);


			$input_args = array(
				'type'    => 'select',
				'label'   => $this->label,
				'name'    => '',
				'id'      => $this->id,
				'value'   => $this->value(),
				'link'    => $this->get_link(),
				'options' => $weights
			);

			?>

			<div id="stm-customize-control-<?php echo esc_attr( $this->id ); ?>" class="stm-customize-control stm-customize-control-<?php echo esc_attr( str_replace( 'stm-', '', $this->type ) ); ?>">

				<span class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</span>

				<div class="stm-form-item">
					<div class="stm-font-weight-wrapper">
						<?php stm_input( $input_args ); ?>
					</div>
				</div>

				<?php if ( '' != $this->description ) : ?>
					<div class="description customize-control-description">
						<?php echo esc_html( $this->description ); ?>
					</div>
				<?php endif; ?>

			</div>
			<?php
		}
	}
}