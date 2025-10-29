<?php

// class client controller
class ClientController extends Controller
{
    private $client_model;
    public function __construct()
    {
        // initialize model
        $this->client_model = $this->loadModel('Client');
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
}
