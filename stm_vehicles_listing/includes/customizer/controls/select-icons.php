<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'STM_Customizer_Vehicle_Select_Icons_Control' ) ) {

	class STM_Customizer_Vehicle_Select_Icons_Control extends WP_Customize_Control {

		public $type = 'stm-select-icons';

		public function render_content() {

			if ( empty( $this->choices ) ) {
				return;
			}

			$name = 'customize-radio-' . $this->id;

			?>

			<div id="stm-customize-control-<?php echo esc_attr( $this->id ); ?>" class="stm-customize-control stm-customize-control-<?php echo esc_attr( str_replace( 'stm-', '', $this->type ) ); ?>">

				<span class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</span>

				<div class="stm-form-item">
					<ul class="stm-icons-wrapper">
						<?php foreach ( $this->choices as $value => $label ) : ?>
							<li<?php if ( $value == $this->value() ) { echo ' class="active"'; } ?>>
								<label>
									<span class="icon-<?php echo esc_attr( $value ); ?>"></span>
									<span class="stm-icon-description">
										<?php echo esc_html( $label ); ?>
									</span>
									<input class="hide" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
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