<?php

class MrtApiAlbums extends WP_REST_Controller {

    protected $rest_base;
    protected $auth;
    protected $route;
    protected $mrt_album;
    protected $mrt_file_stack;
    protected $message;
    protected $error = 0;

    function __construct() {
        $this->rest_base = 'albums';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        $this->mrt_album = new MrtAlbum;
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

        if (isset($input['id'])) {
            if (!$this->mrt_album->is_user_album($this->user->ID, $input['id'])) {
                return false;
            }
        }

        return true;
    }

    public function get_items($request) {

        $records = $this->mrt_album->all($this->user->ID);

        if ($records) {
            return new WP_REST_Response($records, 200);
        } else {
            return new WP_REST_Response([], 404);
        }
    }

    public function create_item($request) {
        $this->input = $request->get_params();
        $validate = $this->validate();
        
        if ($validate['status']) {
            $id = $this->mrt_album->insert($validate['input']);
            return new WP_REST_Response(['id' => $id], 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    public function get_item($request) {
        $records = $this->mrt_album->get($request['id']);

        if ($records) {
            return new WP_REST_Response($records, 200);
        } else {
            return new WP_REST_Response([], 404);
        }
    }

    public function update_item($request) {

        $this->input = $request->get_params();
        $this->mrt_album = MrtAlbum::find($this->input['id']);
        $validate = $this->validate_update();

        if ($validate['status']) {
            $this->mrt_album->update($validate['input']);
            return new WP_REST_Response([], 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    public function delete_item($request) {

        $this->mrt_album = MrtAlbum::find($request['id']);

        if (isset($this->mrt_album->id)) {
            $this->mrt_album->delete();
            return new WP_REST_Response([], 200);
        }

        return new WP_REST_Response(null, 401);
    }

    public function validate() {

        if (empty($this->input['user_id']) && !isset($this->user->ID)) {
            $this->message['user_id'] = 'User not found';
            ++$this->error;
            return $this->validate_response();
        } else {
            $this->input['user_id'] = $this->user->ID;
        }

        if (empty($this->input['caption'])) {
            $this->message['caption'] = 'Please enter a caption';
            ++$this->error;
            return $this->validate_response();
        }

        if (empty($this->input['uri'])) {
            $this->input['uri'] = Dot::get_slug($this->input['caption']);
        }

        if (empty($this->input['album_Date'])) {
            $this->input['album_Date'] = time();
        }

        return $this->validate_response();
    }
    
    public function validate_update() {
        
        if (isset($this->input['caption'])) {
            $this->input['uri'] = Dot::get_slug($this->input['caption']);
        }
        
        if (empty($this->input['album_Date'])) {
            $this->input['album_Date'] = time();
        }
        
        return $this->validate_response();
    }

    function validate_response() {
        return [
            'status' => ($this->error > 0) ? false : true,
            'message' => $this->message,
            'input' => $this->input,
        ];
    }

}

$d = new MrtApiAlbums();
