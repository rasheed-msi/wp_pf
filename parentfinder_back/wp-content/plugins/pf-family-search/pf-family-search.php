<?php

defined('ABSPATH') OR exit;
/**
 * Plugin Name: ParentFinder Family Search 
 * Description: Search Families  using varuious filters like  country,state,religion etc
 * Author:      Rasheed P.K
 * Author URL:  https://profiles.wordpress.org/abdulrasheedpk7/
 * Plugin URL:  http://parentfinder.com
 */

register_activation_hook(__FILE__, array('PF_Search_Inc', 'on_activation'));
register_deactivation_hook(__FILE__, array('PF_Search_Inc', 'on_deactivation'));
register_uninstall_hook(__FILE__, array('PF_Search_Inc', 'on_uninstall'));

if (!defined('PFFS_URL'))
    define('PFFS_URL', plugin_dir_url(__FILE__));


add_action('plugins_loaded', array('PF_Search_File', 'init'));

class PF_Search_File {

    protected static $instance;

    public static function init() {
        is_null(self::$instance) AND self::$instance = new self;
        return self::$instance;
    }

    public function __construct() {
        add_action(current_filter(), array($this, 'load_files'), 30);
    }

    public function load_files() {
        foreach (glob(plugin_dir_path(__FILE__) . 'inc/*.php') as $file)
            include_once $file;
    }

}
