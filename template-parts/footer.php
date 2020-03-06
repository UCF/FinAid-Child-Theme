<?php use FinAid\Theme\Includes\Breadcrumb as Breadcrumb; ?>

<?php
    if( !is_page_template( array( 'template-right-sidebar-parent.php', 'template-right-sidebar-child.php' ) ) ) {
        echo Breadcrumb\get_breadcrumb_markup();
    }
?>

<?php require_once get_template_directory() . '/' . ucfwp_get_template_part_slug() . '/footer.php'; ?>
