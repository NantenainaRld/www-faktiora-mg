<?php
//session start
session_start();

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
define('SERVICE_PAH', ROOT_PATH . '/services');
define('I18N_PATH', ROOT_PATH . '/i18n');
define('PUBLIC_PATH', __DIR__);

//charge config file
require_once CONFIG_PATH . '/config.php';
//charge translations
require_once SERVICE_PAH . '/TranslationService.php';
//charge helper
require_once CORE_PATH . '/helper.php';

//time_zone
$time_zone = $_COOKIE['time_zone'] ?? 'Indian/Antananarivo';
define('TIME_ZONE', $time_zone);

//initialize translation
TranslationService::init();

// charge autoload
require_once CORE_PATH . '/Autoload.php';

// Autoload
Autoload::register();

// Router
$router = new Router();
$router->run();
