<?php

namespace OAuth2Demo\Server\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class Resource
{
    // Connects the routes in Silex
    public static function addRoutes($routing)
    {
        $routing->get('/resource', array(new self(), 'resource'))->bind('access');
    }

    /**
     * This is called by the client app once the client has obtained an access
     * token for the current user.  If the token is valid, the resource (in this
     * case, the "friends" of the current user) will be returned to the client
     */
    public function resource(Application $app)
    {
        // get the oauth server (configured in src/OAuth2Demo/Server/Server.php)
        $server = $app['oauth_server'];

        // get the oauth response (configured in src/OAuth2Demo/Server/Server.php)
        $response = $app['oauth_response'];

		
        if (!$server->verifyResourceRequest($app['request'], $response)) {
            return $server->getResponse();
        } else {
            // return a fake API response - not that exciting
            // @TODO return something more valuable, like the name of the logged in user
			
			//MO
			$token = $server->getAccessTokenData($app['request'], $response);
			if(isset($token['user_id'])){
				$user_info = get_userdata($token['user_id']);
				$api_response = array(
					'profile' => array(
						'username' => $user_info->user_login,
						'email' =>  $user_info->user_email,
						'first_name' =>  $user_info->first_name,
						'last_name' =>  $user_info->last_name,
						'display_name' =>  $user_info->display_name,
						'nickname' =>  $user_info->nickname,
						'roles' =>  implode(', ', $user_info->roles),
						'avatar' => get_avatar( $token['user_id'], 32 )
					)
				);
			} else {
				$api_response = array('response' =>  array('error' => 'Some error'));
				
			}
			
            return new Response(json_encode($api_response));
        }
    }
}
