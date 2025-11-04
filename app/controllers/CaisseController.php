<?php

// class client controller
class CaisseController extends Controller
{
    private $caisse_model;
    public function __construct()
    {
        //initialize model
        $this->caisse_model = $this->loadModel('Caisse');
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
            if (filter_var($json['num_caisse'], FILTER_VALIDATE_INT) === false) {
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
                    filter_var($json['solde'], FILTER_VALIDATE_FLOAT) === false
                    || filter_var($json['seuil'], FILTER_VALIDATE_FLOAT) === false
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
                        $response = $this->caisse_model->addCaisse($json);
                    }
                }
            }
            echo json_encode($response);
        }
    }
    //action - filter caisse
    public function filterCaisse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            //get parameters
            $order_default = ['asc', 'desc'];
            $by_default = [
                'nb_factures',
                'nb_ae',
                'nb_sorties',
                'nb_entrees',
                'nb_transactions',
                'total_factures',
                'total_ae',
                'total_sorties',
                'total_entrees',
                'total_transactions',
            ];
            $per_default = ['day', 'week', 'month', 'year'];
            //__search_user
            $search_caisse = $_GET['search_caisse'] ?? '';
            $search_caisse = trim($search_caisse);
            //__by
            $by = $_GET['by'] ?? 'none';
            $by = strtolower(trim($by));
            $by = in_array($by, $by_default, true) ? $by : 'none';
            //__order_by
            $order_by = $_GET['order_by'] ?? 'desc';
            $order_by = strtolower(trim($order_by));
            $order_by = in_array($order_by, $order_default, true) ? $order_by : 'desc';
            //__date_from
            $from = $_GET['from'] ?? '';
            $from = strtolower(trim($from));
            //__date_to
            $to = $_GET['to'] ?? '';
            $to = strtolower(trim($to));
            //__per
            $per = $_GET['per'] ?? 'none';
            $per = strtolower(trim($per));
            $per = in_array($per, $per_default, true) ? $per : 'none';
            //__month
            $month = $_GET['month'] ?? 'none';
            $month = trim($month);
            $month = ($month !== 'none') ? (((int)$month >= 1 && (int)$month <= 12) ? (int)($month) : 1) : $month;
            //__year
            $year = $_GET['year'] ?? 'none';
            $year = trim($year);
            $year  = ($year !== 'none') ? (((int)$year >= 1970 && (int)$year <= 2500) ? (int)$year : date('Y')) : $year;

            //paramters
            $params = [
                'per' => $per, //on and
                'from' => $from, //on and
                'to' => $to, //on and
                'month' => $month, //on and
                'year' => $year, //on and
                'search_caisse' => $search_caisse, //where
                'by' => $by, //order
                'order_by' => $order_by //order by
            ];

            echo json_encode($this->caisse_model->filterCaisse($params));
        }
    }
    //action - update caisse
    public function updateCaisse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            header('Content-TYpe: application/json');
            $json = json_decode(file_get_contents('php://input'), true);
            $response = null;
            //trim data
            $json['num_caisse'] = trim($json['num_caisse']);
            $json['num_caisse_update'] = trim($json['num_caisse_update']);
            $json['solde'] = trim($json['solde']);
            $json['seuil'] = trim($json['seuil']);
            $json['id_utilisateur'] = trim($json['id_utilisateur']);

            //invalid num_caisse
            if (filter_var($json['num_caisse'], FILTER_VALIDATE_INT) === false || filter_var($json['num_caisse_update'], FILTER_VALIDATE_INT) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => 'Le numéro du caisse doit être des nombres entiers positifs .'
                ];
            }
            //valid num_caisse
            else {
                $json['num_caisse'] = (int)$json['num_caisse'];
                $json['num_caisse_update'] = (int)$json['num_caisse_update'];

                //invalid solde && seuil
                if (
                    filter_var($json['solde'], FILTER_VALIDATE_FLOAT) === false ||
                    filter_var($json['seuil'], FILTER_VALIDATE_FLOAT) === false
                ) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => 'Le solde et le seuil doivent être des valeurs décimaux .'
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
                            'message' => 'Le sole doit être supérieur ou égal au seuil .'
                        ];
                    }
                    //solde > seuil
                    else {
                        $response = $this->caisse_model->updateCaisse($json);
                    }
                }
            }

            echo json_encode($response);
        }
    }
}
