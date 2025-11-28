<?php

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

        //list all caisse
        $response = AutreEntree::listAllAutreEntree();

        echo json_encode($response);
        return;
    }

    //action - update autre caisse
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
                $ae_num_caisse = $response['model']->getNumCaisse();

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

                //create autre entree
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
                //num_ae deleted - role caissier
                if ($is_loged_in->getRole() === 'caissier' && $response['model']->getEtatAe() === 'supprimé') {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.not_found.entree_num_ae', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }
                $ae_num_caisse = $response['model']->getNumCaisse();

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

                //create autre entree
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

    //action - list connection facture
}
