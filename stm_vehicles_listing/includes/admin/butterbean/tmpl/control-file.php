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

<input type="hidden" class="butterbean-attachment-id" name="{{ data.field_name }}" value="{{ data.value }}" {{{ data.attr }}} />

<# if ( data.src ) { #>
	<span class="stm_pdf_input">
		<i class="far fa-file-pdf"></i><span>{{data.src}}</span>
	</span>
<# } else { #>
	<div class="butterbean-placeholder">{{ data.l10n.placeholder }}</div>
<# } #>

<p>
	<# if ( data.src ) { #>
		<button type="button" class="button button-primary butterbean-change-media">{{ data.l10n.change }}</button>
		<button type="button" class="button button-secondary butterbean-remove-media">{{ data.l10n.remove }}</button>
	<# } else { #>
		<button type="button" class="button button-primary butterbean-add-media">{{ data.l10n.upload }}</button>
	<# } #>
</p>
