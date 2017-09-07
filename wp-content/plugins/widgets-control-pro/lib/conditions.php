<?php
/**
 * conditions.php
 *
 * Copyright (c) www.itthinx.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package widgets-control-pro
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Old functions maintained for backwards-compatibility.

/**
 * Returns true if the current page is within the page hierarchy of $page.
 * 
 * @param string $page page id
 * @return true if current page is within hierarchy of $page
 */
function _widgets_plugin_in_page_hierarchy( $page ) {
	return Widgets_Control_Conditions::in_page_hierarchy( $page );
}

/**
 * Find out if the current page is $page. If $page is empty we consider that we ask for the current page which will always be true.
 * Note that is_page() is of no use for us as it will only consider the condition to be met if
 * $wp_query->is_page ...
 *
 * @param mixed $page Page ID, title, slug, or array of those
 * @return true if $page is empty or $page is the ID, title, slug, ... of the current page
 */
function _widgets_plugin_is_poge( $page = '' ) {
	return Widgets_Control_Conditions::is_page();
}

/**
 * Determines TRUE if the current page matches. Used to show or not sidebars and widgets on a page.
 * @param string $condition evaluation condition
 * @param string $pages pages expression
 */
function _widgets_plugin_evaluate_display_condition( $condition, $pages ) {
	return Widgets_Control_Conditions::evaluate_display_condition( $condition, $pages );
}
