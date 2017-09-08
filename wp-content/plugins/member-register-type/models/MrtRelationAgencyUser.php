<?php

class MrtRelationAgencyUser extends MrtDbbase {

    public $link;
    public $table = 'pf_agency_users';
    public $pkey = 'pf_agency_user_id';
    public $id;
    public $fields = [
        'pf_agency_id',
        'wp_user_id',
        'pf_profile_id',
        'is_admin',
    ];
    public $field_default = [];
    

    public function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    public function get_agencies($profile_id) {
        return $this->link->get_col("SELECT pf_agency_id FROM {$this->table} WHERE pf_profile_id = {$profile_id}");
    }

}
