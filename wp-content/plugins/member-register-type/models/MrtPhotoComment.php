<?php

class MrtPhotoComment extends MrtDbbase {

    public $link;
    public $table = 'pf_photo_comments';
    public $pkey = 'id';
    public $id;
    public $fields = [
        'content',
        'user_id',
        'pf_photo_id',
        'created_at',
        'updated_at',
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
        
        $where_key = (is_null($where_key)) ? 'c.pf_photo_id' : $where_key;
        $id = (is_null($id)) ? $this->id : $id;
        
        return $this->link->get_results(
                "SELECT "
                . "DISTINCT c.id, "
                . "c.*, "
                . "CONCAT(p.first_name, ' ', p.spouse_first_name) AS display_name, "
                . "CONCAT('" . MRT_URL_AVATHAR . "/', p.avatar, '.jpg') AS avatar "
                . "FROM {$this->table} c "
                . "RIGHT JOIN pf_profiles p ON p.wp_user_id = c.user_id "
                . "WHERE {$where_key} = {$id} ORDER BY {$this->pkey} DESC", ARRAY_A
        );

    }
    
}
