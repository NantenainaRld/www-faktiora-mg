<?php

//CLASS - sortie controller
class SortieController extends Controller
{

    //============================ PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index sortie";
    }

    //page - sortie dashboard
    public function pageSortie()
    {
        $this->render('sortie_dashboard', ['title' => "Gestion des sorties"]);
    }

    //=========================== ACTIONS =============================

    //action - create sortie
    public function createSortie()
    {
        header('Content-Type: application/json');
        //is loged in
        $is_loged_in = Auth::isLogedIn();
        $response = null;

        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login
            header('Location: ' . SITE_URL . '/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            // //trim
            // $json = array_map(fn($x) => trim($x), $json);

            // //libelle_produit - empty
            // if ($json['libelle_article'] === '') {
            //     $response = [
            //         'message_type' => 'success',
            //         'message' => __('messages.empty.libelle')
            //     ];

            //     echo json_encode($response);
            //     return;
            // }
            // //ibelle_produit - invalid
            // if (strlen($json['libelle_article']) > 100) {
            //     $response = [
            //         'message_type' => 'success',
            //         'message' => __('messages.invalids.libelle')
            //     ];

            //     echo json_encode($response);
            //     return;
            // }


            try {

                //create sortie
                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.sortie_createSortie',
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
        //redirect to sortie index
        else {
            header('Location: ' . SITE_URL . '/sortie');
            return;
        }
    }
}
