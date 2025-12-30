<?php

//CLASS - produit controller
class ProduitController extends Controller
{

    //============================ PAGES ==============================

    //page - index
    public function index()
    {
        //redirect to page produit
        header('Location: ' . SITE_URL . '/produit/page_produit');
        return;
    }

    //page - produit dashboard
    public function pageProduit()
    {
        //is loged in ?
        $is_loged_in = Auth::isLogedIn();
        //loged
        if ($is_loged_in->getLoged()) {

            //get currency_units
            $currency_units = '';
            try {
                $config = json_decode(file_get_contents(PUBLIC_PATH . '/config/config.json'), true);

                //currency_units not found
                if (!isset($config['currency_units'])) {
                    //redirect to error page 
                    header('Location:' . SITE_URL . '/errors?messages=' . __('errors.not_found.json', ['field' => 'currency_units']));

                    return;
                }

                $currency_units = $config['currency_units'];
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()
                ];

                //redirect to error page
                header('Location: ' . SITE_URL . '/errors?messages=' . $response['message']);

                return;
            }

            $_SESSION['menu'] = 'produit';
            $this->render('produit_dashboard', [
                'title' => 'Faktiora - ' . __('forms.titles.produit_dashboard'),
                'role' => $is_loged_in->getRole(),
                'currency_units' => $currency_units
            ]);
            return;
        }
        //not loged
        else {
            //redirect to login page
            header('Location: ' . SITE_URL . '/auth');
            return;
        }
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
            echo json_encode($response);
            return;
        }
        //redirect to produit index
        else {
            //redirect to produit index
            header('Location: ' . SITE_URL . '/produit');
            return;
        }
    }

    //action - filter produit
    public function filterProduit()
    {
        header('Content-Type: application/json');
        //is loged in
        $is_loged_in = Auth::isLogedIn();
        $response = null;

        //not loged
        if (!$is_loged_in->getLoged()) {
            //redirect to login page
            header('Location: ' . SITE_URL . '/login');
            return;
        }

        //defaults
        $arrange_by_default = ['id', 'libelle', 'nb_stock', 'total', 'quantite'];
        $order_default = ['ASC', 'DESC'];
        $status_default = ['active', 'deleted'];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'all'));
        $status = !in_array($status, $status_default, true) ? 'all' : $status;

        //arrange_by
        $arrange_by = strtolower(trim($_GET['arrange_by'] ?? 'id'));
        $arrange_by = in_array($arrange_by, $arrange_by_default, true) ? $arrange_by : 'id';
        switch ($arrange_by) {
            case 'id':
                $arrange_by = 'p.id_produit';
                break;
            case 'libelle':
                $arrange_by = 'p.libelle_produit';
                break;
            case 'nb_stock':
                $arrange_by = 'p.nb_stock';
                break;
            case 'quantite':
                $arrange_by = 'quantite_produit';
                break;
            case 'total':
                $arrange_by = 'total_produit';
                break;
        }
        //order
        $order = strtoupper(trim($_GET['order'] ?? 'ASC'));
        $order = in_array($order, $order_default, true) ? $order : 'ASC';

        //search_produit
        $search_produit = trim($_GET['search_produit'] ?? '');

        $params = [
            'status' => $status,
            'arrange_by' => $arrange_by,
            'order' => $order,
            'search_produit' => $search_produit
        ];

        //filter produit
        $response = ProduitRepositorie::filterProduit($params);

        echo json_encode($response);
        return;
    }

    //action - list all produit
    public function listAllProduit()
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

        //list produit all
        $response = Produit::listAllProduit();

        echo json_encode($response);
        return;
    }

    //action - update produit
    public function updateProduit()
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

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //libelle_produit - empty
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

                //is id_produit exist ?
                $response = Produit::findById($json['id_produit']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.produit_id_produit', ['field' => $json['id_produit']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update produit 
                $produit_model = new Produit();
                $produit_model
                    ->setIdProduit($json['id_produit'])
                    ->setLibelleProduit($json['libelle_produit'])
                    ->setPrixProduit($json['prix_produit'])
                    ->setNbStock($json['nb_stock']);
                $response = $produit_model->updateProduit();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.produit_updateProduit',
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
        //redirect to produit index
        else {
            header('Location: ' . SITE_URL . '/produit');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete all produit
    public function deleteAllProduit()
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

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to produit index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $ids_produit = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['ids_produit']));

            //ids_produit - empty
            if (count($ids_produit) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.produit_ids_produit_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all produit
                $response = Produit::deleteAllProduit($ids_produit);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.produit_deleteAllProduit',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all produit
    public function permanentDeleteAllProduit()
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

        //role - not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to produit index
            header("Location: " . SITE_URL . '/entree');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $ids_produit = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['ids_produit']));

            //ids_produit - empty
            if (count($ids_produit) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.produit_ids_produit_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //permanent delete all produit
                $response = Produit::permanentDeleteAllProduit($ids_produit);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.produit_deleteAllProduit',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }

        echo json_encode($response);
        return;
    }
}
