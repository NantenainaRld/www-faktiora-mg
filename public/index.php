<?php

// charge config file
require_once __DIR__ . '/../config/config.php';
// charge autoload
require_once ROOT . '/core/Autoload.php';
// charge router
require_once ROOT . '/core/Router.php';

// Autoload
Autoload::register();

// Router
$route = $_GET['route'] ?? 'not found';
$router = new Router();
$router->run();
