<?php

class MrtContact extends MrtDbbase {

    public $link;
    public $table = 'pf_contact_details';
    public $pkey = 'pf_contact_detail_id';
    public $id;
    public $fields = [
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
    public $field_default = [];
    public $data;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    public static function find($id, $key = null, $class = null) {
        return parent::find($id, $key, __CLASS__);
    }

}
