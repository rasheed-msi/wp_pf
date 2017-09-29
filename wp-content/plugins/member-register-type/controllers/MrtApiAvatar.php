<?php

class MrtApiAvatar extends WP_REST_Controller {

    protected $rest_base;
    protected $auth;
    protected $route;
    protected $mrt_album;
    protected $mrt_photo;
    protected $mrt_file_stack;
    protected $mrt_photo_comments;
    private $input;

    function __construct() {
        $this->rest_base = 'avatar';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        $this->mrt_avatar = new MrtAvatar;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'index'), array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'read_permission'),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'change_permission'),
            ),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'read'), array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'read_permission'),
            ),
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_item'),
                'permission_callback' => array($this, 'change_permission'),
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_item'),
                'permission_callback' => array($this, 'change_permission'),
            ),
        ));
    }

    public function read_permission($request) {

        $this->input = $request->get_params();
        
        $this->user = $this->auth->validate_token();

        return true;
    }

    public function change_permission($request) {

        $this->input = $request->get_params();

        $this->user = $this->auth->validate_token();

        if (!$this->user) {
            return false;
        }

        return true;
    }

    public function get_item($request) {
        $records = $this->mrt_avatar->get($this->user->ID, 'user_id');
        return new WP_REST_Response($records, 200);
    }

    public function get_items($request) {
        $records = $this->mrt_avatar->all($this->user->ID, 'user_id');
        return new WP_REST_Response($records, 200);
    }

    public function create_item($request) {

        $response = [];

        $validate = $this->validate($this->input);

        if ($validate['status']) {
            $response[] = $this->mrt_avatar->insert($validate['input']);
        }

        return new WP_REST_Response($response, 200);
    }

    public function update_item($request) {

        $this->mrt_avatar = MrtAvatar::find($this->input['id']);

        $validate = $this->validate('update');

        if ($validate['status']) {
            $count = $this->mrt_avatar->update($validate['input']);
            return new WP_REST_Response(['count' => $count], 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    function delete_item($request) {

        $this->mrt_avatar = MrtAlbum::find($this->input);

        if (isset($this->mrt_avatar->id)) {
            $this->mrt_avatar->delete();
            return new WP_REST_Response([], 200);
        }

        return new WP_REST_Response(null, 404);
    }

    public function validate($type = 'create') {

        $message = [];
        $errors = 0;

        if (empty($this->input['cloud_path'])) {
            $message['cloud_path'] = 'Cloud path not found';
            ++$errors;
        }

        if (empty($this->input['cloud_filename'])) {
            $message['cloud_filename'] = 'Cloud filename not found';
            ++$errors;
        }
        if (empty($this->input['view_type'])) {
            $message['view_type'] = 'View type not found';
            ++$errors;
        }

        if (isset($this->input['user_id']) && !isset($this->user->ID)) {
            $message['user_id'] = 'User not found';
            ++$errors;
        } else {
            $this->input['user_id'] = $this->user->ID;
        }

        return [
            'status' => ($errors > 0) ? false : true,
            'message' => $message,
            'input' => $this->input,
        ];
    }

}

$d = new MrtApiAvatar;