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
    //action - list user
    public function defaultList()
    {
        header('Content-Type: application/json');

        echo json_encode($this->user_model->defaultList());
    }
    // //action - nb user
    // public function nbUser()
    // {
    //     header('Content-Type: application/json');

    //     echo json_encode($this->user_model->nbUser());
    // }
    // //action - transactions user
    // public function transactionsUser()
    // {
    //     header('Content-Type: application/json');
    //     $id = trim($_GET['id']);

    //     echo json_encode($this->user_model->transactionsUser($id));
    // }

    //---------------------PRIVATE FUNCTION---------------------
}
