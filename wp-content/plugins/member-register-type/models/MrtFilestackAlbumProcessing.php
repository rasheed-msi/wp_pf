<?php

class MrtFilestackAlbumProcessing extends MrtDbbase {

    public $link;
    public $table = 'pf_filestack_album_processing';
    public $pkey = 'id';
    public $id;
    public $fields = [
        'album_id',
        'url',
        'cloud_filename',
        'size',
        'status',
        'created',
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
        return parent::find($id, $key, __CLASS__);
    }

}
