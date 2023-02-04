<label>
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

	<input type="{{ data.type }}" value="{{ data.value }}" {{{ data.attr }}} />

	<# if ( data.attr.indexOf('reset=') !== -1 ) { #>
		<a href="#" data-type="{{data.name}}" class="reset_field">Reset counter</a>
	<# } #>
</label>
