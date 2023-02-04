<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class STMMultiListing {
	protected $listings = [];
	public function __construct() {
		$this->listings = $this->stm_get_listings();
		add_action( 'after_setup_theme', array($this, 'multilisting_load_theme_textdomain') );
	}

	static public function stm_get_listings() {
		$options = get_option('stm_motors_listing_types');
		
		if( isset($options['multilisting_repeater']) && !empty($options['multilisting_repeater']) ) {
			return $options['multilisting_repeater'];
		} else {
			return array();
		}
	}

	function multilisting_load_theme_textdomain() {
		load_theme_textdomain( 'motors_listing_types', MULTILISTING_PLUGIN_URL . '/languages' );
	}

	public function stm_get_current_listing() {
		if(is_admin()) return false;
		
		global $wp_query;
		
		if ( !$wp_query ) return false;

		if ( !empty($_REQUEST['posttype']) ) {
			$post_type = esc_html($_REQUEST['posttype']);
		} else {
			$post_type = !empty($wp_query->get('listings_type')) ? $wp_query->get('listings_type') : get_post_type();
		}

		$slugs = $this->stm_get_listing_type_slugs();
		if ( is_post_type_archive( $slugs ) && !empty( get_queried_object()->name ) && in_array( strtolower( get_queried_object()->name ), $slugs ) ) {
			$post_type = get_queried_object()->name;
		}

		if ( $post_type ) {
			if ( !empty( $this->listings ) ) {
				foreach ( $this->listings as $key => $listing ) {
					if ( $post_type == $listing['slug'] ) {
						return $listing;
					}
				}
			}
		}
		
		return false;
	}
	
	public static function stm_get_current_listing_slug() {
		$multilisting = new STMMultiListing();
		$current = $multilisting->stm_get_current_listing();

		if(is_array($current) && isset($current['slug'])) {
			return $current['slug'];
		}

		return false;
	}

	public function stm_get_listing_name_by_slug($slug) {
		if(!empty($this->listings)){
			foreach ($this->listings as $key => $listing) {
				if($listing['slug'] == $slug) return $listing['label'];
			}
		}

		return '';
	}

	public function stm_register_post_types() {
		if(is_array($this->listings) && count($this->listings)){
			foreach ($this->listings as $key => $listing) {
				if(empty($listing['slug']) || empty($listing['label'])) return;
				if(post_type_exists($listing['slug'])) continue;

				$this->registerListings($listing);
			}
		}
	}

	protected function stm_save_post_type_options($listing, $options)	{
		$settings = stm_listings_page_options();

		foreach ($options as $key => $option) {
			foreach ($option as $name => $item) {
				if ($settings[$name]['type'] == 'checkbox' and !empty($item) and $item) {
					$options[$key][$name] = 1;
				}
			}
		}

		if(current_user_can('administrator') || current_user_can('editor')) {
			update_option( "stm_{$listing['slug']}_options", $options );
		}
	}

	static public function stm_get_listing_type_slugs() {
		$slugs = [];
		$listings = STMMultiListing::stm_get_listings();
		if(!empty($listings)){
			foreach ($listings as $key => $listing) {
				$slugs[] = $listing['slug'];
			}
		}

		return $slugs;
	}

	public function stm_get_listing_type_settings($setting_name, $slug){
		$options = get_option('stm_motors_listing_types', []);

		if(isset($options[$slug.'_'.$setting_name]) && !empty($options[$slug.'_'.$setting_name])) {
			return $options[$slug.'_'.$setting_name];
		}

		return false;
	}

}

new STMMultiListing;
