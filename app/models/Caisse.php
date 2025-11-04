<?php

class Caisse extends Database
{

    public function __construct()
    {
        parent::__construct();
    }


    //===================== PUBLIC FUNCTION ================

    //add caisse
    public function addCaisse($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_caisse exist?
            $response = $this->isNumCaisseExist($json['num_caisse']);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //found
                if ($response['message'] === 'found') {
                    $response['message_type'] = 'invalid';
                    $response['message'] = "Cet numéro de caisse existe déjà .";
                }
                //not found
                else {
                    //add caisse
                    $response = $this->executeQuery("INSERT INTO caisse (num_caisse, solde, seuil) VALUES (:num_caisse, :solde, :seuil)", [
                        'num_caisse' => $json['num_caisse'],
                        'solde' => $json['solde'],
                        'seuil' => $json['seuil']
                    ]);

                    //error
                    if ($response['message_type'] === 'error') {
                        return $response;
                    }
                    //success
                    else {
                        $response['message'] = "Noveau caisse ajoutée avec succès .";
                    }
                }
            }
        } catch (Throwable $e) {
            $response['message'] = 'error';
            $response['message_type'] = "Error : " . $e->getMessage();
        }

        return $response;
    }

    //================= PRIVATE FUNCTION ======================

    //num_caisse exist?
    private function isNumCaisseExist($num_caisse)
    {
        $response  = [
            'message_type' => 'success',
            'message' => 'not found'
        ];
        try {
            //num_caisse
            $response = $this->selectQuery("SELECT num_caisse FROM caisse WHERE num_caisse = :num_caisse", ['num_caisse' => $num_caisse]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //found
                if (count($response['data']) >= 1) {
                    $response['message'] = 'found';
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
}
