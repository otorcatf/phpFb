phpFb (V. 0.1 Beta)
==========================

phpFacebook it's a PHP class which simplifies the development with the Facebook PHP/JS SDK


Features
-----

* Include the Facebook PHP SDK
* Check if a user likes a Facebook Page
* Manage signed_request data
* Renew Access Token
* Get long-lived user access_token
* Tab and Canvas redirect
* Get and delete App requests


Basic Usage
-----

First you need to edit the FBconfig.php file and configure the parameters with your application values

The Basic usage of the class is:

    require 'phpFb/phpFb.php';
    
    $phpFb = new phpFb();
    
    //Loads the Facebook PHP SDK with the default config on FBconfig.php
    $phpFb->loadFB();

You can use the api method direct from the phpFacebook object:

    $phpFb->api("/me");

You can use any of the Facebook PHP SDK:
 
    $phpFb->destroySession();
	

Common tasks
-----

###Authentication
    $phpFb->loadFB($redirect);
$redirect = 1 => Automatic redirect to the auth dialog.

$redirect = 0 => Get the login/logout url

###Renew Access Token
    $access_token = $phpFb->renewAccessToken();
Returns the new access token


###Get long-lived Access Token
    $access_token = $phpFb->getExtendedToken();
Returns the long-lived access token

###Redirect to App Tab
    $phpFb->tabRedirect();
Redirects to the App Tab for the page ID configured on the FBconfig file

###Redirect to App Canvas
    $phpFb->tabRedirect();
Redirects to the App Canvas

###Check the permisions
    $phpFb->checkPermissions();
Check if the user have the necessary permission to use App. Return 1 if have all the necessary permissions

###Check like of Facebook Page
    $phpFb->checkLikePage();
Check if the user likes the page where the App Tab is installed

###Get App Requests
    $phpFb->getRequests();
Returns array of requests for the actual user

###Get App Requests
    $phpFb->deleteRequests();
Delete all the requests for the actual user


Don't forget to see the [examples][1]

Links
===============
[Facebook PHP SKD on github](https://github.com/facebook/php-sdk/)

[Facebook Developers](https://developers.facebook.com/)

[1]: https://github.com/otorcatf/phpFb/tree/master/examples