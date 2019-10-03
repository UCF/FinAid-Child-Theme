<?php
/**
 * Handle all theme configuration here
 **/
namespace FinAid\Theme\Includes\Config;


define( 'FINAID_THEME_URL', get_stylesheet_directory_uri() );
define( 'FINAID_THEME_STATIC_URL', FINAID_THEME_URL . '/static' );
define( 'FINAID_THEME_CSS_URL', FINAID_THEME_STATIC_URL . '/css' );
define( 'FINAID_THEME_JS_URL', FINAID_THEME_STATIC_URL . '/js' );
define( 'FINAID_THEME_IMG_URL', FINAID_THEME_STATIC_URL . '/img' );


/**
 * Initialization functions to be fired early when WordPress loads the theme.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return void
 */
function init() {
	// Adds support for Yoast-generated breadcrumbs.
	add_theme_support( 'yoast-seo-breadcrumbs' );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\init', 11 );


/**
 * Defines sections used in the WordPress Customizer.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return void
 */
function define_customizer_sections( $wp_customize ) {
	// Remove Navigation Settings section from UCF WP Theme since we don't
	// utilize the fallback Main Site navigation in this theme
	if ( defined( 'UCFWP_THEME_CUSTOMIZER_PREFIX' ) ) {
		$wp_customize->remove_section( UCFWP_THEME_CUSTOMIZER_PREFIX . 'nav_settings' );
	}
}
add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_sections', 11 );


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


/**
 * Adds a custom ACF WYSIWYG toolbar called 'Inline Text' that only includes
 * simple inline text formatting tools and link insertion/deletion.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $toolbars Array of toolbar information from ACF
 * @return array
 */
function acf_inline_text_toolbar( $toolbars ) {
	$toolbars['Inline Text'] = array();
	$toolbars['Inline Text'][1] = array( 'bold', 'italic', 'link', 'unlink', 'undo', 'redo' );
	return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars', __NAMESPACE__ . '\acf_inline_text_toolbar' );


/**
 * TODO
 */
if ( ! defined( 'WP_LOCAL_DEV' ) || ! WP_LOCAL_DEV ) {
    add_filter( 'acf/settings/show_admin', '__return_false' );
}


/**
 * Enable responsive embeds (handled by Athena Shortcodes Plugin)
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
add_filter( 'athena_sc_enable_responsive_embeds', '__return_true' );
