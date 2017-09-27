<?php

/*
  Plugin Name: Member Register Type
  Plugin URI:
  Description: User registration and user albums management
  Author: Dinoop
  Author URI:
  Terms and Conditions:
  Version: 0.0.1
 */

define('MRT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MRT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MRT_TEMPLATE_PATH', MRT_PLUGIN_PATH . '/templates');
define('MRT_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * 
 * 
 */
define('MRT_URL_UPLOADS', wp_upload_dir()['baseurl']);
define('MRT_DIR_UPLOADS', wp_upload_dir()['basedir']);
define('MRT_URL_ALBUMS_UPLOADS', MRT_URL_UPLOADS . '/albums');
define('MRT_DIR_ALBUMS_UPLOADS', MRT_DIR_UPLOADS . '/albums');
define('MRT_URL_AVATHAR_UPLOADS', MRT_URL_UPLOADS . '/avatar');
define('MRT_DIR_AVATHAR_UPLOADS', MRT_DIR_UPLOADS . '/avatar');
define('MRT_URL_IMAGE_UPLOADS', MRT_URL_UPLOADS . '/images');
define('MRT_URL_IMAGE_PROCESSING', MRT_PLUGIN_URL . 'images/processing.gif');

/**
 * MRT_URL_PHOTOS => MRT_URL_ALBUMS_UPLOADS
 * MRT_URL_AVATHAR => MRT_URL_AVATHAR_UPLOADS
 * Images
 */
define('MRT_PARENTFINDER', 'https://www.parentfinder.com');
define('MRT_URL_AVATHAR', MRT_PARENTFINDER . '/modules/boonex/avatar/data/favourite');
define('MRT_URL_PHOTOS', MRT_PARENTFINDER . '/modules/boonex/photos/data/files');
define('MRT_URL_VIDEO', MRT_PARENTFINDER . '/flash/modules/video/files');
define('MRT_URL_DEFAULT_PHOTOS_THUMB', 'http://via.placeholder.com/220X150');
//define('MRT_URL_DEFAULT_PHOTOS_AVATHAR', 'http://via.placeholder.com/300X230');
define('MRT_URL_DEFAULT_PHOTOS_AVATHAR', MRT_PLUGIN_URL . 'images/avatar.jpg');
define('MRT_URL_YOUTUBE_EMBED', 'https://www.youtube.com/embed');

/**
 * 
 * 
 * Filestack
 */
//define('MRT_URL_S3BUCKET', 'https://s3.amazonaws.com/cairs'); // LIVE
define('MRT_URL_S3BUCKET', 'https://s3-us-west-2.amazonaws.com/s3.childconnect.com');
define('MRT_S3DOMAIN', 'testwppf');
define('MRT_FILESTACK_APIKEY', 'A9Ul90L7XRqWxNswfaGOGz');

/**
 * 
 * 
 * Helpers
 */
include_once MRT_PLUGIN_PATH . 'helpers/Stock.php';
include_once MRT_PLUGIN_PATH . 'helpers/Dot.php';
include_once MRT_PLUGIN_PATH . 'helpers/AppForm.php';
include_once MRT_PLUGIN_PATH . 'helpers/State.php';
include_once MRT_PLUGIN_PATH . 'helpers/Temp.php';
include_once MRT_PLUGIN_PATH . 'helpers/MrtPrint.php';
include_once MRT_PLUGIN_PATH . 'helpers/MrtFileStackUpload.php';
include_once MRT_PLUGIN_PATH . 'helpers/MrtRole.php';
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
    wp_enqueue_style('mrt-styles', MRT_PLUGIN_URL . 'css/styles.css', [], null);
    wp_enqueue_style('mrt-data-table-style', 'https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
    // wp_enqueue_style('mrt-jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_script('mrt-data-table-scripts', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-app-const-scripts', MRT_PLUGIN_URL . 'js/appConst.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-filestack-scripts', 'https://static.filestackapi.com/v3/filestack.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('mrt-scripts', MRT_PLUGIN_URL . 'js/mrt-scrpts.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-ng-prototype', MRT_PLUGIN_URL . 'app/prototype.js', array('jquery'), '1.0.0', true);
    // wp_enqueue_script('mrt-jquery-ui-scripts', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), null, true);
    
    //App
    $page_title = trim(get_the_title());
    $pages_angularjs = ['Albums'];
    if(in_array($page_title, $pages_angularjs)) {
        wp_enqueue_script('angularjs', MRT_PLUGIN_URL . 'app/angular.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('mrt-ng-app', MRT_PLUGIN_URL . 'app/app.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('mrt-ng-directive', MRT_PLUGIN_URL . 'app/directive.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('mrt-ng-services', MRT_PLUGIN_URL . 'app/services.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('mrt-ng-controllers', MRT_PLUGIN_URL . 'app/controllers.js', array('jquery'), '1.0.0', true);
    }
}

function mrt_admin_scripts() {

    wp_enqueue_style('mrt-data-table-style', 'https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
    wp_enqueue_script('mrt-data-table-scripts', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-scripts', MRT_PLUGIN_URL . 'js/mrt-scrpts.js', array('jquery'), '1.0.0', true);
}

add_action('admin_enqueue_scripts', 'mrt_admin_scripts');
