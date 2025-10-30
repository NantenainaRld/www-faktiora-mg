<?php

// class client controller
class UserController extends Controller
{
    private $user_model;
    public function __construct()
    {
        // initialize model
        $this->user_model = $this->loadModel('User');
    }

    //---------------------- PAGE ------------------------

    //page - add user
    public function pageAdd()
    {
        $this->render('create_user', array('title' => "Création du compte",
        'description' => "Page de création du compte utilisateur"));
    }
    //page - index
    public function index()
    {
        echo "page index";
    }

    //---------------------ACTION------------------------

    //action - add user
    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Content-Type: application/json");
            // $response = null;
            // $json = json_decode(file_get_contents("php://input"), true);
            echo json_encode("ssss");
        }
    }
}
