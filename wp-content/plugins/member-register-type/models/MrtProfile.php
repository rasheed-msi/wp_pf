<?php

class MrtProfile extends MrtDbbase {

    public $user_id;
    public $logged_in = false;

    public function __construct() {

        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'pf_profiles';
        $this->pkey = 'pf_profile_id';
        $this->fields = [
            'wp_user_id',
            'pf_old_id',
            'first_name',
            'last_name',
            'gender',
            'dob',
            'marital_status',
            'occupation',
            'agency_attorney_name',
            'agency_website',
            'religion_id',
            'faith_id',
            'ethnicity_id',
            'education_id',
            'status_id',
            'joined_agency_id',
        ];

        $this->field_default = [];
 
    }

    public function getId($user_id) {
        return $this->link->get_var("SELECT {$this->pkey} FROM {$this->table} WHERE wp_user_id = {$user_id}");
    }
    

}
