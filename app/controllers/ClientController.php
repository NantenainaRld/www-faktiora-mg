<?php

// class client controller
class ClientController extends Controller
{
    private $client_model;
    public function __construct()
    {
        // initialize model
        // $this->client_model = new ClientModel();
    }

    //---------------------- PAGE ------------------------

    public function pageAddUser()
    {
        $this->render('create_user', array('title' => "Création du compte",
        'description' => "Page de création du compte utilisateur"));
    }
}
