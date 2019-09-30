<?php use FinAid\Theme\Includes\Breadcrumb as Breadcrumb; ?>

<?php echo Breadcrumb\get_breadcrumb_markup(); ?>

<?php require_once get_template_directory() . '/' . ucfwp_get_template_part_slug() . '/footer.php'; ?>
