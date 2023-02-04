<?php

function stm_handle_upload( $file ) {
	stm_fix_image_orientation( $file['file'] );
	return $file;
}

add_filter( 'wp_handle_upload', 'stm_handle_upload' );


/**
 * Check if the EXIF orientation flag matches one of the values we're looking for
 * http://www.impulseadventure.com/photo/exif-orientation.html
 *
 * If it does, this means we need to rotate the image based on the orientation flag and then remove the flag.
 * This will ensure the image has the correct orientation, regardless of where it's displayed.
 *
 * Whilst most browsers and applications will read this flag to perform the rotation on displaying just the image, it's
 * not possible to do this in some situations e.g. displaying an image within a lightbox, or when the image is
 * within HTML markup.
 *
 * Orientation flags we're looking for:
 * 8: We need to rotate the image 90 degrees counter-clockwise
 * 3: We need to rotate the image 180 degrees
 * 6: We need to rotate the image 90 degrees clockwise (270 degrees counter-clockwise)
 *
 * @param string $file
 */
function stm_fix_image_orientation( $file ) {

	// Check we have a file
	if ( ! file_exists( $file ) ) {
		return false;
	}

	if ( ! function_exists( 'wp_read_image_metadata' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	// Attempt to read EXIF data from the image
	$exif_data = wp_read_image_metadata( $file );
	if ( ! $exif_data ) {
		return false;
	}

	// Check if an orientation flag exists
	if ( ! isset( $exif_data['orientation'] ) ) {
		return false;
	}

	// Check if the orientation flag matches one we're looking for
	$required_orientations = array( 8, 3, 6 );
	if ( ! in_array( $exif_data['orientation'], $required_orientations ) ) {
		return false;
	}

	// If here, the orientation flag matches one we're looking for
	// Load the WordPress Image Editor class
	$image = wp_get_image_editor( $file );
	if ( is_wp_error( $image ) ) {
		// Something went wrong - abort
		return false;
	}

	// Store the source image EXIF and IPTC data in a variable, which we'll write
	// back to the image once its orientation has changed
	// This is required because when we save an image, it'll lose its metadata.
	getimagesize( $file, $image_info );

	// Depending on the orientation flag, rotate the image
	switch ( $exif_data['orientation'] ) {

		/**
		 * Rotate 90 degrees counter-clockwise
		 */
	case 8:
		$image->rotate( 90 );
		break;

		/**
		 * Rotate 180 degrees
		 */
	case 3:
		$image->rotate( 180 );
		break;

		/**
		 * Rotate 270 degrees counter-clockwise ($image->rotate always works counter-clockwise)
		 */
	case 6:
		$image->rotate( 270 );
		break;

	}

	// Save the image, overwriting the existing image
	// This will discard the EXIF and IPTC data
	$image->save( $file );

	// Drop the EXIF orientation flag, otherwise applications will try to rotate the image
	// before display it, and we don't need that to happen as we've corrected the orientation

	// Write the EXIF and IPTC metadata to the revised image
	return stm_transfer_iptc_exif_to_image( $image_info, $file, $exif_data['orientation'] );
}

/**
 * Transfers IPTC and EXIF data from a source image which contains either/both,
 * and saves it into a destination image's headers that might not have this IPTC
 * or EXIF data
 *
 * Useful for when you edit an image through PHP and need to preserve IPTC and EXIF
 * data
 *
 * @since 1.0.0
 *
 * @source http://php.net/iptcembed - ebashkoff at gmail dot com
 *
 * @param string $image_info              EXIF and IPTC image information from the source image, using getimagesize()
 * @param string $destination_image       Path and File of Destination Image, which needs IPTC and EXIF data
 * @param int    $original_orientation    The image's original orientation, before we changed it.
 *                                        Used when we replace this orientation in the EXIF data
 *
 * @return bool|int
 */
function stm_transfer_iptc_exif_to_image( $image_info, $destination_image, $original_orientation ) {

	// Check destination exists
	if ( ! file_exists( $destination_image ) ) {
		return false;
	}

	// Get EXIF data from the image info, and create the IPTC segment
	$exif_data = ( ( is_array( $image_info ) && key_exists( 'APP1', $image_info ) ) ? $image_info['APP1'] : null );
	if ( $exif_data ) {
		// Find the image's original orientation flag, and change it to 1
		// This prevents applications and browsers re-rotating the image, when we've already performed that function
		// @TODO I'm not sure this is the best way of changing the EXIF orientation flag, and could potentially affect
		// other EXIF data
		$exif_data = str_replace( chr( dechex( $original_orientation ) ) , chr( 0x1 ), $exif_data );

		$exif_length = strlen( $exif_data ) + 2;
		if ( $exif_length > 0xFFFF ) {
			return false;
		}

		// Construct EXIF segment
		$exif_data = chr(0xFF) . chr(0xE1) . chr( ( $exif_length >> 8 ) & 0xFF) . chr( $exif_length & 0xFF ) . $exif_data;
	}

	// Get IPTC data from the source image, and create the IPTC segment
	$iptc_data = ( ( is_array( $image_info ) && key_exists( 'APP13', $image_info ) ) ? $image_info['APP13'] : null );
	if ( $iptc_data ) {
		$iptc_length = strlen( $iptc_data ) + 2;
		if ( $iptc_length > 0xFFFF ) {
			return false;
		}

		// Construct IPTC segment
		$iptc_data = chr(0xFF) . chr(0xED) . chr( ( $iptc_length >> 8) & 0xFF) . chr( $iptc_length & 0xFF ) . $iptc_data;
	}

	// Get the contents of the destination image
	$destination_image_contents = file_get_contents( $destination_image );
	if ( ! $destination_image_contents ) {
		return false;
	}
	if ( strlen( $destination_image_contents ) == 0 ) {
		return false;
	}

	// Build the EXIF and IPTC data headers
	$destination_image_contents = substr( $destination_image_contents, 2 );
	$portion_to_add = chr(0xFF) . chr(0xD8); // Variable accumulates new & original IPTC application segments
	$exif_added = ! $exif_data;
	$iptc_added = ! $iptc_data;

	while ( ( substr( $destination_image_contents, 0, 2 ) & 0xFFF0 ) === 0xFFE0 ) {
		$segment_length = ( substr( $destination_image_contents, 2, 2 ) & 0xFFFF );
		$iptc_segment_number = ( substr( $destination_image_contents, 1, 1 ) & 0x0F );   // Last 4 bits of second byte is IPTC segment #
		if ( $segment_length <= 2 ) {
			return false;
		}

		$thisexistingsegment = substr( $destination_image_contents, 0, $segment_length + 2 );
		if ( ( 1 <= $iptc_segment_number) && ( ! $exif_added ) ) {
			$portion_to_add .= $exif_data;
			$exif_added = true;
			if ( 1 === $iptc_segment_number ) {
				$thisexistingsegment = '';
			}
		}

		if ( ( 13 <= $iptc_segment_number ) && ( ! $iptc_added ) ) {
			$portion_to_add .= $iptc_data;
			$iptc_added = true;
			if ( 13 === $iptc_segment_number ) {
				$thisexistingsegment = '';
			}
		}

		$portion_to_add .= $thisexistingsegment;
		$destination_image_contents = substr( $destination_image_contents, $segment_length + 2 );
	}

	// Write the EXIF and IPTC data to the new file
	if ( ! $exif_added ) {
		$portion_to_add .= $exif_data;
	}
	if ( ! $iptc_added ) {
		$portion_to_add .= $iptc_data;
	}

	$output_file = fopen( $destination_image, 'w' );
	if ( $output_file ) {
		return fwrite( $output_file, $portion_to_add . $destination_image_contents );
	}

	return false;

}