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
        $this->toCamelCase();

        try {
            //initialize controller
            $controller = new $this->controller();
            $action = $this->action;

            //method exist
            if (method_exists($controller, $action)) {
                $controller->$action();
            }
            //method !exist
            else {
                $message = null;
                //page not found
                if (str_contains($this->action, 'page')) {
                    $message = "Erreur : la page demandée n'est pas trouvée .";
                }
                //action not found
                else {
                    $message = "Erreur : l'action demandée n'est pas trouvée .";
                }
                //*********************page errors
                echo $message;
            }
        } catch (Throwable $e) {
            //error dev
            if (DEBUG) {
                echo $e->getMessage() . "<br>" . $e->getFIle()
                . "<br>Line : " . $e->getLine();
            }
            //error prod
            else {
                //*********************page errors
                echo "Une erreur est survenue, retourner à la page d'accuille .";
            }
        }
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
        $this->controller = $converted . 'Controller';
    }

    //convert action name
    private function toCamelCase()
    {
        // words
        $parts = explode('_', $this->action);
        // first word
        $converted = $parts[0];

        // conert to pascal case
        for ($i = 1; $i < count($parts); $i++) {
            $converted .= ucfirst($parts[$i]);
        }

        $this->action = $converted;
    }
}
