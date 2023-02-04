<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'STM_Customizer_Vehicle_Font_Size_Control' ) ) {

	class STM_Customizer_Vehicle_Font_Size_Control extends WP_Customize_Control {

		public $type = 'stm-font-size';
		public $units = '';
		public $min = '';
		public $max = '';

		public function render_content() {

			$input_args = array(
				'type'  => 'number',
				'label' => $this->label,
				'name'  => '',
				'id'    => $this->id,
				'value' => $this->value(),
				'link'  => $this->get_link(),
				'min'   => $this->min,
				'max'   => $this->max
			);

			?>

			<div id="stm-customize-control-<?php echo esc_attr( $this->id ); ?>" class="stm-customize-control stm-customize-control-<?php echo esc_attr( str_replace( 'stm-', '', $this->type ) ); ?>">

				<span class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</span>

				<div class="stm-form-item">
					<div class="stm-font-size-wrapper stm-form-item stm_input_group">
						<?php stm_input( $input_args ); ?>
						<?php if ( $this->units ): ?>
							<span class="input-addon"><?php echo esc_html( $this->units ); ?></span>
						<?php endif; ?>
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