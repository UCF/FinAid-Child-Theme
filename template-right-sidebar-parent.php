<?php

use FinAid\Theme\Includes\RightSidebar as RightSidebar;

/**
 * Template Name: Right Sidebar Parent
 * Template Post Type: page
 */

get_header();
the_post();

echo RightSidebar\get_right_sidebar_template_markup( $post, null );

get_footer();

?>