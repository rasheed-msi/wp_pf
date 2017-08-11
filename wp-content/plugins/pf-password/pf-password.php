<?php

/*
  Plugin Name: PF Password
  Plugin URI: http://parentfinder.com
  Description:
  Version: 1.0
  Author: Rasheed P.K
  Author URI: https://profiles.wordpress.org/abdulrasheedpk7/
  Text Domain: pf-txt-domain
 */



if (session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

if (!function_exists('wp_hash_password')) {

    function wp_hash_password($password) {
        $salt = wp_generate_password(8, false);
        $encrypt = sha1(md5($password) . $salt);
        $_SESSION['PF_Encrypt_P'] = array('salt' => $salt, 'encrypt' => $encrypt);
        return $encrypt;
    }

}


if (!function_exists('wp_check_password')) {

    function wp_check_password($password, $hash, $user_id = '') {
        $data = get_userdata($user_id);
        $salt = $data->Salt;
        $encrypt = sha1(md5($password) . $salt);
        //check for your hash match
        return $encrypt === $hash;
    }

}

add_action('profile_update', 'pf_user_register');
add_action('user_register', 'pf_user_register');

function pf_user_register($user_id) {
    $data = get_userdata($user_id);
    $userData = array('ID' => $user_id, 'salt' => $_SESSION['PF_Encrypt_P']['salt']);
    if (isset($_SESSION['PF_Encrypt_P']) && $data->data->user_pass === $_SESSION['PF_Encrypt_P']['encrypt']) {
        global $wpdb;
        //update salt
        $wpdb->update(
                $wpdb->users, array('Salt' => $_SESSION['PF_Encrypt_P']['salt']), array('ID' => $user_id), array('%s'), array('%d')
        );
    }
    unset($_SESSION['PF_Encrypt_P']);
}
