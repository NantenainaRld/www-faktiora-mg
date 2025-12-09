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

    //setter - etat_ae
    public function setEtatAe($etat_ae)
    {
        $this->etat_ae = $etat_ae;
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

    //====================== GETTERS ===============================

    //getter - id_ae
    public function getIdAe()
    {
        return $this->id_ae;
    }

    //getter - num_ae
    public function getNumAe()
    {
        return $this->num_ae;
    }

    //getter - libelle_ae
    public function getLibelleAe()
    {
        return $this->libelle_ae;
    }

    //getter - date_ae
    public function getDateAe()
    {
        return $this->date_ae;
    }

    //getter - montant_ae
    public function getMontantAe()
    {
        return $this->montant_ae;
    }

    //getter - etat_ae
    public function getEtatAe()
    {
        return $this->etat_ae;
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

    //====================== PUBLIC FUNCTION =======================

    //create autre entree
    public function createAutreEntree()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $sql = "";
        $paramsQuery = [];

        //date_ae - empty
        if ($this->date_ae === '') {
            $sql = "INSERT INTO autre_entree (libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse) VALUES (:libelle, NOW(), :montant, :id_user, :num_caisse) ";
            $paramsQuery = [
                'libelle' => $this->libelle_ae,
                'montant' => $this->montant_ae,
                'id_user' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse
            ];
            //date_ae - not empty
        } else {
            $sql = "INSERT INTO autre_entree (libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse) VALUES (:libelle, :date_ae, :montant, :id_user, :num_caisse) ";
            $paramsQuery = [
                'libelle' => $this->libelle_ae,
                'date_ae' => $this->date_ae,
                'montant' => $this->montant_ae,
                'id_user' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse
            ];
        }

        try {

            //add autre entree
            $response = parent::executeQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //add num_ae
            $this->id_ae = $response['last_inserted'];
            $this->num_ae = strtoupper("A" . date('Ym') . '-' . $this->id_ae);
            $response = parent::executeQuery("UPDATE autre_entree SET num_ae = :num_ae WHERE id_ae = :id_ae ", [
                'num_ae' => $this->num_ae,
                'id_ae' => $this->id_ae
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //update caisse - solde
            $response = Caisse::updateSolde($this->num_caisse, $this->montant_ae, 'increase');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
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

    //static - filter autre entree
    public static function filterAutreEntree($params)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        $sql = "SELECT num_ae, libelle_ae, date_ae, montant_ae, id_utilisateur, num_caisse FROM autre_entree ";
        $paramsQuery = [];

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status - active
        if ($params['status'] === 'active') {
            $sql .= "AND etat_ae = 'actif' ";
        }
        //status - deleted
        else {
            $sql .= "AND etat_ae = 'supprimé' ";
        }

        //num_caisse - !all
        if ($params['num_caisse'] !== 'all') {
            $sql .= "AND num_caisse = :num_caisse ";
            $paramsQuery['num_caisse'] = $params['num_caisse'];
        }

        //id_user - !all
        if ($params['id_user'] !== 'all') {
            $sql .= "AND id_utilisateur = :id_user ";
            $paramsQuery['id_user'] = $params['id_user'];
        }

        //from - empty
        if ($params['from'] === '') {
            //to - not empty
            if ($params['to'] !== '') {
                $sql .= "AND DATE(date_ae) <= :to ";
                $paramsQuery['to'] = $params['to'];
            }
        }
        //from - not empty
        else {
            //to - empty
            if ($params['to'] === '') {
                $sql .= "AND DATE(date_ae) >= :from ";
                $paramsQuery['from'] = $params['from'];
            }
            //to - not empty
            else {
                $sql .= "AND DATE(date_ae) BETWEEN :from AND :to ";
                $paramsQuery['from'] = $params['from'];
                $paramsQuery['to'] = $params['to'];
            }
        }

        //search_ae
        if ($params['search_ae'] !== '') {
            $sql .= "AND (num_ae LIKE :search OR libelle_ae LIKE :search) ";
            $paramsQuery['search'] = "%" . $params['search_ae'] . "%";
        }

        //group by and order by
        $sql .= "GROUP BY id_ae ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //count total
            $total = array_sum(array_column($response['data'], 'montant_ae'));

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_ae' => count($response['data']),
                'total' => $total
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_filterAutreEntree',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //list all autre entree
    public static function listAllAutreEntree()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::selectQuery("SELECT num_ae , libelle_ae, montant_ae FROM autre_entree WHERE etat_ae != 'supprimé' AND num_ae IS NOT NULL");

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //total
            $total = array_sum(array_column($response['data'], 'montant_ae'));

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_ae' => count($response['data']),
                'total_ae' => $total,
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_listAllAutreEntree',
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
    public static function findById($num_ae)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM autre_entree WHERE num_ae = :num_ae ", ['num_ae' => $num_ae]);

            //error
            if ($response['message_type'] == 'error') {
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
                $autre_entree_model = new AutreEntree();
                $autre_entree_model
                    ->setIdAe($response['data'][0]['id_ae'])
                    ->setNumAe($response['data'][0]['num_ae'])
                    ->setLibelleAe($response['data'][0]['libelle_ae'])
                    ->setDateAe($response['data'][0]['date_ae'])
                    ->setMontantAe($response['data'][0]['montant_ae'])
                    ->setEtatAe($response['data'][0]['etat_ae'])
                    ->setIdUtilsateur($response['data'][0]['id_utilisateur'])
                    ->setNumCaisse($response['data'][0]['num_caisse']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $autre_entree_model
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
                    'errors.catch.entree_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //update autre_entree
    public function updateAutreEntree($role)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $sql = "";
        $paramsQuery = [];

        //role - caissier
        if ($role === 'caissier') {
            $sql = "UPDATE autre_entree SET libelle_ae = :libelle WHERE num_ae = :num_ae";
            $paramsQuery['libelle'] = $this->libelle_ae;
            $paramsQuery['num_ae'] = $this->num_ae;
        }
        //role - admin
        else {
            $sql = "UPDATE autre_entree SET libelle_ae = :libelle, date_ae = :date, id_utilisateur = :id_user, num_caisse = :num_caisse WHERE num_ae = :num_ae";
            $paramsQuery['libelle'] = $this->libelle_ae;
            $paramsQuery['date'] = $this->date_ae;
            $paramsQuery['id_user'] = $this->id_utilisateur;
            $paramsQuery['num_caisse'] = $this->num_caisse;
            $paramsQuery['num_ae'] = $this->num_ae;
        }

        try {

            $response = parent::executeQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __(
                    'messages.success.entree_updateAutreEntree',
                    ['field' => $this->num_ae]
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
                    'errors.catch.entree_updateAutreEntree',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - delete all autre entree
    public static function deleteAllAutreEntree($nums_ae)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_ae), '?'));
        $sql = "UPDATE autre_entree SET etat_ae = 'supprimé' WHERE num_ae IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $nums_ae);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.entree_deleteAllAutreEntree_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.entree_deleteAllAutreEntree_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.entree_deleteAllAutreEntree_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.entree_deleteAllAutreEntree',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - permanent delete all autre entree
    public static function permanentDeleteAllAutreEntree($nums_ae)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_ae), '?'));
        $sql = "DELETE FROM autre_entree WHERE num_ae IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $nums_ae);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.entree_deleteAllAutreEntree_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.entree_deleteAllAutreEntree_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.entree_deleteAllAutreEntree_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.entree_deleteAllAutreEntree',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - connection autre entree
    public static function connectionAutreEntree($num = "")
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $num = strtoupper(trim($num));
        $libelle = "correction/" . $num;
        $libelle = "%" . $libelle . "%";

        try {

            $response = parent::selectQuery(
                "SELECT num_ae, libelle_ae, date_ae, montant_ae FROM autre_entree WHERE (libelle_ae LIKE :libelle ) AND etat_ae !='supprimé' ",
                ['libelle' => $libelle]
            );

            //error
            if ($response['message_type'] === 'error') {
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
                    'errors.catch.entree_connectionAutreEntree',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //list connection autre entree
    public function listConnectionAutreEntree()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //connection autre entree
            $response = self::connectionAutreEntree($this->num_ae);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            $autre_entree = $response['data'];

            //connection sortie
            $response = SortieRepositorie::connectionSortie($this->num_ae);
            //error
            if ($response['message_type'] == 'error') {
                return $response;
            }
            $sortie = $response['data'];

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'autre_entree' => $autre_entree,
                'sortie' => $sortie
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_listConnectionAutreEntree',
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

    //static - is num_ae exist ?
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
}
