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

    //======================== GETTERS ==========================

    //getter - etat_client
    public function getEtatClient()
    {
        return $this->etat_client;
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

    //static - list all client
    public static function listAllClient()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::selectQuery("SELECT id_client, nom_client, prenoms_client FROM client WHERE etat_client != 'supprimé' ");

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
                    'errors.catch.client_listAllClient',
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
    public static function findById($id_client)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM client WHERE id_client = :id ", ['id' => $id_client]);

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
                $client_model = new Client();
                $client_model
                    ->setIdClient($response['data'][0]['id_client'])
                    ->setNomClient($response['data'][0]['nom_client'])
                    ->setPrenomsClient($response['data'][0]['prenoms_client'])
                    ->setSexeClient($response['data'][0]['sexe_client'])
                    ->setSexeClient($response['data'][0]['telephone'])
                    ->setAdresse($response['data'][0]['adresse'])
                    ->setEtatClient($response['data'][0]['etat_client']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $client_model
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
                    'errors.catch.client_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //update client
    public function updateClient()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::executeQuery("UPDATE client SET nom_client = :nom, prenoms_client = :prenoms, sexe_client = :sexe, telephone = :telephone, adresse = :adresse WHERE id_client = :id ", [
                'nom' => $this->nom_client,
                'prenoms' => $this->prenoms_client,
                'sexe' => $this->sexe_client,
                'telephone' => $this->telephone,
                'adresse' => $this->adresse,
                'id' => $this->id_client
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.client_updateClient', ['field' => $this->id_client])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.client_updateClient',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - delete all client
    public static function deleteAllClient($ids_client)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($ids_client), '?'));
        $sql = "UPDATE client SET etat_client = 'supprimé' WHERE id_client IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $ids_client);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.client_deleteAllClient_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.client_deleteAllClient_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.client_deleteAllClient_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.client_deleteAllClient',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - permanent delete all client
    public static function permanentDeleteAllClient($ids_client)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($ids_client), '?'));
        $sql = "DELETE FROM client WHERE id_client IN ({$placeholders}) AND etat_client = 'supprimé' ";

        try {

            $response = parent::executeQuery($sql, $ids_client);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.client_deleteAllClient_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.client_deleteAllClient_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.client_deleteAllClient_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.client_deleteAllClient',
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
