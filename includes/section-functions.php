<?php
/**
 * Provides functions and filters related
 * to the UCF Section plugin
 */
namespace FinAid\Theme\Includes\Sections;


/**
 * Generic function that returns markup for a single list item
 * in any type of icon list  (sections with layout = 'list')
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @return string HTML markup for the list item
 */
function display_list_item( $content, $icon_class='' ) {
	ob_start();
?>
<li class="icon-list-item">
	<?php if ( $icon_class ) : ?>
	<span class="<?php echo $icon_class; ?>" aria-hidden="true"></span>
	<?php endif; ?>

	<?php echo $content; ?>
</li>
<?php
	return trim( ob_get_clean() );
}


/**
 * Returns markup for all list items in an icon list
 * (sections with layout = 'list')
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param WP_Post $section Section object
 * @return string HTML markup
 */
function display_list_items( $section ) {
	$retval = '';

	// Back out early if this section isn't a list, or is empty:
	if (
		get_field( 'section_layout', $section ) !== 'list'
		|| ! have_rows( 'list_item' )
	) {
		return $retval;
	}

	$list_content_type = get_field( 'list_content_type', $section );
	$heading_elem      = $list_content_type === 'headings' ? get_field( 'list_heading_level', $section ) : null;
	$bullet_color      = get_field( 'list_bullet_color', $section );

	while( have_rows( 'list_item' ) ) : the_row();
		$content    = '<div class="icon-list-item-content">' . get_sub_field( 'content' ) . '</div>';
		$icon_class = 'icon-list-bullet';

		if ( $list_content_type === 'headings' ) {
			// Append headings to inner list item content
			$heading_content = wptexturize( get_sub_field( 'heading' ) );
			$heading         = "<$heading_elem class=\"icon-list-item-heading\">$heading_content</$heading_elem>";
			$content         = $heading . $content;
		}

		// Determine necessary CSS classes for individual list item bullets
		switch ( get_field( 'list_type' ) ) {
			case 'numbered-list':
				$icon_class .= ' icon-list-bullet-number';
				switch ( $bullet_color ) {
					case 'secondary':
						$icon_class .= ' bg-inverse';
						break;
					case 'inverse':
						$icon_class .= ' bg-secondary';
						break;
					default:
						$icon_class .= " bg-$bullet_color";
						break;
				}
				break;
			case 'icon-list':
				$icon_class .= ' icon-list-bullet-fonticon fa';
				$icon_class .= ' ' . get_sub_field( 'icon' );
				$icon_class .= " text-$bullet_color";
				break;
			case 'checklist':
			default:
				$icon_class .= ' icon-list-bullet-fonticon fa fa-check-square-o';
				$icon_class .= " text-$bullet_color";
				break;
		}

		// Finally, generate the list item markup, and append it to our list
		$retval .= display_list_item( $content, $icon_class );
	endwhile;

	return $retval;
}


/**
 * Returns opening markup for any type of icon list
 * (sections with layout = 'list')
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param WP_Post $section Section object
 * @return string HTML markup
 */
function display_list_before( $section ) {
	$retval = '';
	if ( get_field( 'section_layout', $section ) !== 'list' ) {
		return $retval;
	}

	$list_type         = get_field( 'list_type', $section );
	$list_content_type = get_field( 'list_content_type', $section );
	$css_classes       = 'section-list icon-list';

	if ( $list_content_type === 'headings' ) {
		$css_classes .= ' icon-list-lg';
	}

	switch ( $list_type ) {
		case 'numbered-list':
			$css_classes .= ' icon-list-numbered';
			$retval = "<ol class=\"$css_classes\">";
			break;
		case 'icon-list':
			$css_classes .= ' icon-list-custom';
			$retval = "<ul class=\"$css_classes\">";
			break;
		case 'checklist':
		default:
			$css_classes .= ' icon-list-checkbox';
			$retval = "<ul class=\"$css_classes\">";
			break;
	}

	return $retval;
}


/**
 * Returns closing markup for any type of icon list
 * (sections with layout = 'list')
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param WP_Post $section Section object
 * @return string HTML markup
 */
function display_list_after( $section ) {
	$retval = '';
	if ( get_field( 'section_layout', $section ) !== 'list' ) {
		return $retval;
	}

	$list_type = get_field( 'list_type', $section );
	switch ( $list_type ) {
		case 'numbered-list':
			$retval = '</ol>';
			break;
		default:
			$retval = '</ul>';
			break;
	}

	return $retval;
}


/**
 * Returns complete markup for an icon list (sections with layout = 'list')
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param WP_Post $section Section object
 * @return string HTML markup
 */
function display_list( $section ) {
	ob_start();

	echo display_list_before( $section );
	echo display_list_items( $section );
	echo display_list_after( $section );

	return trim( ob_get_clean() );
}


/**
 * Returns opening markup around a section, depending on the
 * given section's layout.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @param string $class The class to use when displaying the section
 * @param string $title The title the display
 * @param string $section_id The section id to use
 * @return string HTML markup
 */
function display_section_before( $retval, $section, $class, $title, $section_id ) {
	$layout = get_field( 'section_layout', $section );
	switch ( $layout ) {
		case 'list':
			$retval = display_list_before( $section );
			break;
		case 'default':
		default:
			break;
	}

	return $retval;
}

add_filter( 'ucf_section_display_before', __NAMESPACE__ . '\display_section_before', 11, 5 );


/**
 * Returns inner section markup, depending on the given section's layout.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string HTML markup
 */
function display_section_content( $retval, $section ) {
	$layout = get_field( 'section_layout', $section );
	switch ( $layout ) {
		case 'list':
			$retval = display_list_items( $section );
			break;
		case 'default':
		default:
			break;
	}

	return $retval;
}

add_filter( 'ucf_section_display', __NAMESPACE__ . '\display_section_content', 11, 2 );


/**
 * Returns closing markup around a section, depending on the
 * given section's layout.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string HTML markup
 */
function display_section_after( $retval, $section ) {
	$layout = get_field( 'section_layout', $section );
	switch ( $layout ) {
		case 'list':
			$retval = display_list_after( $section );
			break;
		case 'default':
		default:
			break;
	}

	return $retval;
}

add_filter( 'ucf_section_display_after', __NAMESPACE__ . '\display_section_after', 11, 2 );
