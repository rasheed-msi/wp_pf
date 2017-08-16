<?php

class MrtProfile extends MrtDbbase {

    public $link;
    public $table = 'pf_profiles';
    public $pkey = 'pf_profile_id';
    public $id;
    public $fields = [
        'wp_user_id',
        'pf_old_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'occupation',
        'account_id',
        'waiting_id',
        'religion_id',
        'faith_id',
        'ethnicity_id',
        'education_id',
        'Status_id',
        'role_id',
        'profile_no',
        'profile_year',
        'zoho_id',
        'pf_agency_id',
        'avatar',
        'spouse_first_name',
        'spouse_last_name',
        'spouse_gender',
        'spouse_dob',
        'spouse_occupation',
        'spouse_religion_id',
        'spouse_ethnicity_id',
        'spouse_education_id',
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
            $obj->data['marital_status'] = (is_null($obj->data['spouse_first_name'])) ? 'single' : 'couple';
            $obj->data['display_name'] = $obj->data['first_name'] . ' & ' . $obj->data['spouse_first_name'];
            $obj->data['avatar'] = MRT_URL_AVATHAR . '/' . $obj->data['avatar'] . '.jpg';
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
                . "e.ethnicity ethnicity, "
                . "ed.education_text education, "
                . "rel.Religion religion, "
                . "se.ethnicity spouse_ethnicity, "
                . "sed.education_text spouse_education, "
                . "srel.Religion spouse_religion "
                . "FROM {$this->table} p "
                . "LEFT JOIN pf_ethnicity e ON e.ethnicity_id = p.ethnicity_id "
                . "LEFT JOIN pf_education ed ON ed.education_id = p.education_id "
                . "LEFT JOIN pf_religions rel ON rel.ReligionId = p.religion_id "
                . "LEFT JOIN pf_ethnicity se ON se.ethnicity_id = p.spouse_ethnicity_id "
                . "LEFT JOIN pf_education sed ON sed.education_id = p.spouse_education_id "
                . "LEFT JOIN pf_religions srel ON srel.ReligionId = p.spouse_religion_id "
                . "WHERE p.pf_profile_id = {$this->id} ", ARRAY_A
        );

        $this->info['gender'] = ucfirst($this->data['gender']);
        $this->info['spouse_gender'] = ucfirst($this->data['spouse_gender']);
        
        
    }

}
