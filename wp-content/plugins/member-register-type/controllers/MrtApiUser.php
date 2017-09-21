<?php

class MrtApiUser extends WP_REST_Controller {

    protected $rest_base;
    protected $auth;
    protected $route;
    protected $mrt_photo;
    private $mrt_user;

    function __construct() {
        $this->rest_base = 'users';
        $this->auth = new MrtAuth;
        $this->route = new MrtRoute;
        $this->mrt_photo = new MrtPhoto;

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'current'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'current'),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'read'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'read'),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'login'), array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'login'),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'logout'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'logout'),
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'about'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'about'),
            'permission_callback' => array($this, 'dashboard_permissions_check')
        ));
        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'vitals'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'vitals'),
            'permission_callback' => array($this, 'dashboard_permissions_check')
        ));

        register_rest_route($this->route->namespace, $this->route->base($this->rest_base, 'token'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'token'),
        ));
    }

    function read($request) {
        $params = $request->get_params();
        $mrt_user = new MrtUser($params['id']);
        $data['profile'] = $mrt_user->profile->data;
        return new WP_REST_Response($data, 200);
    }

    function current($request) {
        // $params = $request->get_params();
        $data['user'] = $this->auth->validate_token();
        $data['user_id'] = get_current_user_id();

        $mrt_user = new MrtUser($data['user_id']);
        $data['profile'] = $mrt_user->profile->data;
        return new WP_REST_Response($data, 200);
    }

    function dashboard_permissions_check($request) {
        $input = $request->get_params();
        $this->user = $this->auth->validate_token();

        if (!$this->user) {
            return false;
        }

        return true;
    }

    function vitals($request) {
        $input = $request->get_params();
        $data = [];

        $this->mrt_user = new MrtUser($this->user->ID);
        $this->mrt_user->profile->set_info();
        $this->mrt_user->set_preferences();

        if (isset($input['html'])) {
            $data['html'] = Temp::vitals($this->mrt_user->profile->data, $this->mrt_user->profile->info, $this->mrt_user->preferences);
        } else {
            $data['profile'] = $this->mrt_user->profile->data;
            $data['info'] = $this->mrt_user->profile->info;
            $data['preferences'] = $this->mrt_user->preferences;
        }

        return new WP_REST_Response($data, 200);
    }

    function about($request) {

        $this->mrt_user = new MrtUser($this->user->ID);
        $this->mrt_video = new MrtVideo($this->user->ID);

        $this->mrt_user->profile->set_info();
        $this->mrt_user->set_preferences();

        $data['profile'] = $this->mrt_user->profile->data;
        $data['info'] = $this->mrt_user->profile->info;
        $data['preferences'] = $this->mrt_user->preferences;

        $video = $this->mrt_video->getDashboardVideo($this->user->ID);
        $data['info']['YoutubeLink'] = $video['YoutubeLink'];

        if ($video['YoutubeLink']) {
            $data['info']['video_url'] = MRT_URL_YOUTUBE_EMBED . '/' . $video['video'];
        } else {
            $data['info']['video_url'] = MRT_URL_VIDEO . '/' . $video['video'];
        }


        return new WP_REST_Response($data, 200);
    }

    function login($request) {

        $creds['user_login'] = $request['username'];
        $creds['user_password'] = $request['password'];

        $user = wp_signon($creds);

        if (!is_wp_error($user)) {
            $auth = new MrtAuth();
            $token = $auth->set_token($user->ID);
            return new WP_REST_Response(['user' => $user->user_login, 'token' => $token], 200);
        }

        return new WP_REST_Response([], 401);
    }

    function logout($request) {

        $user = $this->auth->validate_token();

        if ($user) {
            $this->auth->clear_token($user->ID);
            return new WP_REST_Response(null, 200);
        }

        return new WP_REST_Response(null, 401);
    }

    public function token($request) {


        $header['Apikey'] = $request->get_header('Apikey');

        $APIKEY = 'IvEMjYyMjg5xMKLHMDM1MTI3MzY4';

        if (!is_null($header['Apikey']) && $header['Apikey'] == $APIKEY) {
            $user = get_user_by('ID', $request['id']);
            if ($user) {
                if (isset($user->api_token)) {
                    return new WP_REST_Response(['Token' => $user->api_token], 200);
                } else {
                    $token = $this->auth->set_token($user->ID);
                    return new WP_REST_Response(['Token' => $token], 200);
                }
            }

            return new WP_REST_Response(null, 404);
        }

        return new WP_REST_Response(null, 400);
    }

}

$d = new MrtApiUser();
