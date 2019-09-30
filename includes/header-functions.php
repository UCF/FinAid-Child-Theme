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
