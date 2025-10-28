<?php

// base controller
class Controller
{
    // method load model instance
    public function loadModel(string $modelName)
    {
        // class exist? not
        if (!class_exists($modelName)) {
            throw new exception("Model not found : " . $modelName);
        }
        // ?
        else {
            // return object
            return new $modelName();
        }
    }
    // method load view
    public function render(string $viewName, array $data = [])
    {
        // extract data to variables
        extract($data);
        $viewFile = ROOT . '/app/views/' . $viewName . '.php';
        // view exist ? not
        if (!file_exists($viewFile)) {
            die("View not found : " . $viewFile);
        }
        // ?
        else {
            require_once $viewFile;
        }
    }
}
