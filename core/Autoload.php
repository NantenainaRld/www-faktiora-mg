<?php

class Autoload
{
    // Save the autoload method to autoatically require classes
    public static function register()
    {
        // php autoload function
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    // Method autoload
    public static function autoload($className)
    {
        // initialize directory
        $dirs = [];
        // controllers classes
        if (str_ends_with($className, 'Controller')) {
            $dirs[] = '/app/controllers/';
        }
        // database class
        elseif (class_exists('Database')
            && $className === 'Database') {
            $dirs[] = '/core/';
        }
        // models or core
        else {
            $dirs[] = '/app/models/';
            $dirs[] = '/core/';
        }
        // ROOT exist ? not
        if (!defined('ROOT')) {
            // config file
            require_once __DIR__ . '/../config/config.php';
        }
        // require all classes files
        foreach ($dirs as $dir) {
            $file = ROOT . $dir . $className . '.php';
            // file found
            if (file_exists($file)) {
                require_once $file;
                // stop search
                return;
            }
        }
        // class not found
        if (defined('DEBUG') && DEBUG) {
            error_log("Autoload : class not found : " . $className);
        }
    }
}
