<?php

//CLASS - produit
class Produit extends Database
{
    private $id_produit = null;
    private $libelle_produit = "";
    private $prix_produit = 1;
    private $nb_stock = 0;
    private $etat_produit = 'actif';

    public function __()
    {
        parent::__construct();
    }

    //=======================  SETTERS ======================

    //setter - id_produit
    public function setIdProduit($id_produit)
    {
        $this->id_produit = $id_produit;
        return $this;
    }

    //setter - libelle_produit
    public function setLibelleProduit($libelle_produit)
    {
        $this->libelle_produit = $libelle_produit;
        return $this;
    }

    //setter - prix_produit
    public function setPrixProduit($prix_produit)
    {
        $this->prix_produit = $prix_produit;
        return $this;
    }

    //setter - nb_stock
    public function setNbStock($nb_stock)
    {
        $this->nb_stock = $nb_stock;
        return $this;
    }

    //setter - etat_produit
    public function setEtatProduit($etat_produit)
    {
        $this->etat_produit = $etat_produit;
        return $this;
    }

    //========================== PUBLIC FUNCTION =======================

    //create produit
    public function createProduit()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $response = parent::executeQuery("INSERT INTO produit (libelle_produit, prix_produit, nb_stock) VALUES (:libelle, :prix, :nb_stock) ", [
                'libelle' => $this->libelle_produit,
                'prix' => $this->prix_produit,
                'nb_stock' => $this->nb_stock
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.produit_createProduit')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.produit_createProduit',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - list produit all
    public static function listProduitAll()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $response = parent::selectQuery("SELECT id_produit, libelle_produit, prix_produit FROM produit WHERE etat_produit != 'supprimé' ");

            //error
            if ($response['message_type'] == 'error') {
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
                    'errors.catch.produit_listAllProduit',
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
    public static function findById($id_produit)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM produit WHERE id_produit = :id ", ['id' => $id_produit]);

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
                $produit_model = new Produit();
                $produit_model
                    ->setIdProduit($response['data'][0]['id_produit'])
                    ->setLibelleProduit($response['data'][0]['libelle_produit'])
                    ->setPrixProduit($response['data'][0]['prix_produit'])
                    ->setNbStock($response['data'][0]['nb_stock'])
                    ->setEtatProduit($response['data'][0]['etat_produit']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $produit_model
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
                    'errors.catch.produit_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //update produit 
    public function updateProduit()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $response = parent::executeQuery("UPDATE produit SET libelle_produit = :libelle, prix_produit = :prix, nb_stock = :nb_stock WHERE id_produit = :id ", [
                'libelle' => $this->libelle_produit,
                'prix' => $this->prix_produit,
                'nb_stock' => $this->nb_stock,
                'id' => $this->id_produit
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.produit_updateProduit', ['field' => $this->id_produit])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.produit_updateProduit',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - delete all produit
    public static function deleteAllProduit($ids_produit)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($ids_produit), '?'));
        $sql = "UPDATE produit SET etat_produit = 'supprimé' WHERE id_produit IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $ids_produit);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.produit_deleteAllProduit_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.produit_deleteAllProduit_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.produit_deleteAllProduit_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.produit_deleteAllProduit',
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
