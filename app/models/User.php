<?php

class User extends Database
{
    private $id_utilisateur = "";

    public function __construct()
    {
        parent::__construct();
    }

    //add user
    public function addUser($json)
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
            //success
            else {
                //email exist ?
                $emailExist = $this->isEmailExist($json['email_utilisateur']);
                //error
                if ($emailExist['message_type'] === 'error') {
                    return $emailExist;
                }
                //success
                else {
                    //found
                    if ($emailExist['message'] === 'found') {
                        return $response = [
                            'message_type' => 'invalid',
                            'message' => 'Cette adresse <b>email</b> est déjà utilisée .'
                        ];
                    }
                    //not found
                    else {
                        //passsword hash
                        $json['mdp'] = password_hash($json['mdp'], PASSWORD_DEFAULT);

                        $response = $this->executeQuery(
                            "INSERT INTO utilisateur 
                    (id_utilisateur, nom_utilisateur, prenoms_utilisateur, sexe_utilisateur,
                    email_utilisateur, role, mdp)
                VALUES (:id, :nom, :prenoms, :sexe, :email, :role, :mdp)",
                            [
                                'id' => $this->id_utilisateur,
                                'nom' => $json['nom_utilisateur'],
                                'prenoms' => $json['prenoms_utilisateur'],
                                'sexe' => $json['sexe_utilisateur'],
                                'email' => $json['email_utilisateur'],
                                'role' => $json['role'],
                                'mdp' => $json['mdp']
                            ]
                        );

                        //error
                        if ($response['message_type'] === 'error') {
                            return $response;
                        } else {
                            return $response = [
                                'message_type' => 'success',
                                'message' => 'Utilisateur créé avec succès .'
                            ];
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            return $response = [
                'message_type' => 'error',
                'message' => 'Error addUser : ' . $e->getMessage()
            ];
        }
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

    //------------------PRIVATE FUNCTION------------------

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
            //sql
            $sql = null;
            $found = true;
            do {
                $sql = $this->selectQuery("SELECT id_utilisateur FROM 
            utilisateur WHERE id_utilisateur = :id", ['id' =>
                $this->id_utilisateur]);
                //error
                if ($sql['message_type'] === 'error') {
                    $response = $sql;
                    break;
                }
                //data
                else {
                    //found
                    if (count($sql['data'])  >= 1) {
                        //second id_utilisateur
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
                    }
                }
            } while ($found);
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return $response;
    }
    //email exist?
    private function isEmailExist($email_utilisateur)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'not found'
        ];

        try {
            //sql
            $sql = null;
            $sql = $this->selectQuery("SELECT email_utilisateur FROM 
            utilisateur WHERE email_utilisateur = :email", ['email' => $email_utilisateur]);
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
