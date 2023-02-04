<div class="stm-multiselect-wrapper">

	<div class="labels">
		<div class="select-from-label">
			<# if ( data.label ) { #>
				<span class="butterbean-label">{{ data.label }}</span>
			<# } #>
		</div>

		<div class="select-to-label">

			<span class="butterbean-label">{{ data.l10n.selected }} {{data.label}}</span>

		</div>

	</div>

	<select class="stm-multiselect" multiple="multiple" name="{{data.field_name}}[]" {{{ data.attr }}}>

		<# _.each( data.choices, function( label, choice ) { #>

			<option <# if(choice == '') { #>disabled<# } #> value="{{ choice }}" <# if ( -1 !== _.indexOf( data.value, choice) ) { #> selected="selected" <# } #>>{{ label }}</option>

		<# } ) #>

	</select>

	<div class="stm_add_new_optionale">
		<div class="stm_add_new_inner">
			<input placeholder="{{data.l10n.add_new}}" value="" />
			<i class="fas fa-plus"></i>
		</div>
	</div>

</div>