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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //num_ae
            $num_ae = "";
            //admin num_ae not empty - invalid
            if ($is_loged_in->getRole() === 'admin' && $json['num_ae'] !== '' && strlen($json['num_ae']) > 20) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.entree_num_ae')
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
            if (strlen($json['libelle_ae']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //date_ae
            $date_ae = "";
            //role admin date_ae - empty
            if ($is_loged_in->getRole() === 'admin' && $json['date_ae'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //role admin date_ae - invalid
            if ($is_loged_in->getRole() === 'admin') {
                $date_ae = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ae']);
                if ($date_ae === false) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_ae']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                $date = new DateTime();
                //date_ae - futur
                if ($date_ae > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_ae = $date_ae->format('Y-m-d H:i:s');
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
            $montant_ae = filter_var($json['montant_ae'], FILTER_VALIDATE_FLOAT);
            if ($montant_ae === false || $montant_ae < 1) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.montant')
                ];

                echo json_encode($response);
                return;
            }

            //id_utilisateur
            $id_utilisateur = "";
            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                $id_utilisateur = $json['id_utilisateur'];
                //id_utilisateur - empty
                if ($id_utilisateur === "") {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.user_id')
                    ];

                    echo json_encode($response);
                    return;
                }
            }
            //role caissier
            else {
                $id_utilisateur = $is_loged_in->getIdUtilisateur();
            }

            try {

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
                    $num_caisse = $json['num_caisse'];
                }
                //role caissier
                else {
                    //is user hash caisse ?
                    $response = LigneCaisse::findCaisse($id_utilisateur);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.user_caisse')
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $num_caisse = $response['model']->getNumCaisse();
                }

                //role admin - is user exist ?
                if ($is_loged_in->getRole() === 'admin') {
                    $response = User::findById($id_utilisateur);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.user_id', ['field' => $id_utilisateur])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //role not caissier
                    if ($response['model']->getRole() !== 'caissier') {
                    }
                }

                // //create autre entree
                // $autre_entree_model = new AutreEntree();
                // $autre_entree_model->setIdAe($json['id_ae'])
                //     ->setLibelleAe($json['libelle_ae'])
                //     ->setDateAe($json['date_ae'])
                //     ->setIdUtilsateur($response['model']->getIdUtilisateur())
                //     ->setNumCaisse($response['model']->getNumCaisse());
                // $response = $autre_entree_model->createAutreEntree();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.caisse_createAutreEntree',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
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
