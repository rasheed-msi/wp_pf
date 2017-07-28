<?php

class MrtAuth {

    private $link;
    private $token_key = 'Token';

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    public function create_token($user_id) {
        $token = $user_id . rand(1000000000, 9999999999);
        return base64_encode($token);
    }

    public function read_token($token) {

        $str = base64_decode($token);
        $user_id = substr($str, 0, -10);

        if (is_numeric($user_id)) {
            return $user_id;
        }

        return false;
    }

    public function set_token($user_id) {
        $token = $this->create_token($user_id);
        $data = ['api_token' => $token];
        $where = ['ID' => $user_id];
        $this->link->update($this->link->users, $data, $where);
        return $token;
    }
    
    public function clear_token($user_id) {
        $data = ['api_token' => NULL];
        $where = ['ID' => $user_id];
        $this->link->update($this->link->users, $data, $where);
    }

    public function validate_token() {
        
        $headers = getallheaders();

        if (!isset($headers[$this->token_key])) {
            return false;
        }

        $user_id = $this->read_token($headers[$this->token_key]);

        if ($user_id) {
            $user = get_user_by('ID', $user_id);
            if ($user && $user->api_token == $headers[$this->token_key]) {
                wp_set_current_user($user->ID);
                return $user;
            }
        }

        return false;
    }

}
