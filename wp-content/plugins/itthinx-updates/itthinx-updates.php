<?php
/**
 * Plugin Name: Itthinx Updates
 * Plugin URI: http://www.itthinx.com/plugins/itthinx-updates/
 * Description: Automatic updates for plugins by <a href="http://www.itthinx.com">itthinx</a>.
 * Version: 1.2.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ITTHINX_UPDATES_VERSION', '1.2.0' );
define( 'ITTHINX_UPDATES_FILE', __FILE__ );
define( 'ITTHINX_UPDATES_PLUGIN_DOMAIN', 'itthinx-updates' );

if ( is_admin() ) {
	require_once 'includes/class-itthinx-updates.php';
}
