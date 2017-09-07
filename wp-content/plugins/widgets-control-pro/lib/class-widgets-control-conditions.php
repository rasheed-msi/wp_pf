<?php
/**
 * widgets-control-conditions.php
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
 * @since 1.4.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Condition Engine
 */
class Widgets_Control_Conditions {

	/**
	 * Returns true if the current page is within the page hierarchy of $page.
	 * 
	 * @param string $page page id
	 * @return true if current page is within hierarchy of $page
	 */
	public static function in_page_hierarchy( $page ) {
		global $post, $wpdb, $wp_query;
		$result = false;

		// find page ID, $page is either the ID, the title or the slug
		$page_id = null;
		if ( self::is_page( $page ) ) {
			$result = true;
		} else {
			if ( $the_post = get_post( $page ) ) {
				$page_id = $the_post->ID;
			} else {
				$post_types = get_post_types( array( 'hierarchical' => true ) );
				foreach ( $post_types as $post_type ) {
					$the_post = get_page_by_path( $page, OBJECT, $post_type );
					if ( $the_post ) {
						$page_id = $the_post->ID;
						break;
					} else {
						$the_post = get_page_by_title( $page, OBJECT, $post_type );
						if ( $the_post ) {
							$page_id = $the_post->ID;
							break;
						}
					}
				}
			}
			$ancestors = get_post_ancestors( $post->ID );
			$ancestors[] = $post->ID;
			if ( in_array( $page_id, $ancestors ) ) {
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * Find out if the current page is $page. If $page is empty we consider that we ask for the current page which will always be true.
	 * Note that is_page() is of no use for us as it will only consider the condition to be met if
	 * $wp_query->is_page ...
	 *
	 * @param mixed $page Page ID, title, slug, or array of those
	 * @return true if $page is empty or $page is the ID, title, slug, ... of the current page
	 */
	public static function is_page( $page = '' ) {
		global $post;
		$result = false;
		if ( empty( $page ) ) {
			$result = true;
		} else if ( !empty( $post ) ) {
			if (
				( is_single() || is_page() ) &&
				(
					is_numeric( $page ) && ( $post->ID == $page ) ||
					( $post->post_title == $page )
				)
			) {
				$result = true;
			} else {
				$permalink = get_permalink( $post->ID );
				$prefix = home_url();
				$slug = substr( $permalink, strlen( $prefix ) );
				$slug = ltrim( rtrim( $slug, '/'), '/' );
				$page = ltrim( rtrim( $page, '/'), '/' );
				if ( $slug == $page ) {
					$result = true;
				}
			}
		}
		return $result;
	}

	/**
	 * Determines TRUE if the current page matches. Used to show or not sidebars and widgets on a page.
	 * @param string $condition evaluation condition
	 * @param string $pages pages expression
	 */
	public static function evaluate_display_condition( $condition, $pages ) {
		global $post;
		$show = false;
		if ( empty( $condition ) || !$condition || ( $condition == WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES ) ) {
			$show = true;
		}
		if ( ! $show ) {
			$pages = explode( "\n", $pages ); // must use "
			if ( is_array( $pages ) ) {
				// Conditions are interpreted as follows :
				// ci = any condition that can be matched (no ! prefix)
				// dj = any condition that must not be matched (is prefixed by !)
				// Conditions are fulfilled, if any of ci are matched and
				// none of dj are matched.
				// ( c1 v c2 ... v cn ) ^ -d1 ^ -d2 ... ^ -dm
				// <=> ( c1 v c2 ... v cn ) ^ -( d1 v d2 v ... dm )
				// ... reflected as $disj_result && ! $conj_result below
				$pages = array_map( 'trim', $pages );
				$disj = array();
				$conj = array();
				foreach( $pages as $page ) {
					if ( strpos( $page, '!' ) === 0 ) {
						$page = substr( $page, 1 );
						$conj[] = $page;
					} else {
						$disj[] = $page;
					}
				}
				$split = count( $disj );
				$new_pages = array_merge( $disj, $conj );
				$pages = $new_pages;
				$disj_result = array_map( array( __CLASS__,'match' ), $disj );
				$disj_result[] = false;
				$conj_result = array_map( array( __CLASS__,'match' ), $conj );
				$conj_result[] = false;
				// or
				$disj_result = in_array( true, $disj_result, true );
				// or
				$conj_result = in_array( true, $conj_result, true );
				// disj && ! conj ... any that can match but none of those that must not match
				$result = $disj_result && !$conj_result;
				switch ( $condition ) {
					case WIDGETS_PLUGIN_SHOW_ON_THESE_PAGES :
						$show = $result;
						break;
					case WIDGETS_PLUGIN_SHOW_NOT_ON_THESE_PAGES :
						$show = ! $result;
						break;
				}
			}
		}
		return $show;
	}

	/**
	 * Check if the current page is a match.
	 * 
	 * @param string $page token, title, ID, ...
	 * @return boolean returns true if the current page matches
	 */
	public static function match( $page ) {
		$page = trim( $page );
		$match = false;
		// token?
		if ( ( strpos( $page, '[' ) === 0 ) && ( strrpos( $page, ']' ) === strlen( $page ) - 1 ) ) {
			// strip tokenizers
			$page = trim ( substr( $page, 1, strlen( $page ) - 2 ) );
			// decompose
			$page_params = explode( ':', $page );
			$token = isset( $page_params[0] ) ? trim( $page_params[0] ) : null;
			$value = isset( $page_params[1] ) ? trim( $page_params[1] ) : null;
			$value2 = isset( $page_params[2] ) ? trim( $page_params[2] ) : null;
			switch ( $token ) {
				case 'home' :
					$match = is_home();
					break;
				case 'front' :
					$match = is_front_page();
					break;
				case 'single' :
					$match = is_single();
					break;
				case 'type' :
					$types = array_map( 'trim', explode( ',', $value ) );
					foreach( $types as $type ) {
						$current_post_type = get_post_type();
						if ( $type == $current_post_type ) {
							$match = true;
							break;
						}
					}
					break;
				case 'page' :
					$match = is_page();
					break;
				case 'category' :
					if ( ! empty( $value ) ) {
						$match = is_category( $value );
					} else {
						$match = is_category();
					}
					break;
				case 'has_term' :
					if ( !empty( $value ) ) {
						if ( !empty( $value2 ) ) {
							$match = has_term( $value, $value2 );
						} else {
							$match = has_term( $value, 'category' ) || has_term( $value, 'post_tag' );
						}
					}
					break;
				case 'tag' :
					if ( ! empty ( $value ) ) {
						$match = is_tag( $value );
					} else {
						$match = is_tag();
					}
					break;
				case 'tax' :
					if ( ! empty( $value ) ) {
						$taxonomies = array_map( 'trim', explode( ',', $value ) );
						foreach( $taxonomies as $taxonomy ) {
							if ( empty( $value2 ) ) {
								switch( $taxonomy ) {
									case 'category' :
										$match = is_category();
										break;
									case 'tag' :
										$match = is_tag();
										break;
									default :
										$match = is_tax( $taxonomy );
								}
							} else {
								$terms = array_map( 'trim', explode( ',', $value2 ) );
								switch( $taxonomy ) {
									case 'category' :
										$match = is_category( $terms );
										break;
									case 'tag' :
										$match = is_tag( $terms );
										break;
									default :
										$match = is_tax( $taxonomy, $terms );
								}
							}
							if ( $match ) {
								break;
							}
						}
					} else {
						$match = is_tax() || is_category() || is_tag();
					}
					break;
				case 'author' :
					if ( ! empty( $value) ) {
						$match = is_author( $value );
					} else {
						$match = is_author();
					}
					break;
				case 'archive' :
					if ( empty( $value ) ) {
						$match = is_archive();
					} else {
						$types = array_map( 'trim', explode( ',', $value ) );
						$match = is_post_type_archive( $types );
					}
					break;
				case 'search' :
					$match = is_search();
					break;
				case '404' :
					$match = is_404();
					break;
				case 'language' :
					if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
						$languages = array_map( 'trim', explode( ',', $value ) );
						if ( in_array( ICL_LANGUAGE_CODE, $languages ) ) {
							$match = true;
						}
					}
					break;
				case 'role' :
					$roles = array_map( 'trim', explode( ',', $value ) );
					foreach( $roles as $role ) {
						if ( current_user_can( $role ) ) {
							$match = true;
							break;
						}
					}
					break;
				case 'group' :
					if ( class_exists( 'Groups_Group' ) && method_exists( 'Groups_Group', 'read_by_name' ) ) {
						$groups = array_map( 'trim', explode( ',', $value ) );
						$user_id = get_current_user_id();
						foreach( $groups as $group ) {
							if ( $group_object = Groups_Group::read_by_name( $group ) ) {
								if ( Groups_User_Group::read( $user_id , $group_object->group_id ) ) {
									$match = true;
									break;
								}
							}
						}
					}
					break;
			}
		} else {
			$page = trim( $page );
			if ( strcmp( "/*", substr( $page, strlen( $page ) - 2, 2 ) ) == 0 ) {
				$match = self::in_page_hierarchy( substr( $page, 0, strlen( $page ) - 2 ) );
			} else {
				$match = self::is_page( $page );
			}
		}
		return $match;
	}

}
