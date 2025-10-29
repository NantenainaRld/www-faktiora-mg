<?php

// class router
class Router
{
    private $controller;
    private $action;

    public function __construct()
    {
        // get controller name and change to lower_case
        $this->controller = strtolower($_GET['c'] ?? 'Login');
        // get action name and change to lower_case
        $this->action = strtolower($_GET['a'] ?? 'index');
    }

    // run route
    public function run()
    {
        $this->toPasCalCase();
    }

    // convert controller name
    private function toPasCalCase()
    {
        // words
        $parts = explode('_', $this->controller);
        // first word
        $converted = ucfirst($parts[0]);

        // conert to pascal case
        for ($i = 1; $i < count($parts); $i++) {
            $converted .= ucfirst($parts[$i]);
        }
        echo $converted;
    }

    //convert action name
    private function toCamelCase()
    {

    }
}
