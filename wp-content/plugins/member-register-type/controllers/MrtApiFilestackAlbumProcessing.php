<?php

class MrtApiFilestackAlbumProcessing extends WP_REST_Controller {

    protected $rest_base;
    protected $auth;
    protected $route;
    protected $mrt_album;
    protected $mrt_photo;
    protected $mrt_file_stack;

    function __construct() {
        $this->rest_base = 'filestack_album_processing';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        $this->mrt_album = new MrtAlbum;
        $this->mrt_photo = new MrtPhoto;
        $this->mrt_file_stack = new MrtFileStack;
        $this->mrt_filestack_album_processing = new MrtFilestackAlbumProcessing;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'index'), array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'items_permissions_check'),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'items_permissions_check'),
            ),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'read'), array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'items_permissions_check'),
            ),
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_item'),
                'permission_callback' => array($this, 'items_permissions_check'),
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_item'),
                'permission_callback' => array($this, 'items_permissions_check'),
            ),
        ));
    }

    public function items_permissions_check($request) {

        $input = $request->get_params();
        $this->user = $this->auth->validate_token();

        if (!$this->user) {
            return false;
        }

        if (!isset($input['album_id'])) {
            return false;
        }

        if (!$this->mrt_album->is_user_album($this->user->ID, $input['album_id'])) {
            return false;
        }

        return true;
    }

    public function get_item($request) {
        $records = $this->mrt_filestack_album_processing->get($request['id']);
        return new WP_REST_Response($records, 200);
    }

    public function get_items($request) {
        $records = $this->mrt_filestack_album_processing->all($request['album_id']);
        return new WP_REST_Response($records, 200);
    }

    public function create_item($request) {

        $input = $request->get_params();

        $response = [];

        $validate = $this->validate($input);

        if ($validate['status']) {
            $response[] = $this->mrt_filestack_album_processing->insert($validate['input']);
        }

        return new WP_REST_Response($response, 200);
    }

    public function update_item($request) {
        
    }

    public function delete_item($request) {
        
    }

    public function validate($input) {

        $message = [];
        $errors = 0;

        if (empty($input['album_id'])) {
            $message['album_id'] = 'Album not found';
            ++$errors;
        }
        if (empty($input['url'])) {
            $message['url'] = 'URL not found';
            ++$errors;
        }

        if (isset($input['user_id']) && !isset($this->user->ID)) {
            $message['user_id'] = 'User not found';
            ++$errors;
        } else {
            $input['user_id'] = $this->user->ID;
        }

        if (empty($input['key'])) {
            $message['cloud_filename'] = 'cloud filename not found';
            ++$errors;
        } else {
            $input['cloud_filename'] = $input['key'];
        }

        if (empty($input['size'])) {
            $message['size'] = 'size not found';
            ++$errors;
        } else {
            $input['size'] = $input['size'];
        }

        if (isset($input['created'])) {
            $input['created'] = date('Y-m-d H:i:s');
        }

        $input['status'] = 'pending';

        return [
            'status' => ($errors > 0) ? false : true,
            'message' => $message,
            'input' => $input,
        ];
    }

}

$d = new MrtApiFilestackAlbumProcessing();
