<?php

//CLASS - autre entree
class AutreEntree extends Database
{
    private $id_ae = "";
    private $libelle_ae = "";
    private $date_ae = "";
    private $montant_ae = null;
    private $etat_ae = 'actif';
    private $id_utilisateur = "";
    private $num_caisse = null;

    public function __construct()
    {
        parent::__construct();
    }

    //====================== SETTERS ==============================

    //setter - id_ae
    public function setIdAe($id_ae)
    {
        $this->id_ae = $id_ae;
        return $this;
    }
    //setter - libelle_ae
    public function setLibelleAe($libelle_ae)
    {
        $this->libelle_ae = $libelle_ae;
        return $this;
    }
    //setter - dae_ae
    public function setDateAe($date_ae)
    {
        $this->date_ae = $date_ae;
        return $this;
    }
    //setter - montant_ae
    public function setMontantAe($montant_ae)
    {
        $this->montant_ae = $montant_ae;
        return $this;
    }
    //setter - id_utilisateur
    public function setIdUtilsateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }
    //setter - num_caisse
    public function setNumCaisse($num_caisse)
    {
        $this->num_caisse = $num_caisse;
        return $this;
    }

    //====================== PUBLIC FUNCTION =======================

    //create autre entree
    public function createAutreEntree()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //id_ae - empty
            if ($this->id_ae === '') {
                //generate id_ae
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.entree_createAutreEntree', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }


    //====================== PRIVATE FUNCTION ===========================s

    //is id_ae exist ?
    private static function isIdAeExist($id_ae, $exclude = null)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];
        $sql = "SELECT id_ae FROM autre_entree WHERE id_ae = :id ";
        $paramsQuery = ['id' => $id_ae];

        //exclude
        if ($exclude) {
            $sql .= "AND id_ae != :exclude ";
        }

        try {
        } catch (Throwable $e) {
            // error_log()
        }

        return $response;
    }

    //generate id_ae
    private function generateIdUser()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        //generate id_ae
        $this->id_utilisateur = 'AE' .
            strval(sprintf("%06d", mt_rand(0, 999999))) .
            substr(
                str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                0,
                2
            );

        try {

            $found = true;
            while ($found) {
                // $response = self::isIdUserExist($this->id_utilisateur, null);

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
