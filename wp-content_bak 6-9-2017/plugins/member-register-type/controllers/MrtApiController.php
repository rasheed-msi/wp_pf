<?php

class MrtApiController extends WP_REST_Controller {

    public static $api_namespace = 'mrt/v1';
    public static $request = [
        'set_agency_status_approve' => [
            'base' => '/agency-status/(?P<agency_id>\d+)/status/(?P<status_id>\d+)',
            'format' => '/agency-status/{{agency_id}}/status/{{status_id}}',
        ],
        'get_states' => [
            'base' => '/states/(?P<id>\d+)',
            'format' => '/states/{{id}}',
        ],
        'get_lstates' => [
            'base' => '/lstates',
            'format' => '/lstates/{{term}}',
        ],
        'user_login' => [
            'base' => '/login',
            'format' => '/login',
        ],
        'user_logout' => [
            'base' => '/logout',
            'format' => '/logout',
        ],
        'get_current_user' => [
            'base' => '/current-user',
            'format' => '/current-user',
        ],
        'get_user_token' => [
            'base' => '/token-user/(?P<user_id>\d+)',
            'format' => '/token-user',
        ],
    ];
    private $auth;

    public function __construct() {

        $this->auth = new MrtAuth();
        add_action('rest_api_init', [$this, 'register_routes']);
        //$this->state = new MrtMidController();
    }

    public static function base($id) {
        return (isset(self::$request[$id]['base'])) ? self::$request[$id]['base'] : false;
    }

    public static function format($id, $param = []) {

        $format = (isset(self::$request[$id]['format'])) ? self::$request[$id]['format'] : false;

        if (!$format) {
            return false;
        }

        foreach ($param as $key => $value) {
            $format = str_replace('{{' . $key . '}}', $value, $format);
        }

        return site_url('wp-json/' . self::$api_namespace) . $format;
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $version = '1';
        $namespace = 'mrt/v' . $version;
        $base = 'route';

        register_rest_route('mrt/v1', self::base('get_states'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_states'),
            'args' => array(
            ),
        ));
        register_rest_route('mrt/v1', self::base('get_lstates'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_lstates'),
            'args' => array(
            ),
        ));

        register_rest_route('mrt/v1', self::base('set_agency_status_approve'), array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'set_agency_status_approve'),
            'args' => array(
            ),
        ));

       
        register_rest_route($namespace, self::base('get_user_token'), [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_user_token'],
        ]);
    }

    public function get_user_token($request) {
        $user_id = $request['user_id'];
        $data['user_id'] = $user_id;
        $data['_token'] = $this->auth->create_token($user_id);
        return new WP_REST_Response($data, 200);
    }

    

    function get_states($request) {
        $params = $request->get_params();
        $states = State::get_states($params['id']);
        return new WP_REST_Response($states, 200);
    }
    function get_lstates($request) {
        $params = $request->get_params();
        $states = State::get_statesByLetter($params['term']);
        return new WP_REST_Response($states, 200);
    }

    /**
     * 
     * Set agency approved by super admin
     * @param type $request
     * @return \WP_REST_Response
     */
    function set_agency_status_approve($request) {
        $response = [];
        $params = $request->get_params();

        $agency = new MrtAgencies();
        $data['status'] = $params['status_id']; // Set status:approve
        $agency->id = $params['agency_id'];
        if ($agency->update($data)) {
            $response['message'] = 'Agency status updated';
            $new_agency_status = Temp::get_new_agency_status($params['status_id']);
            $response['new_url'] = MrtApiController::format('set_agency_status_approve', ['agency_id' => $params['agency_id'], 'status_id' => $new_agency_status['new_status_id']]);
            $response['new_label'] = $new_agency_status['new_label'];
            $response['status'] = 200;
            return new WP_REST_Response($response, 200);
        } else {
            $response['message'] = 'Agency not found';
            return new WP_REST_Response($response, 404);
        }
    }

}

$d = new MrtApiController();
