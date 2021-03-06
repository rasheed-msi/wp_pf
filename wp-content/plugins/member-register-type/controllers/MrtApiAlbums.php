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
        $this->mrt_role = new MrtRole;
        $this->mrt_album = new MrtAlbum;
        $this->mrt_photo = new MrtPhoto;
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

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'bulk_delete'), array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'bulk_delete_items'),
            'permission_callback' => array($this, 'items_permissions_check'),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'download_items'), array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'download_items'),
        ));
    }

    public function items_permissions_check($request) {

        $this->input = $request->get_params();
        $this->user = $this->auth->validate_token();

        if (!$this->user) {
            return false;
        }

        if (
                isset($this->input['id']) &&
                !$this->mrt_album->is_user_album($this->user->ID, $this->input['id']) &&
                (isset($this->input['user']) && !$this->mrt_role->hasrole('user_edit_dashboard', $this->input['user']))
        ) {
            return false;
        }


        if (isset($this->input['user']) && !$this->mrt_role->hasrole('user_edit_dashboard', $this->input['user'])) {
            return false;
        }

        return true;
    }

    public function get_item($request) {
        $records = $this->mrt_album->get($this->input['id']);
        return new WP_REST_Response($records, 200);
    }

    public function get_items($request) {

        if (isset($this->input['user'])) {
            $records = $this->mrt_album->all($this->input['user']);
            return new WP_REST_Response($records, 200);
        }

        $records = $this->mrt_album->all($this->user->ID);
        return new WP_REST_Response($records, 200);
    }

    public function create_item($request) {

        $validate = $this->validate();

        if ($validate['status']) {
            $id = $this->mrt_album->insert($validate['input']);
            return new WP_REST_Response(['id' => $id], 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    public function update_item($request) {

        $this->mrt_album = MrtAlbum::find($this->input['id']);
        $validate = $this->validate_update();

        if ($validate['status']) {
            $response[] = $this->mrt_album->update($validate['input']);
            return new WP_REST_Response($response, 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    public function delete_item($request) {

        $this->input = $request->get_params();
        $this->mrt_album = MrtAlbum::find($request['id']);

        if (isset($this->mrt_album->id)) {
            $this->mrt_album->delete();
            $this->mrt_photo->update(['deleteFlag' => 1], $this->mrt_album->pkey, $this->mrt_album->id);

            return new WP_REST_Response([], 200);
        }

        return new WP_REST_Response(null, 401);
    }

    public function bulk_delete_items($request) {
        $this->input = $request->get_params();
        $this->mrt_album->bulk_delete(null, $this->input['ids']);
        // $this->input = [1,2,3];
        return new WP_REST_Response($this->input, 200);
    }

    public function validate() {

        if (isset($this->input['user'])) {
            $this->input['user_id'] = $this->input['user'];
        } elseif (empty($this->input['user_id']) && !isset($this->user->ID)) {
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

    public function validate_response() {
        return [
            'status' => ($this->error > 0) ? false : true,
            'message' => $this->message,
            'input' => $this->input,
        ];
    }

    public function download_items($request) {

        $input = $request->get_params();

        $photos = $this->mrt_photo->getset_items($input['ids']);
        $files = [];
        foreach ($photos as $key => $value) {
            $files[] = $value['original'];
        }

        $zip['zip_url'] = State::create_zip($files);
        $zip['files_count'] = count($files);
        return new WP_REST_Response($zip, 200);
    }

}

$d = new MrtApiAlbums();
