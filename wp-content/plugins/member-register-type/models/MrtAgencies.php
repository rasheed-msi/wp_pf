<?php

class MrtAgencies extends MrtDbbase {

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'pf_agencies';
        $this->pkey = 'pf_agency_id';
        $this->fields = [
            'title',
            'uri',
            'descr',
            'agency_email',
            'agency_address',
            'agency_phone',
            'admin_id',
            'status',
        ];
        
        $this->field_default = [];
    }

    function validate($data) {
        $errors = [];
        if (!isset($data['title']) || $data['title'] == ''){
            $errors[] = 'Title is required';
        } else {
            if($this->is_exist($data['title'])){
                $errors[] = 'Agency name already exist';
            }
        }

        return $errors;
    }

    function is_exist($item){

        $title = $this->link->get_var("SELECT title FROM {$this->table} WHERE title = '{$item}'");
        return ($title)? true : false;

    }
    
    function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY `pf_agency_id` DESC";
        $results = $this->link->get_results($sql, ARRAY_A);
        return $results;
    }
    
    function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 1";
        $results = $this->link->get_results($sql, ARRAY_A);
        return $results;
    }

}
