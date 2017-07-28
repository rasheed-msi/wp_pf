<?php

class MrtPhoto extends MrtDbbase {

    public $link;
    public $table = 'pf_photos';
    public $pkey = 'pf_photo_id';
    public $id;
    public $fields = [
    ];
    public $field_default = [];
    public $data;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    function all($album_id) {
        return $this->link->get_results(
                        "SELECT * FROM {$this->table} WHERE pf_album_id = {$album_id} ORDER BY display_order ASC", ARRAY_A
        );
    }

}
