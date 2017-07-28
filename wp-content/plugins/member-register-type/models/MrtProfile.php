<?php

class MrtProfile extends MrtDbbase {

    public $link;
    public $table = 'pf_profiles';
    public $pkey = 'pf_profile_id';
    public $id;
    public $fields = [
        'wp_users_id',
        'pf_old_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
//        'marital_status',
        'occupation',
        'religion_id',
        'faith_id',
        'ethnicity_id',
        'education_id',
        'Status_id',
        'pf_agency_id',
        'couple_id',
    ];
    public $field_default = [];
    public $data;

    public function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    public static function get_object($class = null) {
        return parent::get_object(__CLASS__);
    }

    public static function find($id, $key = null, $class = null) {
        $obj = parent::find($id, $key, __CLASS__);
        if (isset($obj->data)) {
            $obj->data['marital_status'] = (is_null($obj->data['couple_id'])) ? 'single' : 'couple';
            $obj->data['display_name'] = $obj->data['first_name'] . ' ' . $obj->data['last_name'];
        }
        return $obj;
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

    public function set_info() {
        
        $this->info = $this->link->get_row(
                "SELECT "
                . "e.ethnicity ethnicity_id, "
                . "ed.education_text education_id, "
                . "rel.Religion religion_id "
                . "FROM {$this->table} p "
                . "LEFT JOIN pf_ethnicity e ON e.ethnicity_id = p.ethnicity_id "
                . "LEFT JOIN pf_education ed ON ed.education_id = p.education_id "
                . "LEFT JOIN pf_religions rel ON rel.ReligionId = p.religion_id "
                . "WHERE p.pf_profile_id = {$this->id} ", ARRAY_A
        );
        
    }

}
