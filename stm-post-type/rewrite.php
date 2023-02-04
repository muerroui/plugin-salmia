<?php
// STM Post Type Rewrite subplugin
add_action( 'admin_menu', 'stm_register_post_types_options_menu' );

if( ! function_exists( 'stm_register_post_types_options_menu' ) ){
	function stm_register_post_types_options_menu(){
		add_submenu_page( 'tools.php', __('STM Post Types', STM_POST_TYPE), __('STM Post Types', STM_POST_TYPE), 'manage_options', 'stm_post_types', 'stm_post_types_options' );
	}
}

if( ! function_exists( 'stm_post_types_options' ) ){
	function stm_post_types_options(){

	if ( ! empty( $_POST['stm_post_types_options'] ) ) {
		update_option( 'stm_post_types_options', $_POST['stm_post_types_options'] );
	}

	$options = get_option('stm_post_types_options');

	$defaultPostTypesOptions = array(
		'listings' => array(
			'title' => __( 'Listings', STM_POST_TYPE ),
			'plural_title' => __( 'Listings', STM_POST_TYPE ),
			'rewrite' => 'listings'
		),
		'stm_events' => array(
			'title' => __( 'Events', STM_POST_TYPE ),
			'plural_title' => __( 'Events', STM_POST_TYPE ),
			'rewrite' => 'events'
		),
		'stm_review' => array(
			'title' => __( 'Review', STM_POST_TYPE ),
			'plural_title' => __( 'Review', STM_POST_TYPE ),
			'rewrite' => 'review'
		),
	);

	$options = wp_parse_args( $options, $defaultPostTypesOptions );

	$content = '';

	$content .= '
	<div class="wrap">
		<h2>' . __( 'Custom Post Type Renaming Settings', STM_POST_TYPE ) . '</h2>

		<form method="POST" action="">
			<table class="form-table">';
				foreach ($defaultPostTypesOptions as $key => $value){
				$content .= '
				<tr valign="top">
					<th scope="row">
						<label for="'.$key.'_title">' . __( '"'.$value['title'].'" title (admin panel tab name)', STM_POST_TYPE ) . '</label>
					</th>
					<td>
						<input type="text" id="'.$key.'_title" name="stm_post_types_options['.$key.'][title]" value="' . $options[$key]['title'] . '"  size="25" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="'.$key.'_plural_title">' . __( '"'.$value['plural_title'].'" plural title', STM_POST_TYPE ) . '</label>
					</th>
					<td>
						<input type="text" id="'.$key.'_plural_title" name="stm_post_types_options['.$key.'][plural_title]" value="' . $options[$key]['plural_title'] . '"  size="25" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="'.$key.'_rewrite">' . __( '"'.$value['plural_title'].'" rewrite (URL text)', STM_POST_TYPE ) . '</label>
					</th>
					<td>
						<input type="text" id="'.$key.'_rewrite" name="stm_post_types_options['.$key.'][rewrite]" value="' . $options[$key]['rewrite'] . '"  size="25" />
					</td>
				</tr>
				<tr valign="top"><th scope="row"></th></tr>
				';
				}
				$content .='</table>
			<p>' . __( "NOTE: After you change the rewrite field values, you'll need to refresh permalinks under Settings -> Permalinks", STM_POST_TYPE ) . '</p>
			<br/>
			<p>
				<input type="submit" value="' . __( 'Save settings', STM_POST_TYPE ) . '" class="button-primary"/>
			</p>
		</form>
	</div>
	';

	echo apply_filters('stm_pt_content_filter', $content);
	}
}