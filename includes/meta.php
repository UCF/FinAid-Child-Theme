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
 * Enqueue TinyMCE editor styles
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function enqueue_editor_assets() {
	// If debug mode is enabled, force editor stylesheets to
	// reload on every page refresh.  Caching of these stylesheets
	// is very aggressive
	$cache_bust = '';
	if ( WP_DEBUG === true ) {
		$cache_bust = date( 'YmdHis' );
	}
	else {
		$theme = wp_get_theme();
		$cache_bust = $theme->get( 'Version' );
	}

	add_editor_style( FINAID_THEME_CSS_URL . '/editor.min.css?v=' . $cache_bust );
}

add_action( 'init', __NAMESPACE__ . '\enqueue_editor_assets', 99 ); // Enqueue late to ensure styles are enqueued after Athena SC Plugin's styles


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
