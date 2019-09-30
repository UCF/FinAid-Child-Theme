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
 */
function init() {
	// Adds support for Yoast-generated breadcrumbs.
	add_theme_support( 'yoast-seo-breadcrumbs' );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\init', 11 );


