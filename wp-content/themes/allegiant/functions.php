<?php if(!isset($content_width)) $content_width = 640;
define('CPOTHEME_ID', 'allegiant');
define('CPOTHEME_NAME', 'Allegiant');
define('CPOTHEME_VERSION', '1.0.8');
//Other constants
define('CPOTHEME_LOGO_WIDTH', '215');
define('CPOTHEME_USE_SLIDES', true);
define('CPOTHEME_USE_FEATURES', true);
define('CPOTHEME_USE_PORTFOLIO', true);
define('CPOTHEME_USE_SERVICES', true);
define('CPOTHEME_USE_TESTIMONIALS', true);
define('CPOTHEME_USE_TEAM', true);
define('CPOTHEME_USE_CLIENTS', true);
define('CPOTHEME_PREMIUM_NAME', 'Allegiant Pro');
define('CPOTHEME_PREMIUM_URL', '//www.cpothemes.com/theme/allegiant');

//Load Core; check existing core or load development core
$core_path = get_template_directory().'/core/';
if(defined('CPOTHEME_CORELITE')) $core_path = CPOTHEME_CORELITE;
require_once $core_path.'init.php';

$include_path = get_template_directory().'/includes/';

//Main components
require_once($include_path.'setup.php');

//Include Welcome Screen
require get_template_directory() . '/core/welcome-screen/welcome-page-setup.php';

//Rasheed changes
require get_template_directory() . '/includes/pf-gen-inc.php';

//pf edit profile
require get_template_directory() . '/includes/pf-edit-profile/pf-edit-profile-functions.php';
require get_template_directory() . '/includes/pf-edit-profile/pf-edit-profile-api-functions.php';

//pf journal
require get_template_directory() . '/includes/pf-journal/pf-journal-conf.php';
require get_template_directory() . '/includes/pf-journal/pf-journal-ui.php';

//pf letter
require get_template_directory() . '/includes/pf-letter/pf-letter-conf.php';
require get_template_directory() . '/includes/pf-letter/pf-letter-ui.php';


//pf manage family
require get_template_directory() . '/includes/pf-family/pf-family-view.php';
require get_template_directory() . '/includes/pf-family/pf-family-manage.php';



add_action('wp_enqueue_scripts', 'wp_pf_scripts_common');
/**
 * pf family enqueue script
 */
function wp_pf_scripts_common() {
    //wp_enqueue_style('pf-common-css', get_template_directory_uri() . '/core/css/pf-common-style.css', array(), '5.8.1');
    wp_enqueue_style('pf-bootstrap-css', get_template_directory_uri() . '/custom-theme/css/bootstrap.css', array(), '5.8.1');
    wp_enqueue_style('pf-font-awesome-css', get_template_directory_uri() . '/custom-theme/css/font-awesome.min.css', array(), '5.8.1');
    wp_enqueue_style('pf-material-design-iconic-font-css', get_template_directory_uri() . '/custom-theme/css/material-design-iconic-font.min.css', array(), '5.8.1');
    wp_enqueue_style('pf-animate-css', get_template_directory_uri() . '/custom-theme/css/animate.css', array(), '5.8.1');
    wp_enqueue_style('pf-style-css', get_template_directory_uri() . '/custom-theme/css/style.css', array(), '1.0.0');
    wp_enqueue_style('pf-custom-style-css', get_template_directory_uri() . '/custom-theme/css/custom-style.css', array(), '1.0.2');
    
    wp_enqueue_script('pf-bootstrap-js', get_template_directory_uri() . '/custom-theme/js/bootstrap.min.js', array('jquery'), '5.8.1', true);
    wp_enqueue_script('pf-app-js', get_template_directory_uri() . '/custom-theme/js/app.js', array('jquery'), '1.2.3', true);
}




