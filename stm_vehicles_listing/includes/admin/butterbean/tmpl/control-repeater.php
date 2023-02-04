<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
<# } #>

<p>
	<button type="button" class="button button-primary butterbean-add-field">{{data.l10n.add}}</button>
</p>

<div class="stm_repeater_inputs">
	<# _.each( data.values, function( value, key) { #>
		<p>
			<input type="text" name="{{ data.field_name }}[]" value="{{value}}" data-key="{{key}}" {{{ data.attr }}} />
			<i class="fas fa-times butterbean-delete-field" data-delete="{{key}}"></i>
		</p>
	<# } ) #>
</div>