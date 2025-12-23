<?php

//CLASS - client controller
class ClientController extends Controller
{

    //============================  PAGE ==============================

    //page - index
    public function index()
    {
        echo "page index client";
    }

    //page - client dashboard
    public function pageClient()
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

            //admin
            $_SESSION['menu'] = 'client';
            $this->render('client_dashboard', [
                'title' => 'Faktiora - ' . __('forms.titles.client_dashboard'),
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

    //=========================== ACTIONS ================================

    //action - create client
    public function createClient()
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //nom_client - empty
            if ($json['nom_client'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }
            //nom_client - invalid
            if (strlen($json['nom_client']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.nom')
                ];

                echo json_encode($response);
                return;
            }

            //prenoms_client not empty - invalid
            if ($json['prenoms_client'] !== '' && strlen($json['prenoms_client']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.prenoms')
                ];

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            $json['sexe_client'] = strtolower($json['sexe_client']);
            if (!in_array($json['sexe_client'], ['masculin', 'féminin'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_client']])
                ];

                echo json_encode($response);
                return;
            }

            //telephone not empty - invalid
            if ($json['telephone'] !== '' && !preg_match("/^[+]?[0-9\s\(\)]{10,20}$/", $json['telephone'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.telephone', ['field' => $json['telephone']])
                ];

                echo json_encode($response);
                return;
            }

            //adresse not empty - invalid
            if ($json['adresse'] !== '' && strlen($json['adresse']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //create client
                $client_model = new Client();
                $client_model
                    ->setNomClient($json['nom_client'])
                    ->setPrenomsClient($json['prenoms_client'])
                    ->setSexeClient($json['sexe_client'])
                    ->setTelephone($json['telephone'])
                    ->setAdresse($json['adresse']);
                $response = $client_model->createClient();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.client_createClient',
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
        //redirect to client index
        else {
            header('Location: ' . SITE_URL . '/client');
            return;
        }
        echo json_encode($response);
        return;
    }

    //action - filter client
    public function filterClient()
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

        //defaults
        $status_default = ['active', 'deleted'];
        $sexe_default = ['masculin', 'féminin'];
        $arrange_by_default = [
            'total_facture',
            'nb_facture',
            'name',
            'id',
        ];
        $order_default = ['DESC', 'ASC'];
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
        $status = !in_array($status, $status_default) ? 'all' : $status;

        //sexe
        $sexe = strtolower(trim($_GET['sexe'] ?? 'all'));
        $sexe = in_array($sexe, $sexe_default, true) ? $sexe : 'all';

        //arrange_by
        $arrange_by = strtolower(trim($_GET['arrange_by'] ?? 'id'));
        $arrange_by = in_array($arrange_by, $arrange_by_default, true) ? $arrange_by : 'id';
        $arrange_by = $arrange_by === 'id' ? 'cl.id_client' : $arrange_by;
        $arrange_by = $arrange_by === 'name' ? 'fullname' : $arrange_by;
        //order
        $order = strtoupper(trim($_GET['order'] ?? 'ASC'));
        $order = in_array($order, $order_default, true) ? $order : 'ASC';

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

        //sarch_client
        $search_client = trim($_GET['search_client'] ?? '');

        $params = [
            'status' => $status,
            'sexe' => $sexe,
            'arrange_by' => $arrange_by,
            'order' => $order,
            'date_by' => $date_by,
            'per' => $per,
            'from' => $from,
            'to' => $to,
            'month' => $month,
            'year' => $year,
            'search_client' => $search_client
        ];

        //filter client
        $response = ClientRepositorie::filterClient($params);

        // echo json_encode($params);
        echo json_encode($response);
        return;
    }

    //action - list all client
    public function listAllClient()
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

        //list all client
        $response = Client::listAllClient();

        echo json_encode($response);
        return;
    }

    //action - update client
    public function updateClient()
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

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //nom_client - empty
            if ($json['nom_client'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }
            //nom_client - invalid
            if (strlen($json['nom_client']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.nom')
                ];

                echo json_encode($response);
                return;
            }

            //prenoms_client not empty - invalid
            if ($json['prenoms_client'] !== '' && strlen($json['prenoms_client']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.prenoms')
                ];

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            $json['sexe_client'] = strtolower($json['sexe_client']);
            if (!in_array($json['sexe_client'], ['masculin', 'féminin'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_client']])
                ];

                echo json_encode($response);
                return;
            }

            //telephone not empty - invalid
            if ($json['telephone'] !== '' && !preg_match("/^[+]?[0-9\s\(\)]{10,20}$/", $json['telephone'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.telephone', ['field' => $json['telephone']])
                ];

                echo json_encode($response);
                return;
            }

            //adresse not empty - invalid
            if ($json['adresse'] !== '' && strlen($json['adresse']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }

            try {

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

                //update client
                $client_model = new Client();
                $client_model
                    ->setIdClient($json['id_client'])
                    ->setNomClient($json['nom_client'])
                    ->setPrenomsClient($json['prenoms_client'])
                    ->setSexeClient($json['sexe_client'])
                    ->setTelephone($json['telephone'])
                    ->setAdresse($json['adresse']);
                $response = $client_model->updateClient();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.client_updateClient',
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
        //redirect to client index
        else {
            header('Location: ' . SITE_URL . '/client');
            return;
        }
        echo json_encode($response);
        return;
    }

    //action - delete all client
    public function deleteAllClient()
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
            //redirect to client index
            header("Location: " . SITE_URL . '/client');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $ids_client = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['ids_client']));

            //ids_client - empty
            if (count($ids_client) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.client_ids_client_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all client
                $response = Client::deleteAllClient($ids_client);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.client_deleteAllClient',
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
        //redirect to client index
        else {
            header('Location: ' . SITE_URL . '/client');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all client
    public function permanentDeleteAllClient()
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
            //redirect to client index
            header("Location: " . SITE_URL . '/client');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            $ids_client = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['ids_client']));

            //ids_client - empty
            if (count($ids_client) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.client_ids_client_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //permanent delete all client
                $response = Client::permanentDeleteAllClient($ids_client);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.client_deleteAllClient',
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
        //redirect to client index
        else {
            header('Location: ' . SITE_URL . '/client');
            return;
        }

        echo json_encode($response);
        return;
    }
}
