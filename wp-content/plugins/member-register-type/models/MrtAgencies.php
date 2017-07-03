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
            'street_1',
            'street_2',
            'City',
            'Country',
            'State',
            'Zip',
        ];

        $this->field_default = [];
    }

    public function validate($input) {
        $errors = [];

        if (isset($input['user_type']) && in_array($input['user_type'], ['adoption_agency'])) {
            if (!isset($input['title']) || trim($input['title']) == '') {
                $errors['title'] = '<strong>ERROR</strong>: Please enter agency name.';
            } else {
                if ($this->is_exist($input['title'])) {
                    $errors['title'] = '<strong>ERROR</strong>: Agency name already exist';
                }
            }
        }

        return $errors;
    }

    function is_exist($item) {
        $title = $this->link->get_var("SELECT title FROM {$this->table} WHERE title = '{$item}'");
        return ($title) ? true : false;
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
