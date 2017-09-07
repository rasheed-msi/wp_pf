<?php
/**
 * class-widgets-control-sidebars.php
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
 * Sidebar display control.
 */
class Widgets_Control_Sidebars {

	/**
	 * authentication nonce
	 * @var string
	 */
	const NONCE = 'widgets-control-nonce';

	/**
	 * Adds actions.
	 */
	public static function init() {
		add_filter( 'sidebars_widgets', array( __CLASS__, 'sidebars_widgets' ), 10, 1 );
		add_action( 'dynamic_sidebar_after', array( __CLASS__, 'dynamic_sidebar_after' ), 10, 2 );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_footer', array( __CLASS__, 'admin_footer' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
		add_action(
			'wp_ajax_widgets_control_sidebar_visibility',
			array( __CLASS__, 'widgets_control_sidebar_visibility' )
		);
		add_action(
			'wp_ajax_widgets_control_sidebar_save',
			array( __CLASS__, 'widgets_control_sidebar_save' )
		);
		add_action( 'sidebar_admin_setup', array( __CLASS__, 'sidebar_admin_setup' ) );
	}

	/**
	 * Registers admin script.
	 */
	public static function admin_init() {
		wp_register_script( 'widgets-control-admin', WIDGETS_PLUGIN_URL . '/js/widgets-control-admin.js', array( 'jquery' ), WIDGETS_PLUGIN_VERSION, true );
	}

	/**
	 * Enqueues admin script on widgets screen.
	 * 
	 * @param string $hook_suffix
	 */
	public static function admin_enqueue_scripts( $hook_suffix ) {
		if ( $hook_suffix == 'widgets.php' ) {
			wp_enqueue_script( 'widgets-control-admin' );
		}
	}

	/**
	 * Sets visibility option (display our settings or not on a sidebar).
	 */
	public static function widgets_control_sidebar_visibility() {
		if ( check_ajax_referer( 'widgets-control-ajax-nonce', 'widgets_control_ajax_nonce' ) ) {
			if (!empty($_REQUEST['sidebar'])) {
				$sidebar = !empty( $_REQUEST['sidebar'] ) ? stripslashes( $_REQUEST['sidebar'] ) : '';
				$ss = explode( ' ', $sidebar );
				$sidebar = null;
				foreach ( $ss as $s ) {
					if ( strpos( $s, '-visibility' ) !== false ) {
						$sidebar = str_replace( '-visibility', '', $s );
						break;
					}
				}
				if ( $sidebar !== null ) {
					$visibility = !empty( $_REQUEST['visibility'] ) ? stripslashes( $_REQUEST['visibility'] ) : null;
					$visibility = filter_var( $visibility, FILTER_VALIDATE_BOOLEAN );
					$settings = widgets_plugin_get_widget_settings();
					$settings['sidebars'][$sidebar]['visibility'] = $visibility;
					widgets_plugin_set_widget_settings($settings);
				}
			}
		}
	}

	/**
	 * Saves our settings for a sidebar.
	 */
	public static function widgets_control_sidebar_save() {
		if ( check_ajax_referer( 'widgets-control-ajax-nonce', 'widgets_control_ajax_nonce' ) ) {
			if ( !empty($_REQUEST['sidebars'] ) ) {
				$sidebars = json_decode( stripslashes( $_REQUEST['sidebars'] ) );
				if ( is_array( $sidebars ) ) {
					$settings = widgets_plugin_get_widget_settings();
					foreach( $sidebars as $sidebar ) {
						$id = isset( $sidebar->id ) ? $sidebar->id : null;
						if ( $id !== null ) {
							$condition = isset( $sidebar->condition ) ? trim( $sidebar->condition ) : null;
							switch( $condition ) {
								case WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES :
								case WIDGETS_PLUGIN_SHOW_ON_THESE_PAGES :
								case WIDGETS_PLUGIN_SHOW_NOT_ON_THESE_PAGES :
									break;
								default :
									$condition = WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES;
							}
							$pages = isset( $sidebar->pages ) ? wp_filter_nohtml_kses( trim( $sidebar->pages ) ) : '';
							$settings['sidebars'][$sidebar->id]['display']['pages'] = $pages;
							$settings['sidebars'][$sidebar->id]['display']['condition'] = $condition;
						}
					}
					widgets_plugin_set_widget_settings( $settings );
					// JSON response
					echo json_encode( $settings['sidebars'] );
				}
			}
		}
		wp_die();
	}

	/**
	 * Handles non-Javascript save.
	 */
	public static function sidebar_admin_setup() {
		if (
			isset( $_REQUEST['widgets-control-sidebar-action'] ) &&
			$_REQUEST['widgets-control-sidebar-action'] == 'save' &&
			wp_verify_nonce( $_REQUEST[self::NONCE], 'admin' )
		) {
			if ( !empty( $_REQUEST['widgets-control-sidebar'] ) ) {
				$sidebars = $_REQUEST['widgets-control-sidebar'];
				if ( is_array ($sidebars ) ) {
					$settings = widgets_plugin_get_widget_settings();
					foreach( $sidebars as $id => $values ) {
						$condition = isset( $values['condition'] ) ? trim( $values['condition'] ) : null;
						switch( $condition ) {
							case WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES :
							case WIDGETS_PLUGIN_SHOW_ON_THESE_PAGES :
							case WIDGETS_PLUGIN_SHOW_NOT_ON_THESE_PAGES :
								break;
							default :
								$condition = WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES;
						}
						$pages = isset( $values['pages'] ) ? wp_filter_nohtml_kses( trim( $values['pages'] ) ) : '';
						$settings['sidebars'][$id]['display']['pages'] = $pages;
						$settings['sidebars'][$id]['display']['condition'] = $condition;
					}
					widgets_plugin_set_widget_settings( $settings );
				}
			}
		}
	}

	/**
	 * Initializes the ajax nonce in the footer.
	 */
	public static function admin_footer() {
		$output = '';
		if ( wp_script_is( 'widgets-control-admin' ) ) {
			// action nonce and sidebars
			$output .= '<script type="text/javascript">';
			$output .= 'widgets_control_ajax_nonce = \'' . wp_create_nonce( 'widgets-control-ajax-nonce' ) . '\';';
			$output .= 'widgets_control_sidebars = ';
			$sidebars = wp_get_sidebars_widgets();
			$keys = array();
			foreach ( $sidebars as $key => $value ) {
				if ( $key != 'wp_inactive_widgets' ) {
					$keys[] = '"' . esc_js( $key ) . '"';
				}
			}
			$output .= '[' .  implode( ',', $keys ) . ']';
			$output .= ';';
			$output .= '</script>';

			// visibility & action style
			$output .= '<style type="text/css">';
			$output .= '.widgets-control-sidebar-visibility {';
			$output .= 'padding: 0.62em;';
			$output .= '}';
			$output .= 'a.widgets-control-action {';
			$output .= 'text-decoration: none;';
			$output .= '}';
			$output .= 'a.widgets-control-action:after {';
			$output .= 'border: none;';
			$output .= 'color: #aaa;';
			$output .= 'content: \'\f140\';';
			$output .= 'font: normal 20px/1 \'dashicons\';';
			$output .= 'border: none;';
			$output .= 'background: none;';
			$output .= 'padding: 0;';
			$output .= 'text-indent: 0;';
			$output .= 'text-align: center;';
			$output .= 'vertical-align: middle;';
			$output .= '}';
			$output .= 'a.widgets-control-action.closed:after {';
			$output .= 'content: \'\f142\';';
			$output .= '}';
			$output .= '</style>';
		}
		echo $output;
	}

	/**
	 * Filters sidebars based on vivibility conditions.
	 * 
	 * @param array $sidebars_widgets
	 * @return array
	 */
	public static function sidebars_widgets( $sidebars_widgets ) {
		if (!is_admin()) {
			$remove = array();
			foreach( $sidebars_widgets as $id => $sidebar ) {
				$settings = widgets_plugin_get_widget_settings();
				$condition = isset( $settings['sidebars'][$id]['display']['condition'] ) ? $settings['sidebars'][$id]['display']['condition'] : null;
				$pages = isset( $settings['sidebars'][$id]['display']['pages'] ) ? $settings['sidebars'][$id]['display']['pages'] : '';
				$show = Widgets_Control_Conditions::evaluate_display_condition( $condition, $pages );
				if ( !$show ) {
					$remove[] = $id;
				}
			}
			foreach( $remove as $id ) {
				unset( $sidebars_widgets[$id] );
			}
		}
		return $sidebars_widgets;
	}

	/**
	 * Hooked on the action (is invoked on back end, front end and also for the
	 * Inactive Widgets sidebar under Appearance > Widgets).
	 *
	 * @param int|string $index       Index, name, or ID of the dynamic sidebar.
	 * @param bool       $has_widgets Whether the sidebar is populated with widgets.
	 */
	public static function dynamic_sidebar_after( $index, $has_widgets ) {
		// we need to check because the action is fired on the back and front end
		if ( is_admin() && $index != 'wp_inactive_widgets' ) {

			$settings = widgets_plugin_get_widget_settings();

			$sidebar_settings =
				isset( $settings['sidebars'] ) && isset( $settings['sidebars'][$index] ) ?
				$settings['sidebars'][$index] :
				array() ;

			$visibility = isset( $sidebar_settings['visibility'] ) ? $sidebar_settings['visibility'] : true;
			// .sidebar-description ... easy hack to have it hide when sidebar is closed
			echo '<div class="sidebar-description widgets-control-sidebar-visibility">';
			printf( '<h4 id="%s-visibility">', esc_attr( $index ) );
			echo __( 'Sidebar Visibility', WIDGETS_PLUGIN_DOMAIN );
			echo ' ';
			printf(
				'<a class="widgets-control-action hide-if-no-js %s-visibility %s" href="#%s-visibility"> </a>',
				esc_attr( $index ),
				$visibility ? '' : 'closed',
				esc_attr( $index )
			);
			echo '</h4>';

			// where to show
			$selected = isset( $sidebar_settings['display']['condition'] ) ? $sidebar_settings['display']['condition'] : null;
			$id = 'widgets-control-sidebar[' . $index . '][condition]';
			if ( ( $selected == NULL ) || empty( $selected ) ) {
				$selected = WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES;
			}

			printf(
				'<div class="widgets-control-sidebar-container" style="%s">',
				$visibility ? '' : 'display:none'
			);
			echo '<form action="" method="post">'; 
			echo '<div class="widgets-control-sidebar">';

			echo '<p>';
			echo '<label>';
			echo '<input type="radio" name="' . esc_attr( $id ) . '" value="' . WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES . '" ' . ( $selected == WIDGETS_PLUGIN_SHOW_ON_ALL_PAGES ? 'checked="checked"' : '') . '/>';
			echo ' ';
			echo __( 'Show on all pages', WIDGETS_PLUGIN_DOMAIN );
			echo '</label>';
			echo '<br/>';
			echo '<label>';
			echo '<input type="radio" name="' . esc_attr( $id ) . '" value="' . WIDGETS_PLUGIN_SHOW_ON_THESE_PAGES . '" '  . ( $selected == WIDGETS_PLUGIN_SHOW_ON_THESE_PAGES ? 'checked="checked"' : '') . '/>';
			echo ' ';
			echo  __( 'Show only on these pages', WIDGETS_PLUGIN_DOMAIN );
			echo '</label>';
			echo '<br/>';
			echo '<label>';
			echo '<input type="radio" name="' . esc_attr( $id ) . '" value="' . WIDGETS_PLUGIN_SHOW_NOT_ON_THESE_PAGES . '" ' . ( $selected == WIDGETS_PLUGIN_SHOW_NOT_ON_THESE_PAGES ? 'checked="checked"' : '') . '/>';
			echo ' ';
			echo __( 'Show on all except these pages', WIDGETS_PLUGIN_DOMAIN );
			echo '</label>';
			echo '</p>';

			$pages = isset( $sidebar_settings['display']['pages'] ) ? $sidebar_settings['display']['pages'] : '';
			$pages_id = 'widgets-control-sidebar[' . $index . '][pages]';

			echo '<p>';
			echo '<label>';
			echo __( 'Conditions', WIDGETS_PLUGIN_DOMAIN );
			echo '<br/>';
			echo '<textarea class="widefat" cols="20" rows="3" name="' . esc_attr( $pages_id ) . '">';
			echo esc_attr( stripslashes( $pages ) );
			echo '</textarea>';
			echo '</label>';
			echo '</p>';

			echo '<p class="description">';
			echo __( 'Put each item on a line by itself.', WIDGETS_PLUGIN_DOMAIN );
			echo ' ';
			echo __( 'To include or exclude pages, use page ids, titles or slugs.', WIDGETS_PLUGIN_DOMAIN );
			echo ' ';
			echo __( 'For page hierarchies use: <em>some-page/*</em>.', WIDGETS_PLUGIN_DOMAIN );
			echo ' ';
			echo __( 'Use the prefix <em>!</em> for exclusions.', WIDGETS_PLUGIN_DOMAIN );
			echo '<br/>';
			echo __( 'These tokens can be used:', WIDGETS_PLUGIN_DOMAIN );
			echo '<br/>';
			/* translators: this needs no translation */
			echo ' [home] [front] [single] [type:xyz] [page] [category] [category:xyz] [has_term:term:taxonomy] [tag] [tag:xyz] [tax] [tax:taxonomy] [tax:taxonomy:term] [author] [author:xyz] [archive] [archive:xyz] [search] [404] [role:xyz]';
			if ( class_exists( 'Groups_Group' ) && method_exists( 'Groups_Group', 'read_by_name' ) ) {
				echo ' [group:xyz]';
			}
			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				echo ' [language:xyz]';
			}
			echo '<br/>';
			echo __( 'Please refer to the <a href="http://docs.itthinx.com/document/widgets-control-pro/">Documentation</a> for details.', WIDGETS_PLUGIN_DOMAIN );
			echo '</p>';

			echo '<div class="widgets-control-sidebar-buttons" style="padding:0 1em;">';
			wp_nonce_field( 'admin', self::NONCE, true, true );
			echo '<input class="button button-primary widgets-control-sidebar-save hide-if-js" type="submit" name="widgets-control-sidebar-action-non-js" value="' . __( 'Apply', WIDGETS_PLUGIN_DOMAIN ) . '"/>';
			echo '<div class="button button-primary widgets-control-sidebar-save hide-if-no-js">';
			echo __( 'Apply', WIDGETS_PLUGIN_DOMAIN );
			echo '</div>'; // .widgets-control-sidebar-save
			echo '<span class="spinner"></span>';
			echo '<input type="hidden" name="widgets-control-sidebar-action" value="save"/>';
			echo '</div>'; // .widgets-control-sidebar-buttons

			echo '</div>'; // .widgets-control-sidebar
			echo '</form>';
			echo '</div>'; // .widgets-control-sidebar-container
			echo '</div>'; // .sidebar-description
		}
	}
}
Widgets_Control_Sidebars::init();
