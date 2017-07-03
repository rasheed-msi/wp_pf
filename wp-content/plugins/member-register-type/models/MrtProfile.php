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
            'wp_users_id',
            'pf_old_id',
            'first_name',
            'last_name',
            'gender',
            'dob',
//            'marital_status',
            'occupation',
            'religion_id',
            'faith_id',
            'ethnicity_id',
            'education_id',
            'Status_id',
            'pf_agency_id',
            'couple_id',
        ];

        $this->field_default = [];
    }

    public function getId($user_id) {
        return $this->link->get_var("SELECT {$this->pkey} FROM {$this->table} WHERE wp_users_id = {$user_id}");
    }

    public static function validate($input) {
        $error = [];
        if (isset($input['user_type']) && in_array($input['user_type'], ['adoptive_family', 'birth_mother'])) {
            if (!isset($input['first_name']) || trim($input['first_name']) == '') {
                $error['first_name'] = '<strong>ERROR</strong>: Please enter your first name.';
            }
            if (!isset($input['last_name']) || trim($input['last_name']) == '') {
                $error['last_name'] = '<strong>ERROR</strong>: Please enter your last name.';
            }
        }

        return $error;
    }

}
