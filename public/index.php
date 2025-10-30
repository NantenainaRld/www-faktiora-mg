<?php

// ERROR SHOW
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// PATHS
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('LIBS_PATH', ROOT_PATH . '/libs');
define('PUBLIC_PATH', __DIR__);

// charge config file
require_once CONFIG_PATH . '/config.php';
// charge autoload
require_once CORE_PATH . '/Autoload.php';

// Autoload
Autoload::register();

// Router
$router = new Router();
$router->run();

// //get url request
// $request_uri = $_SERVER['REQUEST_URI'];
// //path , remove ?
// $path = parse_url($request_uri, PHP_URL_PATH);
// //remove '/' begin and after
// $path  = trim($path, '/');

// echo $path . '<br>';
// echo BASE_URL . '<br>';
// echo SITE_URL . '<br>';
// echo str_replace(BASE_URL, "", $path);
