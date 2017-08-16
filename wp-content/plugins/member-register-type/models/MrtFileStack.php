<?php

class MrtFileStack extends MrtDbbase {

    public $link;
    public $table = 'pf_filestack_photos';
    public $pkey = 'pf_filestack_id';
    public $id;
    public $fields = [
        'pf_photo_id',
        'cloud_filename',
        'user_id',
        'title',
        'cloud_path',
        'view_type',
        'last_updated',
        'deleteFlag',
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
    
    public function translate_filestack($input) {
        
        $input['pf_photo_id'] = $input['image_id'];
        unset($input['image_id']);
        
        return $input;
    }
    
    
}
