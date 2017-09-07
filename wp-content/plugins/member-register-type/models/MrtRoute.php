<?php

class MrtRoute {

    private $request;
    public $namespace = 'mrt/v1';
    public $rest_base;
    private $pattern = [
        'id' => '(?P<id>\d+)',
        'user_id' => '(?P<user_id>\d+)',
        'album_id' => '(?P<album_id>\d+)',
        'agency_id' => '(?P<agency_id>\d+)',
        'status_id' => '(?P<status_id>\d+)',
    ];

    function __construct() {
        $this->request = [
            'status' => [
                'set_agency_approve' => "{$this->pattern['agency_id']}/status/{$this->pattern['status_id']}",
            ],
            'albums' => [
                'item' => 'albums',
                'bulk_delete' => 'bulk-delete'
            ],
            'photos' => [
                'item' => "{$this->pattern['album_id']}/photos",
                'download_items' => "download-photos",
            ],
            'users' => [
                'current' => "current",
                'dashboard' => "dashboard",
                'logout' => "logout",
                'login' => "login",
                'token' => "token/{$this->pattern['id']}",
            ],
            'general' => [
                'set_agency_status_approve' => "agency-status/{$this->pattern['agency_id']}/status/{$this->pattern['status_id']}",
                'states' => "states/{$this->pattern['id']}",
            ],
            'filestack_album_processing' => [
                'item' => 'filestack-album-processing'
            ],
        ];

        $this->order = $this->order();
        
    }

    public function base($key, $item) {
        return (isset($this->order[$key][$item])) ? '/' . $this->order[$key][$item] : false;
    }

    public function format($key, $item, $param = []) {

        if (!isset($this->request[$key][$item])) {
            return false;
        }

        $format = $this->request[$key][$item];

        foreach ($param as $key => $value) {
            $format = str_replace($this->pattern[$key], $value, $format);
        }

        return site_url('wp-json/' . $this->namespace) . '/' . $format;
    }

    public function order() {
        foreach ($this->request as $key => $value) {
            $return = [];

            $base = (isset($value['item']))? $value['item']: $key;
            
            if (is_array($value) && isset($value['item'])) {
                $return['index'] = $base;
                $return['create'] = $base;
                $return['delete'] = $base . '/' . $this->pattern['id'];
                $return['update'] = $base . '/' . $this->pattern['id'];
                $return['read'] = $base . '/' . $this->pattern['id'];
                
                $this->request[$key] = array_merge($return, $value);
            }
            
            
            if(is_array($value)){
                $extra = [];
                foreach ($value as $k => $val) {
                    if($k == 'item'){
                        continue;
                    }
                    $this->request[$key][$k] = $base . '/' . $val;
                }
                
            }
        }

        return $this->request;
    }

}
