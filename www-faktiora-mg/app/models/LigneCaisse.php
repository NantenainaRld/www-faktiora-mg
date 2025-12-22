<?php

//CLASS - ligne caisse
class LigneCaisse extends Database
{
    private $id_lc = 0;
    private $date_debut = "";
    private $date_fin = "";
    private $id_utilisateur = "";
    private $num_caisse = "";

    public function __construct()
    {
        parent::__construct();
    }


    //==================== SETTERS ========================

    //setter - id_lc
    public function setIdLc($id_lc)
    {
        $this->id_lc = $id_lc;
        return $this;
    }

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

        $sql = "SELECT * FROM ligne_caisse WHERE num_caisse = :num_caisse AND (id_lc LIKE :id OR id_utilisateur LIKE :id) ";
        $paramsQuery = [
            'num_caisse' => $params['num_caisse'],
            'id' => "%" . $params['id'] . "%"
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

            $response = [
                'message_type' => 'success',
                'message' => 'success',
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

    //static - delete all ligne caisse
    public static function deleteAllLigneCaisse($ids_lc)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($ids_lc), '?'));
        $sql = "DELETE FROM ligne_caisse WHERE id_lc IN ({$placeholders}) AND date_fin IS NOT NULL ";

        try {

            $response = parent::executeQuery($sql, $ids_lc);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.caisse_deleteAllLigneCaisse_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.caisse_deleteAllLigneCaisse_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_deleteAllLigneCaisse_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.caisse_deleteAllLigneCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //add ligne caisse
    public function addLigneCaisse()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //is date intervall exist?
            $response = $this->isDateIntervalExist();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //exist
            if ($response['exist']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_fusion')
                ];

                return $response;
            }

            //date_debut not empty - date_fin empty
            if ($this->date_debut !== '' && $this->date_fin === '') {
                //update caisse to free
                $response = self::updateEtatCaisse($this->id_utilisateur, null, 'libre');
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
                //change caisse to occuped
                $response = self::updateEtatCaisse(null, [$this->num_caisse], 'occupé');
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }

                //close ligne caisse by id_user
                $response = self::closeLigneCaisse($this->id_utilisateur, null);
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
                //close ligne caisse by num_caisse
                $response = self::closeLigneCaisse(null, [$this->num_caisse]);
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
            }
            //create ligne caisse
            $response = $this->createLigneCaisse();

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.caisse_addLigneCaisse')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_addLigneCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - find by id
    public static function findById($id_lc)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM ligne_caisse WHERE id_lc = :id_lc", ['id_lc' => $id_lc]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => false
                ];
            }
            //found
            else {
                $ligne_caisse_model = new LigneCaisse();
                $ligne_caisse_model->id_lc = $response['data'][0]['id_lc'];
                $ligne_caisse_model->date_debut = $response['data'][0]['date_debut'];
                $ligne_caisse_model->date_fin = $response['data'][0]['date_fin'];
                $ligne_caisse_model->id_utilisateur = $response['data'][0]['id_utilisateur'];
                $ligne_caisse_model->num_caisse = $response['data'][0]['num_caisse'];

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $ligne_caisse_model
                ];

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_findByIdLc',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //occup caisse
    public function occupCaisse()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //update caisse to free
            $response = self::updateEtatCaisse($this->id_utilisateur, null, 'libre');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //update caisse to occuped
            $response = self::updateEtatCaisse(null, [$this->num_caisse], 'occupé');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //close caisse by id_user
            $response = self::closeLigneCaisse($this->id_utilisateur, null);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //close caisse by num_caisse
            $response = self::closeLigneCaisse(null, [$this->num_caisse]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //create ligne caisse
            $response = $this->createLigneCaisse();
            //error
            if ($response['message_type'] == 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.caisse_occupCaisse', ['field' => $this->num_caisse])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_occupCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
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

            //update caisse to free
            $response = self::updateEtatCaisse($this->id_utilisateur, null, 'libre');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //close caisse by id_user
            $response = self::closeLigneCaisse($this->id_utilisateur, null);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.caisse_quitCaisse')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_quitCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }
        return $response;
    }

    //free caisse
    public static function freeCaisse($nums_caisse)
    {

        try {

            //update etat caisse to free
            $response = self::updateEtatCaisse(null, $nums_caisse, 'libre');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            $row_count = $response['row_count'];

            //close ligne caisse
            $response = self::closeLigneCaisse(null, $nums_caisse);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($row_count === 0) {
                $response['message'] = __('messages.success.caisse_freeCaisse_0');
            }
            //1
            elseif ($row_count === 1) {
                $response['message'] = __('messages.success.caisse_freeCaisse_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.caisse_freeCaisse_plur', ['field' => $row_count]);
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
                    'errors.catch.caisse_freeCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }



    //update ligne caisse
    public function updateLigneCaisse()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //is date intervall exist?
            $response = $this->isDateIntervalExist($this->id_lc);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //exist
            if ($response['exist']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_fusion')
                ];

                return $response;
            }

            $sql = "";
            $paramsQuery = [];

            //date_fin - empty
            if ($this->date_fin === '') {
                $sql = "UPDATE ligne_caisse SET date_debut = :date_debut WHERE id_lc = :id_lc ";
                $paramsQuery['date_debut'] = $this->date_debut;
                $paramsQuery['id_lc'] = $this->id_lc;
            }
            //date_fin - not empty
            else {
                $sql = "UPDATE ligne_caisse SET date_debut = :date_debut, date_fin = :date_fin, id_utilisateur = :id_user, num_caisse = :num_caisse WHERE id_lc = :id_lc ";
                $paramsQuery['date_debut'] = $this->date_debut;
                $paramsQuery['date_fin'] = $this->date_fin;
                $paramsQuery['id_user'] = $this->id_utilisateur;
                $paramsQuery['num_caisse'] = $this->num_caisse;
                $paramsQuery['id_lc'] = $this->id_lc;
            }

            //update ligne caisse
            $response = self::executeQuery($sql, $paramsQuery);

            //error 
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.caisse_updateLigneCaisse', ['field' => $this->id_lc])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_updateLigneCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
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
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => false
                ];

                return $response;
            }
            //found
            else {
                $ligne_caisse = new LigneCaisse();
                $ligne_caisse
                    ->setIdUtilsateur($response['data'][0]['id_utilisateur'])
                    ->setNumCaisse($response['data'][0]['num_caisse']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $ligne_caisse
                ];

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_findCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - close ligne caisse
    public static function closeLigneCaisse($id_utilisateur = null, $nums_caisse = null)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $sql = "";
            $paramsQuery = [];

            //close by id_user - olde caisse
            if ($id_utilisateur) {
                $sql = "UPDATE ligne_caisse SET date_fin = NOW() WHERE num_caisse IN (SELECT num_caisse FROM ligne_caisse WHERE  id_utilisateur = :id_user AND date_fin IS NULL )";
                $paramsQuery['id_user'] = $id_utilisateur;
            }
            //close by num_caisse - new caisse
            else {
                $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
                $sql = "UPDATE ligne_caisse SET date_fin = NOW() WHERE num_caisse IN ({$placeholders}) AND date_fin IS NULL ";
                $paramsQuery = $nums_caisse;
            }

            $response = parent::executeQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success'
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_closeLigneCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - update etat caisse
    public static function updateEtatCaisse($id_utilisateur = null, $nums_caisse = null, $etat = 'libre')
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $sql = "";
            $paramsQuery = [];

            //change to occuped - new caisse
            if ($nums_caisse) {
                $placeholders = implode(', ', array_fill(0, count($nums_caisse), '?'));
                $sql = "UPDATE caisse SET etat_caisse = '{$etat}' WHERE num_caisse IN ({$placeholders}) AND etat_caisse != 'supprimé' ";
                $paramsQuery = $nums_caisse;
            }
            //change to free - old caisse
            else {
                $sql = "UPDATE caisse SET etat_caisse = 'libre' WHERE num_caisse IN (SELECT num_caisse FROM ligne_caisse WHERE id_utilisateur = :id_user AND date_fin IS NULL) ";
                $paramsQuery['id_user'] = $id_utilisateur;
            }

            $response = parent::executeQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'row_count' => $response['row_count']
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_updateEtatCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //========================  PRIVATE FUNCTION =================

    //create ligne caisse
    private function createLigneCaisse()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {
            $sql = "";
            $paramsQuery = [];

            //date_fin - empty
            if ($this->date_fin === '') {
                //date_debut - empty
                if ($this->date_debut === '') {
                    $sql = "INSERT INTO ligne_caisse (date_debut, id_utilisateur, num_caisse) VALUES (NOW(), :id_user, :num_caisse) ";
                    $paramsQuery['id_user'] = $this->id_utilisateur;
                    $paramsQuery['num_caisse'] = $this->num_caisse;
                }
                //date_debut - not empty
                else {
                    $sql = "INSERT INTO ligne_caisse (date_debut, id_utilisateur, num_caisse) VALUES (:date_debut, :id_user, :num_caisse) ";
                    $paramsQuery['date_debut'] = $this->date_debut;
                    $paramsQuery['id_user'] = $this->id_utilisateur;
                    $paramsQuery['num_caisse'] = $this->num_caisse;
                }
            }
            //date_fin - not empty
            else {
                $sql = "INSERT INTO ligne_caisse (date_debut, date_fin, id_utilisateur, num_caisse) VALUES (:date_debut, :date_fin, :id_user, :num_caisse) ";
                $paramsQuery['date_debut'] = $this->date_debut;
                $paramsQuery['date_fin'] = $this->date_fin;
                $paramsQuery['id_user'] = $this->id_utilisateur;
                $paramsQuery['num_caisse'] = $this->num_caisse;
            }

            $response = parent::executeQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = ['message_type' => 'success', 'message' => 'success'];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_createLigneCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //is date interval exist ?
    private function isDateIntervalExist($exclude = null)
    {
        $respone = [
            'message_type' => 'success',
            'message' => 'success',
            'exist' => false
        ];

        try {

            $sql = "SELECT id_lc FROM ligne_caisse WHERE num_caisse = :num_caisse ";
            $paramsQuery = ['num_caisse' => $this->num_caisse];

            if ($exclude) {
                $sql .= "AND id_lc != :exclude ";
                $paramsQuery['exclude'] = $exclude;
            }

            //date_fin - empty
            if ($this->date_fin === '') {
                $sql .= "AND date_fin > :date_debut ";
                $paramsQuery['date_debut'] = $this->date_debut;
            }
            //date_fin - not empty
            else {
                $sql .= "AND date_debut < :date_fin AND date_fin > :date_debut ";
                $paramsQuery['date_fin'] = $this->date_fin;
                $paramsQuery['date_debut'] = $this->date_debut;
            }

            $respone = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($respone['message_type'] === 'error') {
                return $respone;
            }

            //not exist
            if (count($respone['data']) <= 0) {
                $respone['exist'] = false;
            }
            //exist
            else {
                $respone['exist'] = true;
            }

            $respone = [
                'message_type' => 'success',
                'message' => 'success',
                'exist' => $respone['exist']
            ];

            return $respone;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_isDateIntervallExist',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $respone;
    }
}
