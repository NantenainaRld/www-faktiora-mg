<?php

//dompdf
require_once LIBS_PATH . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//CLASS - entree controller
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

    //page - facture dashboard
    public function pageFacture()
    {
        //is loged in ?
        $is_loged_in = Auth::isLogedIn();
        //loged
        if ($is_loged_in->getLoged()) {

            //get currency_units
            $currency_units = '';
            try {
                $config = json_decode(file_get_contents(PUBLIC_PATH . '/config/config.json'), true);

                //currency_units not found
                if (!isset($config['currency_units'])) {
                    //redirect to error page 
                    header('Location:' . SITE_URL . '/errors?messages=' . __('errors.not_found.json', ['field' => 'currency_units']));

                    return;
                }

                $currency_units = $config['currency_units'];
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

                //redirect to error page
                header('Location: ' . SITE_URL . '/errors?messages=' . $response['message']);

                return;
            }

            $_SESSION['menu'] = 'facture';
            $this->render('facture_dashboard', [
                'title' => 'Faktiora - ' . __('forms.titles.facture_dashboard'),
                'role' => $is_loged_in->getRole(),
                'currency_units' => $currency_units
            ]);
            return;
        }
        //not loged
        else {
            //redirect to login page
            header('Location: ' . SITE_URL . '/auth');
            return;
        }
    }
    //========================== ACTIONS ============================

    //action - create autree entree
    public function createAutreEntree()
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

            //libelle_ae - empty
            if ($json['libelle_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_ae - invalid
            if (strlen($json['libelle_ae']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //date_ae
            $date_ae = "";
            //role admin date_ae - empty
            if ($is_loged_in->getRole() === 'admin' && $json['date_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //role admin date_ae - invalid
            if ($is_loged_in->getRole() === 'admin') {
                $date_ae = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']);
                if ($date_ae === false) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_ae']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                $date = new DateTime();
                //date_ae - future
                if ($date_ae > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_ae = $date_ae->format('Y-m-d H:i:s');
            }

            //montant_ae - empty
            if ($json['montant_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.montant')
                ];

                echo json_encode($response);
                return;
            }
            //montant_ae - invalid
            $montant_ae = filter_var($json['montant_ae'], FILTER_VALIDATE_FLOAT);
            if ($montant_ae === false || $montant_ae < 1) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.montant')
                ];

                echo json_encode($response);
                return;
            }

            //id_utilisateur
            $id_utilisateur = "";
            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                $id_utilisateur = $json['id_utilisateur'];
                //id_utilisateur - empty
                if ($id_utilisateur === "") {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
            }
            //role caissier
            else {
                $id_utilisateur = $is_loged_in->getIdUtilisateur();
            }

            try {

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
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
                    //caisse - deleted
                    if ($response['model']->getEtatCaisse() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.caisse_deleted', ['field' => $json['num_caisse']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $num_caisse = $json['num_caisse'];
                }
                //role caissier
                else {
                    //is user hash caisse ?
                    $response = LigneCaisse::findCaisse($id_utilisateur);
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

                //role admin - is user exist ?
                if ($is_loged_in->getRole() === 'admin') {
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
                }

                //create autre entree
                $autre_entree_model = new AutreEntree();
                $autre_entree_model
                    ->setLibelleAe($json['libelle_ae'])
                    ->setMontantAe($montant_ae)
                    ->setDateAe($date_ae)
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($num_caisse);
                $response = $autre_entree_model->createAutreEntree($is_loged_in->getRole());

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_createAutreEntree',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - filter autre entree
    public function filterAutreEntree()
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

        //defaults
        $order_by_default = ['num', 'date', 'montant'];
        $arrange_default = ['ASC', 'DESC'];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'active'));
        $status = ($status === 'deleted') ? 'deleted' : 'active';
        if ($is_loged_in->getRole() === 'caissier') {
            $status = 'active';
        }

        //num_caisse
        $num_caisse = 'all';
        $num_caisse = trim($_GET['num_caisse'] ?? 'all');
        $num_caisse = filter_var($num_caisse, FILTER_VALIDATE_INT);
        $num_caisse = ($num_caisse === false || $num_caisse < 0) ? 'all' : $num_caisse;

        //id_user
        $id_user = trim($_GET['id_user'] ?? 'all');
        $id_user = filter_var($id_user, FILTER_VALIDATE_INT);
        $id_user = ($id_user === false || $id_user < 10000) ? 'all' : $id_user;

        //oder_by
        $order_by = strtolower(trim($_GET['order_by']) ?? 'num');
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'num';
        switch ($order_by) {
            case 'num':
                $order_by = 'num_ae';
                break;
            case 'date':
                $order_by = 'date_ae';
                break;
            case 'montant':
                $order_by = 'montant_ae';
                break;
        }
        //arrange
        $arrange = strtoupper(trim($_GET['arrange'] ?? 'ASC'));
        $arrange = in_array($arrange, $arrange_default, true) ? $arrange : 'ASC';

        $date = new DateTime();

        //from
        $from = trim($_GET['from'] ?? '');
        //from not empty
        if ($from !== '') {
            $f = DateTime::createFromFormat('Y-m-d', $from);
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
            $from = $f->format('Y-m-d');
        }
        //to
        $to = trim($_GET['to'] ?? '');
        //to not empty
        if ($to !== '') {
            $t = DateTime::createFromFormat('Y-m-d', $to);
            //to - invalid
            if (!$t) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //to - future
            if ($t > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $to = $t->format('Y-m-d');
        }

        //search_ae
        $search_ae = trim($_GET['search_ae'] ?? '');

        //parametters
        $params = [
            'status' => $status,
            'num_caisse' => $num_caisse,
            'id_user' => $id_user,
            'order_by' => $order_by,
            'arrange' => $arrange,
            'from' => $from,
            'to' => $to,
            'search_ae' => $search_ae
        ];

        //filter autre entree
        $response = AutreEntree::filterAutreEntree($params);

        // echo json_encode($params);
        echo json_encode($response);
        return;
    }

    //action - list all autre entree
    public function listAllAutreEntree()
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

        //defaults
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

        //num_caisse
        $num_caisse = "";
        //role - admin
        if ($is_loged_in->getRole() === 'admin') {
            $num_caisse = trim($_GET['num_caisse'] ?? 'all');
            $num_caisse = filter_var($num_caisse, FILTER_VALIDATE_INT);
            $num_caisse = (!$num_caisse || $num_caisse < 0) ? 'all' : $num_caisse;
        }
        //role - caissier
        else {
            //find user caisse
            $find_user_caisse = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());

            //error
            if ($find_user_caisse['message_type'] === 'error') {
                echo json_encode($response);

                return;
            }
            //not found user caisse
            elseif (!$find_user_caisse['found']) {
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'data' => [],
                    'nb_ae' => 0,
                    'total_ae' => 0,
                ];

                echo json_encode($response);

                return;
            }

            //found user caisse
            $num_caisse = $find_user_caisse['model']->getNumCaisse();
        }

        //id_utilisateur
        $id_utilisateur = trim($_GET['id_utilisateur'] ?? 'all');

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
        $date = new DateTime();
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
        $year =  ($year === false || ($year < 1700 || $year > date('Y'))) ? ((new DateTime())->format('Y')) : $year;

        $params = [
            'num_caisse' => $num_caisse,
            'id_utilisateur' => $id_utilisateur,
            'date_by' => $date_by,
            'per' => $per,
            'from' => $from,
            'to' => $to,
            'month' => $month,
            'year' => $year,
        ];

        //list all caisse
        $response = AutreEntree::listAllAutreEntree($params);

        echo json_encode($response);
        return;
    }

    //action - update autre entree
    public function updateAutreEntree()
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

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //libelle_ae - empty
            if ($json['libelle_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_ae - invalid
            if (strlen($json['libelle_ae']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //role admin 
            $date_ae = '';
            if ($is_loged_in->getRole() === 'admin') {
                //date_ae - empty
                if ($json['date_ae'] === '') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.date')
                    ];

                    echo json_encode($response);
                    return;
                }
                //date_ae - invalid
                $date_ae = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']);
                if ($date_ae === false) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_ae']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                $date = new DateTime();
                //date_ae - future
                if ($date_ae > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_ae = $date_ae->format('Y-m-d H:i:s');

                //id_utilisateur - empty
                if ($json['id_utilisateur'] === '') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            try {

                //is num_ae exist ?
                $json['num_ae'] = strtoupper($json['num_ae']);
                $response = AutreEntree::findById($json['num_ae']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.entree_num_ae', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //role caissier - num_ae deleted
                if ($is_loged_in->getRole() === 'caissier' && $response['model']->getEtatAe() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.entree_num_ae', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //role admin
                if ($is_loged_in->getRole() === 'admin') {

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

                    //is user exist ?
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
                }

                //update autre entree
                $autre_entree_model = new AutreEntree();
                $autre_entree_model
                    ->setNumAe($json['num_ae'])
                    ->setLibelleAe($json['libelle_ae'])
                    ->setDateAe($date_ae)
                    ->setIdUtilsateur($json['id_utilisateur'])
                    ->setNumCaisse($json['num_caisse']);
                $response = $autre_entree_model->updateAutreEntree($is_loged_in->getRole());

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_updateAutreEntree',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete all autre entree
    public function deleteAllAutreEntree()
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
            //redirect to entree index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_ae = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_ae']));

            //nums_ae - empty
            if (count($nums_ae) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_nums_ae_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {
                //delete all autre entree
                $response = AutreEntree::deleteAllAutreEntree($nums_ae);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_deleteAllAutreEntree',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all autre entree
    public function permanentDeleteAllAutreEntree()
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
            //redirect to entree index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_ae = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_ae']));

            //nums_ae - empty
            if (count($nums_ae) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_nums_ae_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {
                //delete all autre entree
                $response = AutreEntree::permanentDeleteAllAutreEntree($nums_ae);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_deleteAllAutreEntree',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - list connection autre entree
    public function listConnectionAutreEntree()
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

        //num_ae
        $num_ae = strtoupper(trim($_GET['num_ae'] ?? ''));

        try {

            //is num_ae exist ?
            $response = AutreEntree::findById($num_ae);
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }
            //not found
            if (!$response['found']) {
                $response = [
                    'message_type' => 'invalud',
                    'message' => __('messages.not_found.entree_num_ae', ['field' => $num_ae])
                ];

                echo json_encode($response);
                return;
            }

            //list connection autre entree
            $autre_entree_model = new AutreEntree();
            $autre_entree_model->setNumAe($num_ae);
            $response = $autre_entree_model->listConnectionAutreEntree();

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_listConnectionAutreEntree',
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

    //action - correction  ae
    public function correctionAutreEntree()
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

            //libelle_ae - empty
            if ($json['libelle_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_ae - invalid
            if (strlen($json['libelle_ae']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //date_ae
            $date_ae = "";
            //role admin date_ae - empty
            if ($is_loged_in->getRole() === 'admin' && $json['date_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //role admin date_ae - invalid
            if ($is_loged_in->getRole() === 'admin') {
                $date_ae = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']);
                if ($date_ae === false) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_ae']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                $date = new DateTime();
                //date_ae - future
                if ($date_ae > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_ae = $date_ae->format('Y-m-d H:i:s');
            }

            //montant_ae - empty
            if ($json['montant_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.montant')
                ];

                echo json_encode($response);
                return;
            }
            //montant_ae - invalid
            $montant_ae = filter_var($json['montant_ae'], FILTER_VALIDATE_FLOAT);
            if ($montant_ae === false || $montant_ae < 1) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.montant')
                ];

                echo json_encode($response);
                return;
            }

            //id_utilisateur
            $id_utilisateur = "";
            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                $id_utilisateur = $json['id_utilisateur'];
                //id_utilisateur - empty
                if ($id_utilisateur === "") {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
            }
            //role caissier
            else {
                $id_utilisateur = $is_loged_in->getIdUtilisateur();
            }

            try {

                //is num_ae exist ?
                $json['num_ae'] = strtoupper(trim($json['num_ae']));
                $response = AutreEntree::findById($json['num_ae']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.not_found.entree_num_ae', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //num_ae - deleted
                if ($response['model']->getEtatAe() === 'supprimé') {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.invalids.entree_ae_deleted', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }
                $ae_num_caisse = $response['model']->getNumCaisse();

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //does num_caisse exist ?
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
                    $num_caisse = $json['num_caisse'];
                }
                //role caissier
                else {
                    //does user hash caisse ?
                    $response = LigneCaisse::findCaisse($id_utilisateur);
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
                    //ae num_caisse != user num_caisse
                    if ($ae_num_caisse !== $num_caisse) {
                        $response = [
                            'message_type' => 'success',
                            'message' => __('messages.invalids.entree_correctionAutreEntree', ['field' => $json['num_ae']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                //role admin - is user exist ?
                if ($is_loged_in->getRole() === 'admin') {
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
                }

                //correction autre entree
                $autre_entree_model = new AutreEntree();
                $autre_entree_model
                    ->setLibelleAe('correction/' . $json['num_ae'] . ' - ' . $json['libelle_ae'])
                    ->setMontantAe($montant_ae)
                    ->setDateAe($date_ae)
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($num_caisse);
                $response = $autre_entree_model->createAutreEntree($is_loged_in->getRole());

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_createAutreEntree',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - correction  demande sortie
    public function correctionDemandeSortie()
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

            //libelle_ae - empty
            if ($json['libelle_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_ae - invalid
            if (strlen($json['libelle_ae']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //date_ae
            $date_ae = "";
            //role admin date_ae - empty
            if ($is_loged_in->getRole() === 'admin' && $json['date_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //role admin date_ae - invalid
            if ($is_loged_in->getRole() === 'admin') {
                $date_ae = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']);
                if ($date_ae === false) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_ae']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                $date = new DateTime();
                //date_ae - future
                if ($date_ae > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_ae = $date_ae->format('Y-m-d H:i:s');
            }

            //montant_ae - empty
            if ($json['montant_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.montant')
                ];

                echo json_encode($response);
                return;
            }
            //montant_ae - invalid
            $montant_ae = filter_var($json['montant_ae'], FILTER_VALIDATE_FLOAT);
            if ($montant_ae === false || $montant_ae < 1) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.montant')
                ];

                echo json_encode($response);
                return;
            }

            //id_utilisateur
            $id_utilisateur = "";
            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                $id_utilisateur = $json['id_utilisateur'];
                //id_utilisateur - empty
                if ($id_utilisateur === "") {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
            }
            //role caissier
            else {
                $id_utilisateur = $is_loged_in->getIdUtilisateur();
            }

            try {

                //does num_ds exist ?
                $json['num_ds'] = strtoupper(trim($json['num_ds']));
                $response = DemandeSortie::findById($json['num_ds']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.not_found.sortie_num_ds', ['field' => $json['num_ds']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //num_ds - deleted
                if ($response['model']->getEtatDs() === 'supprimé') {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.invalids.sortie_deleted', ['field' => $json['num_ds']])
                    ];

                    echo json_encode($response);
                    return;
                }
                $ds_num_caisse = $response['model']->getNumCaisse();

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
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
                    //caisse - deleted
                    if ($response['model']->getEtatCaisse() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.caisse_deleted', ['field' => $json['num_caisse']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $num_caisse = $json['num_caisse'];
                }
                //role caissier
                else {
                    //is user hash caisse ?
                    $response = LigneCaisse::findCaisse($id_utilisateur);
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
                    //ds num_caisse != user num_caisse
                    if ($ds_num_caisse !== $num_caisse) {
                        $response = [
                            'message_type' => 'success',
                            'message' => __('messages.invalids.sortie_correctionDemandeSortie', ['field' => $json['num_ds']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //does num_caisse exist ?
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
                    $num_caisse = $json['num_caisse'];
                }
                //role caissier
                else {
                    //does user hash caisse ?
                    $response = LigneCaisse::findCaisse($id_utilisateur);
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
                    //ae num_caisse != user num_caisse
                    if ($ds_num_caisse !== $num_caisse) {
                        $response = [
                            'message_type' => 'success',
                            'message' => __('messages.invalids.sortie_correctionDemandeSortie', ['field' => $json['num_ds']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                //role admin - is user exist ?
                if ($is_loged_in->getRole() === 'admin') {
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
                }

                //montant_ae > montant_ds
                $response = SortieRepositorie::getMontantDs($json['num_ds']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                if ($montant_ae > $response['montant_ds']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.entree_montant_ds')
                    ];

                    echo json_encode($response);
                    return;
                }

                //correction demande sortie
                $autre_entree_model = new AutreEntree();
                $autre_entree_model
                    ->setLibelleAe('correction/' . $json['num_ds'] . ' - ' . $json['libelle_ae'])
                    ->setMontantAe($montant_ae)
                    ->setDateAe($date_ae)
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($num_caisse);
                $response = $autre_entree_model->createAutreEntree($is_loged_in->getRole());

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_createAutreEntree',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to sortie index
        else {
            header('Location: ' . SITE_URL . '/sortie');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - create facture
    public function createFacture()
    {
        header('Content-Type: application/json');
        //is loged in
        $is_loged_in = Auth::isLogedIn();
        $response = null;

        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login
            header('Location: ' . SITE_URL . '/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            foreach ($json as $data => &$value) {
                if ($data !== 'produits') {
                    $value = trim($value);
                } else {
                    //produits - empty
                    if (count($json['produits']) <= 0) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.entree_produits_empty')
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //produits - not empty
                    else {
                        foreach ($value as &$line) {
                            foreach ($line as $produit => &$prod) {
                                $prod = trim($prod);
                            }
                        }
                    }
                }
            }

            //does produits valides ?
            foreach ($json['produits'] as $produit) {
                //quantite - invalid
                $quantite = filter_var($produit['quantite_produit'], FILTER_VALIDATE_INT);
                if (!$quantite || $quantite <= 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.entree_quantite_produit',
                            [
                                'id_produit' => $produit['id_produit'],
                                'quantite_produit' => $produit['quantite_produit']
                            ]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            //date_facture
            $date_facture = "";
            //date_facture empty - role admin
            if ($is_loged_in->getRole() === 'admin' && $json['date_facture'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //date_facture invalid - role admin
            if ($is_loged_in->getRole() === 'admin') {
                $date_facture = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_facture']);
                if (!$date_facture) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_facture']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                //date_facture - future
                $date = new DateTime();
                if ($date_facture > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_facture = $date_facture->format('Y-m-d H:i:s');
            }

            try {

                //id_user - role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //does user exist ?
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
                            'message' => __(
                                'messages.not_found.user_id',
                                ['field' => $json['id_utilisateur']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                }
                //id_user - role caissier
                else {
                    $json['id_utilisateur'] = $is_loged_in->getIdUtilisateur();
                }

                //num_caisse - role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //does num_caisse exist ?
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
                            'message' => __(
                                'messages.not_found.caisse_num_caisse',
                                ['field' => $json['num_caisse']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //caisse - deleted
                    if ($response['model']->getEtatCaisse() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.invalids.caisse_deleted',
                                ['field' => $json['num_caisse']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                }
                //num_caisse - role caissier
                else {
                    //does user have caisse ?
                    $response = LigneCaisse::findCaisse($json['id_utilisateur']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //user doesn't have caisse
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.user_caisse')
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //user has caisse
                    else {
                        $json['num_caisse'] = $response['model']->getNumCaisse();
                    }
                }

                //does client exist ?
                $response = Client::findById($json['id_client']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.client_id_client', ['field' => $json['id_client']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //client - deleted
                if ($response['model']->getEtatClient() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.client_deleted', ['field' => $json['id_client']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //does produit exist ?
                foreach ($json['produits'] as &$produit) {
                    //does produit exist ?
                    $response = Produit::findById($produit['id_produit']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.not_found.produit_id_produit',
                                ['field' => $produit['id_produit']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //produit - deleted
                    if ($response['model']->getEtatProduit() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.invalids.produit_deleted',
                                ['field' => $produit['id_produit']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //nb stock < quantite_produit
                    if ($response['model']->getNbStock() < $produit['quantite_produit']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.produit_nb_stock_less', [
                                'id_produit' =>
                                $produit['id_produit'],
                                'nb_stock' => $response['model']->getNbStock()
                            ])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $produit['prix_produit'] = $response['model']->getPrixProduit();
                }

                //create facture
                $facture_model = new Facture();
                $facture_model
                    ->setDateFacture($date_facture)
                    ->setIdUtilsateur($json['id_utilisateur'])
                    ->setNumCaisse($json['num_caisse'])
                    ->setIdClient($json['id_client']);
                $response = $facture_model->createFacture($json['produits']);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_createFacture',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }
    }

    //action - filter facture
    public function filterFacture()
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

        //defaults
        $arrange_by_default = ['num', 'date', 'montant'];
        $order_default = ['ASC', 'DESC'];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'all'));
        $status = ($status === 'deleted') ? 'deleted' : 'all';

        //num_caisse
        $num_caisse = "";
        //role - admin
        if ($is_loged_in->getRole() === 'admin') {
            $num_caisse = trim($_GET['num_caisse'] ?? 'all');
            $num_caisse = filter_var($num_caisse, FILTER_VALIDATE_INT);
            $num_caisse = (!$num_caisse || $num_caisse < 0) ? 'all' : $num_caisse;
        }
        //role - caissier
        else {
            //find user caisse
            $find_user_caisse = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());

            //error
            if ($find_user_caisse['message_type'] === 'error') {
                echo json_encode($response);

                return;
            }
            //not found user caisse
            elseif (!$find_user_caisse['found']) {
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'data' => [],
                    'nb_sortie' => 0,
                    'total_sortie' => 0,
                ];

                echo json_encode($response);

                return;
            }

            //found user caisse
            $num_caisse = $find_user_caisse['model']->getNumCaisse();
        }

        //id_user
        $id_user = trim($_GET['id_user'] ?? 'all');
        $id_user = filter_var($id_user, FILTER_VALIDATE_INT);
        $id_user = ($id_user === false || $id_user < 10000) ? 'all' : $id_user;

        //arrange_by
        $arrange_by = strtolower(trim($_GET['arrange_by'] ?? 'num'));
        $arrange_by = in_array($arrange_by, $arrange_by_default, true) ? $arrange_by : 'num';
        switch ($arrange_by) {
            case 'num':
                $arrange_by = 'f.num_facture';
                break;
            case 'date':
                $arrange_by = 'f.date_facture';
                break;
            case 'montant':
                $arrange_by = 'montant_facture';
                break;
        }
        //order
        $order = strtoupper(trim($_GET['order'] ?? 'ASC'));
        $order = in_array($order, $order_default, true) ? $order : 'ASC';

        $date = new DateTime();

        //from
        $from = trim($_GET['from'] ?? '');
        //from not empty
        if ($from !== '') {
            $f = DateTime::createFromFormat('Y-m-d', $from);
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
            $from = $f;
        }
        //to
        $to = trim($_GET['to'] ?? '');
        //to not empty
        if ($to !== '') {
            $t = DateTime::createFromFormat('Y-m-d', $to);
            //to - invalid
            if (!$t) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //to - future
            if ($t > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $to = $t;
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
        //from not epty - format
        if ($from !== '') {
            $from = $from->format('Y-m-d');
        }
        //to not empty - format
        if ($to !== '') {
            $to = $to->format('Y-m-d');
        }

        //search_facture
        $search_facture = trim($_GET['search_facture'] ?? '');

        //parametters
        $params = [
            'status' => $status,
            'num_caisse' => $num_caisse,
            'id_user' => $id_user,
            'arrange_by' => $arrange_by,
            'order' => $order,
            'from' => $from,
            'to' => $to,
            'search_facture' => $search_facture
        ];

        //filter facture
        $response = EntreeRepositorie::filterFacture($params);

        echo json_encode($response);
        return;
    }

    //action - list all facture
    public function listAllFacture()
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

        //defaults
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

        //id_client
        $id_client = trim($_GET['id_client'] ?? '');

        //num_caisse
        $num_caisse = '';
        //role - admin
        if ($is_loged_in->getRole() === 'admin') {
            $num_caisse = trim($_GET['num_caisse'] ?? 'all');
            $num_caisse = filter_var($num_caisse, FILTER_VALIDATE_INT);
            $num_caisse = (!$num_caisse || $num_caisse < 0) ? 'all' : $num_caisse;
        }
        //role - caissier and id_client empty
        elseif (empty($id_client)) {
            //find user caisse
            $find_user_caisse = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());

            //error
            if ($find_user_caisse['message_type'] === 'error') {
                echo json_encode($response);

                return;
            }
            //not found user caisse
            elseif (!$find_user_caisse['found']) {
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'data' => [],
                    'nb_facture' => 0,
                    'total_facture' => 0,
                ];

                echo json_encode($response);

                return;
            }

            //found user caisse
            $num_caisse = $find_user_caisse['model']->getNumCaisse();
        }

        //id_utilisateur
        $id_utilisateur = trim($_GET['id_utilisateur'] ?? 'all');

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
        $date = new DateTime();
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
        $year =  ($year === false || ($year < 1700 || $year > date('Y'))) ? ((new DateTime())->format('Y')) : $year;

        $params = [
            'num_caisse' => $num_caisse,
            'id_utilisateur' => $id_utilisateur,
            'date_by' => $date_by,
            'per' => $per,
            'from' => $from,
            'to' => $to,
            'month' => $month,
            'year' => $year,
            'id_client' => $id_client
        ];

        //list all facture
        $response = Facture::listAllFacture($params);

        echo json_encode($response);
        return;
    }
    //action - list lf and produit
    public function listLigneFacture()
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

        //num_facture
        $num_facture = strtoupper(trim($_GET['num_facture'] ?? ''));

        try {

            //does num_facture exist ?
            $response = Facture::findById($num_facture);
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }
            //not found
            if (!$response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.not_found.entree_num_facture', ['field' => $num_facture])
                ];

                echo json_encode($response);
                return;
            }

            //list lf and produit
            $response = EntreeRepositorie::ligneFacture($num_facture);

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_ligneFacture',
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

    //action - list connection facture
    public function listConnectionFacture()
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

        //num_facture
        $num_facture = strtoupper(trim($_GET['num_facture'] ?? ''));

        try {

            //does num_facture exist ?
            $response = Facture::findById($num_facture);
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }
            //not found
            if (!$response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.not_found.entree_num_facture', ['field' => $num_facture])
                ];

                echo json_encode($response);
                return;
            }

            //list connection facture
            $facture_model = new Facture();
            $facture_model->setNumFacture($num_facture);
            $response = $facture_model->listConnectionFacture();

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_listConnectionFacture',
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

    //action - delete all facture
    public function deleteAllFacture()
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
            //redirect to entree index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_facture = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_facture']));

            //nums_facture - empty
            if (count($nums_facture) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_nums_facture_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all facture
                $response = Facture::deleteAllFacture($nums_facture);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_deleteAllFacture',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete permanent all facture
    public function deletePermanentAllFacture()
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
            //redirect to entree index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_facture = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_facture']));

            //nums_facture - empty
            if (count($nums_facture) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_nums_facture_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete permanent all facture
                $response = Facture::deletePermanentAllFacture($nums_facture);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_deleteAllFacture',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - print facture
    public function printFacture()
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

        //formatters
        //number
        $formatterNumber = new NumberFormatter($lang, NumberFormatter::DECIMAL);
        $formatterNumber->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
        //total
        $formatterTotal = new NumberFormatter($lang, NumberFormatter::CURRENCY);
        $formatterTotal->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);

        //num_facture
        $num_facture = strtoupper(trim($_GET['num_facture'] ?? ''));
        //num_ds - empty
        if ($num_facture === '') {
            $response = [
                'message_type' => 'invalid',
                'message' => __('messages.invalids.entree_facture_not_selected')
            ];

            echo json_encode($response);
            return;
        }

        try {

            //does num_facture exist ?
            $facture = Facture::findById($num_facture);
            //error
            if ($facture['message_type'] === 'error') {
                echo json_encode($facture);
                return;
            }
            //not found
            if (!$facture['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.not_found.entree_num_facture', ['field' => $num_facture])
                ];

                echo json_encode($response);
                return;
            }

            //num_caisse
            $num_caisse = $facture['model']->getNumCaisse();
            //date_facture
            $date_facture = $facture['model']->getDateFacture();
            $date_facture = new DateTime($date_facture);
            $formatter = new IntlDateFormatter(
                $lang,
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE
            );
            $date_facture = $formatter->format($date_facture);
            //id_client
            $id_client = $facture['model']->getIdClient();

            //id_client - not null
            //nom_client
            $nom_client = "";
            //prenoms_client
            $prenoms_client = "";
            //telephone
            $telephone = "";
            //adresse
            $adresse = "";
            $sexe_client = "";
            if ($id_client) {
                //get client info
                $client = Client::findById($id_client);
                //error
                if ($client['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                $nom_client = $client['model']->getNomClient();
                if ($client['model']->getPrenomsClient()) {
                    $prenoms_client = $client['model']->getPrenomsClient();
                }
                if ($client['model']->getTelephone()) {
                    $telephone = $client['model']->getTelephone();
                }
                if ($client['model']->getAdresse()) {
                    $adresse = $client['model']->getAdresse();
                }
                if ($client['model']->getSexeClient() === 'masculin') {
                    $sexe_client = __('forms.labels.mister');
                } else {
                    $sexe_client = __('forms.labels.madam');
                }
            }

            //role caissier
            if ($is_loged_in->getRole() === 'caissier') {
                //does caissier have caisse ?
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
            }

            //get facture connection
            $facture_model = new Facture();
            $facture_model->setNumFacture($num_facture);
            $connection_facture = $facture_model->listConnectionFacture();
            $id_utilisateur = $facture['model']->getIdUtilisateur();

            //strings
            $strings = [
                'bill' => strtoupper(__('forms.labels.bill')),
                'phone' => __('forms.labels.phone'),
                'adress' => __('forms.labels.adress'),
                'correction' => __('forms.labels.correction'),
                'on' => __('forms.labels.on'),
                'quantity' => __('forms.labels.quantity'),
                'unit_price' => __('forms.labels.unit_price'),
                'amount' => __('forms.labels.amount'),
                'inflow' => __('forms.labels.inflow'),
                'outflow' => __('forms.labels.outflow'),
                'cashier' => ucfirst(__('forms.labels.cashier')),
                'client' => __('forms.labels.client'),
                'num' => __('forms.labels.num'),
                'label' => __('forms.labels.label'),
                'total' => __('forms.labels.total'),
                'cash' => __('forms.labels.cash')
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
            //caissier numero
            $num_cashier = strtolower($strings['cashier']);
            $html .= "<div class='header'>
                        <h4 class='center'>{$config['enterprise_name']}</h4>
                        <h3 class='center' style='color: green;'>{$strings['bill']} N° {$num_facture}</h3>
                        <div style='display: flex; margin-top: 20px; text-align: center;'>
                            <span>{$strings['cash']} {$strings['num']} : {$num_caisse}</span>  -
                            <span style='margin-left: 10px;'>{$num_cashier} ID : {$id_utilisateur}</span>
                        </div>
                       ";

            //table client and date
            $html .= "<table style='width: 100%; margin-top: 20px;'>
                        <tr>
                            <td style='text-align: left;'>
                                <p>{$sexe_client} , {$nom_client} {$prenoms_client}</p>
                                <p>{$strings['phone']} : {$telephone} </p>
                                <p>{$strings['adress']} : {$adresse} </p>
                                <p>ID : {$id_client} </p>
                            </td>
                            <td style='text-align: right; vertical-align: top;padding-top: 16px;'><b>{$strings['on']} : </b>{$date_facture}</td>
                        </tr>
                    </table>";

            //table
            $total = $connection_facture['montant_facture'];
            $html .= "<table class='table'>
                            <tr class='thead'>
                                <th>ID</th>
                                <th>{$strings['label']}</th>
                                <th>{$strings['quantity']}</th>
                                <th>{$strings['unit_price']} ({$config['currency_units']})</th>
                                <th>{$strings['amount']} ({$config['currency_units']})</th>
                            </tr>";
            $html .= "<tbody>";
            foreach ($connection_facture['lf'] as $line) {
                $quantite_produit_f = str_replace(
                    ["\u{00A0}", "\u{202F}"],
                    ' ',
                    $formatterNumber->format((int)$line['quantite_produit'])
                );
                $prix_f = str_replace(
                    ["\u{00A0}", "\u{202F}"],
                    ' ',
                    $formatterNumber->format((float)$line['prix'])
                );
                $prix_total_f = str_replace(
                    ["\u{00A0}", "\u{202F}"],
                    ' ',
                    $formatterNumber->format((float)$line['prix_total'])
                );
                $html .= "<tr>
                            <td>{$line['id_lf']}</td>
                            <td>{$line['libelle_produit']}</td>
                            <td>{$quantite_produit_f}</td>
                            <td>{$prix_f}</td>
                            <td>{$prix_total_f}</td>
                        </tr>";
            }
            //montant_facture
            $montant_facture_f = str_replace(
                ["\u{00A0}", "\u{202F}"],
                ' ',
                $formatterNumber->format((float)$connection_facture['montant_facture'])
            );
            $html .= "<tr>
                            <td style='border: none;'></td>
                            <td style='border: none;'></td>
                            <td colspan='2'>{$strings['total']}</td>
                            <td>{$montant_facture_f}</td>
                        </tr>";

            //correction ae
            if (count($connection_facture['autre_entree']) > 0) {
                $html .= "<tr>
                            <td style='text-align: center; border: none; padding: 20px; color: blue;' colspan='5'><b>{$strings['correction']}</b> ({$strings['inflow']})</td>
                        </tr>";
                foreach ($connection_facture['autre_entree'] as $line) {
                    $montant_ae_f = str_replace(
                        ["\u{00A0}", "\u{202F}"],
                        ' ',
                        $formatterNumber->format((float)$line['montant_ae'])
                    );
                    $html .= "<tr>
                            <td>{$line['num_ae']}</td>
                            <td>{$line['libelle_ae']}</td>
                            <td>1</td>
                            <td>{$montant_ae_f}</td>
                            <td>{$montant_ae_f}</td>
                        </tr>";
                    $total += $line['montant_ae'];
                }
            }

            //correction ds
            if (count($connection_facture['sortie']) > 0) {
                $html .= "<tr>
                            <td style='text-align: center; border: none; padding: 20px; color: tomato;' colspan='5'><b>{$strings['correction']}</b> ({$strings['outflow']})</td>
                        </tr>";
                foreach ($connection_facture['sortie'] as $line) {
                    $prix_article_f = str_replace(
                        ["\u{00A0}", "\u{202F}"],
                        ' ',
                        $formatterNumber->format((float)$line['prix_article'])
                    );
                    $html .= "<tr>
                            <td>{$line['num_ds']}</td>
                            <td>{$line['libelle_article']}</td>
                            <td>1</td>
                            <td>{$prix_article_f}</td>
                            <td>{$prix_article_f}</td>
                        </tr>";
                    $total -= $line['prix_article'];
                }
            }

            $html .= "</tbody>
                    </table>";

            //total
            $total_f = str_replace(
                ["\u{00A0}", "\u{202F}"],
                ' ',
                $formatterTotal->formatCurrency((float)$total, $config['currency_units'])
            );
            $html .= "<div><span style='float: left;'><b>{$strings['total']} :</b>  {$total_f}</span></div>";

            //sign
            $html .= "<div style='margin-top: 50px;'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='text-align: center'><u><b>{$strings['cashier']}</b></u></td>
                                <td style='text-align: center;'><u><b>{$strings['client']}</b></u></td>
                            </tr>
                        </table>
                    </div>";

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
                'file_name' => str_replace(' ', '_', strtolower($num_facture . '.pdf')),
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
                    'errors.catch.sortie_printDemandeSortie',
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

    //action - restore all facture
    public function restoreAllFacture()
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
            //redirect to entree index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_facture = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_facture']));

            //nums_facture - empty
            if (count($nums_facture) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_nums_facture_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //restore all facture
                $response = Facture::restoreAllFacture($nums_facture);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.entree_restoreAllFacture',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }
}
