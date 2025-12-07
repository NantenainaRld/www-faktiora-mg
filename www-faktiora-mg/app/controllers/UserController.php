<?php

//dompdf
require_once LIBS_PATH . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// class client controller
class UserController extends Controller
{

    //========================= PAGES =============================

    //page - index
    public function index()
    {
        $this->render('header', ['title' => 'Header']);
        return;
    }
    //page - user dashboard
    public function pageUser()
    {
        $this->render('user_dashboard', ['title' => __('forms.titles.user_dashboard')]);
    }

    //page - home
    public function pageHome()
    {
        //is loged in ?
        $is_loged_in = Auth::isLogedIn();
        //loged
        if ($is_loged_in->getLoged()) {
            $_SESSION['menu'] = 'home';
            $this->render('home', [
                'title' => 'Faktiora - ' . __('forms.labels.home'),
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

    //========================== ACIONS ==========================

    //action - create default admin account
    public static function createDefaultAdmin()
    {
        //create default admin
        $response = User::createDefaultAdmin();

        //error
        if ($response['message_type'] === 'error') {
            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . $response['message']);

            return;
        }

        return;
    }

    //action - create user by admin
    public function createUser()
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

        //role - !admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            foreach ($json as $key => &$value) {
                if ($key !== 'mdp' && $key !== 'mdp_confirm') {
                    $value = trim($value);
                }
                if ($key === 'sexe_utilisateur' || $key === 'role') {
                    $value = strtolower($value);
                }
            }

            //nom_utilisateur - empty
            if (empty($json['nom_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }
            //nom_utilisateur - invalid
            if (strlen($json['nom_utilisateur']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.nom')
                ];

                echo json_encode($response);
                return;
            }

            //prenoms_utilisateur not empty - invalid
            if ($json['prenoms_utilisateur'] !== '' && strlen($json['prenoms_utilisateur']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.prenoms')
                ];

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            if (!in_array($json['sexe_utilisateur'], ['masculin', 'féminin'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }

            //email_utilisateur - empty
            if (empty($json['email_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.email')
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - invalid
            if (filter_var($json['email_utilisateur'], FILTER_VALIDATE_EMAIL) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __(
                        'messages.invalids.email',
                        ['field' => $json['email_utilisateur']]
                    )
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - length > 150
            if (strlen($json['email_utilisateur']) > 150) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email_length')
                ];

                echo json_encode($response);
                return;
            }

            //role - invalid
            if (!in_array($json['role'], ['admin', 'caissier'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __(
                        'messages.invalids.role',
                        ['field' => $json['role']]
                    )
                ];

                echo json_encode($response);
                return;
            }

            //mdp - empty
            if (empty($json['mdp'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.mdp')
                ];

                echo json_encode($response);
                return;
            }
            //mdp - invalid
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
            //mdp_confirm - invalid
            if ($json['mdp_confirm'] !== $json['mdp']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp_confirm')
                ];

                echo json_encode($response);
                return;
            }

            try {

                $user_model = new User();

                //create_user
                $user_model
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
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_createUser',
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
        //redirect to user index
        else {
            header('Location: ' . SITE_URL . '/user');
            return;
        }
        echo json_encode($response);
        return;
    }

    //action - filter user
    public function filterUser()
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
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

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
            'name',
            'id'
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
        $order_by = ($order_by === 'id') ? 'u.id_utilisateur' : $order_by;
        // //arrange
        $arrange = strtoupper(trim($_GET['arrange'] ?? 'ASC'));
        $arrange = in_array($arrange, $arrange_default, true) ? $arrange : 'ASC';

        //num_caisse
        $num_caisse = trim($_GET['num_caisse'] ?? 'all');
        $num_caisse = ($num_caisse !== 'all') ? filter_var($num_caisse, FILTER_VALIDATE_INT) : $num_caisse;
        //num_caisse - invalid
        if ($num_caisse === false || $num_caisse < 0) {
            $num_caisse = 'all';
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

        //filter user
        $response = UserRepositorie::filterUser($params);

        echo json_encode($response);
        return;
    }

    //action - list caissier
    public function listCaissier()
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
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        //list caissier
        $response = User::listCaissier();

        echo json_encode($response);
        return;
    }

    //action - account info
    public function accountInfo()
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
        //user exist?
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
                'message' => __(
                    'messages.not_found.user_id',
                    ['field' => $is_loged_in->getIdUtilisateur()]
                )
            ];

            echo json_encode($response);
            return;
        }
        // role caissier 
        $num_caisse = '';
        if ($is_loged_in->getRole() === 'caissier') {
            // user has caisse ?
            $findUserCaisse = LigneCaisse::findCaisse($is_loged_in->getRole());
            //error
            if ($findUserCaisse['message_type'] === 'error') {
                echo json_encode($findUserCaisse);
                return;
            }
            // found 
            if ($findUserCaisse['found']) {
                $num_caisse = $findUserCaisse['model']->getNumCaisse();
            }
        }
        //account info
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'data' => [
                'id_utilisateur' => $response['model']->getIdUtilisateur(),
                'nom_utilisateur' => $response['model']->getNomUtilisateur(),
                'prenoms_utilisateur' => $response['model']->getPrenomsUtilisateur(),
                'sexe_utilisateur' => $response['model']->getSexeUtilisateur(),
                'email_utilisateur' => $response['model']->getEmailUtilisateur(),
                'role_t' => ucfirst(($response['model']->getRole() === 'admin') ? __('forms.labels.admin') : __('forms.labels.cashier')),
                'role' => $response['model']->getRole(),
                'num_caisse' => $num_caisse
            ]
        ];

        echo json_encode($response);
        return;
    }

    //action - update user by admin
    public function updateByAdmin()
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

        //role - !admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            foreach ($json as $key => &$value) {
                if ($key !== 'mdp') {
                    $value = trim($value);
                }
                if ($key === 'sexe_utilisateur' || $key === 'role') {
                    $value = strtolower($value);
                }
            }

            //nom_utilisateur - empty
            if ($json['nom_utilisateur'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }
            //nom_utilisateur - invalid
            if (strlen($json['nom_utilisateur']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.nom')
                ];

                echo json_encode($response);
                return;
            }

            //prenoms_utilisateur - invalid
            if ($json['prenoms_utilisateur'] !== '' && strlen($json['prenoms_utilisateur']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.prenoms')
                ];

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            if (!in_array($json['sexe_utilisateur'], ['masculin', 'féminin'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }

            //email_utilisateur - empty
            if ($json['email_utilisateur'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.email')
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - invalid
            if (filter_var($json['email_utilisateur'], FILTER_VALIDATE_EMAIL) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email', ['field' => $json['email_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - length > 150
            if (strlen($json['email_utilisateur']) > 150) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email_length')
                ];

                echo json_encode($response);
                return;
            }

            //role - invalid
            if (!in_array($json['role'], ['admin', 'caissier'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.role', ['field' => $json['role']])
                ];

                echo json_encode($response);
                return;
            }

            //mdp - invalid
            if ($json['mdp'] !== '' && strlen($json['mdp']) < 6) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp')
                ];

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
                    $response  = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.user_id', ['field' => $json['id_utilisateur']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update user
                ($response['model'])->setIdUtilsateur($json['id_utilisateur'])
                    ->setNomUtilisateur($json['nom_utilisateur'])
                    ->setPrenomsUtilisateur($json['prenoms_utilisateur'])
                    ->setSexeUtilisateur($json['sexe_utilisateur'])
                    ->setEmailUtilisateur($json['email_utilisateur'])
                    ->setRole($json['role'])
                    ->setMdp($json['mdp']);
                $response = $response['model']->updateByAdmin();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_update',
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
        //redirect to user index
        else {
            header('Location: ' . SITE_URL . '/user');
            return;
        }
    }

    //action - update account
    public function updateAccount()
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
            foreach ($json as $key => &$value) {
                if ($key !== 'mdp') {
                    $value = trim($value);
                }
                if ($key === 'sexe_utilisateur' || $key === 'role') {
                    $value = strtolower($value);
                }
            }

            //nom_utilisateur - empty
            if ($json['nom_utilisateur'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }
            //nom_utilisateur - invalid
            if (strlen($json['nom_utilisateur']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.nom')
                ];

                echo json_encode($response);
                return;
            }

            //prenoms_utilisateur - invalid
            if ($json['prenoms_utilisateur'] !== '' && strlen($json['prenoms_utilisateur']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.prenoms')
                ];

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            if (!in_array($json['sexe_utilisateur'], ['masculin', 'féminin'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }

            //email_utilisateur - empty
            if ($json['email_utilisateur'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.email')
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - invalid
            if (filter_var($json['email_utilisateur'], FILTER_VALIDATE_EMAIL) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email', ['field' => $json['email_utilisateur']])
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - length > 150
            if (strlen($json['email_utilisateur']) > 150) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email_length')
                ];

                echo json_encode($response);
                return;
            }

            //mdp - invalid
            if ($json['mdp'] !== '' && strlen($json['mdp']) < 6) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp')
                ];

                echo json_encode($response);
                return;
            }

            try {

                $user_model = new User();
                //update user
                $user_model->setIdUtilsateur($is_loged_in->getIdUtilisateur())
                    ->setNomUtilisateur($json['nom_utilisateur'])
                    ->setPrenomsUtilisateur($json['prenoms_utilisateur'])
                    ->setSexeUtilisateur($json['sexe_utilisateur'])
                    ->setEmailUtilisateur($json['email_utilisateur'])
                    ->setMdp($json['mdp']);
                $response = $user_model->updateAccount();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_update',
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
        //redirect to user index
        else {
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete account
    public function deleteAccount()
    {
        $response = null;

        //loged ?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header('Location: ' . SITE_URL . '/auth');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            try {

                //delete account
                $user_model = new User();
                $user_model->setIdUtilsateur($is_loged_in->getIdUtilisateur());
                $response = $user_model->deleteAccount();
                //error
                if ($response['message_type'] === 'error') {
                    //redirect to error page
                    header('Location: ' . SITE_URL . '/error?messages=' . $response['message']);
                    return;
                }
                //success
                if ($response['message_type'] === 'success') {
                    session_destroy();
                    //redirect to login page
                    header('Location: ' . SITE_URL . '/auth');
                    return;
                }
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_deleteAccount',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                //redirect to error page
                header('Location: ' . SITE_URL . '/error?messages=' . $response['message']);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to user index
        else {
            header('Location: ' . SITE_URL . '/user');
            return;
        }
    }

    //action - delete all user
    public function deleteAll()
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
        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $ids_user = array_map(fn($x) => trim($x), $json['ids_user']);

            //ids_user - empty
            if (count($ids_user) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.user_ids_user_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all
                $response = (User::deleteAll(
                    $ids_user,
                    $is_loged_in->getIdUtilisateur()
                ));

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_deleteAll',
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
        //redirect to user index
        else {
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all user
    public function permanentDeleteAll()
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
        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $ids_user = array_map(fn($x) => trim($x), $json['ids_user']);

            //ids_user - empty
            if (count($ids_user) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.user_ids_user_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //permanent delete all
                $response = (User::permanentDeleteAll(
                    $ids_user,
                    $is_loged_in->getIdUtilisateur()
                ));

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_deleteAll',
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
        //redirect to user index
        else {
            header('Location: ' . SITE_URL . '/user');
            return;
        }
    }

    //action - deconnect all user
    public function deconnectAll()
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
        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            //trim
            $ids_user = array_map(fn($x) => trim($x), $json['ids_user']);

            //id_users - empty
            if (count($ids_user) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.user_ids_user_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //deconnect all user
                $response = User::deconnectAll(
                    $ids_user,
                    $is_loged_in->getIdUtilisateur()
                );

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_deconnectAll',
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

    //action - print all user
    public function printAllUser()
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
        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to user index
            header('Location: ' . SITE_URL . '/user');
            return;
        }

        //config.json
        $config = json_decode(file_get_contents(PUBLIC_PATH . '/config/config.json'), true);

        //status
        $status = strtolower(trim($_GET['status'] ?? 'active'));
        $status = ($status === 'deleted') ? $status : 'active';

        try {

            //strings
            $date = new DateTime();
            $list_date = __('forms.titles.list_date', ['field' => $date->format(__('forms.times.format_datetime'))]);
            $list_all_user = __('forms.titles.list_all_user');
            $name_firstname = __('forms.labels.name_firstname');
            $sex = __('forms.labels.sex');
            $email = __('forms.labels.email');
            $role = __('forms.labels.role');
            $strings = [
                'caissier' => __('forms.labels.cashier'),
                'admin' => __('forms.labels.admin'),
                'total' => __('forms.labels.total'),
                'status' => __('forms.labels.status'),
            ];

            //get all user
            $response = User::listAllUser($status);
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }

            //status
            $status = ($status === 'deleted') ? __('forms.labels.deleted') : __('forms.labels.active');

            //header
            $html = "<!DOCTYPE html> 
                    <body>
                    <head>
                        <style>
                            body {
                                font-size: 11pt;
                            }
                            .header {
                                text-align: center;
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
                                padding: 5px;
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
            $html .= "<div class='header'>
                        <h4>{$config['enterprise_name']}</h4>
                        <p id='list-date'>{$list_date}</p><br>
                        <h3><u>{$list_all_user}</u></h3>
                        <p>{$strings['total']} : {$response['nb_user']}, {$strings['caissier']} : {$response['nb_caissier']}, {$strings['admin']} : {$response['nb_admin']}</p>
                        <p>{$strings['status']} : {$status}</p>
                    </div>";

            //table
            $html .= "<table class='table'>
                            <tr class='thead'>
                                <th>ID</th>
                                <th>{$name_firstname}</th>
                                <th>{$sex}</th>
                                <th>{$email}</th>
                                <th>{$role}</th>
                            </tr>";
            $html .= "<tbody>";
            foreach ($response['data'] as $index => $line) {
                $html .= "<tr>
                            <td>{$line['id_utilisateur']}</td>
                            <td>{$line['nom_prenoms']}</td>
                            <td>{$line['sexe_utilisateur']}</td>
                            <td>{$line['email_utilisateur']}</td>
                            <td>{$line['role']}</td>
                        </tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";

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
                'file_name' => str_replace(' ', '_', strtolower($list_all_user . '.pdf')),
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
                    'errors.catch.user_printAllUser',
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
