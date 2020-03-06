<?php
/**
 * Modified default header template (aka "Header Lite"), used when
 * no header image/video is selected for the page, and the page meta
 * "Header Content - Type of Content" is set to "Title and subtitle".
 *
 * This header is very similar to the UCF WP Theme's `header-media.php`,
 * but uses alternate classes on the top-most wrapper element, and excludes
 * the `.header-media-controlfix` element at the bottom of the header.
 */

use FinAid\Theme\Includes\Header;

$obj            = ucfwp_get_queried_object();
$exclude_nav    = get_field( 'page_header_exclude_nav', $obj );
$bg_image_srcs  = Header\get_default_picture_srcs( $obj );
$bg_class       = Header\get_default_bg_class( $obj );
?>

<div class="header-default mb-0 d-flex flex-column <?php echo $bg_class; ?>">
    <div class="header-media-background-wrap">
        <div class="header-media-background media-background-container">
			<?php echo ucfwp_get_media_background_picture( $bg_image_srcs ); ?>
        </div>
    </div>

    <?php
    // Display the site nav
    if ( ! $exclude_nav ) { echo ucfwp_get_nav_markup(); }
    ?>

    <?php
    // Display the inner header contents
    ?>
    <div class="header-content">
        <div class="header-content-flexfix">
            <?php echo ucfwp_get_header_content_markup(); ?>
        </div>
    </div>
</div>
