<?php
class FBconfig {
    /**
     * @return the AppID
     */
    public static function appID() {
        return ('639220032771852');
    }
    /**
     * @return the AppScret
     */
    public static function appSecret() {
        return ('7456eddffffc65538e364ce963f158cb');
    }
    /**
     * @return the App namespace
     */
    public static function getNameSpace() {
        return ('phpf-test');
    }
	/**
     * @return the scope (Permissions needed for your app)
     * Check the list of permissions in:
     * http://developers.facebook.com/docs/reference/api/permissions/
     */
    public static function getScope(){
        return('email');
    }    
    /**
     * @return the URL of the server where the app is hosted
     */
    public static function getServerURL(){
        return ('http://fbsecurized.com/app/phpfbtest');
    }
	/**
     * @return the App URL
     */
    public static function getTabURL () {
        return ('http://fbsecurized.com/app/phpfbtest');
    }
	
    public static function pageID(){
        return('184808704889583');
    }    
}
?>