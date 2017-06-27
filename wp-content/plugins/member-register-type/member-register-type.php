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
define('MRT_PLUGIN_BASENAME', plugin_basename(__FILE__));

include_once MRT_PLUGIN_PATH . 'controllers/Gform.php';
include_once MRT_PLUGIN_PATH . 'controllers/FormHtmlJq.php';
include_once MRT_PLUGIN_PATH . 'controllers/ListHtml.php';
include_once MRT_PLUGIN_PATH . 'controllers/MrtMidController.php';
include_once MRT_PLUGIN_PATH . 'controllers/MrtApiController.php';
include_once MRT_PLUGIN_PATH . 'helpers/Stock.php';
include_once MRT_PLUGIN_PATH . 'helpers/Dot.php';
include_once MRT_PLUGIN_PATH . 'helpers/AppForm.php';
include_once MRT_PLUGIN_PATH . 'models/MrtDbbase.php';
include_once MRT_PLUGIN_PATH . 'models/MrtProfile.php';
include_once MRT_PLUGIN_PATH . 'models/MrtContact.php';
include_once MRT_PLUGIN_PATH . 'models/MrtRelationAgencyUser.php';
include_once MRT_PLUGIN_PATH . 'models/MrtAgencies.php';
include_once MRT_PLUGIN_PATH . 'models/MrtUser.php';
include_once MRT_PLUGIN_PATH . 'dbconversion/TableDef.php';
include_once MRT_PLUGIN_PATH . 'dbconversion/DataTransfer.php';
include_once MRT_PLUGIN_PATH . 'inc/functions.php';
include_once MRT_PLUGIN_PATH . 'inc/actions.php';
include_once MRT_PLUGIN_PATH . 'inc/page-redirect.php';

add_action('wp_enqueue_scripts', 'mrt_add_user_scripts', 20);

function mrt_add_user_scripts() {
    wp_enqueue_style('mrt-styles', MRT_PLUGIN_URL . 'css/styles.css');
    wp_enqueue_script('mrt-scripts', MRT_PLUGIN_URL . 'js/mrt-scrpts.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('mrt-app-const-scripts', MRT_PLUGIN_URL . 'js/appConst.js', array('jquery'), '1.0.0', true);
}
