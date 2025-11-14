<?php

// class client controller
class UserController extends Controller
{
    // private $user_model;
    // public function __construct()
    // {
    //     // initialize model
    //     $this->user_model = $this->loadModel('User');
    // }

    //---------------------- PAGE ------------------------

    //page - index
    public function index()
    {
        echo "page index";
    }
    //page - user dashboard
    public function pageUser()
    {
        // $_SESSION['auth'] = ['id_utilisateur' => "U556488QI"];

        $this->render('user_dashboard', ['title' => __('forms.titles.user_dashboard')]);
    }

    //========================== ACIONS ==========================

    //create default admin account
    public static function createDefaultAdmin()
    {
        //create deffault admin
        $user_model = new User();
        $response = $user_model->createDefaultAdmin();

        //error
        if ($response['message_type'] === 'error') {
            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . $response['message']);
        }

        return;
    }

    //action - create user
    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Content-Type: application/json");
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;

            //trim
            foreach ($json as $key => &$value) {
                if ($key !== 'mdp' && $key !== 'mdp_confirm') {
                    $value = trim($value);
                }
            }

            //nom_utilisateur  - empty
            if (empty($json['nom_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];
                echo json_encode($response);
                return;
            }
            //nom_utilisateur - to uppercase
            $json['nom_utilisateur'] = strtoupper($json['nom_utilisateur']);

            //sexe_utilisateur - invalid
            if (!in_array($json['sexe_utilisateur'], ['masculin', 'féminin'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_utilisateur']])
                ];
                echo json_encode($response);
                return;
            }

            //email - empty
            if (empty($json['email_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.email')
                ];
                echo json_encode($response);
                return;
            }
            //email - invalid
            if (!filter_var($json['email_utilisateur'], FILTER_VALIDATE_EMAIL)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email', ['field' => $json['email_utilisateur']])
                ];
                echo json_encode($response);
                return;
            }

            //role - admin
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                //role - invalid
                if (!in_array($json['role'], ['admin', 'caissier'], true)) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.role', ['field' => $json['role']])
                    ];
                    echo json_encode($response);
                    return;
                }
            }
            //role - signup
            else {
                $json['role'] = 'caissier';
            }

            // mdp - empty
            if (empty($json['mdp'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.mdp')
                ];
                echo json_encode($response);
                return;
            }
            //mdp - < 6
            if (strlen($json['mdp']) < 6) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp')
                ];
                echo json_encode($response);
                return;
            }
            //mdp_confirm - empty
            if (empty($json['mdp_confirm'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.mdp_confirm')
                ];
                echo json_encode($response);
                return;
            }
            //mdp_confirm != mdp
            if ($json['mdp_confirm'] !== $json['mdp']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp_confirm')
                ];
                echo json_encode($response);
                return;
            }

            try {
                //create user
                $user_model = (new User())
                    ->setNomUtilisateur($json['nom_utilisateur'])
                    ->setPrenomsUtilisateur($json['prenoms_utilisateur'])
                    ->setSexeUtilisateur($json['sexe_utilisateur'])
                    ->setEmailUtilisateur($json['email_utilisateur'])
                    ->setRole($json['role'])
                    ->setMdp($json['mdp']);
                $response = $user_model->createUser();
                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.catch.create_user', ['field' => $e->getMessage()])
                ];
                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
    }

    //action - filter user
    public function filterUser()
    {
        header('Content-Type: application/json');
        $response = null;

        //defaults
        $status_default = ['connected', 'disconnected', 'deleted'];
        $role_default = ['admin', 'caissier'];
        $sexe_default = ['masculin', 'féminin'];
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
            'name'
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

        //role
        $role = strtolower(trim($_GET['role'] ?? 'all'));
        $role = in_array($role, $role_default, true) ? $role : 'all';

        //sexe
        $sexe = strtolower(trim($_GET['sexe'] ?? 'all'));
        $sexe = in_array($sexe, $sexe_default, true) ? $sexe : 'all';

        //order_by
        $order_by = strtolower(trim($_GET['order_by'] ?? 'name'));
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'name';
        $order_by = ($order_by === 'name') ? 'u.nom_utilisateur' : $order_by;
        //arrange
        $arrange = strtoupper(trim($_GET['arrange'] ?? 'ASC'));
        $arrange = in_array($arrange, $arrange_default, true) ? $arrange : 'ASC';

        //num_caisse
        $num_caisse = trim($_GET['num_caisse'] ?? 'all');
        $num_caisse = ($num_caisse !== 'all') ? filter_var($num_caisse, FILTER_VALIDATE_INT) : $num_caisse;
        //num_caisse - string
        if ($num_caisse === false) {
            $num_caisse = 'all';
        }
        //num_caisse - int
        else {
            if ($num_caisse < 0) {
                $response = ['message_type' => 'invalid', __('messages.invalids.num_caisse', ['field' => $num_caisse])];

                echo json_encode($response);
                return;
            }
        }

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

        //sarch_user
        $search_user = trim($_GET['search_user'] ?? '');

        $params = [
            'status' => $status,
            'role' => $role,
            'sexe' => $sexe,
            'order_by' => $order_by,
            'arrange' => $arrange,
            'num_caisse' => $num_caisse,
            'date_by' => $date_by,
            'per' => $per,
            'from' => $from,
            'to' => $to,
            'month' => $month,
            'year' => $year,
            'search_user' => $search_user
        ];

        //num_caisse exist?
        if ($num_caisse !== 'all') {
            $response = Caisse::findById($num_caisse);
            //error
            if ($response['message_type'] === 'error') {

                echo json_encode($response);
                return;
            }
            //num_caisse - not found
            if ($response['message'] === 'not found') {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.not_found.num_caisse', ['field' => $num_caisse]);

                echo json_encode($response);
                return;
            }
        }

        //filter user
        $response = UserRepositorie::filterUser($params);

        // echo json_encode($search_user);

        echo json_encode($response);
        return;
    }

    //action - update user by admin
    public function updateByAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            header('Content-Type: application/json');
            $response = null;
            $json = json_decode(file_get_contents('php://input'), true);

            //loged ?
            $is_loged_in = Auth::isLogedIn();

            //not loged
            if (!$is_loged_in->getLoged()) {
                //redirect to login page
                header('Location: ' . SITE_URL . '/auth');
                return;
            }

            //role - !admin
            if ($is_loged_in->getRole() !== 'admin') {
                //redirect to index
                header('Location: ' . SITE_URL . '/user');
                return;
            }

            //trim
            foreach ($json as $key => &$value) {
                if ($key !== 'mdp') {
                    $value = trim($value);
                }
                if ($key === 'sexe_utilisateur' || $key === 'role') {
                    $value = strtolower($value);
                }
            }

            //id_update - empty
            if (empty($json['id_update'])) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.empty.user_id');

                echo json_encode($response);
                return;
            }
            //id_utilisateur - invalid
            if (strlen($json['id_update']) > 15) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.user_id');

                echo json_encode($response);
                return;
            }

            //nom_utilisateur - empty
            if (empty($json['nom_utilisateur'])) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.empty.nom');

                echo json_encode($response);
                return;
            }
            //nom_utilisateur - invalid
            if (strlen($json['nom_utilisateur']) > 100) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.nom');

                echo json_encode($response);
                return;
            }

            //prenoms_utilisateur - invalid
            if (!empty($json['prenoms_utilisateur']) && strlen($json['prenoms_utilisateur']) > 100) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.prenoms');

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            if (!in_array($json['sexe_utilisateur'], ['masculin', 'féminin'], true)) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.sexe', ['field' => $json['sexe_utilisateur']]);

                echo json_encode($response);
                return;
            }

            //email_utilisateur - empty
            if (empty($json['email_utilisateur'])) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.empty.email');

                echo json_encode($response);
                return;
            }
            //email_utilisateur - invalid
            if (filter_var($json['email_utilisateur'], FILTER_VALIDATE_EMAIL) === false) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.email', ['field' => $json['email_utilisateur']]);

                echo json_encode($response);
                return;
            }
            //email_utilisateur - length > 150
            if (strlen($json['email_utilisateur']) > 150) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.email_length');

                echo json_encode($response);
                return;
            }


            //role - invalid
            if (!in_array($json['role'], ['admin', 'caissier'], true)) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.role', ['field' => $json['role']]);

                echo json_encode($response);
                return;
            }

            //mdp - invalid
            if (!empty($json['mdp']) && strlen($json['mdp']) < 6) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.mdp');

                echo json_encode($response);
                return;
            }

            try {
                //find user
                $response = User::findById($json['id_utilisateur']);

                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }

                //not found
                if (!$response['found']) {

                    $response['message_type'] = 'invalid';
                    $response['message'] = __('messages.not_found.user_id', ['field' => $json['id_utilisateur']]);

                    echo json_encode($response);
                    return;
                }

                //update user
                ($response['model'])->setIdUtilsateur($json['id_utilisateur'])
                    ->setIdUpdate($json['id_update'])
                    ->setNomUtilisateur($json['nom_utilisateur'])
                    ->setPrenomsUtilisateur($json['prenoms_utilisateur'])
                    ->setSexeUtilisateur($json['sexe_utilisateur'])
                    ->setEmailUtilisateur($json['email_utilisateur'])
                    ->setRole($json['role'])
                    ->setMdp($json['mdp']);
                $response = $response['model']->updateUser();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage());

                $response['message_type'] = 'error';
                $response['message'] = __('errors.catch.user_update', ['field' => $e->getMessage()]);

                echo json_encode($response);
                return;
            }

            // echo json_encode($json);
            echo json_encode($response);
            return;
        }
    }

    // //action - delete user
    // public function deleteUser()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    //         header('Content-Type: application/json');
    //         $json = json_decode(file_get_contents("php://input"), true);
    //         $json['id_utilisateur'] = trim($json['id_utilisateur']);

    //         echo json_encode($this->user_model->deleteUser($json));
    //     }
    // }

    //---------------------PRIVATE FUNCTION---------------------
}
