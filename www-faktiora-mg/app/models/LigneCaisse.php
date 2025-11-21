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

    //static - filter ligne caisse
    public static function filterLigneCaisse($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $sql = "SELECT * FROM ligne_caisse WHERE num_caisse = :num_caisse AND id_utilisateur LIKE :id_utilisateur ";
        $paramsQuery = [
            'num_caisse' => $params['num_caisse'],
            'id_utilisateur' => "%" . $params['id_utilisateur'] . "%"
        ];

        //from - empty
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

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if (count($response['data']) === 0) {
                $response['message'] = __('messages.success.caisse_filterLigneCaisse_0');
            }
            //1
            elseif (count($response['data']) === 1) {
                $response['message'] = __('messages.success.caisse_filterLigneCaisse_1');
            }
            //plur
            else {
                $response['message'] = __(
                    'messages.success.caisse_filterLigneCaisse_plur',
                    ['field' => count($response['data'])]
                );
            }

            $response = [
                'message_type' => 'success',
                'message' => $response['message'],
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
                    'errors.catch.caisse_filterLigneCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

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

    //free caisse
    public static function freeCaisse($nums_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
        $sql = "UPDATE ligne_caisse SET date_fin = NOW() WHERE num_caisse IN ({$placeholders}) AND date_fin IS NULL ";

        try {

            //add date fin
            $response = self::executeQuery($sql, $nums_caisse);

            //error
            if ($response['message_type'] === 'error') {
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


    //static - find caisse
    public static function findCaisse($id_utilisateur)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {
            $response = self::selectQuery("SELECT id_utilisateur, num_caisse FROM ligne_caisse WHERE id_utilisateur = :id AND date_fin IS NULL LIMIT 1 ", ['id' => $id_utilisateur]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response['found'] = false;
            }
            //found
            else {
                $response['found'] = true;

                $ligne_caisse = new LigneCaisse();
                $ligne_caisse->setIdUtilsateur($response['data'][0]['id_utilisateur'])
                    ->setNumCaisse($response['data'][0]['num_caisse']);

                $response['model'] = $ligne_caisse;
                $response['data'] = [];

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response = [
                'message_type' => 'error',
                'message' => __('errors.catch.ligne_caisse_findCaisse', ['field' => $e->getMessage()])
            ];
            return $response;
        }

        return $response;
    }
}
