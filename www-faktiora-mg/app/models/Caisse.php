<?php

//CLASS - caisse
class Caisse extends Database
{

    private $num_caisse = null;
    private $num_caisse_update = null;
    private $solde = 0;
    private $seuil = 0;
    private $etat_caisse = 'libre';

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

    //setter - etat_caisse
    public function setEtatCaisse($etat_caisse)
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
            $response = self::isNumCaisseExist($this->num_caisse, null);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __(
                        'messages.duplicate.caisse_num_caisse',
                        ['field' => $this->num_caisse]
                    )
                ];

                return $response;
            }

            //create caisse
            $response = parent::executeQuery("INSERT INTO caisse (num_caisse, solde, seuil) VALUES (:num_caisse, :solde, :seuil) ", [
                'num_caisse' => $this->num_caisse,
                'solde' => $this->solde,
                'seuil' => $this->seuil
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' =>  __(
                    'messages.success.caisse_createCaisse',
                    ['field' => $this->num_caisse]
                )
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_createCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - list free caisse
    public static function listFreeCaisse()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::selectQuery("SELECT num_caisse FROM caisse WHERE etat_caisse = 'libre' ");

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' =>  'success',
                'data' => $response['data']
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_listFreeCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

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
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.duplicate.caisse_num_caisse', ['field' => $this->num_caisse_update])
                ];

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

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.caisse_updateCaisse', ['field' => $this->num_caisse])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_updateCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //delete all caisse
    public static function deleteAll($nums_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
        $sql = "UPDATE caisse SET etat_caisse = 'supprimé' WHERE num_caisse IN ({$placeholders}) AND etat_caisse = 'libre' ";

        try {

            $response = parent::executeQuery($sql, $nums_caisse);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.caisse_deleteAll_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.caisse_deleteAll_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_deleteAll_plur', ['field' => $response['row_count']]);
            }

            $response = [
                'message_type' => 'success',
                'message' => $response['message']
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_deleteAll',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
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
        $sql = "DELETE FROM caisse WHERE num_caisse IN ({$placeholders}) AND etat_caisse = 'supprimé' ";

        try {

            $response = parent::executeQuery($sql, $nums_caisse);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.caisse_deleteAll_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.caisse_deleteAll_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_deleteAll_plur', ['field' => $response['row_count']]);
            }

            $response = [
                'message_type' => 'success',
                'message' => $response['message']
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_deleteAll',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - free caisse
    public static function freeCaisse($nums_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
        $sql = "UPDATE caisse SET etat_caisse = 'libre' WHERE num_caisse IN ({$placeholders}) AND etat_caisse = 'occupé' ";

        try {

            // update etat caisse to libre
            $response = self::executeQuery($sql, $nums_caisse);
            //row_count
            $row_count = $response['row_count'];
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //add date fin ligne caisse
            $response = LigneCaisse::freeCaisse($nums_caisse);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($row_count === 0) {
                $response['message'] = __('messages.success.caisse_freeCaisse_0');

                return $response;
            }
            //1
            elseif ($row_count === 1) {
                $response['message'] = __('messages.success.caisse_freeCaisse_1');

                return $response;
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_freeCaisse_plur', ['field' => $row_count]);

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.caisse_freeCaisse', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //static - find by id
    public static function findById($num_caisse)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {
            $response = parent::selectQuery("SELECT * FROM caisse WHERE num_caisse = :num_caisse", ['num_caisse' => $num_caisse]);

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

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => $response['found'],
                    'model' => $caisse_model
                ];

                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }


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
            $sql .= "AND num_caisse != :exclude";
            $params['exclude'] = $exclude;
        }

        try {

            $response = parent::selectQuery($sql, $params);

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

            $response  = [
                'message_type' => 'success',
                'message' => 'success',
                'found' => $response['found']
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_isNumCaisseExist',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

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
