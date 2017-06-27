<?php

class MrtApiController extends WP_REST_Controller {

    public function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        add_action('rest_api_init', [$this, 'register_routes']);
        //$this->state = new MrtMidController();
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $version = '1';
        $namespace = 'mrt-vendor/v' . $version;
        $base = 'route';

        register_rest_route('mrt/v1', '/states/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_states'),
            'args' => array(
            ),
        ));
    }

    function get_states($request) {
        $params = $request->get_params();
        $states = Dot::get_states($params['id']);
        return new WP_REST_Response($states, 200);
    }
    

}

$d = new MrtApiController();