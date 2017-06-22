<?php

class MrtRelationAgencyUser extends MrtDbbase {

    public function __construct() {

        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'pf_agency_users';
        $this->pkey = 'id';
        $this->fields = [
            'pf_agency_id',
            'wp_users_id',
            'pf_profile_id',
            'is_admin',
        ];

        $this->field_default = [];
    }

}
