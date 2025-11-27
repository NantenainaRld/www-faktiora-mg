<?php

//CLASS - article controller
class ArticleController extends Controller
{

    //============================ PAGES ==============================

    //page - index
    public function index()
    {
        echo "page index article";
    }

    //page - article dashboard
    public function pageArticle()
    {
        $this->render('article_dashboard', ['title' => "Gestion des articles"]);
    }

    //=========================== ACTIONS =============================

    //action - create article
    public function createArticle()
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
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //libelle_produit - empty
            if ($json['libelle_article'] === '') {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //ibelle_produit - invalid
            if (strlen($json['libelle_article']) > 100) {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }


            try {

                //create article
                $article_model = new Article();
                $article_model->setLibelleArticle($json['libelle_article']);
                $response = $article_model->createArticle();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.article_createArticle',
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
        //redirect to article index
        else {
            header('Location: ' . SITE_URL . '/article');
            return;
        }
    }

    //action - filter article
    public function filterArticle()
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
        $order_by_default = ['libelle', 'id', 'total', 'quantite'];
        $arrange_default = ['ASC', 'DESC'];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'active'));
        $status = ($status === 'deleted') ? 'deleted' : 'active';
        if ($is_loged_in->getRole() === 'caissier') {
            $status = 'active';
        }
        switch ($status) {
            case 'active':
                $status = 'actif';
                break;
            case 'deleted':
                $status = 'supprimé';
                break;
        }

        //order_by
        $order_by = strtolower(trim($_GET['order_by'] ?? 'id'));
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'id';
        switch ($order_by) {
            case 'libelle':
                $order_by = 'a.libelle_article';
                break;
            case 'id':
                $order_by = 'a.id_article';
                break;
            case 'total':
                $order_by = 'total_article';
                break;
            case 'quantite':
                $order_by = 'quantite_article';
                break;
        }
        //arrange
        $arrange = strtoupper(trim($_GET['arrange'] ?? 'ASC'));
        $arrange = in_array($arrange, $arrange_default, true) ? $arrange : 'ASC';

        //search_article
        $search_article = "%" . trim($_GET['search_article'] ?? '') . "%";

        $params = [
            'status' => $status,
            'order_by' => $order_by,
            'arrange' => $arrange,
            'search_article' => $search_article
        ];

        //filter article
        $response = ArticleRepositorie::filterArticle($params);

        echo json_encode($response);
        return;
    }
}
