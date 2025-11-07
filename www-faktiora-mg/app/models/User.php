<?php

class User extends Database
{
    private $id_utilisateur = "";

    public function __construct()
    {
        parent::__construct();
    }

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
                    return $this->executeQuery("INSERT INTO utilisateur (id_utilisateur, nom_utilisateur, sexe_utilisateur, email_utilisateur, role, mdp) VALUES (:id, :nom, :sexe, :email, :role, :mdp)", [
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

    //filter user
    public function filterUser($params)
    {
        $paramsQuery = [];

        $response = ['message_type' => 'success', 'message' => 'success'];
        $sql = "SELECT u.id_utilisateur, u.nom_utilisateur, u.prenoms_utilisateur, u.sexe_utilisateur, u.email_utilisateur, u.role, c.num_caisse, COUNT(f.num_facture) AS nb_factures, COUNT(ae.id_entree) AS nb_ae, (COUNT(f.num_facture) + COUNT(ae.id_entree)) AS nb_entrees, COUNT(s.id_sortie) AS nb_sorties, (COUNT(f.num_facture) + COUNT(ae.id_entree) + COUNT(s.id_sortie)) AS nb_transactions , COALESCE(SUM(f.montant_facture), 0) AS total_factures, COALESCE(SUM(ae.montant_entree), 0) AS total_ae, COALESCE(SUM(f.montant_facture) + SUM(ae.montant_entree) , 0) AS total_entrees, COALESCE(SUM(s.montant_sortie), 0) AS total_sorties, COALESCE(SUM(f.montant_facture) + + SUM(ae.montant_entree) + SUM(s.montant_sortie) , 0 )  AS total_transactions FROM utilisateur u LEFT JOIN caisse c ON c.id_utilisateur = u.id_utilisateur ";

        //per - none
        if ($params['per'] === 'none') {
            //from - empty
            if ($params['from'] === '') {
                //to - empty
                if ($params['to'] === '') {
                    //month - none
                    if ($params['month'] === 'none') {
                        //year - none
                        if ($params['year'] === 'none') {
                            $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur ";
                        }
                        //year - 
                        else {
                            $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND YEAR(f.date_facture) = :year LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND YEAR(ae.date_entree) = :year LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND YEAR(s.date_sortie) = :year ";
                            $paramsQuery['year'] = $params['year'];
                        }
                    }
                    //month - 
                    else {
                        //year - none === now
                        if ($params['year'] === 'none') {
                            $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = YEAR(CURDATE()) LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND MONTH(ae.date_entree) = :month AND YEAR(ae.date_entree) = YEAR(CURDATE()) LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND MONTH(s.date_sortie) = :month AND YEAR(s.date_sortie) = YEAR(CURDATE()) ";
                            $paramsQuery['month'] = $params['month'];
                        }
                        //year - 
                        else {
                            $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND MONTH(ae.date_entree) = :month AND YEAR(ae.date_entree) = :year LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND MONTH(s.date_sortie) = :month AND YEAR(s.date_sortie) = :year ";
                            $paramsQuery['month'] = $params['month'];
                            $paramsQuery['year'] = $params['year'];
                        }
                    }
                }
                //to - 
                else {
                    $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) <= :to LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_entree) <= :to LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND DATE(s.date_sortie) <= :to ";
                    $paramsQuery['to'] = $params['to'];
                }
            }
            //from -
            else {
                //to - empty
                if ($params['to'] === '') {
                    $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) >= :from LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_entree) >= :from LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND DATE(s.date_sortie) >= :from ";
                    $paramsQuery['from'] = $params['from'];
                }
                //to -
                else {
                    $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) BETWEEN :from AND :to LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_entree) BETWEEN :from AND :to LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND DATE(s.date_sortie)BETWEEN :from AND :to ";
                    $paramsQuery['from'] = $params['from'];
                    $paramsQuery['to'] = $params['to'];
                }
            }
        }
        //per - type
        else {
            //per - day
            if ($params['per'] === 'day') {
                $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) = CURDATE() LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_entree) = CURDATE() LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND DATE(s.date_sortie) = CURDATE() ";
            }
            //per - week year month
            else {
                $params['per'] = strtoupper($params['per']);
                $sql .= "LEFT JOIN facture f ON f.id_utilisateur = u.id_utilisateur AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) LEFT JOIN autre_entree ae ON ae.id_utilisateur = u.id_utilisateur AND {$params['per']}(ae.date_entree) = {$params['per']}(CURDATE()) LEFT JOIN sortie s ON s.id_utilisateur = u.id_utilisateur AND {$params['per']}(s.date_sortie) = {$params['per']}(CURDATE()) ";
            }
        }

        //where
        $sql .= "WHERE 1=1 ";

        //sexe 
        if ($params['sexe'] !== 'all') {
            $sql .= "AND u.sexe_utilisateur = :sexe ";
            $paramsQuery['sexe'] = $params['sexe'];
        }

        //role
        if ($params['role'] !== 'all') {
            $sql .= "AND u.role = :role ";
            $paramsQuery['role'] = $params['role'];
        }

        //search_user
        if ($params['search_user'] !== '') {
            $params['search_user'] = "%" . $params['search_user'] . "%";
            $sql .= "AND (u.id_utilisateur LIKE :id OR u.nom_utilisateur LIKE :nom OR u.prenoms_utilisateur LIKE :prenoms OR u.email_utilisateur LIKE :email) ";
            $paramsQuery['id'] = $params['search_user'];
            $paramsQuery['nom'] = $params['search_user'];
            $paramsQuery['prenoms'] = $params['search_user'];
            $paramsQuery['email'] = $params['search_user'];
        }

        //group by id
        $sql .= "GROUP BY u.id_utilisateur ";

        //by - 
        if ($params['by'] !== 'none') {
            $sql .= "ORDER BY {$params['by']} " . strtoupper($params['order_by']);
        }
        //per - none
        else {
            $sql .= "ORDER BY u.nom_utilisateur ASC";
        }

        try {
            $response = $this->selectQuery($sql, $paramsQuery);

            //count nbUser
            $response['nb_user'] = count($response['data']);
            //count nb_admin && nb_caissier
            $nb_admin = 0;
            $nb_caissier = 0;
            for ($i = 0; $i < $response['nb_user']; $i++) {
                //nb_admin
                if ($response['data'][$i]['role'] === 'admin') {
                    $nb_admin++;
                }
                //nb_caissier
                else {
                    $nb_caissier++;
                }
            }
            $response['nb_admin'] = $nb_admin;
            $response['nb_caissier'] = $nb_caissier;
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
