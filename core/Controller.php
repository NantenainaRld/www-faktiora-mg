<?php

// base controller
class Controller
{
    // method load model instance
    public function loadModel(string $model_name)
    {
        $model = new $model_name();
        return $model;
    }
    // method load view
    public function render(string $view_name, array $data = [])
    {
        // extract data to variables
        extract($data);
        $view_file = APP_PATH . '/views/' . $view_name . '.php';
        // view not found
        if (!file_exists($view_file) && DEBUG) {
            echo "Views not found : " . $view_file;
            exit;
        }
        //view found
        else {
            require_once $view_file;
        }
    }
}
