<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class TemplatesMultiListing extends STMMultiListing
{

	public function __construct()
	{
		parent::__construct();

		add_filter( 'single_template', array($this, 'singlePostTypeTemplate') );
		add_filter( 'archive_template', array($this, 'archivePostTypeTemplate') );
		add_action( 'stm_account_custom_page', array($this, 'addPostTypeCustomListingPage') );
	}

	public function singlePostTypeTemplate($single) {
		global $post;
		if(is_array($this->listings) && count($this->listings)) {
			foreach ($this->listings as $key => $listing) {
				$slug = $listing['slug'];
				if (empty($slug) || empty($listing['name'])) {
					return $single;
				}
				if ($post->post_type == $slug) {
					if(file_exists(get_stylesheet_directory() . "/single-{$slug}.php")) {
						return get_stylesheet_directory() . "/single-{$slug}.php";
					}else if(file_exists(get_template_directory() . "/single-{$slug}.php")){
						return get_template_directory() . "/single-{$slug}.php";
					}else if(file_exists(get_stylesheet_directory() . '/single-listings.php')){
						return get_stylesheet_directory() . '/single-listings.php';
					}else if(file_exists(get_template_directory() . '/single-listings.php')){
						return get_template_directory() . '/single-listings.php';
					}else{
						return get_template_directory() . '/single.php';
					}
				}
			}
		}
		return $single;
	}

	public function archivePostTypeTemplate($archive) {
		global $post;
		if(is_array($this->listings) && count($this->listings)) {
			foreach ($this->listings as $key => $listing) {
				$slug = $listing['slug'];
				if (empty($slug) || empty($listing['name'])) {
					return $archive;
				}
				if ($post->post_type == $slug) {
					if(file_exists(get_stylesheet_directory() . "/archive-{$slug}.php")) {
						return get_stylesheet_directory() . "/archive-{$slug}.php";
					}else if(file_exists(get_template_directory() . "/archive-{$slug}.php")){
						return get_template_directory() . "/archive-{$slug}.php";
					}else if(file_exists(get_stylesheet_directory() . '/archive-listings.php')){
						return get_stylesheet_directory() . '/archive-listings.php';
					}else if(file_exists(get_template_directory() . '/archive-listings.php')){
						return get_template_directory() . '/archive-listings.php';
					}else{
						return get_template_directory() . '/index.php';
					}
				}
			}
		}
		return true;
	}

	public function addPostTypeCustomListingPage($current) {
		if(is_array($this->listings) && count($this->listings)) {
			foreach ($this->listings as $key => $listing) {
				$slug = $listing['slug'];
				if ($current == $slug) {
					extract(['post_type' => $current]);
					if(file_exists(get_stylesheet_directory() . "/multilisting/user/private/listing-list-$slug.php")) {
						include_once get_stylesheet_directory() . "/multilisting/user/private/listing-list-$slug.php";
					}else if(file_exists(get_template_directory() . "/multilisting/user/private/listing-list-$slug.php")){
						include_once get_template_directory() . "/multilisting/user/private/listing-list-$slug.php";
					}else{
						include_once MULTILISTING_PATH . '/templates/listing-list-edit.php';
					}
				}
			}
		}
		return true;
	}

}

new TemplatesMultiListing();
