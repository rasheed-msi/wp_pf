<?php
/*
  Plugin Name: PF Dependencies
  Plugin URI: http://parentfinder.com
  Description: ParentFinder Dependecy functionalities for both plugins and themes
  Version: 1.0
  Author: Rasheed P.K
  Author URI: https://profiles.wordpress.org/abdulrasheedpk7/
  Text Domain: pf-txt-domain
  Domain Path: /languages
 */

/* plugin constants */

if (!defined('PF_DEP_URL'))
    define('PF_DEP_URL', plugin_dir_url(__FILE__));

if (!defined('PF_DEP_BASENAME'))
    define('PF_DEP_BASENAME', plugin_basename(__FILE__));

if (!defined('PF_DEP_PATH'))
    define('PF_DEP_PATH', plugin_dir_path(__FILE__));

if (!defined('PF_DEP_PLUGIN_URL'))
    define('PF_DEP_PLUGIN_URL', PF_DEP_URL . 'plugins');

if (!defined('PF_DEP_PLUGIN_PATH'))
    define('PF_DEP_PLUGIN_PATH', PF_DEP_PATH . '/plugins');

if (!defined('PF_DEP_CUSTOM_PATH'))
    define('PF_DEP_CUSTOM_PATH', PF_DEP_PATH . '/custom');

if (!defined('PF_DEP_CUSTOM_URL'))
    define('PF_DEP_CUSTOM_URL', PF_DEP_URL . 'custom');



/* required files */

/* plugin customization files */
require_once (PF_DEP_PLUGIN_PATH . '/tml/tml.php'); /* theme my login */
/* plugin customization files */



/* Other PF customisation */
//require_once (PF_DEP_CUSTOM_PATH . '/password-encrypt/pf-encrypt-password.php'); //for password hashing
//require_once (PF_DEP_CUSTOM_PATH . '/agency-approvals/agency-approvals.php'); //for agency approvals
/* Other PF customisation */
