<?php

class LoginController extends Controller
{
    private $user_model;
    public function __construct()
    {
        $this->user_model = $this->loadModel('User');
    }

    //================== PAGE ======================

    //page - login (index)
    public function index()
    {
        $this->render('login', array(
            'title' => "Connexion",
            'description' => "Page de connection"
        ));
    }
}
