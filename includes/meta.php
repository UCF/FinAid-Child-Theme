<?php
/**
 * Includes functions that handle registration/enqueuing of meta tags, styles,
 * and scripts in the document head and footer.
 **/
namespace FinAid\Theme\Includes\Meta;


/**
 * Enqueue front-end css and js.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return void
 **/
function enqueue_frontend_assets() {
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );

	wp_enqueue_style( 'style-child', FINAID_THEME_CSS_URL . '/style.min.css', array( 'style' ), $theme_version );

	wp_enqueue_script( 'script-child', FINAID_THEME_JS_URL . '/script.min.js', array( 'jquery', 'script' ), $theme_version, true );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_frontend_assets', 11 );
