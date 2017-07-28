<?php

class MrtAlbum extends MrtDbbase {

    public $link;
    public $table = 'pf_albums';
    public $pkey = 'pf_album_id';
    public $id;
    public $fields = [
        'caption',
        'uri',
        'location',
    ];
    public $field_default = [];
    public $data;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    function all($user_id) {
        return $this->link->get_results(
                "SELECT * FROM {$this->table} WHERE user_id = {$user_id}", ARRAY_A
        );
        
    }

    function get_photos($album_id) {
        $this->link->get_result(
                "SELECT * "
                . "FROM sys_albums_objects rel "
                . "LEFT JOIN sys_albums al ON al.ID = rel.id_album "
                . "LEFT JOIN bx_photos_main pm ON pm.ID = rel.id_object "
                . "WHERE rel.id_album = {$album_id} "
        );
    }
    
    public function is_user_album($user_id, $album_id) {
        return $this->link->get_var(
                        "SELECT COUNT(*) FROM {$this->table} WHERE user_id = {$user_id} AND pf_album_id = {$album_id}"
        );
    }

}