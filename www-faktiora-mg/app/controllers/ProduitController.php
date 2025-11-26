<?php

//CLASS - produit controller
class EntreeController extends Controller
{

    //============================  PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index entree";
    }

    //page - entree dashboard
    public function pageEntree()
    {
        $this->render('entree_dashboard', ['title' => "Gestion des entrées"]);
    }
}
