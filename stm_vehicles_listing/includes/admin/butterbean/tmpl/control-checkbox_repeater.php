<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">
		{{{ data.description }}}
		<# if ( data.preview ) { #>
			<div class="image_preview">

				<i class="fas fa-eye"></i>
				<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>

			</div>
		<# } #>
	</span>
<# } else { #>
	<span class="butterbean-description butterbean-no-info">
		<# if ( data.preview ) { #>
			<div class="image_preview dede">
				<i class="fas fa-eye"></i>
				<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>
			</div>
		<# } #>
	</span>
<# } #>

<input type="hidden" value="{{ data.value }}" {{{ data.attr }}} />

<div class="stm_checkbox_repeater">
	<p>
		<input type="text" class="stm_checkbox_adder" placeholder="{{data.l10n.add_feature}}" />
		<button type="button" class="button button-primary butterbean-add-checkbox">{{data.l10n.add}}</button>
	</p>

	<div class="stm_repeater_checkboxes">
		<# _.each( data.values, function( value, key) { #>
			<# if(value.val) { #>
				<div class="stm_repeater_checkbox">
					<label>
						<input type="checkbox" data-key="{{key}}" <# if(value.checked) { #> checked="checked" <# } #> />
						<span>{{value.val}}</span>
					</label>
					<i class="fas fa-times" data-key="{{key}}"></i>
				</div>
			<# } #>
		<# } ) #>
	</div>
</div>

<a href="{{data.link}}" target="_blank">{{data.l10n.all}}</a>