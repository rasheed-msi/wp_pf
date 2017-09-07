<?php
/**
 * widgets-control-pro.php
 * 
 * Copyright (c) 2015 "kento" Karim Rahimpur www.itthinx.com
 * 
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * Parts of this code are released under the GNU General Public License
 * Version 3.
 * See COPYRIGHT.txt and LICENSE.txt.
 * 
 * =============================================================================
 * 
 * You MUST be granted a license by the copyright holder for those parts that
 * are not provided under the GPLv3 license.
 * 
 * If you have not been granted a license DO NOT USE this plugin until you have
 * BEEN GRANTED A LICENSE.
 * 
 * Use of this plugin without a granted license constitutes an act of COPYRIGHT
 * INFRINGEMENT and LICENSE VIOLATION and may result in legal action taken
 * against the offending party.
 * 
 * Being granted a license is GOOD because you will get support and contribute
 * to the development of useful free and premium themes and plugins that you
 * will be able to enjoy.
 * 
 * Thank you!
 * 
 * Visit www.itthinx.com for more information.
 * 
 * =============================================================================
 * 
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * All legal, copyright and license notices and all author attributions
 * must be preserved in all files and user interfaces.
 * 
 * Where modified versions of this material are allowed under the applicable
 * license, modified version must be marked as such and the origin of the
 * modified material must be clearly indicated, including the copyright
 * holder, the author and the date of modification and the origin of the
 * modified material.
 * 
 * This material may not be used for publicity purposes and the use of
 * names of licensors and authors of this material for publicity purposes
 * is prohibited.
 * 
 * The use of trade names, trademarks or service marks, licensor or author
 * names is prohibited unless granted in writing by their respective owners.
 * 
 * Where modified versions of this material are allowed under the applicable
 * license, anyone who conveys this material (or modified versions of it) with
 * contractual assumptions of liability to the recipient, for any liability
 * that these contractual assumptions directly impose on those licensors and
 * authors, is required to fully indemnify the licensors and authors of this
 * material.
 * 
 * This header and all notices must be kept intact.
 * 
 * @author itthinx
 * @package widgets-control-pro
 * @since 1.0.0
 * 
 * Plugin Name: Widgets Control Pro
 * Plugin URI: http://www.itthinx.com/shop/widgets-control-pro/
 * Description: The advanced Widget toolbox that adds visibility management and helps to control where widgets and sidebars are shown efficiently.
 * Version: 1.5.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

function widgets_pro_plugin_set() {
	define( 'WIDGETS_PLUGIN_VERSION',  '1.5.0' );
	define( 'WIDGETS_PLUGIN_NAME',     'widgets-control-pro' );
	define( 'WIDGETS_PLUGIN_DOMAIN',   'widgets-control' );
	define( 'WIDGETS_PLUGIN_FILE',     __FILE__ );
	define( 'WIDGETS_PLUGIN_BASENAME', plugin_basename( WIDGETS_PLUGIN_FILE ) );
	define( 'WIDGETS_PLUGIN_DIR',      plugin_dir_path( __FILE__ ) );
	define( 'WIDGETS_PLUGIN_LIB',      WIDGETS_PLUGIN_DIR . 'lib' );
	define( 'WIDGETS_PLUGIN_URL',      plugins_url( 'widgets-control-pro' ) );
	define( 'WIDGETS_PRO',             true );
}

if ( !function_exists( 'itthinx_plugins' ) ) {
	require_once 'itthinx/itthinx.php';
}
itthinx_plugins( __FILE__ );

/**
 * Widget Plugin main class.
 */
class Widgets_Pro_Plugin {

	/**
	 * Plugin setup.
	 */
	public static function init() {
		$active_plugins = get_option( 'active_plugins', array() );
		$widgets_is_active = in_array( 'widgets-control/widgets-control.php', $active_plugins );
		if ( is_multisite() ) {
			$active_sitewide_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$widgets_is_active =
				$widgets_is_active ||
				key_exists( 'widgets-control/widgets-control.php', $active_sitewide_plugins );
		}
		if ( $widgets_is_active ) {
			add_action( 'admin_notices', array( __CLASS__, 'deactivate_widgets' ) );
		} else {
			widgets_pro_plugin_set();
			add_action( 'init', array( __CLASS__, 'wp_init' ) );
			require_once WIDGETS_PLUGIN_LIB . '/includes/constants.php';
			require_once WIDGETS_PLUGIN_LIB . '/conditions.php';
			require_once WIDGETS_PLUGIN_LIB . '/class-widgets-control-conditions.php';
			require_once WIDGETS_PLUGIN_LIB . '/class-widgets-plugin-options.php';
			require_once WIDGETS_PLUGIN_LIB . '/widgets.php';
			if ( is_admin() ) {
				require_once WIDGETS_PLUGIN_LIB . '/admin/class-widgets-plugin-admin.php';
				require_once WIDGETS_PLUGIN_LIB . '/admin/class-widgets-plugin-admin-settings.php';
			}
			require_once WIDGETS_PLUGIN_LIB . '/class-widgets-plugin-cache.php';
			require_once WIDGETS_PLUGIN_LIB . '/class-widgets-control-sidebars.php';
			require_once WIDGETS_PLUGIN_LIB . '/class-widgets-control-shortcodes.php';
		}
	}

	/**
	 * Hooked on the init action, loads translations.
	 */
	public static function wp_init() {
		load_plugin_textdomain( WIDGETS_PLUGIN_DOMAIN, null, 'widgets-control/languages' );
	}

	/**
	 * Prints an admin notice to deactivate the Widgets plugin.
	 */
	public static function deactivate_widgets() {
		if ( current_user_can( 'activate_plugins' ) || current_user_can( 'install_plugins' ) || current_user_can( 'delete_plugins' )) {
			echo '<div class="error">';
			echo '<p>';
			echo '<strong>';
			_e( 'Oops!', WIDGETS_PLUGIN_DOMAIN );
			echo '</strong>';
			echo ' ';
			_e( 'The <em>Widgets Control Pro</em> plugin is activated, but the <em>Widgets Control</em> plugin is also active.', WIDGETS_PLUGIN_DOMAIN );
			echo ' ';
			_e( 'To use the advanced features in <em>Widgets Control Pro</em>, you must deactivate <em>Widgets Control</em>.', WIDGETS_PLUGIN_DOMAIN );
			echo ' ';
			_e( 'You can also remove the <em>Widgets Control</em> plugin, as <em>Widgets Control Pro</em> replaces it completely.', WIDGETS_PLUGIN_DOMAIN );
			echo '</p>';
			echo '</div>';
		}
	}
}
Widgets_Pro_Plugin::init();
