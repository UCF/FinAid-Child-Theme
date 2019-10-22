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

	// Initialize Scrollspy on the List page template.
	if ( ! is_admin() && is_page_template( 'template-list.php' ) ) {
		wp_add_inline_script( 'script-child', get_scrollspy_init_script( '.affixed-sidebar .nav' ) );
	}
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_frontend_assets', 11 );


/**
 * Enqueue admin styles and scripts
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function enqueue_admin_assets( $hook ) {
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );

	wp_enqueue_style( 'admin-style-child', FINAID_THEME_CSS_URL . '/admin.min.css', array( 'acf-input' ), $theme_version );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_assets', 11, 1 );


/**
 * Returns an inline script that initializes Scrollspy on the given nav.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $target Selector that specifies a nav to target
 * @param string $spy Selector that specifies the element that should be spied against
 * @return string Inline script
 */
function get_scrollspy_init_script( $target, $spy='body' ) {
	return "jQuery('$spy').scrollspy({ target: '$target' });";
}
