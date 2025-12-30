<?php

//CLASS - article controller
class ArticleController extends Controller
{

    //============================ PAGES ==============================

    //page - index
    public function index()
    {
        //redirect to page article
        header('Location: ' . SITE_URL . '/article/page_article');
        return;
    }

    //page - article dashboard
    public function pageArticle()
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

            $_SESSION['menu'] = 'article';
            $this->render('article_dashboard', [
                'title' => 'Faktiora - ' . __('forms.titles.article_dashboard'),
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
            //ibelle_article - invalid
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
        $arrange_by_default = ['libelle', 'id', 'total', 'quantite'];
        $order_default = ['ASC', 'DESC'];
        $status_default = ['active', 'deleted'];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'active'));
        $status = !in_array($status, $status_default, true) ? 'all' : 'deleted';

        //arrange_by
        $arrange_by = strtolower(trim($_GET['arrange_by'] ?? 'id'));
        $arrange_by = in_array($arrange_by, $arrange_by_default, true) ? $arrange_by : 'id';
        switch ($arrange_by) {
            case 'libelle':
                $arrange_by = 'a.libelle_article';
                break;
            case 'id':
                $arrange_by = 'a.id_article';
                break;
            case 'total':
                $arrange_by = 'total_article';
                break;
            case 'quantite':
                $arrange_by = 'quantite_article';
                break;
        }
        //order
        $order = strtoupper(trim($_GET['order'] ?? 'ASC'));
        $order = in_array($order, $order_default, true) ? $order : 'ASC';

        //search_article
        $search_article =  trim($_GET['search_article'] ?? '');

        $params = [
            'status' => $status,
            'arrange_by' => $arrange_by,
            'order' => $order,
            'search_article' => $search_article
        ];

        //filter article
        $response = ArticleRepositorie::filterArticle($params);

        echo json_encode($response);
        return;
    }

    //action - list all aricle
    public function listAllArticle()
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

        //list all article
        $response = Article::listAllArticle();

        echo json_encode($response);
        return;
    }

    //action - update article
    public function updateArticle()
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

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //libelle_article - empty
            if ($json['libelle_article'] === '') {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //ibelle_article - invalid
            if (strlen($json['libelle_article']) > 100) {
                $response = [
                    'message_type' => 'success',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //is id_article exist ?
                $response = Article::findById($json['id_article']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.article_id_article', ['field' => $json['id_article']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update produit 
                $article_model = new Article();
                $article_model
                    ->setIdArticle($json['id_article'])
                    ->setLibelleArticle($json['libelle_article']);
                $response = $article_model->updateArticle();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.article_updateArticle',
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
        //redirect to article index
        else {
            header('Location: ' . SITE_URL . '/article');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete all article
    public function deleteAllArticle()
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
            //redirect to article index
            header("Location: " . SITE_URL . '/article');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $ids_article = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['ids_article']));

            //ids_article - empty
            if (count($ids_article) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.article_ids_article_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //delete all article
                $response = Article::deleteAllArticle($ids_article);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.article_deleteAllArticle',
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
        //redirect to article index
        else {
            header('Location: ' . SITE_URL . '/article');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all article
    public function permanentDeleteAllArticle()
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
            //redirect to article index
            header("Location: " . SITE_URL . '/article');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $ids_article = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['ids_article']));

            //ids_article - empty
            if (count($ids_article) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.article_ids_article_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //permanent delete all article
                $response = Article::permanentDeleteAllArticle($ids_article);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.article_deleteAllArticle',
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
        //redirect to article index
        else {
            header('Location: ' . SITE_URL . '/article');
            return;
        }

        echo json_encode($response);
        return;
    }
}
