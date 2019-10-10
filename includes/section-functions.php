<?php
/**
 * Provides functions and filters related
 * to the UCF Section plugin
 */
namespace FinAid\Theme\Includes\Sections;


/**
 * Returns opening markup around a numbered list.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @param string $class The class to use when displaying the section
 * @param string $title The title the display
 * @param string $section_id The section id to use
 * @return string The output
 */
function display_numbered_list_before( $retval, $section, $class, $title, $section_id ) {
	return '<ol class="section-list section-numbered-list">';
}

/**
 * Returns inner content of a numbered list.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function display_numbered_list_content( $retval, $section ) {
	return '';
}

/**
 * Returns the closing markup of a numbered list.
 *
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function display_numbered_list_after( $retval, $section ) {
	return '</ol>';
}


/**
 * Returns opening markup around an icon list.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @param string $class The class to use when displaying the section
 * @param string $title The title the display
 * @param string $section_id The section id to use
 * @return string The output
 */
function display_icon_list_before( $retval, $section, $class, $title, $section_id ) {
	return '<ul class="section-list section-icon-list">';
}

/**
 * Returns inner content of an icon list.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function display_icon_list_content( $retval, $section ) {
	return '';
}

/**
 * Returns the closing markup of an icon list.
 *
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function display_icon_list_after( $retval, $section ) {
	return '</ul>';
}


/**
 * Returns opening markup around a checklist.
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
function display_checklist_before( $retval, $section, $class, $title, $section_id ) {
	return '<ul class="section-list section-checklist">';
}

/**
 * Returns inner content of a checklist.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function display_checklist_content( $retval, $section ) {
	return '';
}

/**
 * Returns the closing markup of a checklist.
 *
 * @author Jim Barnes
 * @since 1.0.0
 * @param string $retval The unfiltered return value
 * @param WP_Post $section The section object
 * @return string The output
 */
function display_checklist_after( $retval, $section ) {
	return '</ul>';
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
			$list_type = get_field( 'section_layout', $section );
			switch ( $list_type ) {
				case 'numbered-list':
					$retval = display_numbered_list_before( $retval, $section, $class, $title, $section_id );
					break;
				case 'icon-list':
					$retval = display_icon_list_before( $retval, $section, $class, $title, $section_id );
					break;
				case 'checklist':
				default:
					$retval = display_checklist_before( $retval, $section, $class, $title, $section_id );
					break;
			}
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
			$list_type = get_field( 'section_layout', $section );
			switch ( $list_type ) {
				case 'numbered-list':
					$retval = display_numbered_list_content( $retval, $section );
					break;
				case 'icon-list':
					$retval = display_icon_list_content( $retval, $section );
					break;
				case 'checklist':
				default:
					$retval = display_checklist_content( $retval, $section );
					break;
			}
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
			$list_type = get_field( 'section_layout', $section );
			switch ( $list_type ) {
				case 'numbered-list':
					$retval = display_numbered_list_after( $retval, $section );
					break;
				case 'icon-list':
					$retval = display_icon_list_after( $retval, $section );
					break;
				case 'checklist':
				default:
					$retval = display_checklist_after( $retval, $section );
					break;
			}
			break;
		case 'default':
		default:
			break;
	}

	return $retval;
}

add_filter( 'ucf_section_display_after', __NAMESPACE__ . '\display_section_after', 11, 2 );
