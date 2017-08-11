<?php

class MrtRoute {

    private $request;
    public $api_namespace = 'mrt/v1';
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
                'set_agency_approve' => "agency-status/{$this->pattern['agency_id']}/status/{$this->pattern['status_id']}",
            ],
            'albums' => [
                'item' => 'albums',
                'get_user_items' => "albums/user/{$this->pattern['user_id']}",
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

        return site_url('wp-json/' . $this->api_namespace) . '/' . $format;
    }

    public function order() {
        foreach ($this->request as $key => $value) {
            $return = [];
            
            if (is_array($value) && isset($value['item'])) {
                $return['index'] = $value['item'];
                $return['create'] = $value['item'];
                $return['delete'] = $value['item'] . '/' . $this->pattern['id'];
                $return['read'] = $value['item'] . '/' . $this->pattern['id'];
                unset($value['item']);
                $this->request[$key] = array_merge($return, $value);
            }
            
        }

        return $this->request;
    }

}