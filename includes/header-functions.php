<?php
/**
 * Header template related functions
 */
namespace FinAid\Theme\Includes\Header;


/**
 * Returns the type of header to use for the given object.
 *
 * Adds logic to enable the new 'custom' header type on pages with no
 * bg img/video and custom header content.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $header_type The header type name
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The header type name
 */
function get_header_type( $header_type, $obj ) {
	$header_content_type = get_field( 'page_header_content_type', $obj );

	if ( $header_type !== 'media' && $header_content_type === 'custom' ) {
		$header_type = 'custom';
	}

	return $header_type;
}

add_filter( 'ucfwp_get_header_type', __NAMESPACE__ . '\get_header_type', 11, 2 );


/**
 * Returns header image srcs to generate a media background from
 * on the default header template ("Header Lite").
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return array An associative array of image URLs by size.  See
 *               `ucfwp_get_media_background_picture_srcs()` for expected
 *               output format
 */
function get_default_picture_srcs( $obj ) {
	$bg_images         = array();
	$fallback_image    = FINAID_THEME_IMG_URL . '/default-headers/dark-geometric.jpg';
	$fallback_image_xs = FINAID_THEME_IMG_URL . '/default-headers/dark-geometric-xs.jpg';
	// TODO retrieve from Customizer settings. Use absolute fallbacks only when necessary
	$header_image    = $fallback_image;
	$header_image_xs = $fallback_image_xs; // TODO only use fallback xs img if the regular header img is the fallback

	$bg_images['xl'] = $header_image;
	if ( $header_image_xs ) {
		$bg_images['xs'] = $header_image_xs;
	}
	$bg_images['fallback'] = $header_image;

	return $bg_images;
}


/**
 * TODO
 */
function get_default_bg_class() {
	// TODO retrieve from Customizer settings
	return 'bg-inverse';
}
