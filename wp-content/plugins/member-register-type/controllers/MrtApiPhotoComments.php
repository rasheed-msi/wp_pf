<?php

class MrtApiPhotoComments extends WP_REST_Controller {

    protected $rest_base;
    protected $auth;
    protected $route;
    protected $mrt_album;
    protected $mrt_photo;
    protected $mrt_file_stack;
    protected $mrt_photo_comments;

    function __construct() {
        $this->rest_base = 'photo_comment';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        $this->mrt_photo_comment = new MrtPhotoComment;
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

        return true;
    }

    public function get_item($request) {
        $input = $request->get_params();
        $records = $this->mrt_photo_comment->get($request['id']);
        return new WP_REST_Response($records, 200);
    }

    public function get_items($request) {
        $input = $request->get_params();
        $records = $this->mrt_photo_comment->all($request['photo_id']);
        return new WP_REST_Response($records, 200);
    }

    public function create_item($request) {

        $input = $request->get_params();

        $response = [];

        $validate = $this->validate($input);

        if ($validate['status']) {
            $response[] = $this->mrt_photo_comment->insert($validate['input']);
        }

        return new WP_REST_Response($response, 200);
    }

    public function validate($input, $type = 'create') {

        $message = [];
        $errors = 0;

        if (empty($input['photo_id'])) {
            $message['album_id'] = 'Photo not found';
            ++$errors;
        }

        if (empty($input['content'])) {
            $message['content'] = 'Content not found';
            ++$errors;
        }

        if (isset($input['user_id']) && !isset($this->user->ID)) {
            $message['user_id'] = 'User not found';
            ++$errors;
        } else {
            $input['user_id'] = $this->user->ID;
        }

        if ($type == 'create') {
            if (isset($input['created'])) {
                $input['created_at'] = date('Y-m-d H:i:s');
                $input['updated_at'] = date('Y-m-d H:i:s');
            }
        } elseif ($type == 'update') {
            if (isset($input['updated_at'])) {
                $input['updated_at'] = date('Y-m-d H:i:s');
            }
        }

        return [
            'status' => ($errors > 0) ? false : true,
            'message' => $message,
            'input' => $input,
        ];
    }

    public function update_item($request) {
        
        $input = $request->get_params();
        
        $this->mrt_photo_comment = MrtPhotoComment::find($input['id']);
        $validate = $this->validate($input, 'update');
        
        if ($validate['status']) {
            $count = $this->mrt_photo_comment->update($validate['input']);
            return new WP_REST_Response(['count' => $count], 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }
        
        return new WP_REST_Response(null, 401);
    }
    
    function delete_item($request) {
        $input = $request->get_params();
        $this->mrt_photo_comment = MrtAlbum::find($input);

        if (isset($this->mrt_photo_comment->id)) {
            $this->mrt_photo_comment->delete();
            return new WP_REST_Response([], 200);
        }

        return new WP_REST_Response(null, 401);
    }

}

$d = new MrtApiPhotoComments();
