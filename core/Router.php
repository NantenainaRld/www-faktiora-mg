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
        $this->route = $_GET['route'] ?? 'not found';
    }

}
