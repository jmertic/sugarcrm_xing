<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/externalAPI/Base/OAuthPluginBase.php');

class ExtAPIXing extends OAuthPluginBase {
    public $authMethod = 'oauth';
    public $useAuth = true;
    public $requireAuth = true;
    public $supportedModules = array();
    public $connector = "ext_rest_xing";

	protected $oauthReq = "https://api.xing.com/v1/request_token";
    protected $oauthAuth = 'https://api.xing.com/v1/authorize';
    protected $oauthAccess = 'https://api.xing.com/v1/access_token';
    protected $oauthParams = array(
    	'signatureMethod' => 'HMAC-SHA1',
    );

    /**
     * Gets a Xing users profile
     * @param $user Xing user id
     * @return array
     */
    public function getProfile($user)
    {
        try {
            $reply = $this->makeRequest('GET', 'https://api.xing.com/v1/users/'.$user,array());
        } catch ( Exception $e ) {
            
        }

        $result = json_decode($reply,true);
        if ( isset($result['error']) ) {
            return $result;
        }
        $profile = array_pop(array_pop($result));

        if ( is_null($profile['id']) ) {
            $GLOBALS['log']->error('Xing failed, reply said: '.$reply);
            $result = array();
            $result['error'] = "Xing user '{$user}' not found";
            return $result;
        }

        return $profile;
    }

    protected function makeRequest($requestMethod, $url, $urlParams = null, $postData = null )
    {
        if ( $urlParams == null ) {
            $urlParams = array();
        }

        $headers = array(
            "User-Agent: SugarCRM",
            "Content-Type: application/json",
            "Accept-Header: application/json",
            "Content-Length: ".strlen($postData),
            );

        $oauth = $this->getOauth();

        $rawResponse = $oauth->fetch($url, $urlParams, $requestMethod, $headers);

        $jsonResponse = json_decode($rawResponse);
        if ( isset($jsonResponse->error_name) ) {
            return json_encode(array('error' => $jsonResponse->message));
        }

        return $rawResponse;
    }
}
