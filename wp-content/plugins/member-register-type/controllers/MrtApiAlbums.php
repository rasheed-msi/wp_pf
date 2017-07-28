<?php

class MrtApiAlbums extends WP_REST_Controller {

    protected $namespace;
    protected $post_type;
    protected $rest_base;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        $this->namespace = 'mrt/v1';
        $this->rest_base = 'albums';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {

        register_rest_route($this->namespace, $this->route->base($this->rest_base, 'index'), array(
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
//                'permission_callback' => array($this, 'create_item_permissions_check'),
//                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
//                'show_in_index' => false,
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

        register_rest_route($this->namespace, $this->route->base($this->rest_base, 'get_user_items'), array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_user_items'),
            //  'permission_callback' => array($this, 'get_user_items_permissions_check'),
            // 'args' => $this->get_collection_params(),
            // 'show_in_index' => false,
            ),
        ));
    }

    public function get_items_permissions_check($request) {

        $this->user = $this->auth->validate_token();

        if (!$this->user) {
            return false;
        }

        return true;
    }

    public function get_items($request) {

        $obj = new MrtAlbum();
        $records = $obj->all($this->user->ID);

        if ($records) {
            return new WP_REST_Response($records, 200);
        } else {
            return new WP_REST_Response([], 404);
        }
    }

    public function get_user_items($request) {
        $data = $request->get_params();
        return new WP_REST_Response($data, 200);
    }

}

$d = new MrtApiAlbums();
