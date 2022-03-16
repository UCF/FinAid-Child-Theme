<?php
/**
 * Header template related functions
 */
namespace FinAid\Theme\Includes\RightSidebar;



/**
 * Overides the subtitle in the header.
 *
 * @since 1.0.0
 * @author RJ Bruneel
 * @return string Filter to overide subtitle in the header.
 */

    function ucf_header_sidebar_subtitle( $subtitle, $obj  ) {
        if( is_page_template( array( 'template-right-sidebar-child.php' ) ) ) {
            $subtitle = get_the_title($obj->post_parent);
        }
        return $subtitle;
    }

    add_filter( 'ucfwp_get_header_subtitle_before', __NAMESPACE__ . '\ucf_header_sidebar_subtitle', 10, 2 );

/**
 * Returns right sidebar template markup.
 *
 * @since 1.0.0
 * @author RJ Bruneel
 * @return string HTML markup for the right sidebar template
 */
function get_right_sidebar_template_markup( $post, $parent_post ) {
    ob_start();
?>
    <article class="<?php echo $post->post_status; ?> container mb-5 pb-sm-4">
        <div class="row">
            <div class="col-auto hidden-md-down mt-4 mt-sm-5 push-lg-8">
                <hr class="hr-vertical">
            </div>
            <div class="col-lg-3 mt-4 mt-sm-5 mt-lg-0 mb-4 mb-lg-0 push-lg-8 text-center text-md-left">
                <?php echo get_right_sidebar_markup( $post, $parent_post ); ?>
            </div>
            <div class="col-lg-8 mt-4 mt-sm-5 pull-lg-4">
                <?php the_content(); ?>
            </div>
        </div>
    </article>
<?php
    return ob_get_clean();
}


/**
 * Returns right sidebar markup.
 *
 * @since 1.0.0
 * @author RJ Bruneel
 * @return string HTML markup for the right sidebar
 */
function get_right_sidebar_markup( $post, $parent_post ) {
    ob_start();

    $id = ( $parent_post ) ? $parent_post->ID : $post->ID;
    $right_sidebar_header = get_field( 'right_sidebar_header', $id );
    $right_sidebar_menu = get_field( 'right_sidebar_menu', $id );
?>
    <div class="sticky-top pt-lg-5">
        <?php if( $right_sidebar_header ) : ?>
            <h2 class="h6 text-uppercase mt-2 mb-4" id="right-sidebar-nav" aria-label="Page navigation">
                <?php echo $right_sidebar_header; ?>
            </h2>
        <?php endif; ?>
        <?php if( $right_sidebar_menu && count($right_sidebar_menu) > 0 ) : ?>
			<nav aria-labelledby="right-sidebar-nav">
				<ul class="nav flex-column">
					<?php
						foreach( $right_sidebar_menu as $menu_item ):
							$menu_item = $menu_item['right_sidebar_menu_item'];
							$active = ( $post->ID === $menu_item->ID ) ? ' active' : '';
					?>
						<li class="nav-item">
							<a href="<?php echo get_permalink( $menu_item->ID ); ?>"
								class="nav-link<?php echo $active; ?>">
								<?php echo $menu_item->post_title; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
        <?php endif; ?>
    </div>
<?php
    return ob_get_clean();
}

 ?>
