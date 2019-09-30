<?php
/**
 * Provides functions and filters related
 * to the UCF Section plugin
 */
namespace FinAid\Theme\Includes\Sections;

/**
 * Function for displaying the beginning
 * of a icon-list section
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @param string $class The class to use when displaying the section
 * @param string $title The title the display
 * @param string $section_id The section id to use
 * @return string The output
 */
function icon_list_before( $retval, $section, $class, $title, $section_id ) {
	$layout = get_field( 'finaid_section_layout', $section->ID );
	// If this isn't an icon-list, return.
	if ( $layout !== 'icon-list' ) return $retval;

	$has_content = ! empty( $section->post_content );

	ob_start();
?>
	<dl class="icon-list">
<?php
	$output = ob_get_clean();

	if ( $has_content ) {
		$retval .= apply_filters( 'the_content', $section->post_content ) . $output;
	} else {
		$retval = $output;
	}

	return $retval;
}

add_filter( 'ucf_section_display_before', __NAMESPACE__ . '\icon_list_before', 11, 5 );

/**
 * Function for displaying the beginning
 * of a icon-list section
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function icon_list_content( $retval, $section ) {
	$layout = get_field( 'finaid_section_layout', $section->ID );

	// If this isn't an icon-list, return.
	if ( $layout !== 'icon-list' ) return $retval;

	$list = get_field( 'finaid_icon_list_fields', $section->ID );

	ob_start();

	if ( isset( $list['finaid_icon_list_items'] ) && count( $list['finaid_icon_list_items'] ) > 0 ) :
		foreach( $list['finaid_icon_list_items'] as $item ) :
?>
	<dt class="align-self-center mb-2">
		<span class="fa <?php echo $item['finaid_item_icon']; ?> fa-2x text-primary mr-2"></span>
		<?php echo $item['finaid_item_label']; ?>
	</dt>
	<dd>
		<?php echo apply_filters( 'the_content', $item['finaid_item_content'] ); ?>
	</dd>
<?php
		endforeach;
	endif;

	return ob_get_clean();
}

add_filter( 'ucf_section_display', __NAMESPACE__ . '\icon_list_content', 10, 2 );

/**
 * Function for displaying the beginning
 * of a icon-list section
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function icon_list_after( $retval, $section ) {
	$layout = get_field( 'finaid_section_layout', $section->ID );

	// If this isn't an icon-list, return.
	if ( $layout !== 'icon-list' ) return $retval;

	$has_content = ! empty( $section->post_content );

	ob_start();
?>
	</dl>
<?php
	$output = ob_get_clean();

	if ( $has_content ) {
		$retval .= $output;
	} else {
		$retval = $output;
	}

	return $retval;
}

add_filter( 'ucf_section_display_after', __NAMESPACE__ . '\icon_list_after', 10, 2 );
