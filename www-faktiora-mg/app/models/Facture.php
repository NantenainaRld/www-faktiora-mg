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
}
