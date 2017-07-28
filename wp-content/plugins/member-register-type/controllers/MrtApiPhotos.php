<?php

class MrtApiPhotos extends WP_REST_Controller {

    protected $namespace;
    protected $post_type;
    protected $rest_base;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        $this->namespace = 'mrt/v1';
        $this->rest_base = 'photos';
        $this->auth = new MrtAuth;
        $this->mrt_photo = new MrtPhoto;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {

        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<album_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            // 'args' => $this->get_collection_params(),
            // 'show_in_index' => false,
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
                'show_in_index' => false,
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));
    }

    public function get_items_permissions_check($request) {

        $this->user = $this->auth->validate_token();

        if (!$this->user) {
            return false;
        }

        if (!isset($request['album_id'])) {
            return false;
        }

        $mrt_album = new MrtAlbum();
        if (!$mrt_album->is_user_album($this->user->ID, $request['album_id'])) {
            return false;
        }

        return true;
    }

    public function get_items($request) {

        $records = $this->mrt_photo->all($request['album_id']);

        if ($records) {
            return new WP_REST_Response($records, 200);
        } else {
            return new WP_REST_Response([], 404);
        }
    }

}

$d = new MrtApiPhotos();
