<?php

class MrtApiPhotos extends WP_REST_Controller {

    protected $rest_base;
    protected $auth;
    protected $route;
    protected $mrt_album;
    protected $mrt_photo;
    protected $mrt_file_stack;

    function __construct() {
        $this->rest_base = 'photos';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        $this->mrt_album = new MrtAlbum;
        $this->mrt_photo = new MrtPhoto;
        $this->mrt_file_stack = new MrtFileStack;
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
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'download_items'),
                'permission_callback' => array($this, 'items_permissions_check'),
            ),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'download_items'), array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'download_items'),
                // 'permission_callback' => array($this, 'items_permissions_check'),
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

        // Photo id
        if (isset($input['id'])) {
            if (!$this->mrt_photo->is_user_photo($this->user->ID, $input['id'])) {
                return false;
            }
        }

        return true;
    }

    public function get_item($request) {
        $records = $this->mrt_photo->get($request['id']);
        return new WP_REST_Response($records, 200);
    }

    public function get_items($request) {
        
        $input = $request->get_params();
        
        if (isset($input['processing'])) {
            $records['photos'] = $this->mrt_photo->all($input['album_id']);
            $mrt_filestack_album_processing = new MrtFilestackAlbumProcessing();
            $records['processing_img_count'] = count($mrt_filestack_album_processing->all($input['album_id'], 'pf_album_id'));
        }else{
            $records = $this->mrt_photo->all($input['album_id']);
        }
        
        return new WP_REST_Response($records, 200);
    }

    public function create_item($request) {

        $input = $request->get_params();

        //return new WP_REST_Response($input, 200);
        $validate = $this->validate($input);

        if ($validate['status']) {
            $validate['input']['pf_photo_id'] = $this->mrt_photo->insert($validate['input']);

            $filestack_validate = $this->validate_filestack($validate['input']);

            if ($filestack_validate['status']) {
                $this->mrt_file_stack->insert($filestack_validate['input']);
            } else {
                $this->mrt_photo->delete(null, $validate['input']['pf_photo_id']);
                return new WP_REST_Response(['message' => $filestack_validate['message']], 400);
            }

            return new WP_REST_Response(['id' => $validate['input']['pf_photo_id']], 200);
        } else {
            return new WP_REST_Response(['validate' => $validate], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    public function update_item($request) {

        $input = $request->get_params();
        $this->mrt_photo = MrtPhoto::find($input['id']);
        $validate = $this->validate($input);

        if ($validate['status']) {

            $this->mrt_photo->update($validate['input']);
            $validate['input']['pf_photo_id'] = $this->mrt_photo->id;
            $filestack_validate = $this->validate_filestack($validate['input']);

            if ($filestack_validate['status']) {
                if (isset($filestack_validate['input']['change_photo']) && $filestack_validate['input']['change_photo'] == true) {
                    $this->mrt_file_stack->delete('pf_photo_id', $this->mrt_photo->id);
                    $this->mrt_file_stack->insert($filestack_validate['input']);
                }
            } else {
                return new WP_REST_Response(['message' => $filestack_validate['message']], 400);
            }

            return new WP_REST_Response([], 200);
        } else {
            return new WP_REST_Response(['message' => $validate['message']], 400);
        }

        return new WP_REST_Response(null, 401);
    }

    public function delete_item($request) {

        $this->mrt_photo = MrtPhoto::find($request['id']);

        if (isset($this->mrt_photo->id)) {
            $this->mrt_photo->delete();

            $this->mrt_file_stack->update(['deleteFlag' => 1], 'pf_photo_id', $this->mrt_photo->id);
            return new WP_REST_Response([], 200);
        }

        return new WP_REST_Response(null, 401);
    }

    public function validate($input) {

        $message = [];
        $errors = 0;

        if (empty($input['album_id'])) {
            $message['pf_album_id'] = 'Album not found';
            ++$errors;
        } else {
            $input['pf_album_id'] = $input['album_id'];
        }

        if (empty($input['user_id']) && !isset($this->user->ID)) {
            $message['user_id'] = 'User not found';
            ++$errors;
        } else {
            $input['user_id'] = $this->user->ID;
        }
        
        if (empty($input['photo_Date'])) {
            $input['photo_Date'] = time();
        }

        return [
            'status' => ($errors > 0) ? false : true,
            'message' => $message,
            'input' => $input,
        ];
    }

    function validate_filestack($input) {

        $message = [];
        $errors = 0;

        if (empty($input['pf_photo_id'])) {
            $message['pf_photo_id'] = 'No photo id found';
            ++$errors;
        }

        if (empty($input['user_id']) && !isset($this->user->ID)) {
            $message['user_id'] = 'User not found';
            ++$errors;
        } else {
            $input['user_id'] = $this->user->ID;
        }

        if (empty($input['last_updated'])) {
            $input['last_updated'] = date('Y-m-d H:i:s');
        }

        return [
            'status' => ($errors > 0) ? false : true,
            'message' => $message,
            'input' => $input,
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

$d = new MrtApiPhotos();
