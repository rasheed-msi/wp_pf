<?php

/**
 * WP Bootstrap Starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_Starter
 */
if (!function_exists('wp_bootstrap_starter_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function wp_bootstrap_starter_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on WP Bootstrap Starter, use a find and replace
         * to change 'wp-bootstrap-starter' to the name of your theme in all the template files.
         */
        load_theme_textdomain('wp-bootstrap-starter', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'wp-bootstrap-starter'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('wp_bootstrap_starter_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        function wp_boostrap_starter_add_editor_styles() {
            add_editor_style('custom-editor-style.css');
        }

        add_action('admin_init', 'wp_boostrap_starter_add_editor_styles');
    }

endif;
add_action('after_setup_theme', 'wp_bootstrap_starter_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wp_bootstrap_starter_content_width() {
    $GLOBALS['content_width'] = apply_filters('wp_bootstrap_starter_content_width', 1170);
}

add_action('after_setup_theme', 'wp_bootstrap_starter_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wp_bootstrap_starter_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'wp-bootstrap-starter'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer 1', 'wp-bootstrap-starter'),
        'id' => 'footer-1',
        'description' => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer 2', 'wp-bootstrap-starter'),
        'id' => 'footer-2',
        'description' => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer 3', 'wp-bootstrap-starter'),
        'id' => 'footer-3',
        'description' => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'wp_bootstrap_starter_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function wp_bootstrap_starter_scripts() {
    // load bootstrap css
    wp_enqueue_style('wp-bootstrap-starter-bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('wp-pf-family-search', get_template_directory_uri() . '/css/pf-family.css', false, 5.8);
    // load bootstrap css
    wp_enqueue_style('wp-bootstrap-starter-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', false, '4.1.0');
    // load AItheme styles
    // load WP Bootstrap Starter styles
    wp_enqueue_style('wp-bootstrap-starter-style', get_stylesheet_uri());
    wp_enqueue_script('jquery');

    // Internet Explorer HTML5 support
    wp_enqueue_script('html5hiv', get_template_directory_uri() . '/js/html5.js', array(), '3.7.0', false);
    wp_script_add_data('html5hiv', 'conditional', 'lt IE 9');
//
//	// load bootstrap js
    wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'));
    wp_enqueue_script('wp-bootstrap-starter-themejs', get_template_directory_uri() . '/js/theme-script.js', array());
    wp_enqueue_script('wp-bootstrap-starter-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);
    wp_enqueue_script('wp-pf-family-search', get_template_directory_uri() . '/js/pf-family.js', array('jquery', 'wp-bootstrap-starter-bootstrapjs'), '20151215', true);
//
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'wp_bootstrap_starter_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
if (!class_exists('wp_bootstrap_navwalker')) {
    require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');
}

/**
 * Get State by Id
 * @global type $wpdb
 * @param type $stateId
 * @return type
 */
function getStateById($stateId) {
    global $wpdb;
    $stateQry = 'SELECT State FROM pf_states WHERE `state_id`=%s';
    return $wpdb->get_var($wpdb->prepare($stateQry, $stateId));
}

/**
 * Get Ethinicity/Faith by Id
 * @global type $wpdb
 * @param type $ethnctyId
 * @return type
 */
function getEthinicityById($ethnctyId) {
    global $wpdb;
    $stateQry = 'SELECT ethnicity FROM pf_ethnicity WHERE `ethnicity_id`=%s';
    return $wpdb->get_var($wpdb->prepare($stateQry, $ethnctyId));
}

/**
 * Calculate Age from DOB (y-m-d)
 * @param type $dob
 * @return int
 */
function ageCalculator($dob) {
    if (!empty($dob)) {
        $birthdate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    } else {
        return 0;
    }
}

//edit profile script
add_action('wp_head', 'pf_edit_profile_scripts', 1);

function pf_edit_profile_scripts() {
//register & call stylesheets from pf edit profile component
    wp_enqueue_style('pf-edit-bootstrap', get_template_directory_uri() . '/inc/pf-edit-profile/assets/bootstrap/css/bootstrap.css', array());
    wp_enqueue_style('pf-edit-demo', get_template_directory_uri() . '/inc/pf-edit-profile/assets/css/demo.css', array());
    wp_enqueue_style('pf-edit-custom', get_template_directory_uri() . '/inc/pf-edit-profile/assets/css/custom.css', array());

//register & call js files from pf edit profile component
    wp_enqueue_script('jquery');
    wp_enqueue_script('pf-edit-angular-1.6.1', get_template_directory_uri() . '/inc/pf-edit-profile/assets/js/angularjs/1.6.1/angular.js');
    wp_enqueue_script('pf-edit-angular-1.6.1-animate', get_template_directory_uri() . '/inc/pf-edit-profile/assets/js/angularjs/1.6.1/angular-animate.js');
    wp_enqueue_script('pf-edit-angular-1.6.1-sanitize', get_template_directory_uri() . '/inc/pf-edit-profile/assets/js/angularjs/1.6.1/angular-sanitize.js');
    wp_enqueue_script('pf-edit-angular-1.6.1-route', get_template_directory_uri() . '/inc/pf-edit-profile/assets/js/angularjs/1.5.1/angular-route.js');
    wp_enqueue_script('pf-edit-angular-1.6.1-mask', get_template_directory_uri() . '/inc/pf-edit-profile/assets/js/mask.js');
    wp_enqueue_script('pf-edit-script', get_template_directory_uri() . '/inc/pf-edit-profile/assets/js/editProfile.js', array(), 1.1);
    wp_enqueue_script('pf-edit-ui-bootstrap', get_template_directory_uri() . '/inc/pf-edit-profile/assets/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap.min.js');
    wp_enqueue_script('pf-edit-ui-bootstrap-tpls', get_template_directory_uri() . '/inc/pf-edit-profile/assets/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js');
}

define('PFCLIENTID', 'GXvOWazQ3lA6YSaFji');
define('PFCLIENTSECRET', 'GXvOWazQ3lA.6/YSaFji');
define('PFAPIURL', 'https://ctpf01.parentfinder.com/api_oauth/');

function redirect_to_profile($redirectTo, $requested_redirect_to, $user) {
    if (!is_wp_error($user)) {
        $userlogin = $_REQUEST['log'];
        $userpass = $_REQUEST['pwd'];

        if (session_id() === "") {
            session_start();
        }

        $apidata = array('grant_type' => 'password', 'client_id' => PFCLIENTID, 'client_secret' => PFCLIENTSECRET, 'password' => $userpass, 'username' => $userlogin);
        $url = PFAPIURL . "oauth/access_token";
        $curl_data = post_curl_call($url, $apidata);
        if ($curl_data['status'] == 200) {
            $response = $curl_data['result'];
            // echo '<pre>';print_r($response);exit;
            if (isset($response['access_token'])) {
                $_SESSION['logged_user_access_token'] = $response['access_token'];
            }
        }
    }
    return $requested_redirect_to;
}

function post_curl_call($url, $data = array(), $config = array()) {

    $curl_config = array();
    if (isset($config['CURLOPT_RETURNTRANSFER']))
        $curl_config['CURLOPT_RETURNTRANSFER'] = $config['CURLOPT_RETURNTRANSFER'];
    else
        $curl_config['CURLOPT_RETURNTRANSFER'] = true;
    if (isset($config['CURLOPT_SSL_VERIFYPEER']))
        $curl_config['CURLOPT_SSL_VERIFYPEER'] = $config['CURLOPT_SSL_VERIFYPEER'];
    else
        $curl_config['CURLOPT_SSL_VERIFYPEER'] = false;
    if (isset($config['CURLOPT_SSL_VERIFYHOST']))
        $curl_config['CURLOPT_SSL_VERIFYHOST'] = $config['CURLOPT_SSL_VERIFYHOST'];
    else
        $curl_config['CURLOPT_SSL_VERIFYHOST'] = 0;
    $ch = curl_init($url);
    // curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $curl_config['CURLOPT_RETURNTRANSFER']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curl_config['CURLOPT_SSL_VERIFYPEER']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $curl_config['CURLOPT_SSL_VERIFYHOST']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ret_arr = array('status' => $status);
    if ($status == 200) {
        $ret_arr['result'] = json_decode($result, true);
    } else {
        $ret['error'] = curl_error($ch);
    }
    curl_close($ch);
    return $ret_arr;
}

add_filter('login_redirect', 'redirect_to_profile', 11, 3);

function pf_unset_user_sessions() {
    unset($_SESSION['logged_user_access_token']);
}

add_action('wp_logout', 'pf_unset_user_sessions');




//edit profile script


require_once get_template_directory() . '/inc/pf-family-search.php';
require_once get_template_directory() . '/inc/pf-manage-families.php';

//custom rules




class PFEditApi {
    
    private $wpdb;
    private $user_ID;
    private $userdata;

    function __construct() {
        global $wpdb,$user_ID,$userdata;
        $this->wpdb = $wpdb;
        $this->user_ID = !empty($user_ID) ? $user_ID : get_current_user_id();
        $this->userdata = !empty($userdata) ? $userdata: get_userdata($this->user_ID);
        
        
        echo "fdgdfgd<pre>";print_r($this->user_ID);
        echo "<pre>";print_r($this->userdata);exit;
        
        //add_action('init', array($this, 'pfed_api_rules'));
        add_action('wp_ajax_pfed_about', array($this, 'pfedAboutFunc'));
        //add_action('wp_ajax_nopriv_pfed_about', array($this, 'pfedAboutFunc'));
        add_action('wp_ajax_pfed_contact', array($this, 'pfedContactFunc'));
        //add_action('wp_ajax_nopriv_pfed_contact', array($this, 'pfedContactFunc'));
        add_action('wp_ajax_pfed_childpref', array($this, 'pfedChildprefFunc'));
        //add_action('wp_ajax_nopriv_pfed_childpref', array($this, 'pfedChildprefFunc'));
        add_action('wp_ajax_pfeds_about', array($this, 'pfedsAboutFunc'));
        //add_action('wp_ajax_nopriv_pfeds_about', array($this, 'pfedsAboutFunc'));
        add_action('wp_ajax_pfeds_contact', array($this, 'pfedsContactFunc'));
        //add_action('wp_ajax_nopriv_pfeds_contact', array($this, 'pfedsContactFunc'));
        add_action('wp_ajax_pfeds_childpref', array($this, 'pfedsChildprefFunc'));
        //add_action('wp_ajax_nopriv_pfeds_childpref', array($this, 'pfedsChildprefFunc'));
        add_action('wp_ajax_pfed_status', array($this, 'pfedStatusFunc'));
        //add_action('wp_ajax_nopriv_pfed_status', array($this, 'pfedStatusFunc'));
    }

    function pfed_api_rules() {
        add_rewrite_rule('^v1/editprofile/aboutus/?', '/wp-admin/admin-ajax.php?action=pfed_about', 'top');
        add_rewrite_rule('^v1/editprofile/contactus/?', '/wp-admin/admin-ajax.php?action=pfed_contact', 'top');
        add_rewrite_rule('^v1/editprofile/childpreference/?', '/wp-admin/admin-ajax.php?action=pfed_childpref', 'top');
        add_rewrite_rule('^v1/editprofile/save/aboutus/?', '/wp-admin/admin-ajax.php?action=pfeds_about', 'top');
        add_rewrite_rule('^v1/editprofile/save/contactus/?', '/wp-admin/admin-ajax.php?action=pfeds_contact', 'top');
        add_rewrite_rule('^v1/editprofile/save/childpreference/?', '/wp-admin/admin-ajax.php?action=pfeds_childpref', 'top');
        add_rewrite_rule('^v1/editprofile/get/states/?', '/wp-admin/admin-ajax.php?action=pfed_status', 'top');
        flush_rewrite_rules();
    }

    /**
     * PF Edit Api Path:-v1/editprofile/aboutus/
     */
    function pfedAboutFunc() {
        
        
    }

    /**
     * PF Edit Api Path:-v1/editprofile/contactus/
     */
    function pfedContactFunc() {
        
    }

    /**
     * PF Edit Api Path:-v1/editprofile/childpreference/
     */
    function pfedChildprefFunc() {
        
    }

    /**
     * PF Edit Api Path:-v1/editprofile/save/aboutus/
     */
    function pfedsAboutFunc() {
        
    }

    /**
     * PF Edit Api Path:-v1/editprofile/save/contactus/
     */
    function pfedsContactFunc() {
        
    }

    /**
     * PF Edit Api Path:-v1/editprofile/save/childpreference/
     */
    function pfedsChildprefFunc() {
        
    }

    /**
     * PF Edit Api Path:-v1/editprofile/get/states/
     */
    function pfedStatusFunc() {
        
    }

}

new PFEditApi;

//custom rules

