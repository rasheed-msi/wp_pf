<?php

define('PFCLIENTID', 'GXvOWazQ3lA6YSaFji');
define('PFCLIENTSECRET', 'GXvOWazQ3lA.6/YSaFji');
define('PFAPIURL', 'https://ctpf01.parentfinder.com/api_oauth/');

//edit profile script
add_action('wp_head', 'pf_edit_profile_scripts', 1);

function pf_edit_profile_scripts() {

    if (is_page(69)) {
        //register & call stylesheets from pf edit profile component
        wp_enqueue_style('pf-edit-bootstrap', get_template_directory_uri() . '/includes/pf-edit-profile/assets/bootstrap/css/bootstrap.css', array());
        wp_enqueue_style('pf-edit-demo', get_template_directory_uri() . '/includes/pf-edit-profile/assets/css/demo.css', array());
        wp_enqueue_style('pf-edit-custom', get_template_directory_uri() . '/includes/pf-edit-profile/assets/css/custom.css', array(), '1.2.5');

        //register & call js files from pf edit profile component
        wp_enqueue_script('jquery');
        wp_enqueue_script('pf-edit-angular-1.6.1', get_template_directory_uri() . '/includes/pf-edit-profile/assets/js/angularjs/1.6.1/angular.js');
        wp_enqueue_script('pf-edit-angular-1.6.1-animate', get_template_directory_uri() . '/includes/pf-edit-profile/assets/js/angularjs/1.6.1/angular-animate.js');
        wp_enqueue_script('pf-edit-angular-1.6.1-sanitize', get_template_directory_uri() . '/includes/pf-edit-profile/assets/js/angularjs/1.6.1/angular-sanitize.js');
        wp_enqueue_script('pf-edit-angular-1.6.1-route', get_template_directory_uri() . '/includes/pf-edit-profile/assets/js/angularjs/1.5.1/angular-route.js');
        wp_enqueue_script('pf-edit-angular-1.6.1-mask', get_template_directory_uri() . '/includes/pf-edit-profile/assets/js/mask.js');
        wp_enqueue_script('pf-edit-ui-bootstrap', get_template_directory_uri() . '/includes/pf-edit-profile/assets/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap.min.js');
        wp_enqueue_script('pf-edit-ui-bootstrap-tpls', get_template_directory_uri() . '/includes/pf-edit-profile/assets/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js');
        wp_register_script('pf-edit-script', get_template_directory_uri() . '/includes/pf-edit-profile/assets/js/editProfile.js', array(), '4.8.10');
        wp_localize_script('pf-edit-script', 'edit_obj', array(
            'userId' => get_current_user_id(), 'aboutus_geturl' => rest_url('/pf/api/v1/aboutus'), 'contactus_geturl' => rest_url('/pf/api/v1/contactus'),
            'childpref_geturl' => rest_url('/pf/api/v1/childpreference'), 'aboutus_posturl' => rest_url('/pf/api/v1/saboutus'), 'contactus_posturl' => rest_url('/pf/api/v1/scontactus'),
            'childpref_posturl' => rest_url('/pf/api/v1/schildpreference'), 'getstates_url' => rest_url('/pf/api/v1/states'), 'template_root_path' => get_template_directory_uri() . '/includes/pf-edit-profile/',
            'agencyselection_geturl' => rest_url('/pf/api/v1/agencylist'), 'agencyselection_posturl' => rest_url('/pf/api/v1/sagencylist'), 'agencydel_posturl' => rest_url('/pf/api/v1/dagencylist'),
        ));
        wp_enqueue_script('pf-edit-script', null, null, '4.8.10');
    }
}

function redirect_to_profile($redirectTo, $requested_redirect_to, $user) {
    if (!is_wp_error($user)) {
        $userlogin = $_REQUEST['log'];
        $userpass = $_REQUEST['pwd'];

        if (session_id() === "") {
            session_start();
        }

        $apidata = array('grant_type' => 'password', 'client_id' => PFCLIENTID, 'client_secret' => PFCLIENTSECRET, 'password' => $userpass, 'username' => $userlogin);
        $url = PFAPIURL . "oauth/access_token";
        //$curl_data = post_curl_call($url, $apidata);
        $curl_data = array('status' => 200, 'result' => array('access_token' => 'wjsZMVwPPQBmNC8kOlNWJO4UKcaeAh5KoykkHdEX'));
        if ($curl_data['status'] == 200) {
            $response = $curl_data['result'];
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
        $ret_arr['error'] = curl_error($ch);
    }
//print_r($ret_arr);exit;
    curl_close($ch);
    return $ret_arr;
}

//add_filter('login_redirect', 'redirect_to_profile', 11, 3);

function pf_unset_user_sessions() {
    unset($_SESSION['logged_user_access_token']);
}

add_action('wp_logout', 'pf_unset_user_sessions');
