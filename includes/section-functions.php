<?php
/**
 * Provides functions and filters related
 * to the UCF Section plugin
 */
namespace FinAid\Theme\Includes\Sections;


/**
 * Define a global that stores the number of times lists
 * and unique headings are used on the current page.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
global $finaid_section_lists;
if ( ! is_array( $finaid_section_lists ) ) {
	$finaid_section_lists = array(
		'all-headings' => array(),
		'all-lists'    => array()
	);
}


/**
 * Returns the number of times the given list has been included
 * on the current page (including the provided list object)
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param WP_Post $section Section object
 * @param bool $increment Whether or not the list count should be incremented
 *             by 1 when this function is called
 * @return int The number of times the list has been included on the page
 */
function get_list_count( $section, $increment=true ) {
	global $finaid_section_lists;
	$count = 0;

	if ( isset( $finaid_section_lists['all-lists'][$section->ID] ) ) {
		if ( $increment ) {
			$finaid_section_lists['all-lists'][$section->ID]++;
		}
		$count = $finaid_section_lists['all-lists'][$section->ID];
	}
	else {
		$count = 1;
		$finaid_section_lists['all-lists'][$section->ID] = $count;
	}

	return $count;
}


/**
 * Increments heading counts, and returns a unique heading ID attribute
 * value for a list item within a list's `have_rows()` loop.
 *
 * NOTE: This function MUST be called only within a `have_rows()` loop.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param WP_Post $section Section object
 * @param int $section_count Current count for the number of the given Section
 *                           objects on the page
 * @param bool $increment Whether or not the heading count should be
 *                        incremented by 1 when this function is called
 * @return string Heading ID attr value
 */
function get_list_item_heading_id( $section, $section_count, $increment=true ) {
	$heading_id = '';
	if ( get_field( 'list_content_type', $section ) !== 'headings' ) { return $heading_id; }

	global $finaid_section_lists;
	$heading_slug      = sanitize_title( get_sub_field( 'heading' ) );
	$heading_id        = $heading_slug;
	$section_count_key = 'list-' . $section->ID . '-' . $section_count;
	$heading_count     = 0;

	// Get + optionally increment all headings count
	if ( isset( $finaid_section_lists['all-headings'][$heading_slug] ) ) {
		if ( $increment ) {
			$finaid_section_lists['all-headings'][$heading_slug]++;
		}
		$heading_count = $finaid_section_lists['all-headings'][$heading_slug];
	}
	else {
		$heading_count = 1;
		$finaid_section_lists['all-headings'][$heading_slug] = $heading_count;
	}

	// Increment section + count-specific heading count
	if ( isset( $finaid_section_lists[$section_count_key][$heading_slug] ) ) {
		if ( $increment ) {
			$finaid_section_lists[$section_count_key][$heading_slug]++;
		}
	}
	else {
		$finaid_section_lists[$section_count_key][$heading_slug] = $heading_count;
	}

	// Append an increment to $heading_id if there are more
	// than one of this heading present:
	if ( $finaid_section_lists[$section_count_key][$heading_slug] > 1 ) {
		$heading_id = $heading_id . '-' . $finaid_section_lists[$section_count_key][$heading_slug];
	}

	return $heading_id;
}


/**
 * Returns whether or not the given section is a headings list.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param WP_Post $section Section object
 * @return bool
 */
function is_headings_list( $section ) {
	return (
		get_field( 'section_layout', $section ) === 'list'
		&& get_field( 'list_content_type', $section ) === 'headings'
	);
}


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
		|| ! have_rows( 'list_item', $section )
	) {
		return $retval;
	}

	$section_count     = get_list_count( $section );
	$list_content_type = get_field( 'list_content_type', $section );
	$heading_elem      = $list_content_type === 'headings' ? get_field( 'list_heading_level', $section ) : null;
	$bullet_color      = get_field( 'list_bullet_color', $section );

	while( have_rows( 'list_item', $section ) ) : the_row();
		$content    = '<div class="icon-list-item-content">' . get_sub_field( 'content' ) . '</div>';
		$icon_class = 'icon-list-bullet';

		if ( $list_content_type === 'headings' ) {
			// Append headings to inner list item content
			$heading_id      = get_list_item_heading_id( $section, $section_count );
			$heading_content = wptexturize( get_sub_field( 'heading' ) );
			$heading         = "<$heading_elem class=\"icon-list-item-heading\" id=\"$heading_id\">$heading_content</$heading_elem>";
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
 * Returns anchor navigation for a list with headings.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param WP_Post $section Section object
 * @param int $section_count Current count for the number of the given Section objects on the page
 * @return string HTML markup
 */
function display_list_nav( $section, $section_count ) {
	if (
		! is_headings_list( $section )
		|| ! have_rows( 'list_item', $section )
	) {
		return '';
	}

	ob_start();
?>
<ul class="section-list-nav">
	<?php
	while( have_rows( 'list_item', $section ) ) : the_row();
		$heading_id      = get_list_item_heading_id( $section, $section_count, false );
		$heading_content = wptexturize( wp_strip_all_tags( get_sub_field( 'heading' ) ) );
	?>
	<li>
		<a href="#<?php echo $heading_id; ?>">
			<?php echo $heading_content; ?>
		</a>
	</li>
	<?php endwhile; ?>
</ul>
<?php
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


/**
 * Adds helpful columns to the All Sections admin view.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $columns Column data
 * @return array Modified column data
 */
function add_columns( $columns ) {
	$columns['layout'] = 'Layout';
	return $columns;
}

add_filter( 'manage_ucf_section_posts_columns', __NAMESPACE__ . '\add_columns' );
add_filter( 'manage_edit-ucf_section_sortable_columns', __NAMESPACE__ . '\add_columns' );


/**
 * Adds data to columns in the All Sections admin view.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param string $column Column name
 * @param int $post_id Post ID
 * @return void
 */
function add_column_data( $column, $post_id ) {
	switch ( $column ) {
		case 'layout':
			$field = get_field_object( 'field_5d9239967f3ed' ); // section_layout key
			$layout = get_field( 'section_layout', $post_id ) ?: 'default';
			echo $field['choices'][$layout];
			break;
	}
}

add_action( 'manage_ucf_section_posts_custom_column', __NAMESPACE__ . '\add_column_data', 10, 2 );


/**
 * Adds sorting capabilities to custom admin columns.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $vars Query vars
 * @return array Modified query vars
 */
function add_column_ordering( $vars ) {
	if (
		isset( $vars['post_type'] )
		&& $vars['post_type'] === 'ucf_section'
		&& isset( $vars['orderby'] )
		&& $vars['orderby'] === 'Layout'
	) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'section_layout',
			'orderby'  => 'meta_value'
		) );
	}

	return $vars;
}

add_filter( 'request', __NAMESPACE__ . '\add_column_ordering' );
