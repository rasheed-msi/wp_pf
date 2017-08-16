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
        'description',
        'album_type_id',
        'user_id',
        'album_Status',
        'album_Date',
        'ObjCount',
        'LastObjId',
        'AllowAlbumView',
        'old_album_id',
    ];
    public $field_default = [];
    public $data;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }
    
    public static function get_object($class = null) {
        return parent::get_object(__CLASS__);
    }

    public static function find($id, $key = null, $class = null) {
        return parent::find($id, $key, __CLASS__);
    }

    function all($user_id) {
        $albums = $this->link->get_results(
                        "SELECT * FROM {$this->table} WHERE user_id = {$user_id}", ARRAY_A
        );
                        
        $mrt_photo = new MrtPhoto;

        foreach ($albums as $key => $value) {
            $albums[$key]['album_thumb'] = $mrt_photo->get_album_thumbnail($value[$this->pkey]);
        }
        
        return $albums;
    }
    
    public function is_user_album($user_id, $album_id) {
        return $this->link->get_var(
                        "SELECT COUNT(*) FROM {$this->table} WHERE user_id = {$user_id} AND pf_album_id = {$album_id}"
        );
    }


      
    
    
    

}
