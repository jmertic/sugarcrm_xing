<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

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