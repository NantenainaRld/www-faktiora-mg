<?php

class Caisse extends Database
{

    private $num_caisse = null;
    private $num_caisse_update = null;
    private $solde = 0;
    private $seuil = 0;
    private $etat_caisse = 'actif';

    public function __construct()
    {
        parent::__construct();
    }

    //===================== SETTERS =======================

    //setter - num_caisse
    public function setNumCaisse($num_caisse)
    {
        $this->num_caisse = $num_caisse;
        return $this;
    }
    //setter - num_caisse_update
    public function setNumCaisseUpdate($num_caisse_update)
    {
        $this->num_caisse_update = $num_caisse_update;
        return $this;
    }
    //setter - solde
    public function setSolde($solde)
    {
        $this->solde = $solde;
        return $this;
    }
    //setter - seuil
    public function setSeuil($seuil)
    {
        $this->seuil = $seuil;
        return $this;
    }
    //setter - seuil
    public function  setEtatCaisse($etat_caisse)
    {
        $this->etat_caisse = $etat_caisse;
        return $this;
    }

    //========================== GETTERS ============================

    //getter - etat_caisse
    public function getEtatCaisse()
    {
        return $this->etat_caisse;
    }


    //=========================== PUBLIC FUNCTION =============================

    // add caisse
    public function createCaisse()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_caisse exist?
            $response = self::isNumCaisseExist($this->num_caisse, false);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.num_caisse', ['field' => $this->num_caisse]);

                return $response;
            }

            //create caisse
            $response = self::executeQuery("INSERT INTO caisse (num_caisse, solde, seuil) VALUES (:num_caisse, :solde, :seuil) ", [
                'num_caisse' => $this->num_caisse,
                'solde' => $this->solde,
                'seuil' => $this->seuil
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                $response['message'] = __('messages.success.create_caisse', ['field' => $this->num_caisse]);

                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_create_caisse', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //update caisse
    public function updateCaisse()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            // num_caisse_update exist?
            $response = self::isNumCaisseExist($this->num_caisse_update, $this->num_caisse);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.num_caisse', ['field' => $this->num_caisse_update]);

                return $response;
            }

            //update caisse
            $response = self::executeQuery("UPDATE caisse SET num_caisse = :num, solde = :solde, seuil = :seuil WHERE num_caisse = :num_caisse", [
                'num' => $this->num_caisse_update,
                'solde' => $this->solde,
                'seuil' => $this->seuil,
                'num_caisse' => $this->num_caisse
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                $response['message'] = __('messages.success.caisse_update_caisse', ['field' => $this->num_caisse]);

                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_update_caisse', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //delete all caisse
    public static function deleteAll($nums_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
        $sql = "UPDATE caisse SET etat_caisse = 'supprimé' WHERE num_caisse IN ({$placeholders}) ";

        try {

            $response = self::executeQuery($sql, $nums_caisse);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.caisse_deleteAll_0');

                return $response;
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.caisse_deleteAll_1');

                return $response;
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_deleteAll_plur', ['field' => $response['row_count']]);

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.caisse_deleteAll', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }
    //permanent delete all caisse
    public static function permanentDeleteAll($nums_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
        $sql = "DELETE FROM caisse WHERE num_caisse IN ({$placeholders}) ";

        try {

            $response = self::executeQuery($sql, $nums_caisse);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.caisse_deleteAll_0');

                return $response;
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.caisse_deleteAll_1');

                return $response;
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_deleteAll_plur', ['field' => $response['row_count']]);

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.caisse_deleteAll', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //occup caisse
    public function occupCaisse($id_utilisateur)
    {
        $response = ['message_type' => 'success', 'messae' => 'success'];

        try {

            //quit caisse
            $response = $this->quitCaisse($id_utilisateur);
            //error
            if ($response['message_type'] == 'error') {
                return $response;
            }

            //update etat_caisse to occupé
            $response = self::executeQuery("UPDATE caisse SET etat_caisse = 'occupé' WHERE num_caisse = :num_caisse ", ['num_caisse' => $this->num_caisse]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //add ligne caisse
            $ligne_caisse = (new LigneCaisse)
                ->setIdUtilsateur($id_utilisateur)
                ->setNumCaisse($this->num_caisse);
            $response = $ligne_caisse->createLigneCaisse();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            $response['message'] = __('messages.success.caisse_occupCaisse', ['field' => $this->num_caisse]);

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'invalid',
                'message' => __('errors.catch.caisse_occupCaisse', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //quit caisse
    public function quitCaisse($id_utilisateur)
    {
        $response = ['message_type' => 'success', 'messae' => 'success'];

        try {

            //update etat_caisse to libre
            $response = self::executeQuery("UPDATE caisse SET etat_caisse = 'libre' WHERE num_caisse IN (SELECT DISTINCT num_caisse FROM ligne_caisse WHERE id_utilisateur = :id AND date_fin IS NULL) ", ['id' => $id_utilisateur]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //add date_fin ligne caisse
            $ligne_caisse = (new LigneCaisse)
                ->setIdUtilsateur($id_utilisateur);
            $response = $ligne_caisse->quitCaisse();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            $response['message'] = __('messages.success.caisse_quitCaisse');

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.caisse_quitCaisse', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //static - find by id
    public static function findById($num_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success', 'found' => false];

        try {
            $response = self::selectQuery("SELECT * FROM caisse WHERE num_caisse = :num_caisse", ['num_caisse' => $num_caisse]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response['found'] = false;
                $response['data'] = [];

                return $response;
            }
            //found
            else {
                $response['found'] = true;

                //model
                $caisse_model = new Caisse();
                $caisse_model->num_caisse = $response['data'][0]['num_caisse'];
                $caisse_model->solde = $response['data'][0]['solde'];
                $caisse_model->seuil = $response['data'][0]['seuil'];
                $caisse_model->etat_caisse = $response['data'][0]['etat_caisse'];

                $response['data'] = [];

                $response['model'] = $caisse_model;

                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_findById', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    // //affect caisse
    // public function affectCaisse($json)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //num_caisse exist?
    //         $response = $this->isNumCaisseExist($json['num_caisse']);

    //         //error
    //         if ($response['message_type'] === 'error') {
    //             return $response;
    //         }
    //         //success
    //         else {
    //             //not found
    //             if ($response['message'] === 'not found') {
    //                 $response['message'] = "Numéro de caisse non trouvé : " . $json['num_caisse'];
    //             }
    //             //found
    //             else {
    //                 //user exist?
    //                 $user_model = new User();
    //                 $response = $user_model->isUserExist($json['id_utilisateur']);

    //                 //error
    //                 if ($response['message_type'] === 'error') {
    //                     return $response;
    //                 }
    //                 //success
    //                 else {
    //                     //not found
    //                     if ($response['message'] === 'not found') {
    //                         $response['message'] = "Utilisateur avec l'ID : <b>"  . $json['id_utilisateur'] . "</b> n'existe pas .";
    //                     }
    //                     //found
    //                     else {
    //                         //affect caisse
    //                         $response = $this->executeQuery("UPDATE caisse SET id_utilisateur = :id WHERE num_caisse = :num_caisse", [
    //                             'id' => $json['id_utilisateur'],
    //                             'num_caisse' => $json['num_caisse']
    //                         ]);

    //                         //error
    //                         if ($response['message_type'] === 'error') {
    //                             return $response;
    //                         }
    //                         //success
    //                         else {
    //                             //move user to the selected caisse
    //                             $response = $this->executeQuery("UPDATE caisse SET id_utilisateur = NULL WHERE id_utilisateur = :id AND num_caisse != :num_caisse", [
    //                                 'id' => $json['id_utilisateur'],
    //                                 'num_caisse' => $json['num_caisse']
    //                             ]);

    //                             //error
    //                             if ($response['message_type'] === 'error') {
    //                                 return $response;
    //                             }
    //                             //success
    //                             else {
    //                                 $response['message'] = "L'utilisateur <b>" . $json['id_utilisateur'] . "</b> a été mis au caisse numéro <b>" . $json['num_caisse'] . "</b>";
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error : ' . $e->getMessage();
    //     }

    //     return $response;
    // }

    //===================== PRIVATE FUNCTION ======================

    //static - is num_caisse exist?
    private static function isNumCaisseExist($num_caisse, $exclude = null)
    {
        $response  = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        $sql = "SELECT num_caisse FROM caisse WHERE num_caisse = :num ";
        $params = ['num' => $num_caisse];

        if ($exclude) {
            $sql .= "AND num_caisse != :num_caisse";
            $params['num_caisse'] = $exclude;
        }

        try {
            $response = self::selectQuery($sql, $params);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response['found'] = false;
            }
            //not found
            else {
                $response['found'] = true;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_isNumCaisseExist', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    // //update solde
    // public function updateSolde($num_caisse, $solde, $type)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //increase
    //         if ($type === 'increase') {
    //             $response = $this->executeQuery("UPDATE caisse SET solde = solde + :solde WHERE num_caisse = :um_caisse", [
    //                 'solde' => $solde,
    //                 'num_caisse' => $num_caisse
    //             ]);
    //         }
    //         //decrease
    //         else {
    //             $response = $this->executeQuery("UPDATE caisse SET solde = solde - :solde WHERE num_caisse = :um_caisse", [
    //                 'solde' => $solde,
    //                 'num_caisse' => $num_caisse
    //             ]);
    //         }
    //         // $response
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error : ' . $e->getMessage();
    //     }

    //     return $response;
    // }
}
