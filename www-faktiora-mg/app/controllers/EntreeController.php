<?php

//CLASS - entree controller
class EntreeController extends Controller
{

    //============================  PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index entree";
    }

    //page - entree dashboard
    public function pageEntree()
    {
        $this->render('entree_dashboard', ['title' => "Gestion des entrées"]);
    }

    //========================== ACTIONS ============================

    //action - create autree entree
    public function createAutreEntree()
    {
        header('Content-Type: application/json');
        $response = null;

        //loged?
        $is_loged_in = Auth::isLogedIn();
        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header("Location: " . SITE_URL . '/auth');
            return;
        }

        //role - not caissier
        if ($is_loged_in->getRole() !== 'caissier') {
            //redirect to entree index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //user has caisse ?
            $response = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }
            //user caisse - not found
            if (!$response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.user_caisse_not_found')
                ];

                echo json_encode($response);
                return;
            }

            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //id_ae not empty - invalid
            if ($json['id_ae'] !== '' && strlen($json['id_ae']) > 20) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_id_ae')
                ];

                echo json_encode($response);
                return;
            }

            //libelle_ae - empty
            if ($json['libelle_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_ae - invalid
            if (strlen(($json['libelle_ae'])) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //date_ae not empty - invalid
            if ($json['date_ae'] !== '' && DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']) === false) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $json['date_ae']])
                ];

                echo json_encode($response);
                return;
            }
            //date_ae not empty - 
            else if ($json['date_ae'] !== '') {
                $json['date_ae'] = (DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']))->format('Y-m-d H:i:s');
            }

            //montant_ae - empty
            if ($json['montant_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.montant')
                ];

                echo json_encode($response);
                return;
            }
            //montant_ae - invalid
            $json['montant_ae'] = filter_var($json['montant_ae'], FILTER_VALIDATE_FLOAT);
            if ($json['montant_ae'] === false || $json['montant_ae'] <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.montant')
                ];

                echo json_encode($response);
                return;
            }

            try {
                //create autre entree
                $autre_entree_model = new AutreEntree();
                $autre_entree_model->setIdAe($json['id_ae'])
                    ->setLibelleAe($json['libelle_ae'])
                    ->setDateAe($json['date_ae'])
                    ->setIdUtilsateur($response['model']->getIdUtilisateur())
                    ->setNumCaisse($response['model']->getNumCaisse());
                $response = $autre_entree_model->createAutreEntree();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage());

                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.catch.entree_createAutreEntree', ['field' => $e->getMessage()])
                ];

                echo json_encode($response);
                return;
            }

            // echo json_encode($json);
            echo json_encode($response);
            return;
        }

        echo json_encode($response);
        return;
    }
}
