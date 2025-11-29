<?php

//CLASS - ligne_facture
class LigneFacture extends Database
{
    private $id_lf = null;
    private $prix = 1;
    private $quantie_produit = 1;
    private $id_facture = "";
    private $id_produit = "";

    public function __construct()
    {
        parent::__construct();
    }

    //===================== SETTERS ============================


    //setter - prix
    public function setPrix($prix)
    {
        $this->prix = $prix;
        return $this;
    }

    //setter - quantite produit
    public function setQuantiteProduit($quantie_produit)
    {
        $this->quantie_produit = $quantie_produit;
        return $this;
    }

    //setter - id_facture
    public function setIdFacture($id_facture)
    {
        $this->id_facture = $id_facture;
        return $this;
    }

    //setter - id_produit
    public function setIdProduit($id_produit)
    {
        $this->id_produit = $id_produit;
        return $this;
    }

    //====================== PUBLIC FUNCTION ========================

    //create ligne_facture
    public function createLigneFacture()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::executeQuery(
                "INSERT INTO ligne_facture (prix, quantite_produit, id_facture, id_produit) VALUES (:prix, :quantite, :id_facture, :id_produit) ",
                [
                    'prix' => $this->prix,
                    'quantite' => $this->quantie_produit,
                    'id_facture' => $this->id_facture,
                    'id_produit' => $this->id_produit
                ]
            );

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.entree_createLigneFacture')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_createLigneFacture',
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
