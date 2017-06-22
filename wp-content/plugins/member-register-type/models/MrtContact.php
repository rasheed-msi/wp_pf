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
            'city',
            'state',
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

}