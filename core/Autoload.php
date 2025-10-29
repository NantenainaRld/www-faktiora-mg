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
            APP_PATH . "/controllers/{$class_nae}",
            APP_PATH . "/models/{$class_name}",
            CORE_PATH . "/core/{$class_name}"
        ];
        // class not found
        if (defined('DEBUG') && DEBUG) {
            error_log("Autoload : class not found : " . $class_name);
        }
    }
}
