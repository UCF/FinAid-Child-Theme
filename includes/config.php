<?php
/**
 * Handle all theme configuration here
 **/

define( 'FINAID_THEME_URL', get_stylesheet_directory_uri() );
define( 'FINAID_THEME_STATIC_URL', FINAID_THEME_URL . '/static' );
define( 'FINAID_THEME_CSS_URL', FINAID_THEME_STATIC_URL . '/css' );
define( 'FINAID_THEME_JS_URL', FINAID_THEME_STATIC_URL . '/js' );
define( 'FINAID_THEME_IMG_URL', FINAID_THEME_STATIC_URL . '/img' );

/**
 * Adds the ACF folder within the child theme
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $path The path to the ACF folder
 * @return string
 */
function add_acf_save_path( $path ) {
	$path = get_stylesheet_directory() . '/dev/acf';

	return $path;
}

add_filter( 'acf/settings/save_json', __NAMESPACE__ . '\add_acf_save_path', 10, 1 );

/**
 * Adds the ACF folder within the child theme
 * to the ACF load paths variable
 * @author Jim Barnes
 * @since 1.0.0
 * @param array $paths The path array
 * @return array
 */
function add_acf_load_path( $paths ) {
	unset( $paths[0] );

	$paths[] = get_stylesheet_directory() . '/dev/acf';

	return $paths;
}

add_filter( 'acf/settings/load_json', __NAMESPACE__ . '\add_acf_load_path', 10, 1 );
