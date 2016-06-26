<?php

error_reporting(E_ALL);

require "vendor/autoload.php";

use app\Router;

/* * * define the site path constant ** */
$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);
define('__ASSETS_PATH', "assets");
//this line should customize by server admin
define("__PREFIX", "/");

//If you locate project in a subdirectory like "/"
//then you have to remove it from URL

$request = str_replace(__PREFIX, "", $_SERVER['REQUEST_URI']);

//default separator of controller,action and parameters ar "-"
$params = explode("-", $request);
//router indicates that which controller and action should run according to URL
$router = new Router();
//when a from passes parameters they will be send to router separately
if (isset($_POST)) {

    $router->routing($params, filter_input_array(INPUT_POST));
} else {
    $router->routing($params);
}
