<?php
function stm_mm_get_terms_by_taxonomy () {
	if(empty($_POST['selected_taxonomy'])) wp_send_json(array('message' => 'Please Select Category'));

	$terms = stm_get_category_by_slug($_POST['selected_taxonomy']);

	if(!empty($terms)) {
	    $termsArr = array();
	    foreach ($terms as $term) {
	        $termsArr[$term->slug] = $term->slug;
        }

        wp_send_json($termsArr);
    }

    wp_send_json(array('message' => 'Terms not exist'));
}

add_action('wp_ajax_stm_mm_get_terms_by_taxonomy', 'stm_mm_get_terms_by_taxonomy');
add_action('wp_ajax_nopriv_stm_mm_get_terms_by_taxonomy', 'stm_mm_get_terms_by_taxonomy');

function stm_mm_categories_for_select () {
    $categories = get_categories(array(
        'child_of'            => 0,
        'current_category'    => 0,
        'depth'               => 0,
        'echo'                => 1,
        'exclude'             => '',
        'exclude_tree'        => '',
        'feed'                => '',
        'feed_image'          => '',
        'feed_type'           => '',
        'hide_empty'          => 0,
        'hide_title_if_empty' => false,
        'hierarchical'        => true,
        'order'               => 'ASC',
        'orderby'             => 'name',
        'separator'           => '<br />',
        'show_count'          => 0,
        'show_option_all'     => '',
        'show_option_none'    => __( 'No categories' ),
        'style'               => 'list',
        'taxonomy'            => 'category',
        'title_li'            => __( 'Categories' ),
        'use_desc_for_title'  => 1,
    ));

    $cat_array_walker = new stm_mm_array_walker();
    $cat_array_walker->walk($categories, 4);
    $catOpt = $cat_array_walker->optList;

    return($catOpt);
}

class stm_mm_array_walker extends Walker {

    public $tree_type = 'category';
    public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    public $optList = array(0 => 'Default');


    function start_lvl( &$output, $depth = 0, $args = array() ) {}

    function end_lvl( &$output, $depth = 0, $args = array() ) {}

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

        $sep = '';

        for($q=0;$q<$depth;$q++) {
            $sep .= ' - ';
        }

        $this->optList[$category->term_id]= $sep . $category->name . '[id:' . $category->term_id . ']';
    }


    function end_el( &$output, $page, $depth = 0, $args = array() ) {}
}

if ( function_exists( 'vc_add_shortcode_param' ) ) {

	function stm_mm_top_categories_vc( $settings, $value )
	{
		return '<div class="stm_autocomplete_vc_field">'
			. '<script type="text/javascript">'
			. 'var st_mm_vc_taxonomies = ' . json_encode( stm_mm_get_taxonomies() )
			. '</script>'
			. '<input type="text" name="' . esc_attr( $settings['param_name'] )
			. '" class="stm_mm_autocomplete_vc wpb_vc_param_value wpb-textinput '
			. esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="text" value="'
			. esc_attr( $value ) . '" />'
			. '</div>';

	}

	vc_add_shortcode_param( 'stm_mm_top_categories_vc', 'stm_mm_top_categories_vc', STM_MM_URL . '/admin/assets/js/vc_extends/stm-mm-top.js' );

	function stm_mm_top_terms_vc( $settings, $value )
	{
		return '<div class="stm_autocomplete_vc_field">'
			. '<input type="text" name="' . esc_attr( $settings['param_name'] )
			. '" class="stm_mm_top_terms_vc wpb_vc_param_value wpb-textinput '
			. esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="text" value="'
			. esc_attr( $value ) . '" />'
			. '</div>';
	}

	vc_add_shortcode_param( 'stm_mm_top_terms_vc', 'stm_mm_top_terms_vc', STM_MM_URL . '/admin/assets/js/vc_extends/stm-mm-top.js?v=1.0' );

}

function stm_mm_get_taxonomies()
{
	//Get all filter options from STM listing plugin - Listing - listing categories
	$filter_options = get_option('stm_vehicle_listing_options');

	$taxonomies = array();

	if (!empty($filter_options)) {
		$i = 0;
		foreach ($filter_options as $filter_option) {
			$taxonomies[$filter_option['single_name']] = $filter_option['slug'];
		}
	}

	return $taxonomies;
}

function stm_mm_get_vehicles()
{
    $posts = get_posts(array('post_type' => stm_listings_post_type(), 'post_status' => 'publish', 'posts_per_page' => -1));

	$vehicles = array();

	if (!empty($posts)) {
		foreach ($posts as $post) {
			$vehicles[$post->ID] = $post->post_title . ' : ' . $post->ID;
		}
	}

	return $vehicles;
}

if(!function_exists('stm_mm_admin_head')) {
	function stm_mm_admin_head()
	{
	?>
		<script>
            var adminAjaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' );?>';
		</script>
	<?php
	}
}
add_action('admin_head', 'stm_mm_admin_head');