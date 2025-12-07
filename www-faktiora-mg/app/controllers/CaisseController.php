<?php

//dompdf
require_once LIBS_PATH . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Image\Cache;
use Dompdf\Options;
use FontLib\Table\Type\head;

// class client controller
class CaisseController extends Controller
{

    //=============================== PAGE ==================================

    //page - index
    public function index()
    {
        echo "page index";
    }
    //page - caisse dashboard
    public function pageCaisse()
    {
        //is loged in
        $is_loged_in  = Auth::isLogedIn();
        //loged
        if ($is_loged_in->getLoged()) {
            //role not admin 
            // if ($is_loged_in->getRole() !== 'admin') {
            //     // redirect to caisse index 
            //     header('Location: ' . SITE_URL . '/caisse');
            //     return;
            // }
            //show login page
            $this->render('caisse_dashboard', [
                'title' => 'Faktiora - ' . __('forms.titles.caisse_dashboard'),
                'role' => $is_loged_in->getRole()
            ]);
        }
        //not loged
        else {
            //redirect to login page
            header('Location: ' . SITE_URL . '/auth');
            return;
        }
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
            //redirect tocaisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_caisse - invalid
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_num_caisse')
                ];

                echo json_encode($response);
                return;
            }

            //solde - empty
            if ($json['solde'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.solde')
                ];

                echo json_encode($response);
                return;
            }
            //solde - invalid
            $solde = filter_var($json['solde'], FILTER_VALIDATE_FLOAT);
            if ($solde === false || $solde < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.solde')
                ];

                echo json_encode($response);
                return;
            }

            //seuil - empty
            if ($json['seuil'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.seuil')
                ];

                echo json_encode($response);
                return;
            }
            // seuil- invalid
            $seuil = filter_var($json['seuil'], FILTER_VALIDATE_FLOAT);
            if ($seuil === false || $seuil < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.seuil')
                ];

                echo json_encode($response);
                return;
            }

            //solde < seuil
            if ($solde < $seuil) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.solde_seuil')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //create caisse
                $caisse_model = new Caisse();
                $caisse_model
                    ->setNumCaisse($num_caisse)
                    ->setSolde($solde)
                    ->setSeuil($seuil);
                $response = $caisse_model->createCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_createCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
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

        //is loged in ?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login
            header('Location: ' . SITE_URL . '/auth');
            return;
        }
        //not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to caisse index
            header('Location: ' . SITE_URL . '/caisse');
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
        $status = in_array($status, $status_default, true) ? $status : 'all';

        //order_by
        $order_by = strtolower(trim($_GET['order_by'] ?? 'num'));
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'num';
        $order_by = ($order_by === 'num') ? 'c.num_caisse' : $order_by;
        // //arrange
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
        //to
        $to = trim($_GET['to'] ?? '');
        if ($date_by === 'between') {
            //from && to - empty
            if ($from === '' && $to === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from_to')
                ];

                echo json_encode($response);
                return;
            }

            $date = new DateTime();

            //from - not empty
            if ($from !== '') {
                $from = DateTime::createFromFormat('Y-m-d', $from);
                //from - invalid
                if (!$from) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date', ['field' => $from])
                    ];

                    echo json_encode($response);
                    return;
                }
                //from - future
                if ($from > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            //to - not empty
            if ($to !== '') {
                $to = DateTime::createFromFormat('Y-m-d', $to);
                //from - invalid
                if (!$to) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date', ['field' => $from])
                    ];

                    echo json_encode($response);
                    return;
                }
                //to - future
                if ($to > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            //from && to not empty - from > to
            if ($from !== '' && $to !== '' && $from > $to) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.from_to')
                ];

                echo json_encode($response);
                return;
            }

            //from not empty- reformat
            if ($from !== '') {
                $from = $from->format('Y-m-d');
            }

            //to not empty - reformat
            if ($to !== '') {
                $to = $to->format('Y-m-d');
            }
        }
        //month
        $month = trim($_GET['month'] ?? 'none');
        $month = filter_var($month, FILTER_VALIDATE_INT);
        $month = ($month === false || ($month < 1 || $month > 12)) ? 'none' : $month;
        //year
        $year = trim($_GET['year'] ?? date('Y'));
        $year = filter_var($year, FILTER_VALIDATE_INT);
        $year =  ($year === false || ($year < 1700 || $year > 2500)) ? ((new DateTime())->format('Y')) : $year;

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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        //num_caisse
        $num_caisse = trim($_GET['num_caisse'] ?? '');
        //num_caisse - empty
        if ($num_caisse === '') {
            $response = [
                'message_type' => 'invalid',
                'message' => __('messages.invalids.caisse_not_selected')
            ];

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
            $response = [
                'message_type' => 'invalid',
                'message' => __('messages.not_found.caisse_num_caisse', ['field' => $num_caisse])
            ];

            echo  json_encode($response);
            return;
        }

        //id_utilisateur
        $id_utilisateur = trim($_GET['id_utilisateur'] ?? '');

        //from
        $from = trim($_GET['from'] ?? '');
        //to
        $to = trim($_GET['to'] ?? '');

        $date = new DateTime();
        //from - not empty
        if ($from !== '') {
            $from = str_replace('T', ' ', $from);
            $from = DateTime::createFromFormat('Y-m-d H:i', $from);
            //from - invalid
            if (!$from) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //from - future
            if ($from > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
        }
        //to - not empty
        if ($to !== '') {
            $to = str_replace('T', ' ', $to);
            $to = DateTime::createFromFormat('Y-m-d H:i', $to);
            //from - invalid
            if (!$to) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //to - future
            if ($to > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
        }
        //from && to not empty - from > to
        if ($from !== '' && $to !== '' && $from > $to) {
            $response = [
                'message_type' => 'invalid',
                'message' => __('messages.invalids.from_to')
            ];

            echo json_encode($response);
            return;
        }

        //from not empty- reformat
        if ($from !== '') {
            $from = $from->format('Y-m-d H:i:s');
        }

        //to not empty - reformat
        if ($to !== '') {
            $to = $to->format('Y-m-d H:i:s');
        }

        //parametters
        $params = [
            'num_caisse' => $num_caisse,
            'id_utilisateur' => $id_utilisateur,
            'from' => $from,
            'to' => $to
        ];

        //filter ligne caisse
        $response = LigneCaisse::filterLigneCaisse($params);

        echo json_encode($response);
        return;
    }

    //action - list free caisse
    public function listFreeCaisse()
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

        //role - not caissier
        if ($is_loged_in->getRole() !== 'caissier') {
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        //list free caisse
        $response = Caisse::listFreeCaisse();

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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_caisse_update - invalid
            $num_caisse_update = filter_var($json['num_caisse_update'], FILTER_VALIDATE_INT);
            if ($num_caisse_update === false || $num_caisse_update < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_num_caisse')
                ];

                echo json_encode($response);
                return;
            }

            //solde - empty
            if ($json['solde'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.solde')
                ];

                echo json_encode($response);
                return;
            }
            //solde - invalid
            $solde = filter_var($json['solde'], FILTER_VALIDATE_FLOAT);
            if ($solde === false || $solde < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.solde')
                ];

                echo json_encode($response);
                return;
            }

            //seuil - empty
            if ($json['seuil'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.seuil')
                ];

                echo json_encode($response);
                return;
            }
            //seuil - invalid
            $seuil = filter_var($json['seuil'], FILTER_VALIDATE_FLOAT);
            if ($seuil === false || $seuil < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.seuil')
                ];

                echo json_encode($response);
                return;
            }

            //solde < seuil
            if ($solde < $seuil) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.solde_seuil')
                ];

                echo json_encode($response);
                return;
            }

            try {
                //num_caisse exist
                $response = Caisse::findById($json['num_caisse']);
                //error
                if ($response['message_type'] === 'error') {

                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.caisse_num_caisse', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update caisse
                ($response['model'])->setNumCaisse($json['num_caisse'])
                    ->setNumCaisseUpdate($num_caisse_update)
                    ->setSolde($solde)
                    ->setSeuil($seuil);
                $response = $response['model']->updateCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_updateCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Locations: ' . SITE_URL . '/caisse');
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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_caisse = array_map(fn($x) => trim($x), $json['nums_caisse']);

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
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_deleteAll',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all caisse
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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_caisse = array_map(fn($x) => trim($x), $json['nums_caisse']);

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
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_deleteAll',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete  all ligne caisse
    public function deleteAllLigneCaisse()
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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            $ids_lc = array_map(fn($x) => trim($x), $json['ids_lc']);

            //ids_lc - empty
            if (count($ids_lc) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_ids_lc_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all ligne caisse
                $response = LigneCaisse::deleteAllLigneCaisse($ids_lc);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_deleteAllLigneCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - add ligne caisse
    public function addLigneCaisse()
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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //id_lc - invalid
            $id_lc = filter_var($json['id_lc'], FILTER_VALIDATE_INT);
            if ($id_lc === false || $id_lc < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_id_lc')
                ];

                echo json_encode($response);
                return;
            }
            $json['id_lc'] = $id_lc;

            //num_caisse - invalid
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_num_caisse')
                ];

                echo json_encode($response);
                return;
            }

            //id_utilisateur - invalid
            $id_utilisateur = filter_var($json['id_utilisateur'], FILTER_VALIDATE_INT);
            if ($id_utilisateur === false || $id_utilisateur < 10000) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.user_id_user', ['field' => $json['id_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }

            //from - empty
            if ($json['date_debut'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from')
                ];

                echo json_encode($response);
                return;
            }
            //date fin not empty - from empty
            if ($json['date_debut'] === '' && $json['date_fin'] !== '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from')
                ];

                echo json_encode($response);
                return;
            }
            //from - invalid
            $date_debut = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_debut']);
            if ($date_debut === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $json['date_debut']])
                ];

                echo json_encode($response);
                return;
            }
            //to not empty - invalid
            if ($json['date_fin'] !== '' && DateTime::createFromFormat('Y-m-d\TH:i', $json['date_fin']) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $json['date_debut']])
                ];

                echo json_encode($response);
                return;
            }

            //to not empty - format
            if ($json['date_fin'] !== '') {
                $json['date_fin'] = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_fin']);;
            }
            //to not empty - from > to
            if ($json['date_fin'] !== '' && $date_debut > $json['date_fin']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.from_to')
                ];

                echo json_encode($response);
                return;
            }
            $date = new DateTime();
            //from - future
            if ($date_debut > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $json['date_debut'] = $date_debut->format('Y-m-d H:i:s');
            //to not empty - reformat
            if ($json['date_fin'] !== '') {
                //to - future
                if ($json['date_fin'] > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }

                $json['date_fin'] = $json['date_fin']->format('Y-m-d H:i:s');
            }

            try {

                //is num_caisse exist ?
                $response = Caisse::findById($json['num_caisse']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.caisse_num_caisse', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //to empty - caisse occuped
                if ($json['date_fin'] === '' && $response['model']->getEtatCaisse() === 'occupé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_occuped', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //to empty - caisse deleted
                if ($json['date_fin'] === '' && $response['model']->getEtatCaisse() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_deleted', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //is user exist?
                $response = User::findById($json['id_utilisateur']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.user_id', ['field' => $json['id_utilisateur']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //to empty - role not caissier
                if ($json['date_fin'] === '' && $response['model']->getRole() !== 'caissier') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_occupCaisse')
                    ];

                    echo json_encode($response);
                    return;
                }
                //to empty - user deleted
                if ($json['date_fin'] === '' && $response['model']->getEtatUtilisateur() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.user_deleted', ['field' => $json['id_utilisateur']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //add ligne caisse
                $ligne_caisse_model = new LigneCaisse();
                $ligne_caisse_model
                    ->setIdLc($json['id_lc'])
                    ->setDateDebut($json['date_debut'])
                    ->setDateFin($json['date_fin'])
                    ->setIdUtilsateur($json['id_utilisateur'])
                    ->setNumCaisse($json['num_caisse']);
                $response = $ligne_caisse_model->addLigneCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_addLigneCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - occup caisse
    public function occupCaisse()
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            $id_utilisateur = null;

            //role - admin
            if ($is_loged_in->getRole() === 'admin') {
                $id_utilisateur = $json['id_utilisateur'];

                //id_utilisateur - empty
                if ($id_utilisateur === '') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
            }
            //role - caisier
            else {
                $id_utilisateur = $is_loged_in->getIdUtilisateur();
            }

            try {

                //find user
                $response = User::findById($id_utilisateur);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.user_id', ['field' => $id_utilisateur])
                    ];

                    echo json_encode($response);
                    return;
                }
                //role - !caissier
                if ($response['model']->getRole() !== 'caissier') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_occupCaisse')
                    ];

                    echo json_encode($response);
                    return;
                }
                // user - deleted
                if ($response['model']->getEtatUtilisateur() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.user_deleted', ['field' => $id_utilisateur])
                    ];

                    echo json_encode($response);
                    return;
                }

                //find caisse
                $response = Caisse::findById($json['num_caisse']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.caisse_num_caisse', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //caisse - deleted
                if ($response['model']->getEtatCaisse() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_deleted', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //caisse - occuped
                if ($response['model']->getEtatCaisse() === 'occupé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_occuped', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //occup caisse
                $ligne_caisse_model = new LigneCaisse();
                $ligne_caisse_model
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($json['num_caisse']);
                $response = $ligne_caisse_model->occupCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_occupCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            // echo json_encode($json);
            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - quit caisse
    public function quitCaisse()
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
        //role - !caissier
        if ($is_loged_in->getRole() !== 'caissier') {
            //redirect to caisse index
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            try {

                //quit caisse
                $ligne_caisse_model = new LigneCaisse();
                $ligne_caisse_model->setIdUtilsateur($is_loged_in->getIdUtilisateur());
                $response = $ligne_caisse_model->quitCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_quitCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - free caisse
    public function freeCaisse()
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
        //role - !admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $nums_caisse = array_map(fn($x) => trim($x), $json['nums_caisse']);

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

                //free caisse
                $response = Caisse::freeCaisse($nums_caisse);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_freeCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }

        echo json_encode($response);
        return;
    }

    //action - update ligne caisse
    public function updateLigneCaisse()
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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //id_update - invalid
            $id_update = filter_var($json['id_update'], FILTER_VALIDATE_INT);
            if ($id_update === false || $id_update < 1) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_id_lc')
                ];

                echo json_encode($response);
                return;
            }
            $json['id_update'] = $id_update;

            //num_caisse - invalid
            $num_caisse = filter_var($json['num_caisse'], FILTER_VALIDATE_INT);
            if ($num_caisse === false || $num_caisse < 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.caisse_num_caisse')
                ];

                echo json_encode($response);
                return;
            }

            //id_utilisateur - invalid
            $id_utilisateur = filter_var($json['id_utilisateur'], FILTER_VALIDATE_INT);
            if ($id_utilisateur === false || $id_utilisateur < 10000) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.user_id_user', ['field' => $json['id_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }

            //from - empty
            if ($json['date_debut'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from')
                ];

                echo json_encode($response);
                return;
            }
            //date fin not empty - from empty
            if ($json['date_debut'] === '' && $json['date_fin'] !== '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from')
                ];

                echo json_encode($response);
                return;
            }
            //from - invalid
            $date_debut = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_debut']);
            if ($date_debut === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $json['date_debut']])
                ];

                echo json_encode($response);
                return;
            }
            //to not empty - invalid
            if ($json['date_fin'] !== '' && DateTime::createFromFormat('Y-m-d\TH:i', $json['date_fin']) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $json['date_debut']])
                ];

                echo json_encode($response);
                return;
            }
            //to not empty - format
            if ($json['date_fin'] !== '') {
                $json['date_fin'] = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_fin']);;
            }
            //to not empty - from > to
            if ($json['date_fin'] !== '' && $date_debut > $json['date_fin']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.from_to')
                ];

                echo json_encode($response);
                return;
            }
            $date = new DateTime();
            //from - future
            if ($date_debut > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }

            $json['date_debut'] = $date_debut->format('Y-m-d H:i:s');
            //to not empty - reformat
            if ($json['date_fin'] !== '') {
                //to - future
                if ($json['date_fin'] > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }

                $json['date_fin'] = $json['date_fin']->format('Y-m-d H:i:s');
            }

            try {

                //id_lc exist ?
                $response = LigneCaisse::findById($json['id_lc']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.caisse_id_lc', ['field' => $json['id_lc']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //to empty - date_fin not empty
                if ($json['date_fin'] === '' && ($response['model']->getDateFin() !== null && $response['model']->getDateFin() !== '')) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_date_fin_reopen')
                    ];

                    echo json_encode($response);
                    return;
                }
                //to empty - id_user modified
                if ($json['date_fin'] === '' && $response['model']->getIdUtilisateur() !== (int)$json['id_utilisateur']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_line_open_user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
                //to empty - num_caisse modified
                if ($json['date_fin'] === '' && $response['model']->getNumCaisse() !== (int)$json['num_caisse']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_line_open_num_caisse')
                    ];

                    echo json_encode($response);
                    return;
                }

                //is num_caisse exist ?
                $response = Caisse::findById($json['num_caisse']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.caisse_num_caisse', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //is user exist?
                $response = User::findById($json['id_utilisateur']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.user_id', ['field' => $json['id_utilisateur']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update ligne caisse
                $ligne_caisse_model = new LigneCaisse();
                $ligne_caisse_model
                    ->setIdLc($json['id_lc'])
                    ->setIdUpdate($json['id_update'])
                    ->setDateDebut($json['date_debut'])
                    ->setDateFin($json['date_fin'])
                    ->setIdUtilsateur($json['id_utilisateur'])
                    ->setNumCaisse($json['num_caisse']);
                $response = $ligne_caisse_model->updateLigneCaisse();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_updateLigneCaisse',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to caisse index
        else {
            header('Location: ' . SITE_URL . '/caisse');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - list all caisse
    public function listAllCaisse()
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
            //redirect to caisse index
            header("Location: " . SITE_URL . '/caisse');
            return;
        }

        //list all caisse
        $response = Caisse::listAllCaisse();

        echo json_encode($response);
        return;
    }

    //action - cash report
    public function cashReport()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged ?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header('Location: ' . SITE_URL . '/auth');
            return;
        }

        //defaults
        $date_by_default = ['per', 'between', 'month_year'];
        $per_default = ['DAY', 'WEEK', 'MONTH', 'YEAR'];

        //date_by
        $date_by = strtolower(trim($_GET['date_by'] ?? 'all'));
        $date_by = !in_array($date_by, $date_by_default, true) ? 'all' : $date_by;

        //per
        $per = strtoupper(trim($_GET['per'] ?? 'DAY'));
        $per = !in_array($per, $per_default, true) ? 'DAY' : $per;

        //between
        //from
        $from = trim($_GET['from'] ?? '');
        //to
        $to = trim($_GET['to'] ?? '');
        if ($date_by === 'between') {
            //from - empty
            if ($from === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from')
                ];

                echo json_encode($response);
                return;
            }
            //to - empty
            if ($to === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.to')
                ];

                echo json_encode($response);
                return;
            }
            $f = DateTime::createFromFormat('Y-m-d', $from);
            $date = new DateTime();
            //from - invalid
            if (!$f) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //from - future
            if ($f > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $t = DateTime::createFromFormat('Y-m-d', $to);
            //to - invalid
            if (!$to) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $to])
                ];

                echo json_encode($response);
                return;
            }
            //to - future
            if ($to > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            //from > to
            if ($f > $t) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.from_to')
                ];

                echo json_encode($response);
                return;
            }

            //format
            $from = $f->format('Y-m-d');
            $to = $t->format('Y-m-d');
        }

        //month_year
        //month
        $month = trim($_GET['month'] ?? 'none');
        $month = filter_var($month, FILTER_VALIDATE_INT);
        $month = (!$month || $month < 1 || $month > 12) ? 'none' : $month;
        //year
        $year = trim($_GET['year'] ?? date('Y'));
        $year = filter_var($year, FILTER_VALIDATE_INT);
        $year = (!$year || $year > date('Y')) ? date('Y') : $year;

        //config.json
        $config = json_decode(file_get_contents(PUBLIC_PATH . '/config/config.json'), true);

        //lang
        $lang = '';
        switch ($_COOKIE['lang']) {
            case 'en':
                $lang = 'en_US';
                break;
            case 'fr':
                $lang = 'fr_FR';
                break;
            case 'mg':
                $lang = 'mg_MG';
                break;
        }

        try {
            //num_caisse
            $num_caisse = "";
            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                $num_caisse = trim($_GET['num_caisse'] ?? '');
                //num_caisse - empty
                if ($num_caisse === '') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_not_selected')
                    ];

                    echo json_encode($response);
                    return;
                }
            }
            //role caissier
            else {
                //does user have caisse ?
                $response = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.user_caisse')
                    ];

                    echo json_encode($response);
                    return;
                }
                $num_caisse = $response['model']->getNumCaisse();
            }
            //does caisse exist
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
                    'message' => __('messages.not_found.caisse_num_caisse', ['field' => $num_caisse])
                ];

                echo json_encode($response);
                return;
            }
            //solde_caisse
            $solde_caisse = $response['model']->getSolde();
            //seuil_caisse
            $seuil_caisse = $response['model']->getSeuil();

            $params = [
                'num_caisse' => $num_caisse,
                'date_by' => $date_by,
                'per' => $per,
                'from' => $from,
                'to' => $to,
                'month' => $month,
                'year' => $year
            ];

            //get cash report
            $cash_report = CaisseRepositorie::cashReport($params);
            //error
            if ($cash_report['message_type'] === 'error') {
                echo json_encode($cash_report);
                return;
            }

            //get name and firstname cashier
            $cashier = "";
            if ($is_loged_in->getRole() === 'caissier') {
                $response = User::findById($is_loged_in->getIdUtilisateur());
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.user_id', ['field' => $is_loged_in->getIdUtilisateur()])
                    ];

                    echo json_encode($response);
                    return;
                }
                $cashier = $response['model']->getNomUtilisateur() . ' ' . $response['model']->getPrenomsUtilisateur();
            }
            $id_utilisateur = $is_loged_in->getIdUtilisateur();

            //strings
            $strings = [
                'cash_report' => __('forms.titles.cash_report'),
                'on' => __('forms.labels.on'),
                'cash_fund' => __('forms.labels.cash_fund'),
                'cash_treshold' => __('forms.labels.cash_treshold'),
                'cash_num' => __('forms.labels.cash_num'),
                'cash_owner' => __('forms.labels.cash_owner'),
                'status' => __('forms.labels.status'),
                'date' => __('forms.labels.date'),
                'num' => __('forms.labels.num'),
                'label' => __('forms.labels.label'),
                'encasement' => __('forms.labels.encasement'),
                'disbursement' => __('forms.labels.disbursement'),
                'total' => __('forms.labels.total'),
                'responsable' => __('forms.labels.responsable')
            ];

            //header
            $html = "<!DOCTYPE html> 
                    <body>
                    <head>
                        <style>
                            body {
                                font-size: 11pt;
                            }
                            div {
                                width: 100%;
                            }
                            .center {
                                text-align: center;
                            }
                            .left {
                                text-align: left;
                            }
                            .table {
                                width: 100%;
                                margin: 40px 0;
                                border-collapse: collapse;
                            }
                            .thead {
                                text-align: center;
                                border: 1px solid black;
                            }
                            .thead th {
                                padding: 5px;
                                border: 1px solid black;
                            }
                            .table td {
                                padding-left: 5px;
                                padding-right: 5px;
                                border: 1px solid black;
                            }
                        </style>
                    </head>
                    <body>";

            //title
            if (!isset($config['enterprise_name'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.config', ['field' => 'enterprise_name'])
                ];

                echo json_encode($response);
                return;
            }
            if (!isset($config['currency_units'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.config', ['field' => 'currency_units'])
                ];

                echo json_encode($response);
                return;
            }
            //date_by
            $on = "";
            switch ($date_by) {
                case 'all':
                    $on = __('forms.labels.all');
                    break;
                case 'per':
                    switch ($per) {
                        case 'DAY':
                            $day = new DateTime();
                            $formatter = new IntlDateFormatter(
                                $lang,
                                IntlDateFormatter::LONG,
                                IntlDateFormatter::NONE
                            );
                            $on = $formatter->format($day);
                            break;

                        case 'WEEK':
                            $week = new DateTime();
                            $formatter = new IntlDateFormatter(
                                $lang,
                                IntlDateFormatter::LONG,
                                IntlDateFormatter::NONE
                            );
                            //start_week
                            $start_week = (clone $week)->modify('monday this week');
                            $start_week = $formatter->format($start_week);
                            //end_week
                            $end_week = (clone $week)->modify('sunday this week');
                            $end_week = $formatter->format($end_week);

                            $on = $start_week . ' ' . __('forms.labels.to') . ' ' . $end_week;
                            break;
                        case 'MONTH':
                            $month = new DateTime();
                            $formatter = new IntlDateFormatter(
                                $lang,
                                IntlDateFormatter::LONG,
                                IntlDateFormatter::NONE,
                                null,
                                null,
                                'MMMM YYYY'
                            );

                            $on  = __('forms.labels.month') . ' ' . $formatter->format($month);
                            break;
                        case 'YEAR':
                            $year = new DateTime();
                            $formatter = new IntlDateFormatter(
                                $lang,
                                IntlDateFormatter::LONG,
                                IntlDateFormatter::NONE,
                                null,
                                null,
                                'YYYY'
                            );

                            $on  = __('forms.labels.year') . ' ' . $formatter->format($year);
                            break;
                    }
                    break;
                case 'between':
                    $from = new DateTime($from);
                    $to = new DateTime($to);;
                    $formatter = new IntlDateFormatter(
                        $lang,
                        IntlDateFormatter::LONG,
                        IntlDateFormatter::NONE,
                    );

                    $on  = $formatter->format($from) . ' ' . __('forms.labels.to') . ' ' . $formatter->format($to);
                    break;
                case 'month_year':
                    //month - none
                    if ($month === 'none') {
                        $year = new DateTime($year . '-04-01');
                        $formatter = new IntlDateFormatter(
                            $lang,
                            IntlDateFormatter::LONG,
                            IntlDateFormatter::NONE,
                            null,
                            null,
                            'YYYY'
                        );

                        $on  = __('forms.labels.year') . ' ' . $formatter->format($year);
                    }
                    //month - !none
                    else {
                        $month_year = new DateTime($year . '-' . $month . '-01');
                        $formatter = new IntlDateFormatter(
                            $lang,
                            IntlDateFormatter::LONG,
                            IntlDateFormatter::NONE,
                            null,
                            null,
                            'MMMM YYYY'
                        );

                        $on  = __('forms.labels.month') . ' ' . $formatter->format($month_year);
                    }
                    break;
            }
            //status
            $interval = $cash_report['total_entree'] - $cash_report['total_sortie'];
            $status = '';
            if ($interval < 0) {
                $status = __('forms.labels.loss') . " (-{$interval} {$config['currency_units']})";
            } elseif ($interval === 0) {
                $status = __('forms.labels.neutral');
            } else {
                $status = __('forms.labels.benefice') . " (+{$interval} {$config['currency_units']})";
            }
            $html .= "<div class='header'>
                        <h4 class='center'>{$config['enterprise_name']}</h4>
                        <h3 class='center' style='margin-bottom: 20px;' ><u>{$strings['cash_report']}</u></h3>
                        <p class='left'><u><b>{$strings['on']} :</b></u> {$on}</p>
                        <p class='left'><u><b>{$strings['cash_fund']} :</b></u> {$solde_caisse} {$config['currency_units']}</p>
                        <p class='left'><u><b>{$strings['cash_treshold']} :</b></u> {$seuil_caisse} {$config['currency_units']}</p>
                        <p class='left'><u><b>{$strings['cash_num']} :</b></u> {$num_caisse}</p>
                        <p class='left'><u><b>{$strings['status']} :</b></u> {$status}</p>";
            if ($is_loged_in->getRole() === 'admin') {
                $html .= "</div>";
            } else {
                $html .= "<p class='left'><u><b>{$strings['cash_owner']} :</b></u> {$cashier} ($id_utilisateur)</p>
                </div>";
            }

            //table
            $html .= "<table class='table'>
                            <tr class='thead'>
                                <th>{$strings['date']}</th>
                                <th>{$strings['num']}</th>
                                <th>{$strings['label']}</th>
                                <th>{$strings['encasement']} ({$config['currency_units']})</th>
                                <th>{$strings['disbursement']} ({$config['currency_units']})</th>
                            </tr>";
            $html .= "<tbody>";
            foreach ($cash_report['data'] as &$line) {
                $date = new DateTime($line['DATE']);
                $formatter = new IntlDateFormatter(
                    $lang,
                    IntlDateFormatter::SHORT,
                    IntlDateFormatter::NONE
                );
                $line['DATE'] = $formatter->format($date);
                if (strpos($line['numero'], '/TOTAL') !== false) {
                    $html .= "<tr style='background-color: gray; color: white;'>";
                } else {
                    $html .= "<tr>";
                }
                $html .= "
                            <td>{$line['DATE']}</td>
                            <td>{$line['numero']}</td>
                            <td>{$line['libelle']}</td>
                            <td>{$line['encaissement']}</td>
                            <td>{$line['decaissement']}</td>
                        </tr>";
            }
            //total
            $html .= "<tr>
                        <td colspan='3'>{$strings['total']}</td>
                        <td>{$cash_report['total_entree']}</td>
                        <td>{$cash_report['total_sortie']}</td>
                    </tr>";
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "<div><p style='text-align: left;'><b><u>{$strings['responsable']} :</u></b></p></div>";

            //footer
            $html .= "</body>
                    </html>";

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $response = [
                'message_type' => 'success',
                'pdf' => base64_encode($dompdf->output()),
                'file_name' => str_replace(' ', '_', strtolower($strings['cash_report'] . ' ' . $strings['on'] . ' ' . $on . '.pdf')),
                'message' => __('messages.success.print')
            ];

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_cashReport',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            echo json_encode($response);
            return;
        }

        echo json_encode($response);
        return;
    }
}
