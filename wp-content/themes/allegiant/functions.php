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
require get_template_directory() . '/includes/gen-inc.php';
require get_template_directory() . '/includes/pf-post-types.php';
require get_template_directory() . '/includes/pf-letters.php';
require get_template_directory() . '/includes/pf-journals.php';
require get_template_directory() . '/includes/pf-manage-families.php';
require get_template_directory() . '/includes/pf-edit-profile-functions.php';
require get_template_directory() . '/includes/pf-edit-profile-api-functions.php';



add_action('wp_enqueue_scripts', 'wp_pf_scripts_common');
/**
 * pf family enqueue script
 */
function wp_pf_scripts_common() {
    wp_enqueue_style('pf-common-css', get_template_directory_uri() . '/core/css/pf-common-style.css', array(), '5.8.1');
}




