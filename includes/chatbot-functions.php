<?php
/**
 * Provides functions needed for getting
 * the chatbot onto pages.
 */
namespace FinAid\Theme\Includes\Chatbot;

/**
 * Returns the chatbot markup if it is enabled.
 * @author Jim Barnes
 * @since 1.0.0
 * @return string
 */
function get_chatbot_markup() {
	$chatbot_enabled = get_theme_mod( 'chatbot_enabled', false );
	$chatbot_markup  = get_theme_mod( 'chatbot_code', false );

	ob_start();
	if ( $chatbot_enabled && ! empty( $chatbot_markup ) ) :
		echo $chatbot_markup;
	endif;
	return ob_get_clean();
}

/**
 * Adds the chatbot markup to the footer
 * @author Jim Barnes
 * @since 1.0.0
 * @return string
 */
function add_chatbot_to_footer() {
	echo get_chatbot_markup();
}

add_action( 'wp_footer', __NAMESPACE__ . '\add_chatbot_to_footer', 10, 0 );
