<?php
/**
 * Template Name: List
 * Template Post Type: page
 */
?>
<?php
use FinAid\Theme\Includes\Sections;

get_header(); the_post();

$list = get_field( 'list' );
if ( $list ):
	$list_markup = Sections\display_list( $list );
	$list_count  = Sections\get_list_count( $list, false );
	$list_nav    = Sections\display_list_nav( $list, $list_count );
?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<div class="row">
			<div class="col-lg-4 p-3 p-md-4 mb-4 mb-sm-5 mb-lg-0 bg-faded sticky-top">
				<?php echo $list_nav; ?>
			</div>
			<div class="col-lg-8">
				<?php echo $list_markup; ?>
			</div>
		</div>
	</div>
</article>

<?php endif; ?>

<?php get_footer(); ?>
