<?php

/*
  Plugin Name: Member Register Type
  Plugin URI:
  Description: Register User in different type
  Author: Dinoop
  Author URI:
  Terms and Conditions:
  Version: 0.0.1
 */

define('MRT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MRT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MRT_TEMPLATE_PATH', MRT_PLUGIN_PATH . '/templates');
define('MRT_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('MRT_ALBUMS_UPLOADS', wp_upload_dir()['baseurl'] . '/albums');
define('MRT_URL_IMAGE_UPLOADS', wp_upload_dir()['baseurl'] . '/images');

/**
 * 
 * Images
 */
define('MRT_PARENTFINDER', 'https://www.parentfinder.com');
define('MRT_URL_AVATHAR', MRT_PARENTFINDER . '/modules/boonex/avatar/data/favourite');
define('MRT_URL_PHOTOS', MRT_PARENTFINDER . '/modules/boonex/photos/data/files');
define('MRT_URL_VIDEO', MRT_PARENTFINDER . '/flash/modules/video/files');
define('MRT_URL_DEFAULT_PHOTOS_THUMB', 'http://via.placeholder.com/220X150');
define('MRT_URL_DEFAULT_PHOTOS_AVATHAR', 'http://via.placeholder.com/220X150');
define('MRT_URL_YOUTUBE_EMBED', 'https://www.youtube.com/embed');

include_once MRT_PLUGIN_PATH . 'helpers/Stock.php';
include_once MRT_PLUGIN_PATH . 'helpers/Dot.php';
include_once MRT_PLUGIN_PATH . 'helpers/AppForm.php';
include_once MRT_PLUGIN_PATH . 'helpers/State.php';
include_once MRT_PLUGIN_PATH . 'helpers/Temp.php';
include_once MRT_PLUGIN_PATH . 'helpers/MrtPrint.php';
include_once MRT_PLUGIN_PATH . 'models/MrtDbbase.php';

// include models
foreach (glob(MRT_PLUGIN_PATH . 'models/*.php') as $file) {
    include_once $file;
}

// include controllers
foreach (glob(MRT_PLUGIN_PATH . 'controllers/*.php') as $file) {
    include_once $file;
}



include_once MRT_PLUGIN_PATH . 'dbconversion/TableDef.php';
include_once MRT_PLUGIN_PATH . 'dbconversion/DataTransfer.php';
include_once MRT_PLUGIN_PATH . 'inc/functions.php';
include_once MRT_PLUGIN_PATH . 'inc/actions.php';
include_once MRT_PLUGIN_PATH . 'inc/page-redirect.php';

add_action('wp_enqueue_scripts', 'mrt_add_user_scripts', 20);

function mrt_add_user_scripts() {
    wp_enqueue_style('mrt-styles', MRT_PLUGIN_URL . 'css/styles.css');
    wp_enqueue_style('mrt-data-table-style', 'https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
    // wp_enqueue_style('mrt-jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_script('mrt-data-table-scripts', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-app-const-scripts', MRT_PLUGIN_URL . 'js/appConst.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-filestack-scripts', 'https://static.filestackapi.com/v3/filestack.js', array('jquery'), '1.0.0', true);

    wp_register_script('mrt-scripts', MRT_PLUGIN_URL . 'js/mrt-scrpts.js', array('jquery'), '1.0.0', true);
    // wp_enqueue_script('mrt-jquery-ui-scripts', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), null, true);
    wp_localize_script('mrt-scripts', 'myLocalized', array(
        'partials' => 'test',
        'nonce' => wp_create_nonce('wp_rest')
            )
    );
    wp_enqueue_script('mrt-scripts');
}

function mrt_admin_scripts() {

    wp_enqueue_style('mrt-data-table-style', 'https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
    wp_enqueue_script('mrt-data-table-scripts', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-scripts', MRT_PLUGIN_URL . 'js/mrt-scrpts.js', array('jquery'), '1.0.0', true);
}

add_action('admin_enqueue_scripts', 'mrt_admin_scripts');
