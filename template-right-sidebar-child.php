<?php

use FinAid\Theme\Includes\RightSidebar as RightSidebar;

/**
 * Template Name: Right Sidebar Child
 * Template Post Type: page
 */

get_header();
the_post();

$parent_post = get_post( $post->post_parent );

if( $parent_post ) {
    echo RightSidebar\get_right_sidebar_template_markup( $post, $parent_post );
}

get_footer();

?>