<?php

class User extends Database
{
    private $id_utilisateur = "";
    private $id_update = "";
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

    //================== SETTERS ============================

    //setter - id_utilisateur
    public function setIdUtilsateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }
    //setter - id_update
    public function setIdUpdate($id_update)
    {
        $this->id_update = $id_update;
        return $this;
    }
    //setter - nom_utilisateur
    public function setNomUtilisateur($nom_utilisateur)
    {
        $this->nom_utilisateur = strtoupper($nom_utilisateur);
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
        $this->mdp = (!empty($mdp)) ? password_hash($mdp, PASSWORD_DEFAULT) : '';
        return $this;
    }
    //setter - mdp_oublie
    // public function setMdpOublie($mdp_oublie)
    // {
    //     return $this->nom_utilisateur = $nom_utilisateur;
    // 

    //================== GETTERS ========================

    //getter - role
    public function getRole()
    {
        return $this->role;
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
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.create_default_admin', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //create_user
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
            $response = self::isEmailUserExist($this->email_utilisateur, null);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.user_email', ['field' => $this->email_utilisateur]);

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
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.create_user', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //signup
    public function signUp()
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
            $response = self::isEmailUserExist($this->email_utilisateur, null);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.user_email', ['field' => $this->email_utilisateur]);

                return $response;
            }

            //create user
            $response = self::executeQuery("INSERT INTO utilisateur (id_utilisateur, nom_utilisateur, prenoms_utilisateur, sexe_utilisateur, email_utilisateur, mdp) VALUES (:id, :nom, :prenoms, :sexe, :email, :mdp)", [
                'id' => $this->id_utilisateur,
                'nom' => $this->nom_utilisateur,
                'prenoms' => $this->prenoms_utilisateur,
                'sexe' => $this->sexe_utilisateur,
                'email' => $this->email_utilisateur,
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
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.create_user', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //update user by admin
    public function updateByAdmin()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {
            //id_update exist?
            $response = self::isIdUserExist($this->id_update, $this->id_utilisateur);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.user_id', ['field' => $this->id_update]);

                return $response;
            }

            //email exist?
            $response = self::isEmailUserExist($this->email_utilisateur, $this->id_utilisateur);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.user_email', ['field' => $this->email_utilisateur]);

                return $response;
            }

            //update user - no password
            if (empty($this->mdp)) {
                $response  = $this->executeQuery(
                    "UPDATE utilisateur SET id_utilisateur = :id, nom_utilisateur = :nom, prenoms_utilisateur = :prenoms, sexe_utilisateur = :sexe, email_utilisateur = :email, role = :role WHERE id_utilisateur = :id_user",
                    [
                        'id' => $this->id_update,
                        'nom' => $this->nom_utilisateur,
                        'prenoms' => $this->prenoms_utilisateur,
                        'sexe' => $this->sexe_utilisateur,
                        'email' => $this->email_utilisateur,
                        'role' => $this->role,
                        'id_user' => $this->id_utilisateur
                    ]
                );
            }
            //update user - with password
            else {
                $response  = $this->executeQuery(
                    "UPDATE utilisateur SET id_utilisateur = :id, nom_utilisateur = :nom, prenoms_utilisateur = :prenoms, sexe_utilisateur = :sexe, email_utilisateur = :email, role = :role, mdp = :mdp WHERE id_utilisateur = :id_user",
                    [
                        'id' => $this->id_update,
                        'nom' => $this->nom_utilisateur,
                        'prenoms' => $this->prenoms_utilisateur,
                        'sexe' => $this->sexe_utilisateur,
                        'email' => $this->email_utilisateur,
                        'role' => $this->role,
                        'mdp' => $this->mdp,
                        'id_user' => $this->id_utilisateur
                    ]
                );
            }
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response['message'] = __('messages.success.update_user', ['field' => $this->id_utilisateur]);
            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_update', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //update user by user
    public function updateByUser()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //email exist?
            $response = self::isEmailUserExist($this->email_utilisateur, $this->id_utilisateur);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.user_email', ['field' => $this->email_utilisateur]);

                return $response;
            }

            //update user - no password
            if (empty($this->mdp)) {
                $response  = $this->executeQuery(
                    "UPDATE utilisateur SET nom_utilisateur = :nom, prenoms_utilisateur = :prenoms, sexe_utilisateur = :sexe, email_utilisateur = :email WHERE id_utilisateur = :id_user",
                    [
                        'nom' => $this->nom_utilisateur,
                        'prenoms' => $this->prenoms_utilisateur,
                        'sexe' => $this->sexe_utilisateur,
                        'email' => $this->email_utilisateur,
                        'id_user' => $this->id_utilisateur
                    ]
                );
            }
            //update user - with password
            else {
                $response  = $this->executeQuery(
                    "UPDATE utilisateur SET nom_utilisateur = :nom, prenoms_utilisateur = :prenoms, sexe_utilisateur = :sexe, email_utilisateur = :email, mdp = :mdp WHERE id_utilisateur = :id_user",
                    [
                        'nom' => $this->nom_utilisateur,
                        'prenoms' => $this->prenoms_utilisateur,
                        'sexe' => $this->sexe_utilisateur,
                        'email' => $this->email_utilisateur,
                        'mdp' => $this->mdp,
                        'id_user' => $this->id_utilisateur
                    ]
                );
            }
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response['message'] = __('messages.success.update_user', ['field' => $this->id_utilisateur]);
            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_update', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //delete account
    public function deleteAccount()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            $response = self::executeQuery("UPDATE utilisateur SET etat_utilisateur = 'supprimé' WHERE id_utilisateur = :id", ['id' => $this->id_utilisateur]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_delete_account', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //delete default admin
    public static function deleteDefaultAdmin()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            $response = self::executeQuery("DELETE from utilisateur WHERE id_utilisateur = '000000' ");

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_delete_account', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //delete all
    public static function deleteAll($id_users, $id_utilisateur)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholers = implode(', ', array_fill(0, count($id_users), '?'));
        $sql = "UPDATE utilisateur  SET etat_utilisateur = 'supprimé' WHERE id_utilisateur IN ({$placeholers}) AND id_utilisateur != ?";

        $id_users[] = $id_utilisateur;

        try {
            $response = self::executeQuery($sql, $id_users);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.user0_delete_all');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.user1_delete_all');
            }
            //plur
            else {
                $response['message'] = __('messages.success.users_delete_all', ['field' => $response['row_count']]);
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.user_deleteAll', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //permanent delete all
    public static function permanentDeleteAll($id_users, $id_utilisateur)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholers = implode(', ', array_fill(0, count($id_users), '?'));
        $sql = "DELETE FROM utilisateur WHERE id_utilisateur IN ({$placeholers}) AND id_utilisateur != ?";

        $id_users[] = $id_utilisateur;

        try {
            $response = self::executeQuery($sql, $id_users);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.user0_delete_all');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.user1_delete_all');
            }
            //plur
            else {
                $response['message'] = __('messages.success.users_delete_all', ['field' => $response['row_count']]);
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.user_deleteAll', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //deconnect all user
    public static function deconnectAll($ids_user, $id_utilisateur)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholers = implode(', ', array_fill(0, count($ids_user), '?'));
        $sql = "UPDATE utilisateur SET etat_utilisateur = 'déconnecté' WHERE id_utilisateur IN ({$placeholers}) AND etat_utilisateur = 'connecté' AND id_utilisateur != ? ";

        //push id user in the end
        array_push($ids_user, $id_utilisateur);

        try {
            $response = self::executeQuery($sql, $ids_user);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.user_deconnectAll_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.user_deconnectAll_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.user_deconnectAll_plur', ['field' => $response['row_count']]);
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.user_deconnectAll', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //static - findyBy ID
    public static function findById($id)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {
            $user_model = new User();

            $response = self::selectQuery("SELECT * FROM utilisateur WHERE id_utilisateur  = :id", ['id' => $id]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //found
            if (count($response['data']) >= 1) {
                $response['found'] = true;

                $user_model->id_utilisateur = $response['data'][0]['id_utilisateur'];
                $user_model->nom_utilisateur = $response['data'][0]['nom_utilisateur'];
                $user_model->prenoms_utilisateur = $response['data'][0]['prenoms_utilisateur'];
                $user_model->sexe_utilisateur = $response['data'][0]['sexe_utilisateur'];
                $user_model->email_utilisateur = $response['data'][0]['email_utilisateur'];
                $user_model->role = $response['data'][0]['role'];
                $user_model->mdp = $response['data'][0]['mdp'];
                $user_model->mdp_oublie = $response['data'][0]['mdp_oublie'];
                $user_model->etat_utilisateur = $response['data'][0]['etat_utilisateur'];
                $user_model->dernier_session = $response['data'][0]['dernier_session'];

                $response['model'] = $user_model;

                $response['data'] = [];
                return $response;
            }
            //not found
            else {
                $response['found'] = false;
                $response['data'] = '';

                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_findById', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //====================== PRIVATE FUNCTION ====================

    //is id_utilisateur exist?
    private static function isIdUserExist($id_utilisateur, $exclude = null)
    {
        $response = ['message_type' => 'success', 'message' => 'success', 'found' => false];
        $sql = "SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur = :id ";
        $params = ['id' => $id_utilisateur];

        //exclude
        if ($exclude) {
            $sql .= "AND id_utilisateur != :id_user";
            $params['id_user'] = $exclude;
        }

        try {

            $response = self::selectQuery($sql, $params);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //found
            if (count($response['data']) >= 1) {
                $response['found'] = true;
            }
            //not found
            else {
                $response['found'] = false;
            }

            return $response;
        } catch (Throwable $e) {

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_isIdUserExist', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //is email_utilisateur exist?
    private static function isEmailUserExist($email_utilisateur, $exclude = null)
    {
        $response = ['message_type' => 'success', 'message' => 'success', 'found' => false];
        $sql = "SELECT email_utilisateur FROM utilisateur WHERE email_utilisateur = :email AND etat_utilisateur != 'supprimé' ";
        $params = ['email' => $email_utilisateur];

        //exclude
        if ($exclude) {
            $sql .= "AND id_utilisateur != :id_user";
            $params['id_user'] = $exclude;
        }

        try {

            $response = self::selectQuery($sql, $params);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //found
            if (count($response['data']) >= 1) {
                $response['found'] = true;
            }
            //not found
            else {
                $response['found'] = false;
            }

            return $response;
        } catch (Throwable $e) {

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_isEmailUserExist', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //is admin exist ?
    private static function isAdminExist()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'not found'
        ];

        try {
            //count admin
            $response = self::selectQuery("SELECT COUNT(id_utilisateur) as nb_admin FROM utilisateur WHERE etat_utilisateur != 'supprimé' AND role ='admin'");

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

        //generate id_utilisateur
        $this->id_utilisateur = 'U' .
            strval(sprintf("%06d", mt_rand(0, 999999))) .
            substr(
                str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                0,
                2
            );

        try {

            $found = true;
            while ($found) {
                $response = self::isIdUserExist($this->id_utilisateur, null);

                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }

                //found
                if ($response['found']) {

                    //regenerate id_utilisateur
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
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] =  __('errors.catch.user_generate_id', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }
}
