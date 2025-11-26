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
}
