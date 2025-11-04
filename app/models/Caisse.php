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
        } catch (Throwable $e) {
            $response['message'] = 'error';
            $response['message_type'] = "Error : " . $e->getMessage();
        }

        return $response;
    }
}
