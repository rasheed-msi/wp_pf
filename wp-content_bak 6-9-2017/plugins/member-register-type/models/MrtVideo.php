<?php

class MrtVideo extends MrtDbbase {

    public $link;
    public $table = 'pf_videofiles';
    public $pkey = 'pf_video_id';
    public $id;
    public $fields = [
        'video_category',
        'video_Title',
        'video_Uri',
        'video_Tags',
        'video_Description',
        'video_Time',
        'video_Date',
        'user_id',
        'Views',
        'Rate',
        'RateCount',
        'CommentsCount',
        'Featured',
        'Source',
        'Video',
        'YoutubeLink',
        'home',
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
        return $this->link->get_results(
                "SELECT * FROM {$this->table} WHERE user_id = {$user_id}", ARRAY_A
        );
    }
    
    function getDashboardVideo($user_id) {
        return $this->link->get_row(
                "SELECT * FROM {$this->table} WHERE user_id = {$user_id} LIMIT 1", ARRAY_A
        );
    }
    
}
