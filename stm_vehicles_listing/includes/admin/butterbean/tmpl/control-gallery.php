<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
<# } #>

<input type="hidden" class="butterbean-attachment-id" name="{{ data.field_name }}" value="{{ data.value }}" />

<# if ( data.values[0] ) { #>
    <div class="main_image">
        <div class="main_image_droppable">
            <div class="inner">
                <div class="inner-bordered">
                    <i class="far fa-file-image"></i>
                </div>
                <span>{{ data.l10n.drop }}</span>
            </div>
        </div>
        <img src="{{data.values[0].src}}" />
    </div>
    <div class="stm_mini_thumbs">
        <# _.each( data.values, function( img, id) { #>
            <div class="thumbs">
                <div class="inner" data-thumb="{{id}}">
                    <img src="{{img.thumb}}" />
                    <div class="inner-hover">
                        <i class="fas fa-times" data-delete="{{id}}"></i>
                        <i class="fas fa-arrows-alt"></i>
                    </div>
                </div>
            </div>
        <# } ) #>
    </div>
<# } else { #>
	<div class="butterbean-placeholder">{{ data.l10n.placeholder }}</div>
<# } #>

<p>
	<# if ( data.values[0] ) { #>
		<button type="button" class="button button-primary butterbean-change-media">{{ data.l10n.change }}</button>
		<button type="button" class="button button-secondary butterbean-remove-media">{{ data.l10n.remove }}</button>
	<# } else { #>
		<button type="button" class="button button-secondary butterbean-add-media">{{ data.l10n.upload }}</button>
	<# } #>
</p>