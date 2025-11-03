<?php

class User extends Database
{
    private $id_utilisateur = "";

    public function __construct()
    {
        parent::__construct();
    }

    //==================  PUBLIC FUNCTION ====================
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

    //list user
    public function defaultListUser()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            $response =
                //error or success
                $this->selectQuery("SELECT u.id_utilisateur, u.nom_utilisateur, u.prenoms_utilisateur, u.sexe_utilisateur, u.email_utilisateur, u.role, c.num_caisse FROM utilisateur u LEFT JOIN caisse c ON u.id_utilisateur = c.id_utilisateur");
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => 'Error : ' . $e->getMessage()
            ];
        }

        return $response;
    }
    //nb user
    public function defaultNbUser()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            $response =
                //error or success
                $this->selectQuery("SELECT COUNT(id_utilisateur) as nb_utilisateur, COUNT(CASE WHEN role = 'admin' THEN 1 END) as nb_admin, COUNT(CASE WHEN role = 'caissier' THEN 1 END) as nb_caissier FROM utilisateur;");
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => 'Error : ' . $e->getMessage()
            ];
        }

        return $response;
    }
    //transactions user
    //     public function transactionsUser($id)
    //     {
    //         $response = [
    //             'message_type' => 'success',
    //             'message' => 'success'
    //         ];

    //         try {
    //             //error or success
    //             $response = $this->selectQuery("SELECT
    //     COUNT(f.num_facture) AS nb_factures,
    //     COUNT(ae.id_entree) AS nb_ae,
    //     COUNT(s.id_sortie) AS nb_sorties,
    //     (
    //         COUNT(ae.id_entree) + COUNT(f.num_facture)
    //     ) AS nb_entrees,
    //     (
    //         COUNT(ae.id_entree) + COUNT(f.num_facture) + COUNT(s.id_sortie)
    //     ) AS nb_transactions,
    //     (COUNT(DISTINCT f.id_client)) AS nb_clients,
    //     COALESCE(
    //         SUM(ae.montant_entree) + SUM(s.montant_sortie) + SUM(f.montant_facture), 0
    //     ) AS total_transactions,
    //     COALESCE(SUM(f.montant_facture),
    //     0) AS total_factures,
    //     COALESCE(SUM(ae.montant_entree),
    //     0) AS total_ae,
    //     COALESCE(SUM(s.montant_sortie),
    //     0) AS total_sorties,
    //     COALESCE(
    //         SUM(ae.montant_entree) + SUM(f.montant_facture),
    //         0
    //     ) AS total_entrees
    // FROM
    //     utilisateur u
    // LEFT JOIN caisse c ON
    //     c.id_utilisateur = u.id_utilisateur
    // LEFT JOIN facture f ON
    //     f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) = CURDATE()
    // LEFT JOIN autre_entree ae ON
    //     ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_entree) = CURDATE()
    // LEFT JOIN sortie s ON
    //     s.id_utilisateur = u.id_utilisateur AND DATE(s.date_sortie) = CURDATE()
    // WHERE
    //     u.id_utilisateur = :id
    // GROUP BY
    //     u.id_utilisateur
    // ORDER BY
    //     total_transactions;", ['id' => $id]);
    //         } catch (Throwable $e) {
    //             $response = [
    //                 'message_type' => 'error',
    //                 'message' => 'Error : ' . $e->getMessage()
    //             ];
    //         }

    //         return $response;
    //     }

    //====================== PRIVATE FUNCTION ====================

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
