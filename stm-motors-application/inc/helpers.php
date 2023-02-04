<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 3/12/19
 * Time: 14:02
 */

function stm_ma_register_menu_page() {
	add_menu_page(
		__( 'Motors App Settings', 'textdomain' ),
		'Motors App Settings',
		'manage_options',
		'ma_settings',
		'stm_ma_settings_page',
		'',
		6
	);
}

add_action( 'admin_menu', 'stm_ma_register_menu_page' );
/**
 * Display a custom menu page
 */
function stm_ma_settings_page() {
	wp_enqueue_style( 'ma-bootstrap' );
	wp_enqueue_style( 'ma-multi-select' );
	wp_enqueue_style( 'ma-select2' );
	wp_enqueue_style( 'ma-styles' );
	wp_enqueue_script( 'ma-bootstrap' );
	wp_enqueue_script( 'ma-multi-select' );
	wp_enqueue_script( 'ma-select2' );
	wp_enqueue_script( 'ma-app' );
	require_once __DIR__ . '/admin/parts/main.php';
}

if ( ! function_exists( 'stm_ma_generate_title_from_slugs' ) ) {
	function stm_ma_generate_title_from_slugs( $post_id, $title_from, $show_labels = false ) {
		if ( ! empty( $title_from ) ) {
			$title_return  = '';
			$title         = stm_ma_replace_curly_brackets( $title_from );
			$title_counter = 0;
			if ( ! empty( $title ) ) {
				foreach ( $title as $title_part ) {
					$title_counter ++;
					if ( $title_counter == 1 ) {
						if ( $show_labels ) {
							$title_return .= '<div class="labels">';
						}
					}
					$term = wp_get_post_terms( $post_id, strtolower( $title_part ), array( 'orderby' => 'none' ) );
					if ( ! empty( $term ) and ! is_wp_error( $term ) ) {
						if ( ! empty( $term[0] ) ) {
							if ( ! empty( $term[0]->name ) ) {
								if ( $title_counter == 1 ) {
									$title_return .= $term[0]->name;
								} else {
									$title_return .= ' ' . $term[0]->name;
								}
							} else {
								$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
								if ( ! empty( $number_affix ) ) {
									$title_return .= ' ' . $number_affix . ' ';
								}
							}
						}
					} else {
						$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
						if ( ! empty( $number_affix ) ) {
							$title_return .= ' ' . $number_affix . ' ';
						}
					}
					if ( $show_labels and $title_counter == 2 ) {
						$title_return .= '</div>';
					}
				}
			}
			if ( empty( $title_return ) ) {
				$title_return = get_the_title( $post_id );
			}
		}

		return $title_return;
	}
}
if ( ! function_exists( 'stm_ma_replace_curly_brackets' ) ) {
	function stm_ma_replace_curly_brackets( $string ) {
		$matches = array();

		//preg_match_all( '/{(.*?)}/', $string, $matches );
		return explode( ',', $string );
	}
}
function stm_ma_getoption_value( $id, $middle_info ) {

	if ( is_null( $middle_info ) ) {
		return '';
	}
	$data_meta  = get_post_meta( $id, $middle_info['slug'], true );
	$data_value = '';
	if ( $data_meta !== '' and $data_meta !== 'none' and $middle_info['slug'] != 'price' ):
		if ( ! empty( $middle_info['numeric'] ) and $middle_info['numeric'] ):
			$affix = '';
			if ( ! empty( $middle_info['number_field_affix'] ) ) {
				$affix = esc_html__( $middle_info['number_field_affix'], 'motors' );
			}
			$data_value = ucfirst( $data_meta ) . ' ' . $affix;
		else:
			$data_meta_array = ( $data_meta && ! empty( $data_meta ) ) ? explode( ',', $data_meta ) : null;
			$data_value = array();
			if ( ! empty( $data_meta_array ) ) {
				foreach ( $data_meta_array as $data_meta_single ) {
					$data_meta = get_the_terms( $id, $middle_info['slug'] );
					if ( ! empty( $data_meta ) and ! is_wp_error( $data_meta ) ) {
						foreach ( $data_meta as $data_metas ) {
							$data_value[] = esc_attr( $data_metas->name );
						}
					}
					break;
				}
			}
		endif;
	endif;

	return $data_value;
}

function stm_ma_get_option_key_value( $id, $middle_info ) {
	$data_meta  = get_post_meta( $id, $middle_info['slug'], true );
	$data_value = '';
	if ( $data_meta !== '' and $data_meta !== 'none' and $middle_info['slug'] != 'price' ):
		if ( ! empty( $middle_info['numeric'] ) and $middle_info['numeric'] ):
			if ( in_array( $middle_info['slug'], array( 'mileage', 'engine' ) ) ) {
				$data_value = $data_meta;
			} else {
				$data_value   = array();
				$data_value[] = array( $data_meta => ucfirst( $data_meta ) );
			}
		else:
			$data_meta_array = explode( ',', $data_meta );
			if ( ! empty( $data_meta_array ) ) {
				foreach ( $data_meta_array as $data_meta_single ) {
					$data_meta = get_the_terms( $id, $middle_info['slug'] );
					if ( ! empty( $data_meta ) and ! is_wp_error( $data_meta ) ) {
						foreach ( $data_meta as $data_metas ) {
							$data_value   = array();
							$data_value[] = array( $data_metas->slug => esc_attr( $data_metas->name ) );
						}
					}
					break;
				}
			}
		endif;
	endif;

	return $data_value;
}

/**
 * @param array $args
 *
 * @return mixed
 */
function stm_app_listings_attributes( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'where'  => array(),
		'key_by' => ''
	) );
	$result = array();
	$data   = array_filter( (array) get_option( 'stm_vehicle_listing_options' ) );
	foreach ( $data as $key => $_data ) {
		$passed = true;
		foreach ( $args['where'] as $_field => $_val ) {
			if ( array_key_exists( $_field, $_data ) && $_data[ $_field ] != $_val ) {
				$passed = false;
				break;
			}
		}
		if ( $passed ) {
			if ( $args['key_by'] ) {
				$result[ $_data[ $args['key_by'] ] ] = $_data;
			} else {
				$result[] = $_data;
			}
		}
	}

	return apply_filters( 'stm_listings_attributes', $result, $args );
}

function stm_ma_get_tax_info( $id, $info ) {
	$translations = ( get_option( 'translations', '' ) ) ? get_option( 'translations', '' ) : [];
	$middle_infos = stm_app_listings_attributes( array(
		'where'  => array( 'slug' => $info ),
		'key_by' => ''
	) );
	$optVal = stm_ma_getoption_value( $id, $middle_infos[0] );
	$dataInfo = ( is_array( $optVal ) ) ? implode( ' ', $optVal ) : $optVal;
	$ico = str_replace( array( 'stm-icon-', 'stm-boats-', 'stm-service-icon-', 'icon-' ), '', $middle_infos[0]['font'] );
	$info  = ( $info == 'ca-year' ) ? 'year' : $info;
	$label = ( ! empty( $translations[ $info ] ) ) ? $translations[ $info ] : $middle_infos[0]['single_name'];

	return array( 'info_1' => $label, 'info_2' => $dataInfo, 'info_3' => $ico );
}

function stm_ma_get_featured_listings( $ppp = 10 ) {
	$args = array(
		'post_type'      => 'listings',
		'post_status'    => 'publish',
		'posts_per_page' => $ppp
	);
	$args['meta_query'][] = array(
		'key'     => 'special_car',
		'value'   => 'on',
		'compare' => '='
	);
	$args['orderby'] = 'rand';
	$featuredQuery = new WP_Query( $args );
	$featured = array();
	if ( $featuredQuery->have_posts() ) {
		while ( $featuredQuery->have_posts() ) {
			$featuredQuery->the_post();
			$id = get_the_ID();
			$price      = get_post_meta( $id, 'price', true );
			$sale_price = get_post_meta( $id, 'sale_price', true );
			if ( empty( $price ) and ! empty( $sale_price ) ) {
				$price = $sale_price;
			}
			$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'stm-img-690-410' );
			if ( ! $featureImg ) {
				$plchldr_id = get_option( 'plchldr_attachment_id', 0 );
				$featureImg = ( $plchldr_id == 0 ) ? STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png' : wp_get_attachment_image_url( $plchldr_id );
			}
			if ( strpos( $featureImg, 'motors.loc' ) ) {
				$featureImg = stm_ma_replace_host( $featureImg );
			}
			$featured[] = array(
				'ID'    => $id,
				'title' => get_the_title(),
				'price' => ( ! empty( $price ) ) ? str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ) : 'No Price',
				'img'   => $featureImg
			);

		}
	}
	wp_reset_postdata();

	return $featured;
}

function stm_ma_get_featured_listings_pagination( $ppp = 10, $paged ) {
	$args = array(
		'post_type'      => 'listings',
		'post_status'    => 'publish',
		'posts_per_page' => $ppp,
		'paged'          => $paged
	);
	$args['meta_query'][] = array(
		'key'     => 'special_car',
		'value'   => 'on',
		'compare' => '='
	);
	$args['orderby'] = 'DESC';
	$featuredQuery = new WP_Query( $args );
	$featured = array();
	if ( $featuredQuery->have_posts() ) {
		while ( $featuredQuery->have_posts() ) {
			$featuredQuery->the_post();
			$id = get_the_ID();
			$price      = get_post_meta( $id, 'price', true );
			$sale_price = get_post_meta( $id, 'sale_price', true );
			if ( empty( $price ) and ! empty( $sale_price ) ) {
				$price = $sale_price;
			}
			$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'stm-img-690-410' );
			if ( ! $featureImg ) {
				$plchldr_id = get_option( 'plchldr_attachment_id', 0 );
				$featureImg = ( $plchldr_id == 0 ) ? STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png' : wp_get_attachment_image_url( $plchldr_id );
			}
			if ( strpos( $featureImg, 'motors.loc' ) ) {
				$featureImg = stm_ma_replace_host( $featureImg );
			}
			$featured[] = array(
				'ID'    => $id,
				'title' => get_the_title(),
				'price' => ( ! empty( $price ) ) ? str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ) : 'No Price',
				'img'   => $featureImg
			);

		}
	}
	wp_reset_postdata();
	$featured = array( 'data' => $featured, 'featured_max_num_pages' => $featuredQuery->max_num_pages );

	return $featured;
}

function stm_ma_get_last_listings( $offset, $limit ) {
	$listings = new WP_Query( array(
		'post_type'      => 'listings',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'offset'         => $offset,
		'orderby'        => 'DESC'
	) );
	$newListings = array();
	if ( $listings->have_posts() ) {
		$gridOpt = get_option( 'grid_view_settings', array() );
		$listOpt = get_option( 'list_view_settings', array() );
		$title    = ( ! empty( $gridOpt['go_two'] ) ) ? $gridOpt['go_two'] : '';
		$subTitle = ( ! empty( $gridOpt['go_one'] ) ) ? $gridOpt['go_one'] : '';
		$info     = ( ! empty( $gridOpt['go_three'] ) ) ? $gridOpt['go_three'] : '';
		$listTitle     = ( ! empty( $listOpt['list_title'] ) ) ? $listOpt['list_title'] : '';
		$listInfoOne   = ( ! empty( $listOpt['list_info_one'] ) ) ? $listOpt['list_info_one'] : '';
		$listInfoTwo   = ( ! empty( $listOpt['list_info_two'] ) ) ? $listOpt['list_info_two'] : '';
		$listInfoThree = ( ! empty( $listOpt['list_info_three'] ) ) ? $listOpt['list_info_three'] : '';
		$listInfoFour  = ( ! empty( $listOpt['list_info_four'] ) ) ? $listOpt['list_info_four'] : '';
		while ( $listings->have_posts() ) {
			$listings->the_post();
			$car_media = stm_get_car_medias( get_the_id() );
			$gallery   = array();
			if ( ! empty( $car_media ) ) {
				foreach ( $car_media['car_photos'] as $link ) {
					if ( strpos( $link, 'motors.loc' ) ) {
						$link = stm_ma_replace_host( $link );
					}
					$gallery[] = array( 'url' => $link );
				}
			}
			$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'stm-img-690-410' );
			if ( ! $featureImg ) {
				$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
			}
			if ( strpos( $featureImg, 'motors.loc' ) ) {
				$featureImg = stm_ma_replace_host( $featureImg );
			}
			if ( ! $featureImg ) {
				$featureImg = '';
			}
			$price      = get_post_meta( get_the_id(), 'price', true );
			$sale_price = get_post_meta( get_the_id(), 'sale_price', true );
			if ( empty( $price ) and ! empty( $sale_price ) ) {
				$price = $sale_price;
			}
			$genTitle    = stm_ma_generate_title_from_slugs( get_the_ID(), $title );
			$genSubTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $subTitle );
			$genListTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $listTitle );
			$infos = stm_ma_get_tax_info( get_the_ID(), $info );
			$infosListOne   = stm_ma_get_tax_info( get_the_ID(), $listInfoOne );
			$infosListTwo   = stm_ma_get_tax_info( get_the_ID(), $listInfoTwo );
			$infosListThree = stm_ma_get_tax_info( get_the_ID(), $listInfoThree );
			$infosListFour  = stm_ma_get_tax_info( get_the_ID(), $listInfoFour );
			$newListings[] = array(
				'ID'       => get_the_ID(),
				'imgUrl'   => $featureImg,
				'gallery'  => $gallery,
				'imgCount' => count( $gallery ),
				'price'    => ( ! empty( $price ) ) ? str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ) : 'No Price',
				'grid'     => array(
					'title'     => $genTitle,
					'subTitle'  => $genSubTitle,
					'infoIcon'  => $infos['info_3'],
					'infoTitle' => $infos['info_1'],
					'infoDesc'  => $infos['info_2'],
				),
				'list'     => array(
					'title'        => $genListTitle,
					'infoOneIcon'  => $infosListOne['info_3'],
					'infoOneTitle' => $infosListOne['info_1'],
					'infoOneDesc'  => $infosListOne['info_2'],
					'infoTwoIcon'  => $infosListTwo['info_3'],
					'infoTwoTitle' => $infosListTwo['info_1'],
					'infoTwoDesc'  => $infosListTwo['info_2'],
					'infoThreeIcon'  => $infosListThree['info_3'],
					'infoThreeTitle' => $infosListThree['info_1'],
					'infoThreeDesc'  => $infosListThree['info_2'],
					'infoFourIcon'  => $infosListFour['info_3'],
					'infoFourTitle' => $infosListFour['info_1'],
					'infoFourDesc'  => $infosListFour['info_2'],
				)
			);
		}
	}
	wp_reset_postdata();
	$lstngs = array( 'data' => $newListings, 'foundPosts' => $listings->found_posts );

	return $lstngs;
}

function stm_ma_get_last_listings_pagination( $offset, $limit, $paged ) {
	$listings = new WP_Query( array(
		'post_type'      => 'listings',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => 'DESC',
		'paged'          => $paged
	) );
	$newListings = array();
	if ( $listings->have_posts() ) {
		$gridOpt = get_option( 'grid_view_settings', array() );
		$listOpt = get_option( 'list_view_settings', array() );
		$title    = ( ! empty( $gridOpt['go_two'] ) ) ? $gridOpt['go_two'] : '';
		$subTitle = ( ! empty( $gridOpt['go_one'] ) ) ? $gridOpt['go_one'] : '';
		$info     = ( ! empty( $gridOpt['go_three'] ) ) ? $gridOpt['go_three'] : '';
		$listTitle     = ( ! empty( $listOpt['list_title'] ) ) ? $listOpt['list_title'] : '';
		$listInfoOne   = ( ! empty( $listOpt['list_info_one'] ) ) ? $listOpt['list_info_one'] : '';
		$listInfoTwo   = ( ! empty( $listOpt['list_info_two'] ) ) ? $listOpt['list_info_two'] : '';
		$listInfoThree = ( ! empty( $listOpt['list_info_three'] ) ) ? $listOpt['list_info_three'] : '';
		$listInfoFour  = ( ! empty( $listOpt['list_info_four'] ) ) ? $listOpt['list_info_four'] : '';
		while ( $listings->have_posts() ) {
			$listings->the_post();
			$car_media = stm_get_car_medias( get_the_id() );
			$gallery   = array();
			if ( ! empty( $car_media ) ) {
				foreach ( $car_media['car_photos'] as $link ) {
					if ( strpos( $link, 'motors.loc' ) ) {
						$link = stm_ma_replace_host( $link );
					}
					$gallery[] = array( 'url' => $link );
				}
			}
			$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'stm-img-690-410' );
			if ( ! $featureImg ) {
				$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
			}
			if ( strpos( $featureImg, 'motors.loc' ) ) {
				$featureImg = stm_ma_replace_host( $featureImg );
			}
			if ( ! $featureImg ) {
				$featureImg = '';
			}
			$price      = get_post_meta( get_the_id(), 'price', true );
			$sale_price = get_post_meta( get_the_id(), 'sale_price', true );
			if ( empty( $price ) and ! empty( $sale_price ) ) {
				$price = $sale_price;
			}
			$genTitle    = stm_ma_generate_title_from_slugs( get_the_ID(), $title );
			$genSubTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $subTitle );
			$genListTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $listTitle );
			$infos = stm_ma_get_tax_info( get_the_ID(), $info );
			$infosListOne   = stm_ma_get_tax_info( get_the_ID(), $listInfoOne );
			$infosListTwo   = stm_ma_get_tax_info( get_the_ID(), $listInfoTwo );
			$infosListThree = stm_ma_get_tax_info( get_the_ID(), $listInfoThree );
			$infosListFour  = stm_ma_get_tax_info( get_the_ID(), $listInfoFour );
			$newListings[] = array(
				'ID'       => get_the_ID(),
				'imgUrl'   => $featureImg,
				'gallery'  => $gallery,
				'imgCount' => count( $gallery ),
				'price'    => ( ! empty( $price ) ) ? str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ) : 'No Price',
				'grid'     => array(
					'title'     => $genTitle,
					'subTitle'  => $genSubTitle,
					'infoIcon'  => $infos['info_3'],
					'infoTitle' => $infos['info_1'],
					'infoDesc'  => $infos['info_2'],
				),
				'list'     => array(
					'title'        => $genListTitle,
					'infoOneIcon'  => $infosListOne['info_3'],
					'infoOneTitle' => $infosListOne['info_1'],
					'infoOneDesc'  => $infosListOne['info_2'],
					'infoTwoIcon'  => $infosListTwo['info_3'],
					'infoTwoTitle' => $infosListTwo['info_1'],
					'infoTwoDesc'  => $infosListTwo['info_2'],
					'infoThreeIcon'  => $infosListThree['info_3'],
					'infoThreeTitle' => $infosListThree['info_1'],
					'infoThreeDesc'  => $infosListThree['info_2'],
					'infoFourIcon'  => $infosListFour['info_3'],
					'infoFourTitle' => $infosListFour['info_1'],
					'infoFourDesc'  => $infosListFour['info_2'],
				)
			);
		}
	}
	wp_reset_postdata();
	$lstngs = array( 'data' => $newListings, 'foundPosts' => $listings->found_posts, 'last_max_num_pages' => $listings->max_num_pages );

	return $lstngs;
}

function stm_ma_get_listing_obj( $query ) {
	$newListings = array();
	if ( $query->have_posts() ) {
		$gridOpt = get_option( 'grid_view_settings', array() );
		$listOpt = get_option( 'list_view_settings', array() );
		$title    = ( ! empty( $gridOpt['go_two'] ) ) ? $gridOpt['go_two'] : '';
		$subTitle = ( ! empty( $gridOpt['go_one'] ) ) ? $gridOpt['go_one'] : '';
		$info     = ( ! empty( $gridOpt['go_three'] ) ) ? $gridOpt['go_three'] : '';
		$listTitle     = ( ! empty( $listOpt['list_title'] ) ) ? $listOpt['list_title'] : '';
		$listInfoOne   = ( ! empty( $listOpt['list_info_one'] ) ) ? $listOpt['list_info_one'] : '';
		$listInfoTwo   = ( ! empty( $listOpt['list_info_two'] ) ) ? $listOpt['list_info_two'] : '';
		$listInfoThree = ( ! empty( $listOpt['list_info_three'] ) ) ? $listOpt['list_info_three'] : '';
		$listInfoFour  = ( ! empty( $listOpt['list_info_four'] ) ) ? $listOpt['list_info_four'] : '';
		while ( $query->have_posts() ) {
			$query->the_post();
			$car_media = stm_get_car_medias( get_the_id() );
			$gallery   = array();
			if ( ! empty( $car_media ) ) {
				foreach ( $car_media['car_photos'] as $link ) {
					if ( strpos( $link, 'motors.loc' ) ) {
						$link = stm_ma_replace_host( $link );
					}
					$gallery[] = array( 'url' => $link );
				}
			}
			$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'stm-img-690-410' );
			if ( ! $featureImg ) {
				$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
			}
			if ( strpos( $featureImg, 'motors.loc' ) ) {
				$featureImg = stm_ma_replace_host( $featureImg );
			}
			if ( ! $featureImg ) {
				$featureImg = '';
			}
			$price      = get_post_meta( get_the_id(), 'price', true );
			$sale_price = get_post_meta( get_the_id(), 'sale_price', true );
			if ( empty( $price ) and ! empty( $sale_price ) ) {
				$price = $sale_price;
			}
			$genTitle    = stm_ma_generate_title_from_slugs( get_the_ID(), $title );
			$genSubTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $subTitle );
			$genListTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $listTitle );
			$infos = stm_ma_get_tax_info( get_the_ID(), $info );
			$infosListOne   = stm_ma_get_tax_info( get_the_ID(), $listInfoOne );
			$infosListTwo   = stm_ma_get_tax_info( get_the_ID(), $listInfoTwo );
			$infosListThree = stm_ma_get_tax_info( get_the_ID(), $listInfoThree );
			$infosListFour  = stm_ma_get_tax_info( get_the_ID(), $listInfoFour );
			$newListings[] = array(
				'ID'       => get_the_ID(),
				'imgUrl'   => $featureImg,
				'gallery'  => $gallery,
				'imgCount' => count( $gallery ),
				'price'    => ( ! empty( $price ) ) ? str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ) : 'No Price',
				'grid'     => array(
					'title'     => $genTitle,
					'subTitle'  => $genSubTitle,
					'infoIcon'  => $infos['info_3'],
					'infoTitle' => $infos['info_1'],
					'infoDesc'  => $infos['info_2'],
				),
				'list'     => array(
					'title'        => $genListTitle,
					'infoOneIcon'  => $infosListOne['info_3'],
					'infoOneTitle' => $infosListOne['info_1'],
					'infoOneDesc'  => $infosListOne['info_2'],
					'infoTwoIcon'  => $infosListTwo['info_3'],
					'infoTwoTitle' => $infosListTwo['info_1'],
					'infoTwoDesc'  => $infosListTwo['info_2'],
					'infoThreeIcon'  => $infosListThree['info_3'],
					'infoThreeTitle' => $infosListThree['info_1'],
					'infoThreeDesc'  => $infosListThree['info_2'],
					'infoFourIcon'  => $infosListFour['info_3'],
					'infoFourTitle' => $infosListFour['info_1'],
					'infoFourDesc'  => $infosListFour['info_2'],
				)
			);
		}
	}
	wp_reset_postdata();

	return $newListings;
}

function stm_ma_get_user_favourites( $userId, $limit = - 1, $offset = 0, $hasFoundPosts = false ) {
	$favourites  = get_the_author_meta( 'stm_user_favourites', $userId );
	$newListings = array();
	if ( ! empty( $favourites ) ) {

		$args = array(
			'post_type'      => stm_listings_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'offset'         => $offset,
			'post__in'       => array_unique( explode( ',', $favourites ) )
		);
		$fav = new WP_Query( $args );
		if ( $fav->have_posts() ) {
			$gridOpt = get_option( 'grid_view_settings', array() );
			$listOpt = get_option( 'list_view_settings', array() );
			$title    = ( ! empty( $gridOpt['go_two'] ) ) ? $gridOpt['go_two'] : '';
			$subTitle = ( ! empty( $gridOpt['go_one'] ) ) ? $gridOpt['go_one'] : '';
			$info     = ( ! empty( $gridOpt['go_three'] ) ) ? $gridOpt['go_three'] : '';
			$listTitle     = ( ! empty( $listOpt['list_title'] ) ) ? $listOpt['list_title'] : '';
			$listInfoOne   = ( ! empty( $listOpt['list_info_one'] ) ) ? $listOpt['list_info_one'] : '';
			$listInfoTwo   = ( ! empty( $listOpt['list_info_two'] ) ) ? $listOpt['list_info_two'] : '';
			$listInfoThree = ( ! empty( $listOpt['list_info_three'] ) ) ? $listOpt['list_info_three'] : '';
			$listInfoFour  = ( ! empty( $listOpt['list_info_four'] ) ) ? $listOpt['list_info_four'] : '';
			$key = 1;
			while ( $fav->have_posts() ) {
				$fav->the_post();
				$car_media = stm_get_car_medias( get_the_id() );
				$gallery   = array();
				if ( ! empty( $car_media ) ) {
					foreach ( $car_media['car_photos'] as $link ) {
						if ( strpos( $link, 'motors.loc' ) ) {
							$link = stm_ma_replace_host( $link );
						}
						$gallery[] = array( 'url' => $link );
					}
				}
				$featureImg = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'stm-img-690-410' );
				if ( ! $featureImg ) {
					$featureImg = STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png';
				}
				if ( strpos( $featureImg, 'motors.loc' ) ) {
					$featureImg = stm_ma_replace_host( $featureImg );
				}
				if ( ! $featureImg ) {
					$featureImg = '';
				}
				$price      = get_post_meta( get_the_id(), 'price', true );
				$sale_price = get_post_meta( get_the_id(), 'sale_price', true );
				if ( empty( $price ) and ! empty( $sale_price ) ) {
					$price = $sale_price;
				}
				$genTitle    = stm_ma_generate_title_from_slugs( get_the_ID(), $title );
				$genSubTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $subTitle );
				$genListTitle = stm_ma_generate_title_from_slugs( get_the_ID(), $listTitle );
				$infos = stm_ma_get_tax_info( get_the_ID(), $info );
				$infosListOne   = stm_ma_get_tax_info( get_the_ID(), $listInfoOne );
				$infosListTwo   = stm_ma_get_tax_info( get_the_ID(), $listInfoTwo );
				$infosListThree = stm_ma_get_tax_info( get_the_ID(), $listInfoThree );
				$infosListFour  = stm_ma_get_tax_info( get_the_ID(), $listInfoFour );
				$newListings[] = array(
					'key'      => $key,
					'ID'       => get_the_ID(),
					'imgUrl'   => $featureImg,
					'gallery'  => $gallery,
					'imgCount' => count( $gallery ),
					'price'    => ( ! empty( $price ) ) ? str_replace( '   ', ' ', stm_listing_price_view( trim( $price ) ) ) : 'No Price',
					'grid'     => array(
						'title'     => $genTitle,
						'subTitle'  => $genSubTitle,
						'infoIcon'  => $infos['info_3'],
						'infoTitle' => $infos['info_1'],
						'infoDesc'  => $infos['info_2'],
					),
					'list'     => array(
						'title'        => $genListTitle,
						'infoOneIcon'  => $infosListOne['info_3'],
						'infoOneTitle' => $infosListOne['info_1'],
						'infoOneDesc'  => $infosListOne['info_2'],
						'infoTwoIcon'  => $infosListTwo['info_3'],
						'infoTwoTitle' => $infosListTwo['info_1'],
						'infoTwoDesc'  => $infosListTwo['info_2'],
						'infoThreeIcon'  => $infosListThree['info_3'],
						'infoThreeTitle' => $infosListThree['info_1'],
						'infoThreeDesc'  => $infosListThree['info_2'],
						'infoFourIcon'  => $infosListFour['info_3'],
						'infoFourTitle' => $infosListFour['info_1'],
						'infoFourDesc'  => $infosListFour['info_2'],
					)
				);
				$key ++;
			}
			if ( $hasFoundPosts ) {
				$newListings['listings']    = $newListings;
				$newListings['found_posts'] = $fav->found_posts;
			}
		}
	}

	return $newListings;
}

function stm_ma_replace_host( $url ) {
	return str_replace( 'http://motors.loc', 'http://192.168.0.125:3000', $url );
}

function addACarStepParse( $paramms ) {
	$forApp = array();
	if ( ! empty( $paramms ) ) {
		foreach ( $paramms as $param ) {


			if ( $param == 'add_media' ) {
				$forApp[ $param ] = array(
					'label' => 'Add Media',
					'data'  => array()
				);
				continue;
			}
			if ( $param == 'seller_notes' ) {
				$forApp[ $param ] = array(
					'label' => 'Seller Notes',
					'data'  => array()
				);
				continue;
			}
			if ( $param == 'location' ) {
				$forApp[ $param ] = array(
					'label' => 'Location',
					'data'  => array()
				);
				continue;
			}
			$getTerms = get_terms( array( 'taxonomy' => $param, 'hide_empty' => false, 'update_term_meta_cache' => false ) );
			if ( $getTerms ) {
				/*$taxObj = get_taxonomy($param);
				$taxLabel = ($taxObj) ? $taxObj->label : $param;*/
				if ( $param != 'price' && $param != 'ca-year' ) {
					$newFilter = array();
					foreach ( $getTerms as $term ) {
						if ( ! $term || ( is_array( $term ) && ! empty( $term['invalid_taxonomy'] ) ) ) {
							continue;
						}
						$image          = get_term_meta( $term->term_id, 'stm_image', true );
						$image          = wp_get_attachment_image_src( $image, 'stm-img-190-132' );
						$category_image = $image[0];
						if ( ! $category_image ) {
							$plchldr_id     = get_option( 'plchldr_attachment_id', 0 );
							$category_image = ( $plchldr_id == 0 ) ? STM_MOTORS_APP_URL . '/assets/img/car_plchldr.png' : wp_get_attachment_image_url( $plchldr_id );
						}
						if ( strpos( $category_image, 'motors.loc' ) ) {
							$category_image = stm_ma_replace_host( $category_image );
						}
						$newFilter[] = array(
							'label'  => $term->name,
							'slug'   => $term->slug,
							'count'  => $term->count,
							'logo'   => ( $category_image ) ? $category_image : '',
							'parent' => get_term_meta( $term->term_id, 'stm_parent', true )
						);

					}
					/*$forApp[$param]['label'] = $taxLabel;
					$forApp[$param]['data'] = $newFilter;*/
					$forApp[ $param ] = $newFilter;
				} else {
					$newFilter = array();
					foreach ( $getTerms as $term ) {
						if ( ! $term || ( is_array( $term ) && ! empty( $term['invalid_taxonomy'] ) ) ) {
							continue;
						}
						$newFilter[] = array(
							'label' => $term->name,
							'value' => $term->slug,
							'slug'  => $term->slug,
							'count' => $term->count,
						);

					}
					$param = ( $param == 'ca-year' ) ? 'ca-year' : $param;
					if ( $param == 'year' || $param == 'price' ) {
						asort( $newFilter );
						$newFilter = array_values( $newFilter );
					}
					/*$forApp[$param]['label'] = $taxLabel;
                    $forApp[$param]['data'] = $newFilter;*/
					$forApp[ $param ] = $newFilter;


				}
			}
		}
	}
	foreach ( $paramms as $att ) {
		if ( ! in_array( $att, array_keys( $forApp ) ) ) {
			if ( $att == 'ca-year' ) {
				continue;
			}
			$tax            = get_taxonomy( $att );
			$forApp[ $att ] = array(
				'label' => $tax->label,
				'data'  => array()
			);
		}
	}
	$forApps = [];
	foreach ( $paramms as $param ) {
		$forApps[ $param ] = $forApp[ $param ];
	}
	$frapps = [];
	foreach ( $forApps as $k => $v ) {
		if ( $k == 'ca-year' ) {
			$k = 'year';
		}
		$frapps[ $k ] = $v;
	}
	if ( $forApps['ca-year'] ) {
		$forApps['year'] = ( $forApps['ca-year'] ) ? $forApps['ca-year'] : [];
	}
	unset( $forApps['ca-year'] );
	$res = [];
	foreach ( $frapps as $key => $val ) {
		if ( $key ) {
			$res[ $key ] = $val;
		}
	}
	$res = get_translations( $res );

	return $res;
}

function get_translations( $forApp ) {
	global $translations;
	$res             = [];
	$res['category'] = $forApp;
	foreach ( $forApp as $key => $val ) {
		$res['translation'][ $key ] = $translations[ $key ];
	}

	return $forApp;
}

function stm_ma_add_a_car( $update = false ) {
	$response     = array();
	$first_step   = array(); //needed fields
	$second_step  = array(); //secondary fields
	$third_step   = array(); //third fields
	$car_features = array(); //array of features car
	$videos       = array(); /*videos links*/
	$notes        = esc_html__( 'N/A', 'stm-motors-application' );
	$registered   = '';
	$vin          = '';
	$history      = array(
		'label' => '',
		'link'  => ''
	);
	$location     = array(
		'label' => '',
		'lat'   => '',
		'lng'   => '',
	);
	if ( empty( $_POST['user_id'] ) && empty( $_POST['user_token'] ) && get_user_meta( $_POST['user_id'], 'stm_app_token', true ) != $_POST['user_token'] ) {
		$response['message'] = esc_html__( 'Please, log in', 'stm-motors-application' );

		return false;
	} else {
		$user         = stm_get_user_custom_fields( $_POST['user_id'] );
		$restrictions = stm_get_post_limits( $_POST['user_id'] );
	}
	if ( defined( 'MOTORS_DEMO_SITE' ) && MOTORS_DEMO_SITE && ! empty( $_POST['stm_current_car_id'] ) ) {
		$post_status = get_post_status( $_POST['stm_current_car_id'] );
		if ( $post_status == 'publish' ) {
			$response['code']    = 403;
			$response['message'] = esc_html__( 'Site is on demo mode', 'stm-motors-application' );
			wp_send_json( $response );
		}
	}
	$response['message'] = '';
	$error               = false;
	$demo = stm_is_site_demo_mode();
	if ( defined( 'MOTORS_DEMO_SITE' ) && MOTORS_DEMO_SITE ) {
		$demo = false;
	}
	if ( $demo ) {
		$error               = true;
		$response['code']    = 403;
		$response['message'] = esc_html__( 'Site is on demo mode', 'stm-motors-application' );
	}
	if ( ! empty( $_POST['stm_current_car_id'] ) ) {
		$post_id  = intval( $_POST['stm_current_car_id'] );
		$car_user = get_post_meta( $post_id, 'stm_car_user', true );
		$update   = true;
		/*Check if current user edits his car*/
		if ( intval( $car_user ) != intval( $user['user_id'] ) ) {
			return false;
		}
	}
	/*Get first step*/
	$stepOne  = explode( ',', get_option( 'add_car_step_one', "add_media,make,serie,ca-year,mileage,exterior-color" ) );
	$not_send = [];
	if ( in_array( 'price', $stepOne ) ) {
		$key                    = array_search( 'price', $stepOne );
		$_POST['stm_f_s_price'] = $_POST['stm_car_price'];
	}
	stm_app_put_log( 'car_add_post', $_POST, $append = true );
	stm_app_put_log( 'stepOne', $stepOne, $append = true );
	foreach ( $stepOne as $k ) {
		if ( empty( $k ) || $k == 'add_media' ) {
			continue;
		}
		$k = ( $k == 'ca-year' ) ? 'year' : $k;
		if ( ! empty( $_POST[ 'stm_f_s_' . $k ] ) ) {
			$postKey = str_replace( "_pre_", "-", $k );
			$postKey = ( $postKey == 'year' ) ? 'ca-year' : $postKey;
			$first_step[ sanitize_title( $postKey ) ] = sanitize_title( $_POST[ 'stm_f_s_' . $k ] );
		} else {
			$error               = true;
			$response['code']    = 403;
			$response['message'] = esc_html__( 'Enter required fields', 'motors' );
		}
		if ( empty( $_POST[ 'stm_f_s_' . $k ] ) ) {
			$not_send[] = 'stm_f_s_' . $k;
		}
	}
	$response['required_fields']          = implode( ',', $stepOne );
	$response['not_send_required_fields'] = implode( ',', $not_send );
	if ( empty( $first_step ) ) {
		$error               = true;
		$response['code']    = 403;
		$response['message'] = esc_html__( 'Enter required fields', 'motors' );
	}
	/*Get if no available posts*/
	if ( $restrictions['posts'] < 1 and ! $update && ! defined( 'MOTORS_DEMO_SITE' ) ) {
		$error               = true;
		$response['code']    = 403;
		$response['message'] = esc_html__( 'You do not have available posts', 'stm-motors-application' );
	}
	/*Getting second step*/
	foreach ( $_POST as $second_step_key => $second_step_value ) {
		if ( empty( $second_step_key ) ) {
			continue;
		}
		if ( strpos( $second_step_key, 'stm_s_s_' ) !== false ) {
			if ( $_POST[ $second_step_key ] != "" ) {
				$original_key                                   = str_replace( 'stm_s_s_', '', $second_step_key );
				$second_step[ sanitize_title( $original_key ) ] = sanitize_text_field( $_POST[ $second_step_key ] );
			}
		}
	}
	/*Getting third step*/
	foreach ( $_POST as $third_step_key => $third_step_value ) {
		if ( empty( $third_step_key ) ) {
			continue;
		}
		if ( strpos( $third_step_key, 'stm_t_s_' ) !== false ) {
			if ( $_POST[ $third_step_key ] != "" ) {
				$original_key                                  = str_replace( 'stm_t_s_', '', $third_step_key );
				$third_step[ sanitize_title( $original_key ) ] = sanitize_text_field( $_POST[ $third_step_key ] );
			}
		}
	}
	/*Getting car features*/
	if ( ! empty( $_POST['stm_additional_features'] ) ) {
		foreach ( $_POST['stm_additional_features'] as $car_feature ) {
			$car_features[] = sanitize_text_field( $car_feature );
		}
	}
	/*Videos*/
	if ( ! empty( $_POST['stm_video'] ) ) {
		foreach ( $_POST['stm_video'] as $video ) {
			$videos[] = esc_url( $video );
		}
	}
	/*Note*/
	if ( ! empty( $_POST['stm_seller_notes'] ) ) {
		$notes = sanitize_textarea_field( $_POST['stm_seller_notes'] );
	}
	/*Registration date*/
	if ( ! empty( $_POST['stm_registered'] ) ) {
		$registered = sanitize_text_field( $_POST['stm_registered'] );
	}
	/*Vin*/
	if ( ! empty( $_POST['stm_vin'] ) ) {
		$vin = sanitize_text_field( $_POST['stm_vin'] );
	}
	/*History*/
	if ( ! empty( $_POST['stm_history_label'] ) ) {
		$history['label'] = sanitize_text_field( $_POST['stm_history_label'] );
	}
	if ( ! empty( $_POST['stm_history_link'] ) ) {
		$history['link'] = esc_url( $_POST['stm_history_link'] );
	}
	/*Location*/
	if ( ! empty( $_POST['stm_location_text'] ) ) {
		$location['label'] = sanitize_text_field( $_POST['stm_location_text'] );
	}
	if ( ! empty( $_POST['stm_lat'] ) ) {
		$location['lat'] = sanitize_text_field( $_POST['stm_lat'] );
	}
	if ( ! empty( $_POST['stm_lng'] ) ) {
		$location['lng'] = sanitize_text_field( $_POST['stm_lng'] );
	}
	if ( empty( $_POST['stm_car_price'] ) ) {
		$error               = true;
		$response['code']    = 403;
		$response['message'] = esc_html__( 'Please add car price', 'stm-motors-application' );
	} else {
		$price = stm_convert_to_normal_price( abs( intval( $_POST['stm_car_price'] ) ) );
	}
	if ( ! empty( $_POST['car_price_form_label'] ) ) {
		$location['car_price_form_label'] = sanitize_text_field( $_POST['car_price_form_label'] );
	}
	if ( ! empty( $_POST['stm_car_sale_price'] ) ) {
		$location['stm_car_sale_price'] = stm_convert_to_normal_price( abs( sanitize_text_field( $_POST['stm_car_sale_price'] ) ) );
	}
	$generic_title = '';
	if ( ! empty( $_POST['stm_car_main_title'] ) ) {
		$generic_title = sanitize_text_field( $_POST['stm_car_main_title'] );
	} else {
		if ( ! empty( $_POST['smt_f_s_make'] ) || ! empty( $_POST['smt_s_s_make'] ) ) {
			$generic_title .= ( ! empty( $_POST['smt_f_s_make'] ) ) ? $_POST['smt_f_s_make'] : $_POST['smt_s_s_make'];
			$generic_title .= ' ';
		}
		if ( ! empty( $_POST['smt_f_s_serie'] ) || ! empty( $_POST['smt_s_s_serie'] ) ) {
			$generic_title .= ( ! empty( $_POST['smt_f_s_serie'] ) ) ? $_POST['smt_f_s_serie'] : $_POST['smt_s_s_serie'];
		}
	}
	$generic_title = sanitize_text_field( $generic_title );
	/*Generating post*/
	if ( ! $error ) {
		if ( $restrictions['premoderation'] ) {
			$status = 'pending';
		} else {
			$status = 'publish';
		}
		if ( defined( 'MOTORS_DEMO_SITE' ) ) {
			//$status = 'pending';
		}
		$post_data = array(
			'post_type'   => 'listings',
			'post_title'  => '',
			'post_status' => $status,
		);
		if ( ! empty( $generic_title ) ) {
			$post_data['post_title'] = $generic_title;
		}
		if ( ! $update ) {
			$post_id = wp_insert_post( $post_data, true );
		}
		if ( ! is_wp_error( $post_id ) ) {

			if ( $update ) {
				$post_data_update = array(
					'ID'          => $post_id,
					'post_status' => $status,
				);
				if ( ! empty( $generic_title ) ) {
					$post_data_update['post_title'] = $generic_title;
				}
				wp_update_post( $post_data_update );
			}
			update_post_meta( $post_id, 'stock_number', $post_id );
			update_post_meta( $post_id, 'stm_car_user', $user['user_id'] );
			update_post_meta( $post_id, 'listing_seller_note', $notes );
			/*Set categories*/
			foreach ( $first_step as $tax => $term ) {
				$tax_info = stm_get_all_by_slug( $tax );
				if ( ! empty( $tax_info['numeric'] ) and $tax_info['numeric'] ) {
					update_post_meta( $post_id, $tax, abs( sanitize_title( $term ) ) );
				} else {
					wp_delete_object_term_relationships( $post_id, $tax );
					wp_add_object_terms( $post_id, $term, $tax, true );
					update_post_meta( $post_id, $tax, sanitize_title( $term ) );
				}
			}
			if ( ! empty( $second_step ) ) {
				/*Set categories*/
				stm_app_put_log( 'steptwo', $second_step, $append = true );
				foreach ( $second_step as $tax => $term ) {
					if ( ! empty( $tax ) and ! empty( $term ) ) {
						$tax_info = stm_get_all_by_slug( $tax );
						if ( ! empty( $tax_info['numeric'] ) and $tax_info['numeric'] ) {
							$tax = ( $tax == 'year' ) ? 'ca-year' : $tax;
							update_post_meta( $post_id, $tax, sanitize_text_field( $term ) );
						} else {
							$tax = ( $tax == 'year' ) ? 'ca-year' : $tax;
							wp_delete_object_term_relationships( $post_id, $tax );
							wp_add_object_terms( $post_id, $term, $tax, true );
							update_post_meta( $post_id, $tax, sanitize_text_field( $term ) );
						}
					}
				}
			}
			if ( ! empty( $third_step ) ) {
				/*Set categories*/
				stm_app_put_log( 'steptthree', $third_step, $append = true );
				foreach ( $third_step as $tax => $term ) {
					if ( ! empty( $tax ) and ! empty( $term ) ) {
						$tax_info = stm_get_all_by_slug( $tax );
						if ( ! empty( $tax_info['numeric'] ) and $tax_info['numeric'] ) {
							$tax = ( $tax == 'year' ) ? 'ca-year' : $tax;
							update_post_meta( $post_id, $tax, sanitize_text_field( $term ) );
						} else {
							$tax = ( $tax == 'year' ) ? 'ca-year' : $tax;
							wp_delete_object_term_relationships( $post_id, $tax );
							wp_add_object_terms( $post_id, $term, $tax, true );
							update_post_meta( $post_id, $tax, sanitize_text_field( $term ) );
						}
					}
				}
			}
			if ( ! empty( $videos ) ) {
				update_post_meta( $post_id, 'gallery_video', $videos[0] );
				if ( count( $videos ) > 1 ) {
					array_shift( $videos );
					update_post_meta( $post_id, 'gallery_videos', array_filter( array_unique( $videos ) ) );
				}

			}
			if ( ! empty( $vin ) ) {
				update_post_meta( $post_id, 'vin_number', $vin );
			}
			if ( ! empty( $registered ) ) {
				update_post_meta( $post_id, 'registration_date', $registered );
			}
			if ( ! empty( $history['label'] ) ) {
				update_post_meta( $post_id, 'history', $history['label'] );
			}
			if ( ! empty( $history['link'] ) ) {
				update_post_meta( $post_id, 'history_link', $history['link'] );
			}
			if ( ! empty( $location['label'] ) ) {
				update_post_meta( $post_id, 'stm_car_location', $location['label'] );
			}
			if ( ! empty( $location['lat'] ) ) {
				update_post_meta( $post_id, 'stm_lat_car_admin', $location['lat'] );
			}
			if ( ! empty( $location['lng'] ) ) {
				update_post_meta( $post_id, 'stm_lng_car_admin', $location['lng'] );
			}
			if ( ! empty( $car_features ) ) {
				stm_app_put_log( 'car_features', $car_features, $append = true );
				update_post_meta( $post_id, 'additional_features', implode( ',', $car_features ) );
			} else {
				update_post_meta( $post_id, 'additional_features', implode( ',', [] ) );
			}
			update_post_meta( $post_id, 'price', $price );
			update_post_meta( $post_id, 'stm_genuine_price', $price );
			if ( ! empty( $location['car_price_form_label'] ) ) {
				update_post_meta( $post_id, 'car_price_form_label', $location['car_price_form_label'] );
			}
			if ( isset( $location['stm_car_sale_price'] ) && ! empty( $location['stm_car_sale_price'] ) ) {
				update_post_meta( $post_id, 'sale_price', $location['stm_car_sale_price'] );
				update_post_meta( $post_id, 'stm_genuine_price', $location['stm_car_sale_price'] );
			} else {
				update_post_meta( $post_id, 'sale_price', '' );
			}
			update_post_meta( $post_id, 'title', 'hide' );
			update_post_meta( $post_id, 'breadcrumbs', 'show' );
			$response['post_id'] = $post_id;
			if ( ( $update ) ) {
				$response['code']    = 200;
				$response['message'] = esc_html__( 'Car Updated, uploading photos', 'stm-motors-application' );
			} else {
				$response['code']    = 200;
				$response['message'] = esc_html__( 'Car Added, uploading photos', 'stm-motors-application' );
			}
		} else {
			$response['message'] = $post_id->get_error_message();
		}
	}
	wp_send_json( $response );
}

function upload_media( $user_id, $meta_key ) {
	$response = array(
		'message' => '',
		'user_id' => $user_id,
		'errors'  => array(),
	);
	if ( ! empty( $_POST['avatar'] ) ) {
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}
		$upload_dir  = wp_upload_dir();
		$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
		$image = $_POST['avatar']; // your base64 encoded
		$image = str_replace( 'data:image/*;charset=utf-8;base64,', '', $image );
		$image = str_replace( 'data:image/*;base64,', '', $image );
		$image = str_replace( 'data:image/jpeg;base64,', '', $image );
		$image = str_replace( ' ', '+', $image );
		$decoded  = base64_decode( $image );
		$filename = 'my-base64-image.png';
		$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
		$image_upload = file_put_contents( $upload_path . $hashed_filename, $decoded );
		if ( ! function_exists( 'wp_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			require_once( ABSPATH . 'wp-includes/pluggable.php' );
		}
		$file             = array();
		$file['error']    = '';
		$file['tmp_name'] = $upload_path . $hashed_filename;
		$file['name']     = $hashed_filename;
		$file['type']     = 'image/png';
		$file['size']     = filesize( $upload_path . $hashed_filename );
		$file_return = wp_handle_sideload( $file, array( 'test_form' => false ) );
		$filename   = $file_return['file'];
		$guid       = $wp_upload_dir['url'] . '/' . basename( $filename );
		$attachment = array(
			'post_mime_type' => $file_return['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $guid
		);
		$uploaded   = wp_insert_attachment( $attachment, $filename );
		$imgSrc = wp_get_attachment_image_url( $uploaded );
		update_user_meta( $user_id, $meta_key, $imgSrc );

		/*$response['user_id'] = $user_id;
		$response['attach'] = $attachment;
		$response['uploaded'] = $uploaded;
		$response['guid'] = wp_get_attachment_image_url($uploaded);
		$response['success'] = true;*/

		return $imgSrc;
	}
}

function stm_ma_add_a_car_media_two( $user_id, $token, $post_id ) {
	if ( empty( $user_id ) && empty( $token ) && ! empty( $post_id ) && get_user_meta( $user_id, 'stm_app_token', true ) != $token ) {
		$response['message'] = esc_html__( 'Upload Image Filed', 'stm-motors-application' );
		wp_send_json( $response );
	}
	if ( ! $post_id ) {
		/*No id passed from first ajax Call?*/
		wp_send_json( array( 'message' => esc_html__( 'Some error occurred, try again later', 'stm-motors-application' ) ) );
	}
	$limits   = stm_get_post_limits( $user_id );
	$position = $_POST['position'];
	$updating = ! empty( $_POST['stm_edit'] ) and $_POST['stm_edit'] == 'update';
	if ( intval( get_post_meta( $post_id, 'stm_car_user', true ) ) != intval( $user_id ) ) {
		/*User tries to add info to another car*/
		wp_send_json( array(
			'message' => esc_html__( 'You are trying to add car to another car user, or your session has expired, please sign in first user id ' . $user_id . ' post id ' . $post_id . ' user token: ' . $token . ' post author: ' . get_post_meta( $post_id, 'stm_car_user', true ),
				'stm-motors-application' )
		) );
		exit;
	}
	$attachs = get_post_meta( $post_id, 'gallery' );
	$attachments_ids = ( (int) $_POST['position'] !== 0 ) ? array_unique( $attachs[0] ) : array();
	$error    = false;
	$response = array(
		'message' => '',
		'post'    => $post_id,
		'errors'  => array(),
	);
	stm_app_put_log( 'upload_media_file_post', $_POST, $append = true );
	if ( ! empty( $_POST['photos'] ) ) {
		//$_POST['photos'] = array_reverse($_POST['photos']);
		$max_uploads = intval( $limits['images'] ) - count( $attachments_ids );
		if ( ! empty( $_POST['photo']['name'] ) && count( $_POST['photo']['name'] ) > $max_uploads ) {
			$response['message'] = sprintf( esc_html__( 'Sorry, you can upload only %d images per add', 'stm-motors-application' ), $max_uploads );
			wp_send_json( $response );
		} else {

			if ( $error ) {
				if ( ! $updating ) {
					wp_delete_post( $post_id, true );
				}
				wp_send_json( $response );
				exit;
			}
			if ( ! isset( $_POST['img_id'] ) ) {
				if ( ! function_exists( 'media_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					require_once( ABSPATH . 'wp-admin/includes/media.php' );
				}
				foreach ( $_POST['photos'] as $photo ) :
					/*-------*/
					$upload_dir     = wp_upload_dir();
					$upload_path    = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
					$_POST['photo'] = $photo;
					$image          = $_POST['photo']; // your base64 encoded
					$image          = str_replace( 'data:image/*;charset=utf-8;base64,', '', $image );
					$image          = str_replace( 'data:image/*;base64,', '', $image );
					$image          = str_replace( 'data:image/jpeg;base64,', '', $image );
					$image          = str_replace( ' ', '+', $image );
					$decoded  = base64_decode( $image );
					$filename = 'my-base64-image.png';
					$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
					$image_upload = file_put_contents( $upload_path . $hashed_filename, $decoded );
					if ( ! function_exists( 'wp_handle_sideload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}
					if ( ! function_exists( 'wp_get_current_user' ) ) {
						require_once( ABSPATH . 'wp-includes/pluggable.php' );
					}
					$file             = array();
					$file['error']    = '';
					$file['tmp_name'] = $upload_path . $hashed_filename;
					$file['name']     = $hashed_filename;
					$file['type']     = 'image/jpg';
					$file['size']     = filesize( $upload_path . $hashed_filename );
					$file_return = wp_handle_sideload( $file, array( 'test_form' => false ) );
					$filename   = $file_return['file'];
					$attachment = array(
						'post_mime_type' => $file_return['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit',
						'guid'           => $upload_dir['url'] . '/' . basename( $filename )
					);
					stm_app_put_log( 'upload_media_file_attachment', $attachment );
					$uploaded = wp_insert_attachment( $attachment, $filename );
					if ( $uploaded['error'] ) {
						$response['errors'][ $_POST['photo']['name'] ] = $uploaded;
						wp_send_json( $response );
					}
					$attachments_ids[] = $uploaded;
				endforeach;
				$uploaded = ( $attachments_ids ) ? $attachments_ids[0] : null;
				stm_app_put_log( 'upload_media_file_uploaded', $uploaded );
			} else {
				$uploaded = $_POST['img_id'];
			}
			$response['attach_one'] = $attachments_ids;
			if ( $_POST['position'] == $_POST['count'] - 1 ) {
				$current_attachments = get_posts( array(
					'fields'         => 'ids',
					'post_type'      => 'attachment',
					'posts_per_page' => - 1,
					'post_parent'    => $post_id,
				) );
				$delete_attachments = array_diff( $current_attachments, $attachments_ids );
				foreach ( $delete_attachments as $delete_attachment ) {
					stm_delete_media( intval( $delete_attachment ) );
				}
			}
			if ( $position == 0 ) {
				if ( ! empty( $uploaded ) ) {
					stm_app_put_log( 'upload_media_file_uploaded1', $uploaded );
					$imgdid = ( is_array( $uploaded ) ) ? min( $uploaded ) : $uploaded;
					update_post_meta( $post_id, '_thumbnail_id', $imgdid );
				}
			}
			if ( $_POST['position'] == $_POST['count'] - 1 ) {
				//	array_shift($attachments_ids);
			}
			stm_app_put_log( 'upload_media_file_attachment_ids', $attachments_ids );
			update_post_meta( $post_id, 'gallery', $attachments_ids );
			do_action( 'stm_after_listing_gallery_saved', $post_id, $attachments_ids );
			$response['attach']   = $attachments_ids;
			$response['uploaded'] = $attachments_ids;
			$response['current']  = $current_attachments;
			$response['position'] = $position;
			$response['message']  = esc_html__( 'Photo uploaded, redirecting to your account profile', 'stm-motors-application' );
			$response['success']  = true;
			stm_app_put_log( 'upload_media_file', $response );
			wp_send_json( $response );
		}
	}

}

function stm_ma_get_image_mime_type( $image_path ) {
	$mimes = array(
		IMAGETYPE_GIF     => "image/gif",
		IMAGETYPE_JPEG    => "image/jpg",
		IMAGETYPE_PNG     => "image/png",
		IMAGETYPE_SWF     => "image/swf",
		IMAGETYPE_PSD     => "image/psd",
		IMAGETYPE_BMP     => "image/bmp",
		IMAGETYPE_TIFF_II => "image/tiff",
		IMAGETYPE_TIFF_MM => "image/tiff",
		IMAGETYPE_JPC     => "image/jpc",
		IMAGETYPE_JP2     => "image/jp2",
		IMAGETYPE_JPX     => "image/jpx",
		IMAGETYPE_JB2     => "image/jb2",
		IMAGETYPE_SWC     => "image/swc",
		IMAGETYPE_IFF     => "image/iff",
		IMAGETYPE_WBMP    => "image/wbmp",
		IMAGETYPE_XBM     => "image/xbm",
		IMAGETYPE_ICO     => "image/ico"
	);
	if ( ( $image_type = exif_imagetype( $image_path ) )
	     && ( array_key_exists( $image_type, $mimes ) )
	) {
		return $mimes[ $image_type ];
	} else {
		return false;
	}
}

function stm_similar_cars_apps() {
	$tax_query = array();
	$taxes     = stm_me_get_wpcfto_mod( 'stm_similar_query', '' );
	$post_id   = (int) $_GET['id'];
	$query     = array(
		'post_type'      => stm_listings_post_type(),
		'post_status'    => 'publish',
		'posts_per_page' => '3',
		'post__not_in'   => array( $post_id ),
	);
	if ( ! empty( $taxes ) ) {
		$taxes      = array_filter( array_map( 'trim', explode( ',', $taxes ) ) );
		$attributes = stm_listings_attributes( array( 'key_by' => 'slug' ) );
		foreach ( $taxes as $tax ) {
			if ( ! isset( $attributes[ $tax ] ) || ! empty( $attributes[ $tax ]['numeric'] ) ) {
				continue;
			}
			$terms = get_the_terms( $post_id, $tax );
			if ( ! is_array( $terms ) ) {
				continue;
			}
			$tax_query[] = array(
				'taxonomy' => esc_attr( $tax ),
				'field'    => 'slug',
				'terms'    => wp_list_pluck( $terms, 'slug' )
			);
		}
	}
	if ( ! empty( $tax_query ) ) {
		$query['tax_query'] = array( 'relation' => 'OR' ) + $tax_query;
	}
	//print_r($query);
	// exit;
	return new WP_Query( apply_filters( 'stm_similar_cars_query', $query ) );
}

function stm_app_put_log( $file_name, $data, $append = true ) {
	$file = STM_MOTORS_APP_PATH . "/logs/{$file_name}.log";
	$data = date( 'd.m.Y H:i:s', time() ) . " - " . var_export( $data, true ) . "\n";
	if ( $append ) {
		file_put_contents( $file, $data, FILE_APPEND );
	} else {
		file_put_contents( $file, $data );
	}
}

function stm_get_filter_user_fields( $response ) {
	$user_id = $response['user_id'];
	if ( ! empty ( get_userdata( $user_id ) ) ) {
		$user_info            = get_userdata( $user_id );
		$response['username'] = $user_info->user_login;
	}

	return $response;
}

add_filter( 'stm_filter_user_fields', 'stm_get_filter_user_fields', 20, 1 );