<?php

class MrtPhoto extends MrtDbbase {

    public $link;
    public $table = 'pf_photos';
    public $pkey = 'pf_photo_id';
    public $id;
    public $fields = [
        'pf_album_id',
        'Categories',
        'user_id',
        'Ext',
        'Size',
        'Title',
        'Uri',
        'photo_Desc',
        'Tags',
        'photo_Date',
        'Views',
        'Rate',
        'RateCount',
        'CommentsCount',
        'Featured',
        'photo_Status',
        'photo_Hash',
        'display_order',
        'old_photo_id',
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

    public function get($id = null, $where_key = null) {
        $where_key = (is_null($where_key)) ? $this->pkey : $where_key;
        $id = (is_null($id)) ? $this->id : $id;

        $photos = $this->link->get_results(
                "SELECT *, p.pf_photo_id, p.user_id FROM {$this->table} p "
                . "LEFT JOIN pf_filestack_photos fp ON fp.pf_photo_id = p.pf_photo_id "
                . "WHERE p.{$where_key} = {$id} ORDER BY p.{$this->pkey} DESC", ARRAY_A
        );

        $records = $this->get_photo_views($photos);
        return isset($records[$id])? $records[$id] : [];
    }

    public function all($album_id) {
        $photos = $this->link->get_results(
                "SELECT *, p.pf_photo_id, p.user_id FROM {$this->table} p "
                . "LEFT JOIN pf_filestack_photos fp ON fp.pf_photo_id = p.pf_photo_id "
                . "WHERE p.pf_album_id = {$album_id} ORDER BY p.{$this->pkey} DESC", ARRAY_A
        );

        $records = $this->get_photo_views($photos);
        return $records;
    }
    
    public function getset_items($ids = null, $where_key = null) {
        if(!is_array($ids)){
            return [];
        }
        
        $where_key = (is_null($where_key)) ? $this->pkey : $where_key;
        
        $ids_str = implode(', ', $ids);

        $photos = $this->link->get_results(
                "SELECT *, p.pf_photo_id, p.user_id FROM {$this->table} p "
                . "LEFT JOIN pf_filestack_photos fp ON fp.pf_photo_id = p.pf_photo_id "
                . "WHERE p.{$where_key} IN ({$ids_str}) ORDER BY p.{$this->pkey} DESC", ARRAY_A
        );

        $records = $this->get_photo_views($photos);
        return $records;
    }

    public function get_photo_views($photos) {
        $records = [];
        foreach ($photos as $key => $photo) {
            if (isset($photo['cloud_filename']) && $photo['cloud_filename'] != "") {

                // Filestack images

                if ($photo['view_type'] == 'thumb') {
                    $photo['thumb'] = MRT_URL_S3BUCKET . '/' . $photo['cloud_filename'];
                }

                if ($photo['view_type'] == 'webview') {
                    $photo['webview'] = MRT_URL_S3BUCKET . '/' . $photo['cloud_filename'];
                }

                if ($photo['view_type'] == 'original') {
                    $photo['original'] = MRT_URL_S3BUCKET . '/' . $photo['cloud_filename'];
                }
                
                
            } else {
                // Server images
                $photo['original'] = MRT_URL_PHOTOS . '/' . $photo['filename'] . '.' . $photo['Ext'];
                $photo['thumb'] = MRT_URL_PHOTOS . '/' . $photo['filename'] . '_t.' . $photo['Ext'];
                $photo['webview'] = MRT_URL_PHOTOS . '/' . $photo['filename'] . '_m.' . $photo['Ext'];
            }
            
            $photo['Size'] = intval($photo['Size'] / 1000); // KB

            if (isset($records[$photo[$this->pkey]])) {
                $records[$photo[$this->pkey]] = array_merge($records[$photo[$this->pkey]], $photo);
            } else {
                $records[$photo[$this->pkey]] = $photo;
            }
        }

        return $records;
    }

    public function is_user_photo($user_id, $photo_id) {
        return $this->link->get_var(
                        "SELECT COUNT(*) FROM {$this->table} WHERE user_id = {$user_id} AND {$this->pkey} = {$photo_id}"
        );
    }
    
    public function get_album_thumbnail($album_id) {
        
        $photo_id = $this->link->get_var("SELECT {$this->pkey} FROM {$this->table} WHERE pf_album_id = {$album_id} LIMIT 1");
        
        $photo = $this->get($photo_id);
        
        return isset($photo['thumb'])? $photo['thumb'] : MRT_URL_DEFAULT_PHOTOS_THUMB;
        
    }
    
}
