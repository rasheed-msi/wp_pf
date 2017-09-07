<?php

class DataTransfer {

    private $table_user = "profiles";
    private $table_groups = "bx_groups_main";
    private $bunex;

    function __construct() {
        $this->bunex = new wpdb('root', '', 'press_parentfinder', 'localhost');
        $rows = $mydb->get_results("select Name from my_table");
    }

    
    function copy_users() {
        $users = $this->bunex->get_results("SELECT * FROM {$this->table_user} LIMIT 0,10");
        foreach ($users as $key => $value) {
            
            $userdata = [
                
            ];
            
            $user_id = wp_insert_user($userdata);
            
            $user_id = wp_create_user($user_name, $random_password, $user_email);
        }
    }

}
