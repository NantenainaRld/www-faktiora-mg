<?php

//CLASS - ligne caisse
class LigneCaisse extends Database
{
    private $id_lc = null;
    private $date_debut = null;
    private $date_fin = null;
    private $id_utilisateur = "";
    private $num_caisse = null;

    public function __construct()
    {
        parent::__construct();
    }


    //==================== SETTERS ========================

    //setter - date_debut
    public function setDateDebut($date_debut)
    {
        $this->date_debut = $date_debut;
        return $this;
    }
    //setter - date_fin
    public function setDateFin($date_fin)
    {
        $this->date_fin = $date_fin;
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

    //======================== GETTERS ======================

    //getter - id_lc
    public function getIdLc()
    {
        return $this->id_lc;
    }
    //setter - date_debut
    public function getDateDebut()
    {
        return $this->date_debut;
    }
    //getter - date_fin
    public function getDateFin()
    {
        return $this->date_fin;
    }
    //getter - id_utilisateur
    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }
    //getter - num_caisse
    public function getNumCaisse()
    {
        return $this->num_caisse;
    }

    //======================== PUBLIC FUNCTION =====================

    //create ligne caisse
    public function createLigneCaisse()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $response = self::executeQuery("INSERT INTO ligne_caisse (date_debut, id_utilisateur, num_caisse) VALUES (NOW(), :id, :num) ", [
                'id' => $this->id_utilisateur,
                'num' => $this->num_caisse
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.ligne_caisse_createLigneCaisse', ['field' => $e->getMessage()])
            ];

            return $response;
        }

        return $response;
    }

    //quit caisse
    public function quitCaisse()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //add date fin
            $response = self::executeQuery("UPDATE ligne_caisse SET date_fin = NOW() WHERE id_utilisateur = :id AND date_fin IS NULL ", ['id' => $this->id_utilisateur]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

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

    //static - filter ligne caisse
    public static function filterLigneCaisse($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $sql = "SELECT * FROM ligne_caisse WHERE num_caisse = :num_caisse AND id_utilisateur LIKE :id_utilisateur ";
        $paramsQuery = [
            'num_caisse' => $params['num_caisse'],
            'id_utilisateur' => "%" . $params['id_utilisateur'] . "%"
        ];

        //from  - empty
        if ($params['from'] === '') {
            //to - not empty
            if ($params['to'] !== '') {
                $sql .= "AND date_debut <= :to ";
                $paramsQuery['to'] = $params['to'];
            }
        }
        //from - not empty
        else {
            //to - empty
            if ($params['to'] === '') {
                $sql .= "AND date_debut >= :from ";
                $paramsQuery['from'] = $params['from'];
            }
            //to - not empty
            else {
                $sql .= "AND date_debut BETWEEN :from AND :to ";
                $paramsQuery['from'] = $params['from'];
                $paramsQuery['to'] = $params['to'];
            }
        }

        //group and order by 
        $sql .= "GROUP BY id_lc ORDER BY id_lc ASC ";

        try {
            $response = self::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.filter_ligne_caisse', ['field' =>
            $e->getMessage()]);

            return $response;
        }

        return $response;
    }
}
