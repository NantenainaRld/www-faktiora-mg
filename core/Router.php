<?php

// class router
class Router
{
    private $route;

    // controller variables
    private $client_controller;

    public function __construct($route)
    {
        $this->route = $route;

        // controller initialize
        $this->client_controller = null;
    }

    // run route
    public function run()
    {
        // switch route
        switch ($route) {

            //----------------------- PAGE --------------------------

            // not found
            case 'not found':
                require_once __DIR__ . '../app/views/not_found.php';
                break;
        }
    }

}
