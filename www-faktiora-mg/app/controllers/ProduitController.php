<?php

//CLASS - produit controller
class ProduitController extends Controller
{

    //============================ PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index produit";
    }

    //page - produit dashboard
    public function pageProduit()
    {
        $this->render('produit_dashboard', ['title' => "Gestion des produiuts"]);
    }

    //============================ ACTIONS ==============================

    //action - create produit
    public function createProduit()
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

        //role not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to produit index
            header('Location: ' . SITE_URL . '/produit');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //libelle_produit - empty*
            if ($json['libelle_produit'] === '') {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //ibelle_produit - invalid
            if (strlen($json['libelle_produit']) > 100) {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //prix_produit - empty
            if ($json['prix_produit'] === '') {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.empty.prix')
                ];

                echo json_encode($response);
                return;
            }
            //prix_produit - invalid
            $prix_produit = filter_var($json['prix_produit'], FILTER_VALIDATE_FLOAT);
            if ($prix_produit === false || $prix_produit <= 0) {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.invalids.prix')
                ];

                echo json_encode($response);
                return;
            }

            //nb_stock - invalid
            $nb_stock = filter_var($json['nb_stock'], FILTER_VALIDATE_INT);
            if ($nb_stock === false || $nb_stock < 0) {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.invalids.nb_stock')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //create produit
                $produit_model = new Produit();
                $produit_model
                    ->setLibelleProduit($json['libelle_produit'])
                    ->setPrixProduit($json['prix_produit'])
                    ->setNbStock($json['nb_stock']);
                $response = $produit_model->createProduit();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.produit_createProduit',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            // echo json_encode($json);
            echo json_encode("test");
            return;
        }
        //redirect to produit index
        else {
            //redirect to produit index
            header('Location: ' . SITE_URL . '/produit');
            return;
        }
    }
}
