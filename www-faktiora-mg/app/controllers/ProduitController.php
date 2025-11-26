<?php

//CLASS - produit controller
class ProduitController extends Controller
{

    //============================ PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index produit";
    }

    //page - produit dashboard
    public function pageProduit()
    {
        $this->render('produit_dashboard', ['title' => "Gestion des produiuts"]);
    }

    //============================ ACTIONS ==============================

    //action - create produit
    public function createProduit()
    {
        //is loged in
        $is_loged_in = Auth::isLogedIn();
        $response = null;

        //not loged
    }
}
