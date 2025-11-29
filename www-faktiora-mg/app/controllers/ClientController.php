<?php

//CLASS - client controller
class ClientController extends Controller
{

    //============================  PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index client";
    }

    //page - client dashboard
    public function pageClient()
    {
        $this->render('client_dashboard', ['title' => "Gestion des clients"]);
    }

    //=========================== ACTIONS ================================

    //action - create client
    public function createClient()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged ?
        $is_loged_in = Auth::isLogedIn();

        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header('Location: ' . SITE_URL . '/auth');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //nom_client - empty
            if ($json['nom_client'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }
            //nom_client - invalid
            if (strlen($json['nom_client']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.nom')
                ];

                echo json_encode($response);
                return;
            }

            //prenoms_client not empty - invalid
            if ($json['prenoms_client'] !== '' && strlen($json['prenoms_client']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.prenoms')
                ];

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            $json['sexe_client'] = strtolower($json['sexe_client']);
            if (!in_array($json['sexe_client'], ['masculin', 'féminin'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sexe', ['field' => $json['sexe_client']])
                ];

                echo json_encode($response);
                return;
            }

            //telephone not empty - invalid
            if ($json['telephone'] !== '' && !preg_match("/^[+]?[0-9\s\(\)]{10,20}$/", $json['telephone'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.telephone', ['field' => $json['telephone']])
                ];

                echo json_encode($response);
                return;
            }

            //adresse not empty - invalid
            if ($json['adresse'] !== '' && strlen($json['adresse']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.nom')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //create client
                $client_model = new Client();
                $client_model
                    ->setNomClient($json['nom_client'])
                    ->setPrenomsClient($json['prenoms_client'])
                    ->setSexeClient($json['sexe_client'])
                    ->setTelephone($json['telephone'])
                    ->setAdresse($json['adresse']);
                $response = $client_model->createClient();

                echo json_encode($response);
                return;
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

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to client index
        else {
            header('Location: ' . SITE_URL . '/client');
            return;
        }
        echo json_encode($response);
        return;
    }
}
