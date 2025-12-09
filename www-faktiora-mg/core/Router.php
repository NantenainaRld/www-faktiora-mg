<?php

// class router
class Router
{
    private $controller;
    private $action;

    public function __construct()
    {
        $url = $_GET['url'] ?? 'auth';

        //remove '/' begin and after
        $url = trim($url, '/');
        //explode url
        $parts = $url ? explode('/', $url) : [];

        //controller
        $this->controller = $parts[0];
        //action
        $this->action = $parts[1] ?? 'index';
    }

    // run route
    public function run()
    {
        $this->toPasCalCase();
        $this->toCamelCase();

        //page error
        if ($this->controller === 'Error') {
            require_once APP_PATH . '/views/error.php';

            return;
        }
        //change lang
        elseif ($this->controller === 'Lang') {
            //get lang
            $lang = trim($_GET['lang'] ?? 'fr');
            TranslationService::setLang($lang);

            return;
        }
        //favicon
        else if ($this->controller === 'Favicon.ico') {
            return;
        }
        //bootstrap
        else if ($this->controller === 'Bootstrap-5.3.3') {
            return;
        }
        //chart js
        else if ($this->controller === 'Chart-js') {
            return;
        }
        //no error
        else {
            try {
                //create default admin
                UserController::createDefaultAdmin();

                //auth ?
                if ($this->controller !== 'Auth') {
                    $is_loged_in = Auth::isLogedIn();

                    //not loged
                    if (!$is_loged_in->getLoged()) {
                        //redirect to login page
                        header('Location: ' . SITE_URL . '/auth');
                        return;
                    }
                }

                $this->controller .= 'Controller';
                //initialize controller
                $controller = new $this->controller();
                $action = $this->action;

                //method exist
                if (method_exists($controller, $action) && $this->action !== 'createDefaultAdmin') {
                    $controller->$action();
                }
                //method !exist
                else {
                    //page not found
                    $type = 'page';

                    //action not found
                    if (!str_contains($this->action, 'page')) {
                        $type = 'action';
                    }

                    //redirect to error page
                    header('Location: ' . SITE_URL . '/error?messages=' . __(
                        'errors.not_found.' . $type,
                        ['field' => $this->action]
                    ));

                    return;
                }
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                //redirect to error page
                header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.router', ['field' => $e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile()]));

                return;
            }
        }

        return;
    }

    // convert controller name
    private function toPasCalCase()
    {
        // words
        $parts = explode('_', $this->controller);
        // first word
        $converted = ucfirst($parts[0]);

        // convert to pascal case
        for ($i = 1; $i < count($parts); $i++) {
            $converted .= ucfirst($parts[$i]);
        }
        $this->controller = $converted;
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
