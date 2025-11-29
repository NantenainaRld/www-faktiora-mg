<?php

//CLASS - client controller
class ClientController extends Controller
{

    //============================  PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index client";
    }

    //page - client dashboard
    public function pageClient()
    {
        $this->render('client_dashboard', ['title' => "Gestion des clients"]);
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
        $sexe_default = ['masculin', 'féminin'];
        $order_by_default = [
            'nb_factures',
            'name',
            'id',
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

        // //status
        $status = strtolower(trim($_GET['status'] ?? 'active'));
        $status = ($status === 'deleted') ? $status : 'active';
        $status = ($is_loged_in->getRole() === 'caissier') ? 'active' : $status;
        switch ($status) {
            case 'active':
                $status = 'actif';
                break;
            case 'deleted':
                $status = 'supprimé';
                break;
        }

        //sexe
        $sexe = strtolower(trim($_GET['sexe'] ?? 'all'));
        $sexe = in_array($sexe, $sexe_default, true) ? $sexe : 'all';

        //order_by
        $order_by = strtolower(trim($_GET['order_by'] ?? 'name'));
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'name';
        $order_by = ($order_by === 'name') ? 'cl.nom_client' : $order_by;
        $order_by = ($order_by === 'id') ? 'cl.id_client' : $order_by;
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
            'order_by' => $order_by,
            'arrange' => $arrange,
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
}
