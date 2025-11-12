<?php

class User extends Database
{
    private $id_utilisateur = "";
    private $nom_utilisateur = "";
    private $prenoms_utilisateur = "";
    private $sexe_utilisateur = "masculin";
    private $email_utilisateur = "";
    private $role = "caissier";
    private $mdp = "";
    private $mdp_oublie = null;
    private $etat_utilisateur = 'déconnecté';
    private $dernier_session =  null;

    public function __construct()
    {
        parent::__construct();
    }

    //================== GETTERS ============================

    //================== SETTERS ============================

    //setter - id_utilisateur
    // public function setNomUtilsateur($id_utilisateur)
    // {
    //     return $this->nom_utilisateur = $nom_utilisateur;
    // }
    //setter - nom_utilisateur
    public function setNomUtilisateur($nom_utilisateur)
    {
        $this->nom_utilisateur = $nom_utilisateur;
        return $this;
    }
    //setter - prenoms_utilisateur
    public function setPrenomsUtilisateur($prenoms_utilisateur)
    {
        $this->prenoms_utilisateur = $prenoms_utilisateur;
        return $this;
    }
    //setter - sexe_utilisateur
    public function setSexeUtilisateur($sexe_utilisateur)
    {
        $this->sexe_utilisateur = $sexe_utilisateur;
        return $this;
    }
    //setter - email_utilisateur
    public function setEmailUtilisateur($email_utilisateur)
    {
        $this->email_utilisateur = $email_utilisateur;
        return $this;
    }
    //setter - role
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
    //setter - mdp
    public function setMdp($mdp)
    {
        $this->mdp = password_hash($mdp, PASSWORD_DEFAULT);
        return $this;
    }
    //setter - mdp_oublie
    // public function setMdpOublie($mdp_oublie)
    // {
    //     return $this->nom_utilisateur = $nom_utilisateur;
    // }
    // //setter - nom_utilisateur
    // public function setNomUtilsateur($nom_utilisateur)
    // {
    //     return $this->nom_utilisateur = $nom_utilisateur;
    // }

    //==================  PUBLIC FUNCTION ====================

    //create default admin account
    public function createDefaultAdmin()
    {
        $response = [
            'message_type' => "success",
            'message' => 'success'
        ];

        try {
            //admin exist?
            $response = self::isAdminExist();

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //not found
                if ($response['message'] === 'not found') {
                    //create default admin
                    return self::executeQuery("INSERT INTO utilisateur (id_utilisateur, nom_utilisateur, sexe_utilisateur, email_utilisateur, role, mdp) VALUES (:id, :nom, :sexe, :email, :role, :mdp)", [
                        'id' => "000000",
                        'nom' => 'admin',
                        'email' => 'admin@faktiora.mg',
                        'sexe' => 'masculin',
                        'role' => 'admin',
                        'mdp' => password_hash('admin', PASSWORD_DEFAULT)
                    ]);
                }

                return $response;
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.create_default_admin', ['field' => $e->getMessage()]);
        }

        return $response;
    }

    //add user
    public function createUser()
    {
        //response
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        try {
            //generate id_utilisateur
            $response = $this->generateIdUser();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //email exist ?
            $response = $this->isEmailExist();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['message'] === 'found') {
                $response['message_type'] = 'invalid';
                $response['data'] = __('messages.invalids.email_exist', ['field' => $this->email_utilisateur]);

                return $response;
            }

            //create user
            $response = self::executeQuery("INSERT INTO utilisateur (id_utilisateur, nom_utilisateur, prenoms_utilisateur, sexe_utilisateur, email_utilisateur, role, mdp) VALUES (:id, :nom, :prenoms, :sexe, :email, :role, :mdp)", [
                'id' => $this->id_utilisateur,
                'nom' => $this->nom_utilisateur,
                'prenoms' => $this->prenoms_utilisateur,
                'sexe' => $this->sexe_utilisateur,
                'email' => $this->email_utilisateur,
                'role' => $this->role,
                'mdp' => $this->mdp
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            $response['message'] = __('messages.success.create_user');

            return $response;
        } catch (Throwable $e) {
            return $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.create_user', ['field' => $e->getMessage()])
            ];
        }

        return $response;
    }

    //login user
    public function loginUser($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        try {
            //get password
            $response = $this->selectQuery(
                "SELECT mdp, id_utilisateur FROM utilisateur
         WHERE email_utilisateur = :email OR id_utilisateur = :id",
                [
                    'email' => $json['login'],
                    'id' => $json['login']
                ]
            );

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //account not exist
                if (count($response['data']) <= 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => "Compte avec l'identifiant/email <b>{$json['login']}</b> n'existe pas ."
                    ];
                }
                //account existé
                else {
                    //password incorrect
                    if (!password_verify(
                        $json['mdp'],
                        $response['data'][0]['mdp']
                    )) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => 'Adresse email/identifiant ou mot de passe incorrect .'
                        ];
                    }
                    //password correct
                    else {
                        //session
                        $_SESSION['id_utilisateur'] =
                            $response['data'][0]['id_utilisateur'];
                        $response['message'] = "Loged in";
                    }
                }
            }
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => 'Error : ' . $e->getMessage()
            ];
        }

        return $response;
    }

    //update user
    public function updateUser($json)
    {
        //response
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        try {
            //user exist?
            $response = $this->isUserExist($json['id_utilisateur']);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //not found
                if ($response['message'] === 'not found') {
                    $response = ['message_type' => 'inalid', 'message' => "L'tilisateur avec l'ID <b>{$json['id_utilisateur']}</b> n'existe pas"];
                }
                //found
                else {
                    //email exist ?
                    $response = $this->updateUserIsEmailExist($json['email_utilisateur'], $json['id_utilisateur']);

                    //error
                    if ($response['message_type'] === 'error') {
                        return $response;
                    }
                    //success
                    else {
                        //found
                        if ($response['message'] === 'found') {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => 'Cette adresse <b>email</b> est déjà utilisée .'
                            ];
                        }
                        //not found
                        else {
                            //mdp - empty
                            if (empty($json['mdp'])) {
                                //update user
                                $response = $this->executeQuery(
                                    "UPDATE utilisateur SET nom_utilisateur = :nom, prenoms_utilisateur = :prenoms, sexe_utilisateur = :sexe, role = :role, email_utilisateur = :email WHERE id_utilisateur = :id",
                                    [
                                        'nom' => $json['nom_utilisateur'],
                                        'prenoms' => $json['prenoms_utilisateur'],
                                        'sexe' => $json['sexe_utilisateur'],
                                        'role' => $json['role'],
                                        'email' => $json['email_utilisateur'],
                                        'id' => $json['id_utilisateur']
                                    ]
                                );
                            }
                            //mdp -
                            else {
                                //passsword hash
                                $json['mdp'] = password_hash($json['mdp'], PASSWORD_DEFAULT);

                                //update user
                                $response = $this->executeQuery(
                                    "UPDATE utilisateur SET nom_utilisateur = :nom, prenoms_utilisateur = :prenoms, sexe_utilisateur = :sexe, role = :role, email_utilisateur = :email, mdp = :mdp WHERE id_utilisateur = :id",
                                    [
                                        'nom' => $json['nom_utilisateur'],
                                        'prenoms' => $json['prenoms_utilisateur'],
                                        'sexe' => $json['sexe_utilisateur'],
                                        'role' => $json['role'],
                                        'email' => $json['email_utilisateur'],
                                        'id' => $json['id_utilisateur'],
                                        'mdp' => $json['mdp']
                                    ]
                                );
                            }

                            //error
                            if ($response['message_type'] === 'error') {
                                return $response;
                            }
                            //success
                            else {
                                $response['message'] = "Les informations de l'utilisateur on été modifiées avec succès .";
                            }
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => 'Error update user : ' . $e->getMessage()
            ];
        }

        return $response;
    }

    //delete user
    public function deleteUser($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //user exist?
            $response = $this->isUserExist($json['id_utilisateur']);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //not found
                if ($response['message'] === 'not found') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => "L'utilisateur avec l'ID <b>{$json['id_utilisateur']}</b> n'existe pas ."
                    ];
                }
                //found 
                else {
                    //delete user
                    $response = $this->executeQuery(
                        "DELETE FROM utilisateur WHERE id_utilisateur = :id",
                        ['id' => $json['id_utilisateur']]
                    );

                    //error
                    if ($response['message_type'] === 'error') {
                        return $response;
                    }
                    //success
                    else {
                        $response['messages'] = "L'utilisateur avec l'ID <b>{$json['id_utilisateur']}</b> a été supprimé avec succès.";
                    }
                }
            }
        } catch (Throwable $e) {
            $response = ['message_type' => 'error', 'message' => "Error " . $e->getMessage()];
        }

        return $response;
    }

    //findyBy ID
    public static function findById($id)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = _('errors.catch.user_findby', ['field' => $e->getMessage()]);
        }

        return $response;
    }
    //====================== PRIVATE FUNCTION ====================

    //is admin exist ?
    private static function isAdminExist()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'not found'
        ];

        try {
            //count admin
            $user = new self();
            $response = $user->selectQuery("SELECT COUNT(id_utilisateur) as nb_admin FROM utilisateur WHERE etat_utilisateur != 'supprimé' AND role ='admin'");

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //exist
                if ($response['data'][0]['nb_admin'] >= 1) {
                    $response['message'] = 'found';
                }
                //not exist
                else {
                    $response['message'] = 'not found';
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.create_default_admin', ['field' => $e->getMessage()]);
        }

        return $response;
    }

    //generate id_utilisateur
    private function generateIdUser()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        //first id_utilisateur
        $this->id_utilisateur = 'U' .
            strval(sprintf("%06d", mt_rand(0, 999999))) .
            substr(
                str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                0,
                2
            );

        //regenerate if exist
        try {
            $found = true;
            while ($found) {
                $response = $this->selectQuery("SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur = :id", ['id' => $this->id_utilisateur]);

                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }

                //found
                if (count($response['data']) >= 1) {
                    //regenerate id
                    $this->id_utilisateur = 'U' .
                        strval(sprintf("%06d", mt_rand(0, 999999))) .
                        substr(
                            str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                            0,
                            2
                        );
                }
                //not found
                else {
                    $found = false;
                    break;
                }
            }
            return $response;
        } catch (Throwable $e) {
            return $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.user_generate_id', ['field' => $e->getMessage()])
            ];
        }

        return $response;
    }
    //email exist?
    private function isEmailExist()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'not found'
        ];

        try {
            $response = $this->selectQuery("SELECT email_utilisateur FROM 
            utilisateur WHERE email_utilisateur = :email AND etat_utilisateur != 'supprimé'", ['email' => $this->email_utilisateur]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if (count($response['data']) >= 1) {
                $response['message'] = 'found';
                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            return $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.user_isEmailExist', ['field' => $e->getMessage()])
            ];
        }

        return $response;
    }
    //user exist ?
    public function isUserExist($id_utilisateur)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'not found'
        ];

        try {
            //sql
            $sql = null;
            $sql = $this->selectQuery("SELECT id_utilisateur FROM 
            utilisateur WHERE id_utilisateur = :id", [
                'id' => $id_utilisateur
            ]);
            //error
            if ($sql['message_type'] === 'error') {
                $response = $sql;
            }
            //data
            else {
                //found
                if (count($sql['data'])  >= 1) {
                    $response['message'] = 'found';
                }
                //not found
                else {
                    $response['message'] = 'not found';
                }
            }
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return $response;
    }
    //email exist?
    private function updateUserIsEmailExist($email_utilisateur, $id_utilisateur)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'not found'
        ];

        try {
            //sql
            $sql = null;
            $sql = $this->selectQuery("SELECT email_utilisateur FROM 
            utilisateur WHERE email_utilisateur = :email AND id_utilisateur != :id", [
                'email' => $email_utilisateur,
                'id' => $id_utilisateur
            ]);
            //error
            if ($sql['message_type'] === 'error') {
                return $sql;
            }
            //data
            else {
                //found
                if (count($sql['data'])  >= 1) {
                    $response['message'] = 'found';
                    return $response;
                }
                //not found
                else {
                    return $response;
                }
            }
        } catch (Throwable $e) {
            return $response = [
                'message_type' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
