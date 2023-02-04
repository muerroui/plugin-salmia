<label>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<span class="butterbean-description">{{{ data.description }}}</span>
	<# } #>

	<div class="stm_car_location_admin">
		<input type="text" value="{{ data.value }}" {{{ data.attr }}} />
	</div>
</label>
