<?php

// charge config file
require_once __DIR__ . '/../config/config.php';
// charge autoload
require_once ROOT . '/core/Autoload.php';

// Autoload
Autoload::register();

try {
    // Router
    $route = $_GET['route'] ?? 'not_found';
    $router = new Router($route);
    $router->run();
} catch (Exception $e) {
    if (defined('DEBUG') && DEBUG) {
        echo "Error : " . $e->getMessage();
    }
}
