<?php
if( !function_exists('stm_mm_layout_name')) {
    function stm_mm_layout_name () {
        $layout = get_option('current_layout', 'personal');
        return $layout;
    }
}

if(!function_exists('stm_mm_get_featured_listings')) {
	function stm_mm_get_featured_listings( $ppp = 2, $imgSize = 'stm-img-690-410')
	{
		$args = array( 'post_type' => 'listings', 'post_status' => 'publish', 'posts_per_page' => $ppp );

		$args['meta_query'][] = array( 'key' => 'special_car', 'value' => 'on', 'compare' => '=' );

		$args['orderby'] = 'rand';

		$featuredQuery = new WP_Query( $args );

		$featured = array();

		if ( $featuredQuery->have_posts() ) {
			while ( $featuredQuery->have_posts() ) {
				$featuredQuery->the_post();

				$id = get_the_ID();

				$price = get_post_meta( $id, 'price', true );
				$sale_price = get_post_meta( $id, 'sale_price', true );

				$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), $imgSize );

				if ( !$featureImg ) {
					$plchldr_id = get_option( 'plchldr_attachment_id', 0 );
					$featureImg = ( $plchldr_id == 0 ) ? STM_MM_URL . '/assets/img/car_plchldr.png' : wp_get_attachment_image_url( $plchldr_id );
				}

				$featured[] = array( 'ID' => $id, 'title' => get_the_title(), 'price' => str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ), 'sale_price' => str_replace( '   ', ' ', stm_listing_price_view( trim( $sale_price) ) ), 'img' => $featureImg );

			}
		}

		wp_reset_postdata();

		return $featured;
	}
}