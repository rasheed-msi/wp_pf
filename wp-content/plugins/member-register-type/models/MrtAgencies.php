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
        ];
        
        $this->field_default = [];
    }

}
