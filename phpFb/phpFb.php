<?php
/**
* phpFacebook PHP Class [https://github.com/otorcatf/phpFb]
* Author: Omar Torcat [@oftc007]
* Version: 0.1 Beta
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* or see http://www.gnu.org/
**/
require_once 'FbConfig.php';
require_once 'facebook.php';
class phpFb extends Facebook{    
    public $user_profile;
	protected static $fbQueryVars = array(
		'code',
		'state',
		'signed_request'
  	);
    public function __construct() {
        //Header for "Platform for Privacy Preferences" (P3P): allow IE to accept third-party cookie
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        $config = array(
            'appId'  => FBConfig::appID(),
            'secret' => FBConfig::appSecret());
        parent::__construct($config);
        return ($this->getUser());
    }

    public function loadFb($redirect = 1){
        $user = $this->getUser();
        if ($user){
            try {                
                $this->user_profile = $this->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }
		$params = array();
		$params ['scope'] = FBconfig::getScope();
		$params['redirect_uri'] = $this->getCurrentUrl();
		
		if ($this->user_profile) {
			$buttonURL = $this->getLogoutUrl($params);
		}else{
			$buttonURL = $this->getLoginUrl($params);			
		}
		
		if ($redirect && !$this->user_profile){			
			echo "<script type='text/javascript'>top.location.href = '$buttonURL';</script>";
			exit();
		}else{
			return ($buttonURL);
		}
    }

    public function tabRedirect($redirect = 0){
        $signedRequest = $this->getSignedRequest();
        if (!$signedRequest || empty($signedRequest["page"])){
            $protocol = $_SERVER['HTTPS'] ? "https://" : "http://";
            $pageInfo = $this->api("/".FBconfig::pageID());
            $redirectURL = $pageInfo["link"] . "?sk=app_" . $this->app_id;
            if (!$redirect)
                return ($redirectURL);
            else{
                echo "<script type='text/javascript'>top.location.href = '$redirectURL';</script>";
                exit();
            }
        }
    }

    public function canvasRedirect($redirect = false){
        $signedRequest = $this->getSignedRequest();
        if (!$signedRequest || !empty($signedRequest["page"])){
            $protocol = $_SERVER['HTTPS'] ? "https://" : "http://";
            $redirectURL = $protocol . "apps.facebook.com/" . FBconfig::getNameSpace();
            if (!$redirect)
                return ($redirectURL);
            else{
                echo "<script type='text/javascript'>top.location.href = '$redirectURL';</script>";
                exit();
            }
        }
    }

    public function getUserData($id = ''){
		if ($id){
			try {                
                return ( $this->api('/'.$id) );
            } catch (FacebookApiException $e) {
				$this->loadFb();                
            }			
		}else{
			return ($this->user_profile);
		}
    }

    public function checkPermissions(){
        try{
            $app_permissons = explode(",",FBconfig::getScope());
            $permissions = $this->api("/me/permissions");
            $permissions_match = array_intersect($app_permissons, array_keys($permissions['data'][0]));
            if(count($permissions_match) == count($app_permissons)  )
                return 1;
            else
                return 0;
        }catch(Exception $e){
            return 0;
        }
    }

    public function renewAccessToken(){
        $code = isset($_GET['code']) ? $_GET['code'] : 0;
        if ($code) {
            $token_url="https://graph.facebook.com/oauth/access_token?client_id="
                . $this->app_id . "&redirect_uri=" . urlencode($this->getCurrentUrl())
                . "&client_secret=" . $this->app_secret
                . "&code=" . $code . "&display=popup";

			$response = $this->curURL($token_url);
            $params = NULL;
            parse_str($response, $params);

            $this->setAccessToken($params['access_token']);
            return ($params['access_token']);
        }else
            return (NULL);
    }

    public function checkLikePage(){
		if(!empty($this->signedRequest["page"]) && !empty($this->signedRequest["page"]["liked"]))
			return 1;
		else
			return 0;
	}

    public function getRequests(){
        return($this->api('/me/apprequests/'));
    }


    public function deleteRequests($api = 0){
		if (isset($_REQUEST['request_ids'])){			
			try{
				$requests = explode($_REQUEST['request_ids']);
				foreach ($requests as $request){
					$this->api("$request", 'DELETE');
				}
			}catch(Exception $e){
				$this->loadFb();
			}
		}
		if (!isset($_REQUEST['request_ids']) || $api)
			deleteRequestsApi();
    }
	
	public function deleteRequestsApi(){	
		try{
			$requests = $this->api('/me/apprequests/');
			foreach($requests["data"] as $request){
				//$full_request_id = $request["id"];
				$this->api('/'.$request["id"],'DELETE');
			}
		}catch(Exception $e){
			$this->loadFb();
		}		
	}

    public function getExtendedToken(){
        $extended_url = "https://graph.facebook.com/oauth/access_token?".
            "client_id=". $this->app_id.
            "&client_secret=". $this->app_secret.
            "&grant_type=fb_exchange_token".
            "&fb_exchange_token=". $this->getAccessToken();
        $response = $this->curlURL($extended_url);
        $params = NULL;
        parse_str($response, $params);
        $this->setAccessToken($params['access_token']);
        return ($params['access_token']);
    }
	public function getCurrentUrl(){
		$protocol = $_SERVER['HTTPS'] ? "https://" : "http://";
		$domain = $_SERVER['HTTP_HOST'];
		$url = $protocol . $domain . $_SERVER['REQUEST_URI'];
		$ulrParts = parse_url($url);
		$params = explode('&', $ulrParts['query']);
      	$QueryVar = array();
		$query = "";
		if (!empty($ulrParts['query'])) {		
			foreach ($params as $param) {
				$dropParam = 0;
				foreach (self::$fbQueryVars as $dropQueryVar) {
					if (strpos($param, $dropQueryVar.'=') === 0) {
						$dropParam = 1;
						break;
					}
				}
				if (!$dropParam)
					$QueryVar[] = $param;
			}			
			if (!empty($QueryVar)) {
				$query = '?'.implode($QueryVar, '&');
			}
		}		
		return $protocol . $ulrParts['host'] . $ulrParts['path'] . $query;
	}
	
	public function curlURL($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));		
		curl_setopt($ch, CURLOPT_URL, $URL);
		$contents = curl_exec($ch);
		$err  = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($contents)
			return ($contents);
		else
			return (false);
  	}
}