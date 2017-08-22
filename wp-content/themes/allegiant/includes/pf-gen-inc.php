<?php

class GenInc {

    function __construct() {
        
    }

    public static function is_couple($user_ID) {
        global $wpdb;
        return $wpdb->get_var("SELECT count(spouse_first_name) as status FROM pf_profiles WHERE wp_user_id=%d", array($user_ID));
    }

}

new GenInc;
