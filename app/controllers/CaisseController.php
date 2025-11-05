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
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_caisse INT ?
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);
            //invalid num_caisse
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => 'Le numéro du caisse doit être des nombres entiers supérieurs ou égaux à 0 .'
                ];
            }
            //valid num_caisse
            else {
                $solde = filter_var($json['solde'], FILTER_VALIDATE_FLOAT);
                $seuil = filter_var($json['seuil'], FILTER_VALIDATE_FLOAT);

                //invalid solde && seuil
                if ($solde === false || $seuil === false || $solde < 0 || $seuil < 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => 'Le solde et caisse doivent être des valeurs décimaux supérieurs ou égaux à 0 .'
                    ];
                }
                //valid solde && seuil
                else {
                    //solde < seuil
                    if ($solde < $seuil) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Le solde doit être supérieur ou égal au seuil ."
                        ];
                    }
                    //solde >= seuil
                    else {
                        $params = [
                            'num_caisse' => $num_caisse,
                            'solde' => $solde,
                            'seuil' => $seuil
                        ];
                        $response = $this->caisse_model->addCaisse($params);
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
                'c.num_caisse'
            ];
            $per_default = ['day', 'week', 'month', 'year'];
            $type_default = ['null', '!null'];
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
            //__type
            $type = trim($_GET['type'] ?? 'all');
            $type = in_array($type, $type_default) ? $type : 'all';

            //paramters
            $params = [
                'per' => $per, //on and
                'from' => $from, //on and
                'to' => $to, //on and
                'month' => $month, //on and
                'year' => $year, //on and
                'search_caisse' => $search_caisse, //where
                'by' => $by, //order
                'order_by' => $order_by, //order by
                'type' => $type //where
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

            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //numca_isse INT ?
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);

            //invalid num_caisse
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => "Le numéro de caisse choisi est invalide : " . $json['num_caisse']
                ];
            }
            //valid num_caisse
            else {
                //update_num_caisse INT?
                $update_num_caisse = filter_var($json['update_num_caisse'], FILTER_VALIDATE_INT);

                //invalid update num_caisse
                if ($update_num_caisse === false || $update_num_caisse < 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => "Le numéro de caisse doit être des nombres entiers supérieurs ou égaux à 0 ."
                    ];
                }
                //valid update num_caisse
                else {
                    //solde && seuil FLOAT ?
                    $solde = filter_var($json['solde'], FILTER_VALIDATE_FLOAT);
                    $seuil = filter_var($json['seuil'], FILTER_VALIDATE_FLOAT);

                    //invalid solde && seuil
                    if ($solde === false || $seuil === false || $solde < 0 || $seuil < 0) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Le solde et le seuil doivent être des valeurs décimaux supérieurs ou égaux à 0 . "
                        ];
                    }
                    //valid solde && seuil
                    else {
                        //solde < seuil
                        if ($solde < $seuil) {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => "Le solde doit être supérieur ou égal au seuil ."
                            ];
                        }
                        //solde >= seuil
                        else {
                            $params = [
                                'num_caisse' => $num_caisse,
                                'update_num_caisse' => $update_num_caisse,
                                'solde' => $solde,
                                'seuil' => $seuil,
                                'id_utilisateur' => $json['id_utilisateur']
                            ];
                            $response = $this->caisse_model->updateCaisse($params);
                        }
                    }
                }
            }

            echo json_encode($response);
        }
    }
    //action - update solde && seuil
    public function updateSoldeSeuil()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            header('Content-Type: application/json');
            $response  = null;
            $json = json_decode(file_get_contents("php://input"), true);

            $nums = $json['nums'];
            //trim values
            $json = [
                'solde' => trim($json['solde']),
                'seuil' => trim($json['seuil'])
            ];

            $nums = array_map(fn($x) => trim($x), $nums);

            //no selection
            if (count($nums) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => "Aucune caisse n'est séléctionnée ."
                ];
            }
            //selection
            else {
                // filter values (char and negatif)
                $invalidNum = array_values(array_filter($nums, function ($x) {
                    $val = filter_var($x, FILTER_VALIDATE_INT);
                    return $val === false || $val < 0;
                }));

                //invalid num
                if (count($invalidNum) >= 1) {
                    //sing
                    if (count($invalidNum) === 1) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Numéro de caisse invalide : " . $invalidNum[0]
                        ];
                    }
                    //plur
                    else {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Numéros de caisse invalides : " . implode(', ', $invalidNum)
                        ];
                    }
                }
                //valid num
                else {
                    //invalid solde && seuil
                    if (filter_var($json['solde'], FILTER_VALIDATE_FLOAT) === false || filter_var($json['seuil'], FILTER_VALIDATE_FLOAT) === false) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Valeurs de solde ou seuil entrées sont invalides."
                        ];
                    }
                    //valid solde && seuil
                    else {
                        //< 0
                        if ($json['solde'] < 0 || $json['seuil'] < 0) {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => "Valeurs de solde et seuil doient être supérieur ou égal à 0."
                            ];
                        }
                        //>0
                        else {
                            //solde < seuil
                            if ($json['solde'] < $json['seuil']) {
                                $response = [
                                    'message_type' => 'invalid',
                                    'message' => "Le solde doit être supérieur ou égal au seuil."
                                ];
                            }
                            //solde >= seuil
                            else {
                                //convert to int
                                $nums  = array_map('intval', $nums);
                                $params = [
                                    'nums' => $nums,
                                    'solde' => $json['solde'],
                                    'seuil' => $json['seuil']
                                ];

                                //update caisses
                                $response = $this->caisse_model->updateSoldeSeuil($params);
                            }
                        }
                    }
                }
            }

            echo json_encode($response);
        }
    }
    //action - free caisse
    public function freeCaisse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            header('Content-Type: application/json');
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;

            //no selection
            if (count($json['nums']) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => "Aucune caisse n'est séléctionnée ."
                ];
            }
            //selection
            else {
                //trim
                $json = array_map(fn($x) => trim($x), $json['nums']);

                // filter values (char and negatif)
                $invalidNum = array_values(array_filter($json, function ($x) {
                    $val = filter_var($x, FILTER_VALIDATE_INT);
                    return $val === false || $val < 0;
                }));

                //invalid num_caisse
                if (count($invalidNum) >= 1) {
                    //sing
                    if (count($invalidNum) === 1) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Numéro de caisse invalide : " . $invalidNum[0]
                        ];
                    }
                    //plur
                    else {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Numéros de caisse invalides : " . implode(', ', $invalidNum)
                        ];
                    }
                }
                //valid num_caisse
                else {
                    $response = $this->caisse_model->freeCaisse($json);
                }
            }

            echo json_encode($response);
        }
    }
    //action - delete caisse
    public function deleteCaisse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            header('Content-Type: application/json');
            $json = json_decode(file_get_contents('php://input'), true);
            $response = null;

            //no selection
            if (count($json['nums']) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => "Aucune caisse n'est séléctionnée ."
                ];
            }
            //selection
            else {
                //trim
                $json = array_map(fn($x) => trim($x), $json['nums']);

                //filter invalid num
                $invalidNums = array_values(array_filter(($json), function ($x) {
                    $val = filter_var($x, FILTER_VALIDATE_INT);
                    return $val === false || $val < 0;
                }));

                //invalid num_caisse
                if (count($invalidNums) >= 1) {
                    //sing
                    if (count($invalidNums) === 1) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Numéro de caisse invalide : " . $invalidNums[0]
                        ];
                    }
                    //plur
                    else {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => "Numéros de caisse invalides : " . implode(', ', $invalidNums)
                        ];
                    }
                }
                //valid num_caisse
                else {
                    $response = $this->caisse_model->deleteCaisse($json);
                }
            }

            echo json_encode($response);
        }
    }
    //action - affect caisse
    public function affectCaisse()
    {
        if ($_SERVER['REQUEST_METHOD'] === "PUT") {
            header('Content-Type: application/json');
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;

            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_caisse INT ?
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);

            //num_caisse invalid
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => "Numéro de caisse invalide : " .  $num_caisse
                ];
            }
            //num_caisse valid
            else {
                //id_utilisateur - empty
                if (empty($json['id_utilisateur'])) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => "Veuiller entrer le numéro de l'utilisateur ."
                    ];
                }
                //if_utilisateur -
                else {
                    $response = $this->caisse_model->affectCaisse($json);
                }
            }

            echo json_encode($response);
        }
    }
}
