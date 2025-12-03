<?php

//CLASS - facture
class Facture extends Database
{
    private $id_facture = "";
    private $num_facture = "";
    private $date_facture = "";
    private $etat_facture = 'actif';
    private $id_utilisateur = "";
    private $num_caisse = "";
    private $id_client = "";

    public function __construct()
    {
        parent::__construct();
    }

    //============================== SETTERS =========================


    //setter - id_facture
    public function setIdFacture($id_facture)
    {
        $this->id_facture = $id_facture;
        return $this;
    }

    //setter - num_facture
    public function setNumFacture($num_facture)
    {
        $this->num_facture = $num_facture;
        return $this;
    }

    //setter - date_facture
    public function setDateFacture($date_facture)
    {
        $this->date_facture = $date_facture;
        return $this;
    }

    //setter - etat facture
    public function setEtatFacture($etat_facture)
    {
        $this->etat_facture = $etat_facture;
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

    //setter - id_client
    public function setIdClient($id_client)
    {
        $this->id_client = $id_client;
        return $this;
    }

    //============================== GETTERS ======================

    //setter - etat_facture
    public function getEtatFacture()
    {
        return $this->etat_facture;
    }

    //============================= GETTERS =======================

    //getter - date_facture
    public function getDateFacture()
    {
        return $this->date_facture;
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

    //getter - id_client
    public function getIdClient()
    {
        return $this->id_client;
    }

    //============================== PUBLIC FUNCTION ==================

    //create facture
    public function createFacture($produits)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            //create sortie
            $sql = "";
            $paramsQuery = [
                'id_utilisateur' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse,
                'id_client' => $this->id_client
            ];
            //date_facture - empty
            if ($this->date_facture === '') {
                $sql = "INSERT INTO facture (date_facture, id_utilisateur, num_caisse, id_client) VALUES (NOW(), :id_utilisateur, :num_caisse, :id_client) ";
            }
            //date_facture - not empty
            else {
                $sql = "INSERT INTO facture (date_facture, id_utilisateur, num_caisse, id_client) VALUES (:date_facture, :id_utilisateur, :num_caisse, :id_client) ";
                $paramsQuery['date_facture'] = $this->date_facture;
            }
            $response = parent::executeQuery($sql, $paramsQuery);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //add num_facture
            $this->id_facture = $response['last_inserted'];
            $this->num_facture = 'F' . date('Ym') . '-' . $this->id_facture;
            $response = parent::executeQuery("UPDATE facture SET num_facture = :num WHERE id_facture = :id", [
                'num' => $this->num_facture,
                'id' => $this->id_facture
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $montant_facture = 0;
            //create ligne_facture
            $ligne_facture_model  = new LigneFacture();
            foreach ($produits as $index => $produit) {
                $ligne_facture_model
                    ->setPrix($produit['prix_produit'])
                    ->setQuantiteProduit($produit['quantite_produit'])
                    ->setIdFacture($this->id_facture)
                    ->setIdProduit($produit['id_produit']);
                $ligne_facture_model->createLigneFacture();
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }

                //update nb stock
                $response = Produit::updateNbStock($produit['id_produit'], $produit['quantite_produit'], 'decrease');
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }

                $montant_facture += $produit['prix_produit'] * $produit['quantite_produit'];
            }

            //update caisse solde
            $response = Caisse::updateSolde($this->num_caisse, $montant_facture, 'increase');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.entree_createFacture')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_createFacture',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static- list all facture
    public static function listAllFacture()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        try {

            $response = parent::selectQuery("SELECT num_facture FROM facture WHERE etat_facture != 'supprimé' AND num_facture IS NOT NULL");

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
                    'errors.catch.entree_listAllFacture',
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
    public static function findById($num_facture)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM facture WHERE num_facture = :num_facture ", ['num_facture' => $num_facture]);

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
                $facture_model = new Facture();
                $facture_model
                    ->setIdFacture($response['data'][0]['id_facture'])
                    ->setNumFacture($response['data'][0]['num_facture'])
                    ->setDateFacture($response['data'][0]['date_facture'])
                    ->setEtatFacture($response['data'][0]['etat_facture'])
                    ->setIdUtilsateur($response['data'][0]['id_utilisateur'])
                    ->setNumCaisse($response['data'][0]['num_caisse'])
                    ->setIdClient($response['data'][0]['id_client']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $facture_model
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
                    'errors.catch.facture_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //list connection facture
    public function listConnectionFacture()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //ligne facture
            $response = EntreeRepositorie::ligneFacture($this->num_facture);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            $lf = $response['data'];
            $montant_facture = $response['montant_facture'];

            //connection autre entree
            $response = AutreEntree::connectionAutreEntree($this->num_facture);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            $autre_entree = $response['data'];

            //connection sortie
            $response = SortieRepositorie::connectionSortie($this->num_facture);
            //error
            if ($response['message_type'] == 'error') {
                return $response;
            }
            $sortie = $response['data'];

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'lf' => $lf,
                'montant_facture' => $montant_facture,
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
                    'errors.catch.entree_listConnectionFacture',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //update facture
    public function updateFacture()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::executeQuery("UPDATE facture SET date_facture = :date , id_utilisateur = :id_user, num_caisse = :num_caisse, id_client = :id_client WHERE num_facture = :num_facture ", [
                'date' => $this->date_facture,
                'id_user' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse,
                'id_client' => $this->id_client,
                'num_facture' => $this->num_facture
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.entree_updateFacture', ['field' => $this->num_facture])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_updateFacture',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - delete all facture
    public static function deleteAllFacture($nums_facture)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_facture), '?'));
        $sql = "UPDATE facture SET etat_facture = 'supprimé' WHERE num_facture IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $nums_facture);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.entree_deleteAllFacture_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.entree_deleteAllFacture_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.entree_deleteAllFacture_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.entree_deleteAllFacture',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - permanent delete all facture
    public static function permanentDeleteAllFacture($nums_facture)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_facture), '?'));
        $sql = "DELETE FROM facture WHERE num_facture IN ({$placeholders}) AND etat_facture = 'supprimé' ";

        try {

            $response = parent::executeQuery($sql, $nums_facture);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.entree_deleteAllFacture_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.entree_deleteAllFacture_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.entree_deleteAllFacture_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.entree_deleteAllFacture',
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
