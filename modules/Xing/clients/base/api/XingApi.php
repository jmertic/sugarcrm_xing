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


require_once('include/api/SugarApi.php');

class XingApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'profile' => array(
                'reqType' => 'GET',
                'path' => array('Xing','?'),
                'pathVars' => array('module', 'username'),
                'method' => 'getProfile',
                'shortHelp' => "Get the given user's Xing profile",
                'longHelp' => '',
            ),
        );
    }

    /**
     * Returns the given user's profile
     * @param $api
     * @param $args
     * @return array
     */
    public function getProfile($api, $args) 
    {    
        $extApi = $this->getEAPM();
        if (is_array($extApi) && isset($extApi['error'])) {
            throw new SugarApiExceptionRequestMethodFailure(null, $args, null, 424, $extApi['error']);
        }

        if ($extApi === false) {
            throw new SugarApiExceptionRequestMethodFailure($GLOBALS['app_strings']['ERROR_UNABLE_TO_RETRIEVE_DATA'], $args);
        }

        $result = $extApi->getProfile($args['username']);
        if (isset($result['error'])) {
            throw new SugarApiExceptionRequestMethodFailure('errors_from_xing: '.$result['error'], $args);
        }
        return $result;
    }

    /**
     * gets Xing EAPM
     * @return array|bool|ExternalAPIBase
     */
    protected function getEAPM()
    {
        // ignore auth and load to just check if connector configured
        $xingEAPM = ExternalAPIFactory::loadAPI('Xing', true);

        if (!$xingEAPM) {
            $source = SourceFactory::getSource('ext_rest_xing');
            if ($source && $source->hasTestingEnabled()) {
                try {
                    if (!$source->test()) {
                        return array('error' =>'ERROR_NEED_OAUTH');
                    }
                } catch (Exception $e) {
                    return array('error' =>'ERROR_NEED_OAUTH');
                }
            }
            return array('error' =>'ERROR_NEED_OAUTH');
        }

        $xingEAPM->getConnector();

        $eapmBean = EAPM::getLoginInfo('Xing');

        if (empty($eapmBean->id)) {
            return array('error' =>'ERROR_NEED_AUTHORIZE');
        }

        //return a fully authed EAPM
        $xingEAPM = ExternalAPIFactory::loadAPI('Xing');
        return $xingEAPM;
    }
}