<?php

// class client controller
class UserController extends Controller
{
    private $user_model;
    public function __construct()
    {
        // initialize model
        $this->user_model = $this->loadModel('User');
    }

    //---------------------- PAGE ------------------------

    //page - add user
    public function pageAdd()
    {
        $this->render('create_user', array(
            'title' => "Création du compte",
            'description' => "Page de création du compte utilisateur"
        ));
    }
    //page - index
    public function index()
    {
        echo "page index";
    }
    //page - user dashboard
    public function pageUser()
    {
        $this->render('user_dashboard', ['title' => 'Gestion des utilisateurs']);
    }

    //---------------------ACTION------------------------

    //action - add user
    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Content-Type: application/json");
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;

            //trim datas
            $json = [
                'nom_utilisateur' => trim($json['nom_utilisateur']),
                'prenoms_utilisateur' => trim($json['prenoms_utilisateur']),
                'sexe_utilisateur' => trim($json['sexe_utilisateur']),
                'role' => trim($json['role']),
                'email_utilisateur' => trim($json['email_utilisateur']),
                'mdp' => $json['mdp'],
                'mdpConfirm' => $json['mdpConfirm']
            ];

            //nom_utilisateur empty
            if (empty($json['nom_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => 'Veuiller compléter le champ <b>Nom</b>'
                ];
            }
            //**nom_utilisateur
            else {
                $json['nom_utilisateur'] = strtoupper($json['nom_utilisateur']);

                //sexe_utilisateur error
                if (
                    $json['sexe_utilisateur'] !== 'masculin'
                    && $json['sexe_utilisateur'] !== 'féminin'
                ) {
                    $response = [
                        'message_type' => 'error',
                        'error_message' => "Error : sexe_utilisateur = {$json['sexe_utilisateur']}"
                    ];
                }
                //**sexe_utilisateur
                else {
                    //role error
                    if (
                        $json['role'] !== 'admin' &&
                        $json['role'] !== 'caissier'
                    ) {
                        $response = [
                            'message_type' => 'error',
                            'error_message' => "Error : role = {$json['role']}"
                        ];
                    }
                    //**role
                    else {
                        //email invalid
                        if (!filter_var(
                            $json['email_utilisateur'],
                            FILTER_VALIDATE_EMAIL
                        )) {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => "L'adresse <b>email</b> saisie n'est pas valide ."
                            ];
                        }
                        //**email_utilisateur
                        else {
                            //mdp small
                            if (strlen($json['mdp']) < 6) {
                                $response = [
                                    'message_type' => 'invalid',
                                    'message' => "Le mot de passe doit être au moins <b>6</b> caratctères ."
                                ];
                            }
                            //**mdp
                            else {
                                //mdp != mdpConfirm
                                if ($json['mdp'] !== $json['mdpConfirm']) {
                                    $response = [
                                        'message_type' => 'invalid',
                                        'message' => "Veuiller entrer le même mot de passe pour la confirmation"
                                    ];
                                }
                                //**ok
                                else {
                                    //add_user
                                    $response = $this->user_model->addUser(
                                        $json
                                    );
                                }
                            }
                        }
                    }
                }
            }

            echo json_encode($response);
        }
    }

    //action - filter user
    public function filterUser()
    {
        header('Content-Type: application/json');

        //get parameters
        $order_default = ['asc', 'desc'];
        $role_default = ['admin', 'caissier'];
        $sexe_default = ['masculin', "féminin"];
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
            'nom_utilisateur'

        ];
        $per_default = ['day', 'week', 'month', 'year'];
        //__search_user
        $search_user = $_GET['search_user'] ?? '';
        $search_user = trim($search_user);
        //__role
        $role = $_GET['role'] ?? 'all';
        $role = strtolower(trim($role));
        $role = in_array($role, $role_default, true) ? $role : 'all';
        //__sexe
        $sexe = $_GET['sexe'] ?? 'all';
        $sexe = strtolower(trim($sexe));
        $sexe = in_array($sexe, $sexe_default, true) ? $sexe : 'all';
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
            'sexe' => $sexe, //where
            'role' => $role, //where
            'search_user' => $search_user, //where
            'by' => $by, //order
            'order_by' => $order_by //order by
        ];
        echo json_encode($this->user_model->filterUser($params));
    }

    //action - update user
    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            header("Content-Type: application/json");
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;

            //trim datas
            $json = [
                'id_utilisateur' => trim($json['id_utilisateur']),
                'nom_utilisateur' => trim($json['nom_utilisateur']),
                'prenoms_utilisateur' => trim($json['prenoms_utilisateur']),
                'sexe_utilisateur' => trim($json['sexe_utilisateur']),
                'role' => trim($json['role']),
                'email_utilisateur' => trim($json['email_utilisateur']),
                'mdp' => $json['mdp'],
            ];

            //nom_utilisateur empty
            if (empty($json['nom_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => 'Veuiller compléter le champ <b>Nom</b>'
                ];
            }
            //**nom_utilisateur
            else {
                $json['nom_utilisateur'] = strtoupper($json['nom_utilisateur']);

                //sexe_utilisateur error
                if (
                    $json['sexe_utilisateur'] !== 'masculin'
                    && $json['sexe_utilisateur'] !== 'féminin'
                ) {
                    $response = [
                        'message_type' => 'error',
                        'error_message' => "Error : sexe_utilisateur = {$json['sexe_utilisateur']}"
                    ];
                }
                //**sexe_utilisateur
                else {
                    //role error
                    if (
                        $json['role'] !== 'admin' &&
                        $json['role'] !== 'caissier'
                    ) {
                        $response = [
                            'message_type' => 'error',
                            'error_message' => "Error : role = {$json['role']}"
                        ];
                    }
                    //**role
                    else {
                        //email invalid
                        if (!filter_var(
                            $json['email_utilisateur'],
                            FILTER_VALIDATE_EMAIL
                        )) {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => "L'adresse <b>email</b> saisie n'est pas valide ."
                            ];
                        }
                        //**email_utilisateur
                        else {
                            //mdp small
                            if (strlen($json['mdp']) < 6) {
                                $response = [
                                    'message_type' => 'invalid',
                                    'message' => "Le mot de passe doit être au moins <b>6</b> caratctères ."
                                ];
                            }
                            //**mdp
                            else {
                                $response = $this->user_model->updateUser($json);
                            }
                        }
                    }
                }
            }

            echo json_encode($response);
        }
    }

    //---------------------PRIVATE FUNCTION---------------------
}
