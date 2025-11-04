<?php

// class client controller
class CaisseController extends Controller
{
    private $caisse_model;
    public function __construct()
    {
        // initialize model
        // $this->caisse_model = $this->loadModel('Caisse');
    }

    //---------------------- PAGE ------------------------

    //page - index
    public function index()
    {
        echo "page index";
    }
    //page - caisse dashboard
    public function pageDashboard()
    {
        $this->render('caisse_dashboard', ['title' => 'Gestion des caisses']);
    }
}
