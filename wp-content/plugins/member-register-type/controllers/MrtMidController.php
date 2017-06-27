<?php

class MrtMidController {

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    function test() {
        $mrtuser = new MrtProfile(8226);
        $data = [
            'wp_users_id' => '8226',
            'gender' => 'male',
        ];
        $mrtuser->insert($data);
        
        
        exit();
    }

}
