<?php

class MrtParentFilestackPhoto extends MrtDbbase {

    public $link;
    public $table = 'pf_parent_filestack_photos';
    public $pkey = 'parent_photo_id';
    public $id;
    public $fields = [
        'cloud_filename',
        'user_id',
        'title',
        'cloud_path',
        'view_type',
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
    
    public function all($id = null, $where_key = null) {

        $where_key = (is_null($where_key)) ? $this->pkey : $where_key;
        $id = (is_null($id)) ? $this->id : $id;

        if (is_null($id)) {
            return [];
        }

        return $this->link->get_results("SELECT * FROM {$this->table} WHERE {$where_key} = {$id}", ARRAY_A);
    }
    
}
