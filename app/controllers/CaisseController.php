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

    //===================== PAGE ======================

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

    //================== ACTIONS ===================

    //action - add caisse
    public function addCaisse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Content-Type: application/json");
            $response = null;
            $json = json_decode(file_get_contents("php://input"), true);
            $json = [
                'num_caisse' => trim($json['num_caisse']),
                'solde' => trim($json['solde']),
                'seuil' => trim($json['seuil'])
            ];

            //invalid num_caisse
            if (!filter_var($json['num_caisse'], FILTER_VALIDATE_INT)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => 'Le numéro du caisse doit être des nombres entiers positifs .'
                ];
            }
            //valid num_caisse
            else {
                $json['num_caisse'] = (int)$json['num_caisse'];

                //invalid solde && seuil
                if (
                    !filter_var($json['solde'], FILTER_VALIDATE_FLOAT)
                    || !filter_var($json['seuil'], FILTER_VALIDATE_FLOAT)
                ) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => "Le solde et le seuil doivent être des valeurs décimaux"
                    ];
                }
                //valid solde && seuil
                else {
                    $json['solde'] = (float)$json['solde'];
                    $json['seuil'] = (float)$json['seuil'];

                    //solde < seuil
                    if ($json['solde'] < $json['seuil']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Le solde doit être supérieur ou égal au seuil ."
                        ];
                    }
                    //solde >= seuil
                    else {
                    }
                }
            }
            echo json_encode($response);
        }
    }
}
