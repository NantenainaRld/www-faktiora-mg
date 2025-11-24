<?php

//CLASS - autre entree
class AutreEntree extends Database
{
    private $id_ae = "";
    private $num_ae = "";
    private $libelle_ae = "";
    private $date_ae = "";
    private $montant_ae = 1;
    private $etat_ae = 'actif';
    private $id_utilisateur = "";
    private $num_caisse = "";

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

    //setter - num_ae
    public function setNumAe($num_ae)
    {
        $this->num_ae = strtoupper($num_ae);
        return $this;
    }

    //setter - libelle_ae
    public function setLibelleAe($libelle_ae)
    {
        $this->libelle_ae = $libelle_ae;
        return $this;
    }

    //setter - date_ae
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
        $sql = "";
        $paramsQuery = [];

        //num_ae - empty
        if ($this->num_ae === '') {
            //date_ae - empty
            if ($this->date_ae === '') {
                $sql = "INSERT INTO autre_entree (libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse) VALUES (:libelle, NOW(), :montant, :id_user, :num_caisse) ";
                $paramsQuery = [
                    'libelle' => $this->libelle_ae,
                    'montant' => $this->montant_ae,
                    'id_user' => $this->id_utilisateur,
                    'num_caisse' => $this->num_caisse
                ];
            }
            //date_ae - not empty
            else {
                $sql = "INSERT INTO autre_entree (libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse) VALUES (:libelle, :date_ae, :montant, :id_user, :num_caisse) ";
                $paramsQuery = [
                    'libelle' => $this->libelle_ae,
                    'date_ae' => $this->date_ae,
                    'montant' => $this->montant_ae,
                    'id_user' => $this->id_utilisateur,
                    'num_caisse' => $this->num_caisse
                ];
            }
        }
        //num_ae - not empty
        else {
            //date_ae - empty
            if ($this->date_ae === '') {
                $sql = "INSERT INTO autre_entree (num_ae, libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse) VALUES (:num_ae, :libelle, NOW(), :montant, :id_user, :num_caisse) ";
                $paramsQuery = [
                    'num_ae' => $this->num_ae,
                    'libelle' => $this->libelle_ae,
                    'montant' => $this->montant_ae,
                    'id_user' => $this->id_utilisateur,
                    'num_caisse' => $this->num_caisse
                ];
            }
            //date_ae - not empty
            else {
                $sql = "INSERT INTO autre_entree (num_ae, libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse) VALUES (:num_ae, :libelle, :date_ae, :montant, :id_user, :num_caisse) ";
                $paramsQuery = [
                    'num_ae' => $this->num_ae,
                    'libelle' => $this->libelle_ae,
                    'date_ae' => $this->date_ae,
                    'montant' => $this->montant_ae,
                    'id_user' => $this->id_utilisateur,
                    'num_caisse' => $this->num_caisse
                ];
            }
        }

        try {

            //num_ae not ampty - is exist ?
            if ($this->num_ae !== '') {
                $response = self::isNumAeExist($this->num_ae, null);
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
                //found
                if ($response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.duplicate.entree_num_ae', ['field' => $this->num_ae])
                    ];

                    return $response;
                }
            }

            //add autre entree
            $response = self::executeQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //num_ae - not empty
            if ($this->num_ae !== '') {
                //add num_ae
                $this->id_ae = $response['last_inserted'];
                $this->num_ae = strtoupper("A" . date('Ym') . '-' . $this->id_ae);
                $response = self::executeQuery("UPDATE autre_entree SET num_ae = :num_ae WHERE id_ae = :id_ae ", [
                    'num_ae' => $this->num_ae,
                    'id_ae' => $this->id_ae
                ]);
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.entree_createAutreEntree')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_createAutreEntree',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }


    //====================== PRIVATE FUNCTION ===========================s

    //is num_ae exist ?
    private static function isNumAeExist($num_ae, $exclude = null)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        $sql = "SELECT num_ae FROM autre_entree WHERE num_ae = :num_ae ";
        $paramsQuery = ['num_ae' => $num_ae];

        if ($exclude) {
            $sql .= "AND num_ae != :exclude ";
            $paramsQuery['exclude'] = $exclude;
        }

        //exclude
        if ($exclude) {
            $sql .= "AND id_ae != :exclude ";
        }

        try {

            $response = self::selectQuery($sql, $paramsQuery);

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

            $response = [
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
                    'errors.catch.entree_isNumAeExist',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
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
