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
define( 'FINAID_THEME_CUSTOMIZER_PREFIX', 'finaid_child_theme_' );


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

	$wp_customize->add_section(
		'finaid_chatbot_section',
		array(
			'title' => 'Chatbot Settings'
		)
	);
}
add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_sections', 11 );

/**
 * Defines the controls used in the WordPress Customizer
 * @author Jim Barnes
 * @since 1.0.0
 * @return void
 */
function define_customizer_controls( $wp_customize ) {
	$wp_customize->add_setting(
		'enable_chatbot'
	);

	$wp_customize->add_control(
		'enable_chatbot',
		array(
			'label'       => 'Enable KnightBot Chatbot',
			'description' => 'When checked, the KnightBot code will be added to the site.',
			'section'     => 'finaid_chatbot_section',
			'type'        => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'chatbot_code'
	);

	$wp_customize->add_control(
		'chatbot_code',
		array(
			'label'       => 'Chatbot Code',
			'description' => 'The code to add to the site when using the Chatbot is enabled.',
			'section'     => 'finaid_chatbot_section',
			'type'        => 'textarea'
		)
	);
}
add_action( 'customize_register', __NAMESPACE__ . '\define_customizer_controls', 11 );


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
 * Moves the page WYSIWYG editor to a placeholder field within the
 * Section Fields group.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return void
 */
function acf_section_wysiwyg_position() {
?>
<script type="text/javascript">
	(function($) {
		$(document).ready(function(){
			// 5d9ca92819a1c = "Basic Section Content" Message field (placeholder)
			$('.acf-field-5d9ca92819a1c .acf-input').append( $('#postdivrich') );
		});
	})(jQuery);
</script>
<style type="text/css">
	.acf-field #wp-content-editor-tools {
		background: transparent;
		padding-top: 0;
	}
</style>
<?php
}

add_action( 'acf/input/admin_head', __NAMESPACE__ . '\acf_section_wysiwyg_position' );


/**
 * Disables ACF admin menu options on non-local development environments.
 *
 * @since 1.0.0
 * @author Jim Barnes
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
add_filter( 'option_athena_sc_enable_responsive_embeds', '__return_true' );
