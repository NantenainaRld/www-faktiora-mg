<?php

class Autoload
{
    // autocharge all classess
    public static function register()
    {
        // php autoload function
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    // Method autoload
    public static function autoload($class_name)
    {
        $class_name = str_replace('\\', '/', $class_name);

        //possible paths
        $possible_paths = [
            APP_PATH . "/controllers/{$class_name}.php",
            APP_PATH . "/models/{$class_name}.php",
            CORE_PATH . "/{$class_name}.php",

        ];

        // foreach paths
        foreach ($possible_paths as $file) {
            // file found
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
        // class not found
        if (defined('DEBUG') && DEBUG) {
            error_log("Autoload : class not found : " . $class_name);
            throw new Exception("Autoload : class not found : " . $class_name);
        }
        //page error
        else {
            header('Location: ' . SITE_URL . '/error?message="Une erreur est survenue');
        }
    }
}
