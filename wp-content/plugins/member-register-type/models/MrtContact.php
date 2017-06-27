<?php

class MrtContact extends MrtDbbase {

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'pf_contact_details';
        $this->pkey = 'pf_contact_detail_id';
        $this->fields = [
            'pf_profile_id',
            'StreetAddress',
            'City',
            'State',
            'Country',
            'Region',
            'Zip',
            'mobile_num',
            'home_num',
            'office_num',
            'fax_num',
            'DefaultContact',
            'AllowDefaultContact',
            'website',
        ];
        
        $this->field_default = [];
    }

    public function getId($profile_id) {
        if (is_null($profile_id)) {
            return false;
        }
        return $this->link->get_var("SELECT {$this->pkey} FROM {$this->table} WHERE pf_profile_id = {$profile_id}");
    }

   
}
