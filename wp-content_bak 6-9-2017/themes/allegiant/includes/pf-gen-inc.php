<?php

class GenInc {

    function __construct() {
        
    }

    public static function is_couple($user_ID) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT count(spouse_first_name) FROM pf_profiles WHERE wp_user_id=%d", array($user_ID)));
    }
    public static function parent1($user_ID) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT first_name FROM pf_profiles WHERE wp_user_id=%d", array($user_ID)));
    }
    public static function parent2($user_ID) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT spouse_first_name FROM pf_profiles WHERE wp_user_id=%d", array($user_ID)));
    }

}

new GenInc;
