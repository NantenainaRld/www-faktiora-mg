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

    //=============================== PAGE ==================================

    //page - index
    public function index()
    {
        echo "page index";
    }
    //page - caisse dashboard
    public function pageCaisse()
    {
        $this->render('caisse_dashboard', ['title' => 'Gestion des caisses']);
    }

    //============================== ACTIONS ===========================

    //action - create caisse
    public function createCaisse()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header("Location: " . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_caisse - invalid
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);
            if ($num_caisse === false || $num_caisse < 0) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.num_caisse')];

                echo json_encode($response);
                return;
            }

            //solde - empty
            if ($json['solde'] === '') {
                $response = ['message_type' => 'invalid', 'message' => __('messages.empty.solde')];

                echo json_encode($response);
                return;
            }
            //solde - invalid
            $solde = filter_var($json['solde'], FILTER_VALIDATE_FLOAT);
            if ($solde === false || $solde < 0) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.solde')];

                echo json_encode($response);
                return;
            }

            //seuil - empty
            if ($json['seuil'] === '') {
                $response = ['message_type' => 'invalid', 'message' => __('messages.empty.seuil')];

                echo json_encode($response);
                return;
            }
            // seuil- invalid
            $seuil = filter_var($json['seuil'], FILTER_VALIDATE_FLOAT);
            if ($seuil === false || $seuil < 0) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.seuil')];

                echo json_encode($response);
                return;
            }

            //solde < seuil
            if ($solde < $seuil) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.solde_seuil')];

                echo json_encode($response);
                return;
            }

            try {
                $caisse_model = new Caisse();
                $caisse_model->setNumCaisse($num_caisse)
                    ->setSolde($solde)
                    ->setSeuil($seuil);

                //create caisse
                $response = $caisse_model->createCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage());

                $response = ['message_type' => 'error', 'message' => __('errors.catch.caisse_create_caisse', ['field' => $e->getMessage()])];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - filter caisse
    public function filterCaisse()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header("Location: " . SITE_URL . '/user');
            return;
        }

        //defaults
        $status_default = ['libre', 'occupé', 'deleted'];
        $order_by_default = [
            'nb_factures',
            'nb_ae',
            'nb_entrees',
            'nb_sorties',
            'nb_transactions',
            'total_factures',
            'total_ae',
            'total_entrees',
            'total_sorties',
            'total_transactions',
            'num'
        ];
        $arrange_default = ['DESC', 'ASC'];
        $date_by_default = [
            'per',
            'between',
            'month_year'
        ];
        $per_default = [
            'DAY',
            'WEEK',
            'MONTH',
            'YEAR'
        ];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'all'));
        $status = in_array($status, $status_default) ? $status : 'all';

        //order_by
        $order_by = strtolower(trim($_GET['order_by'] ?? 'num'));
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'num';
        $order_by = ($order_by === 'num') ? 'c.num_caisse' : $order_by;
        //arrange
        $arrange = strtoupper(trim($_GET['arrange'] ?? 'ASC'));
        $arrange = in_array($arrange, $arrange_default, true) ? $arrange : 'ASC';

        //date_by
        $date_by = strtolower(trim($_GET['date_by'] ?? 'all'));
        $date_by = in_array($date_by, $date_by_default, true) ? $date_by : 'all';
        //per
        $per = strtoupper(trim($_GET['per'] ?? 'DAY'));
        $per = in_array($per, $per_default, true) ? $per : 'DAY';
        //from
        $from = trim($_GET['from'] ?? '');
        // //to
        $to = trim($_GET['to'] ?? '');
        //from && to - empty
        if ($date_by === 'between') {
            if (empty($from) && empty($to)) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.empty.from_to');

                echo json_encode($response);
                return;
            }
            //from - !empty
            if (!empty($from)) {

                //from - invalid
                if (DateTime::createFromFormat("Y-m-d", $from) === false) {
                    $response['message_type'] = 'invalid';
                    $response['message'] = __('messages.invalids.date', ['field' => $from]);

                    echo json_encode($response);
                    return;
                }
            }
            //to - !empty
            if (!empty($to)) {
                //from - invalid
                if (DateTime::createFromFormat("Y-m-d", $to) === false) {
                    $response['message_type'] = 'invalid';
                    $response['message'] = __('messages.invalids.date', ['field' => $to]);

                    echo json_encode($response);
                    return;
                }
            }
        }
        //month
        $month = trim($_GET['month'] ?? 'none');
        $month = filter_var($month, FILTER_VALIDATE_INT);
        if ($month === false || ($month < 1 || $month > 12)) {
            $month = 'none';
        }
        //year
        $year = trim($_GET['year'] ?? 2025);
        $year = filter_var($year, FILTER_VALIDATE_INT);
        if ($year === false || ($year < 1700 || $year > 2500)) {
            $year = 2025;
        }

        //sarch_caisse
        $search_caisse = trim($_GET['search_caisse'] ?? '');

        $params = [
            'status' => $status,
            'order_by' => $order_by,
            'arrange' => $arrange,
            'date_by' => $date_by,
            'per' => $per,
            'from' => $from,
            'to' => $to,
            'month' => $month,
            'year' => $year,
            'search_caisse' => $search_caisse
        ];

        //filter caisse
        $response = CaisseRepositorie::filterCaisse($params);

        // echo json_encode($response);
        echo json_encode($response);
        return;
    }

    //action - filter ligne caisse
    public function filterLigneCaisse()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header("Location: " . SITE_URL . '/user');
            return;
        }

        //num_caisse
        $num_caisse = trim($_GET['num_caisse'] ?? '');
        //num_caisse - empty
        if ($num_caisse === '') {
            $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.caisse_not_selected')];

            echo json_encode($response);
            return;
        }
        //num_caisse exist ?
        $response = Caisse::findById($num_caisse);
        //error
        if ($response['message_type'] === 'error') {
            echo json_encode($response);
            return;
        }
        //num_caisse - not found
        if (!$response['found']) {
            $response['message_type'] = 'invalid';
            $response['message'] = __('messages.not_found.num_caisse', ['field' => $num_caisse]);

            echo  json_encode($response);
            return;
        }

        //id_utilisateur
        $id_utilisateur = trim($_GET['id_utilisateur'] ?? '');

        //from
        $f = trim($_GET['from'] ?? '');
        $from = (!empty($f)) ? DateTime::createFromFormat('Y-m-d\TH:i', $f) : '';
        //from - invalid
        if ($from === false) {
            $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.date', ['field' => $f])];

            echo json_encode($response);
            return;
        }
        $from = ($from !== '') ? $from->format('Y-m-d H:i:s') : '';
        //to
        $t = trim($_GET['to'] ?? '');
        $to = (!empty($t)) ? DateTime::createFromFormat('Y-m-d\TH:i', $t) : '';
        //to - invalid
        if ($to === false) {
            $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.date', ['field' => $t])];

            echo json_encode($response);
            return;
        }
        $to = ($to !== '') ? $to->format('Y-m-d H:i:s') : '';
        //parametters
        $params = [
            'num_caisse' => $num_caisse,
            'id_utilisateur' => $id_utilisateur,
            'from' => $from,
            'to' => $to
        ];

        // filter ligne caisse
        $response = LigneCaisse::filterLigneCaisse($params);

        echo json_encode($response);
        return;
    }

    //action - update caisse
    public function updateCaisse()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header("Location: " . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_caisse_update - invalid
            $num_caisse_update = filter_var($json['num_caisse_update'], FILTER_VALIDATE_INT);
            if ($num_caisse_update === false || $num_caisse_update < 0) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.num_caisse')];

                echo json_encode($response);
                return;
            }

            //solde - empty
            if ($json['solde'] === '') {
                $response = ['message_type' => 'invalid', 'message' => __('messages.empty.solde')];

                echo json_encode($response);
                return;
            }
            //solde - invalid
            $solde = filter_var($json['solde'], FILTER_VALIDATE_FLOAT);
            if ($solde === false || $solde < 0) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.solde')];

                echo json_encode($response);
                return;
            }

            //seuil - empty
            if ($json['seuil'] === '') {
                $response = ['message_type' => 'invalid', 'message' => __('messages.empty.seuil')];

                echo json_encode($response);
                return;
            }
            //seuil - invalid
            $seuil = filter_var($json['seuil'], FILTER_VALIDATE_FLOAT);
            if ($seuil === false || $seuil < 0) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.seuil')];

                echo json_encode($response);
                return;
            }

            //solde < seuil
            if ($solde < $seuil) {
                $response = ['message_type' => 'invalid', 'message' => __('messages.invalids.solde_seuil')];

                echo json_encode($response);
                return;
            }

            //num_caisse - invalid
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.not_found.num_caisse', ['field' => $json['num_caisse']])
                ];

                echo json_encode($response);
                return;
            }

            try {
                //num_caisse exist
                $response = Caisse::findById($num_caisse);
                //error
                if ($response['message_type'] === 'error') {

                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.num_caisse', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update caisse
                ($response['model'])->setNumCaisse($num_caisse)
                    ->setNumCaisseUpdate($num_caisse_update)
                    ->setSolde($solde)
                    ->setSeuil($seuil);
                $response = $response['model']->updateCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage());

                $response = ['message_type' => 'error', 'message' => __('errors.catch.caisse_update_caisse', ['field' => $e->getMessage()])];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }

        echo  json_encode($response);
        return;
    }

    //action - delete all caisse
    public function deleteAll()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header("Location: " . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_caisse = array_values(array_map(fn($x) => trim($x), $json['nums_caisse']));

            //nums_caisse - empty
            if (count($nums_caisse) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_nums_caisse_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all caisse
                $response = Caisse::deleteAll($nums_caisse);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage());

                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.catch.caisse_deleteAll', ['field' => $e->getMessage()])
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }

        echo json_encode($response);
        return;
    }

    //permanent delete all caisse
    public function permanentDeleteAll()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header("Location: " . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_caisse = array_values(array_map(fn($x) => trim($x), $json['nums_caisse']));

            //nums_caisse - empty
            if (count($nums_caisse) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_nums_caisse_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //permanent delete all caisse
                $response = Caisse::permanentdeleteAll($nums_caisse);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage());

                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.catch.caisse_deleteAll', ['field' => $e->getMessage()])
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }

        echo json_encode($response);
        return;
    }

    // public function freeCaisse()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    //         header('Content-Type: application/json');
    //         $json = json_decode(file_get_contents("php://input"), true);
    //         $response = null;

    //         //no selection
    //         if (count($json['nums']) <= 0) {
    //             $response = [
    //                 'message_type' => 'invalid',
    //                 'message' => "Aucune caisse n'est séléctionnée ."
    //             ];
    //         }
    //         //selection
    //         else {
    //             //trim
    //             $json = array_map(fn($x) => trim($x), $json['nums']);

    //             // filter values (char and negatif)
    //             $invalidNum = array_values(array_filter($json, function ($x) {
    //                 $val = filter_var($x, FILTER_VALIDATE_INT);
    //                 return $val === false || $val < 0;
    //             }));

    //             //invalid num_caisse
    //             if (count($invalidNum) >= 1) {
    //                 //sing
    //                 if (count($invalidNum) === 1) {
    //                     $response = [
    //                         'message_type' => 'invalid',
    //                         'message' => "Numéro de caisse invalide : " . $invalidNum[0]
    //                     ];
    //                 }
    //                 //plur
    //                 else {
    //                     $response = [
    //                         'message_type' => 'invalid',
    //                         'message' => "Numéros de caisse invalides : " . implode(', ', $invalidNum)
    //                     ];
    //                 }
    //             }
    //             //valid num_caisse
    //             else {
    //                 $response = $this->caisse_model->freeCaisse($json);
    //             }
    //         }

    //         echo json_encode($response);
    //     }
    // }
    // //action - delete caisse
    // public function deleteCaisse()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    //         header('Content-Type: application/json');
    //         $json = json_decode(file_get_contents('php://input'), true);
    //         $response = null;

    //         //no selection
    //         if (count($json['nums']) <= 0) {
    //             $response = [
    //                 'message_type' => 'invalid',
    //                 'message' => "Aucune caisse n'est séléctionnée ."
    //             ];
    //         }
    //         //selection
    //         else {
    //             //trim
    //             $json = array_map(fn($x) => trim($x), $json['nums']);

    //             //filter invalid num
    //             $invalidNums = array_values(array_filter(($json), function ($x) {
    //                 $val = filter_var($x, FILTER_VALIDATE_INT);
    //                 return $val === false || $val < 0;
    //             }));

    //             //invalid num_caisse
    //             if (count($invalidNums) >= 1) {
    //                 //sing
    //                 if (count($invalidNums) === 1) {
    //                     $response = [
    //                         'message_type' => 'invalid',
    //                         'message' => "Numéro de caisse invalide : " . $invalidNums[0]
    //                     ];
    //                 }
    //                 //plur
    //                 else {
    //                     $response = [
    //                         'message_type' => 'invalid',
    //                         'message' => "Numéros de caisse invalides : " . implode(', ', $invalidNums)
    //                     ];
    //                 }
    //             }
    //             //valid num_caisse
    //             else {
    //                 $response = $this->caisse_model->deleteCaisse($json);
    //             }
    //         }

    //         echo json_encode($response);
    //     }
    // }
    // //action - affect caisse
    // public function affectCaisse()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    //         header('Content-Type: application/json');
    //         $json = json_decode(file_get_contents("php://input"), true);
    //         $response = null;

    //         //trim
    //         $json = array_map(fn($x) => trim($x), $json);

    //         //num_caisse INT ?
    //         $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);

    //         //num_caisse invalid
    //         if ($num_caisse === false || $num_caisse < 0) {
    //             $response = [
    //                 'message_type' => 'invalid',
    //                 'message' => "Numéro de caisse invalide : " .  $num_caisse
    //             ];
    //         }
    //         //num_caisse valid
    //         else {
    //             //id_utilisateur - empty
    //             if (empty($json['id_utilisateur'])) {
    //                 $response = [
    //                     'message_type' => 'invalid',
    //                     'message' => "Veuiller entrer le numéro de l'utilisateur ."
    //                 ];
    //             }
    //             //if_utilisateur -
    //             else {
    //                 $response = $this->caisse_model->affectCaisse($json);
    //             }
    //         }

    //         echo json_encode($response);
    //     }
    // }
}
