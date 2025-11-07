<?php

//======= controllers for autre_entree && facture
class EntreeController extends Controller
{

    //============================  PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index";
    }
    //page - entree dashboard
    public function pageEntree()
    {
        $this->render('entree_dashboard', ['title' => "Gestion des entrées"]);
    }


    //========================== ACTIONS ============================

    //action - add autree entree
    public function addAutreEntree()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;

            echo json_encode($response);
        }
    }
}
