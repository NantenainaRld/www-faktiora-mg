<?php

// ERROR SHOW
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// PATHS
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', __DIR__);

// charge config file
require_once __DIR__ . '/../config/config.php';
// // charge autoload
// require_once ROOT . '/core/Autoload.php';

// // Autoload
// Autoload::register();

// // Router
// try {
//     $route = $_GET['route'] ?? 'not_found';
//     $router = new Router($route);
//     $router->run();
// } catch (Exception $e) {
//     if (defined('DEBUG') && DEBUG) {
//         echo "Error : " . $e->getMessage();
//     }
// }
