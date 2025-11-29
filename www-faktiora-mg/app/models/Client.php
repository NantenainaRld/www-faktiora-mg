<?php

//CLASS - client
class Client extends Database
{

    private $id_client = "";
    private $nom_client = "";
    private $prenoms_client = "";
    private $sexe_client = 'masculin';
    private $telephone = "";
    private $adresse = "";
    private $etat_client = 'actif';

    public function __construct()
    {
        parent::__construct();
    }

    //========================== SETTERS =========================

    //setter - id_client
    public function setIdClient($id_client)
    {
        $this->id_client = $id_client;
        return $this;
    }

    //setter - nom_client
    public function setNomClient($nom_client)
    {
        $this->nom_client = strtoupper($nom_client);
        return $this;
    }

    //setter - prenoms_client
    public function setPrenomsClient($prenoms_client)
    {
        $this->prenoms_client = ucwords($prenoms_client);
        return $this;
    }

    //setter - sexe_client
    public function setSexeClient($sexe_client)
    {
        $this->sexe_client = strtolower($sexe_client);
        return $this;
    }

    //setter - telephone
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    //setter - adresse
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    //setter - etat_client
    public function setEtatClient($etat_client)
    {
        $this->etat_client = $etat_client;
        return $this;
    }

    //========================= PUBLIC FUNCTION ====================

    //create client
    public function createClient()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::executeQuery("INSERT INTO client (nom_client, prenoms_client, sexe_client, telephone, adresse) VALUES (:nom, :prenoms, :sexe, :telephone, :adresse) ", [
                'nom' => $this->nom_client,
                'prenoms' => $this->prenoms_client,
                'sexe' => $this->sexe_client,
                'telephone' => $this->telephone,
                'adresse' => $this->adresse
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.client_createClient')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.client_createClient',
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
