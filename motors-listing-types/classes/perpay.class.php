<?php

class STM_Multi_Listing_Data_Store_CPT  extends WC_Product_Data_Store_CPT implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface {

	/**
	 * Method to read a product from the database.
	 * @param WC_Product
	 */


	public function read( &$product ) {

		add_filter( 'woocommerce_is_purchasable', function () { return true; }, 10, 1);

		$listings = new STMMultiListing;

		$product->set_defaults();
		$post_types = $listings->stm_get_listing_type_slugs();
		$post_types[] = 'listings';

		$post_object = get_post( $product->get_id() );

		if (
			! $product->get_id() || ! ( $post_object = get_post( $product->get_id() ) ) ||
			! ( ('product' === $post_object->post_type) ||
				(in_array($post_object->post_type, $post_types)) )
		) {
			throw new Exception( __( 'Invalid product.', 'motors_listing_types' ) );
		}



		$product->set_props( array(
			'name'              => $post_object->post_title,
			'slug'              => $post_object->post_name,
			'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
			'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
			'status'            => $post_object->post_status,
			'description'       => $post_object->post_content,
			'short_description' => $post_object->post_excerpt,
			'parent_id'         => $post_object->post_parent,
			'menu_order'        => $post_object->menu_order,
			'reviews_allowed'   => 'open' === $post_object->comment_status,
		) );

		$this->read_attributes( $product );
		$this->read_downloads( $product );
		$this->read_visibility( $product );
		$this->read_product_data( $product );
		$this->read_extra_data( $product );
		$product->set_object_read( true );

	}

	/**
	 * Get the product type based on product ID.
	 *
	 * @since 3.0.0
	 * @param int $product_id
	 * @return bool|string
	 */

	public function get_product_type( $product_id ) {
		$listings = new STMMultiListing;
		$post_type = get_post_type( $product_id );
		if ( 'product_variation' === $post_type ) {
			return 'variation';
		} elseif ( ( $post_type === 'product' ) || (in_array($post_type, $listings->stm_get_listing_type_slugs())) ) {
			$terms = get_the_terms( $product_id, 'product_type' );
			return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
		} else {
			return false;
		}
	}
}
