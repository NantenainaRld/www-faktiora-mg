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
            //trim
            foreach ($json as $data => &$value) {
                if ($data !== 'articles') {
                    $value = trim($value);
                } else {
                    foreach ($value as &$line) {
                        foreach ($line as $article => &$art) {
                            $art = trim($art);
                        }
                    }
                }
            }

            //articles valides ?
            foreach ($json['articles'] as $index => $article) {
                //prix - invalid
                $prix = filter_var($article['prix_article'], FILTER_VALIDATE_FLOAT);
                if ($prix === false || $prix <= 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.sortie_prix_article',
                            [
                                'id_article' => $article['id_article'],
                                'prix_article' => $article['prix_article']
                            ]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                //quantite - invalid
                $quantite = filter_var($article['quantite_article'], FILTER_VALIDATE_FLOAT);
                if ($quantite === false || $quantite <= 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.sortie_prix_article',
                            [
                                'id_article' => $article['id_article'],
                                'prix_article' => $article['quantite_article']
                            ]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            //date_ds
            $date_ds = "";
            //date_ds empty - role admin
            if ($is_loged_in->getRole() === 'admin' && $json['date_ds'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //date_ds invalid - role admin
            if ($is_loged_in->getRole() === 'admin') {
                $date_ds = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ds']);
                if ($date_ds === false) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.date',
                            ['field' => $json['date_ds']]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
                //date_ds - future
                $date = new DateTime();
                if ($date_ds > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
                $date_ds = $date_ds->format('Y-m-d H:i:s');
            }

            try {

                //id_user - role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //is user exist ?
                    $response = User::findById($json['id_utilisateur']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.not_found.user_id',
                                ['field' => $json['id_utilisateur']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                }
                //id_user - role caissier
                else {
                    $json['id_utilisateur'] = $is_loged_in->getIdUtilisateur();
                }

                //num_caisse - role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //is num_caisse exist and not deleted ?
                    $response = Caisse::findById($json['num_caisse']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.not_found.caisse_num_caisse',
                                ['field' => $json['num_caisse']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //caisse - deleted
                    if ($response['model']->getEtatCaisse() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.invalids.caisse_deleted',
                                ['field' => $json['num_caisse']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                }
                //num_caisse - role caissier
                else {
                    //does user have caisse ?
                    $response = LigneCaisse::findCaisse($json['id_utilisateur']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //user doesn't have caisse
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.user_caisse')
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //user has caisse
                    else {
                        $json['num_caisse'] = $response['model']->getNumCaisse();
                    }
                }

                //articles exist ?
                foreach ($json['articles'] as $index => $article) {
                    //is article exist ?
                    $response = Article::findById($article['id_article']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.not_found.article_id_article',
                                ['field' => $article['id_article']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //article - deleted
                    if ($response['model']->getEtatArticle() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __(
                                'messages.invalids.article_deleted',
                                ['field' => $article['id_article']]
                            )
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                //find caisse
                $response = Caisse::findById($json['num_caisse']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.caisse_num_caisse', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //caisse - deleted
                if ($response['model']->getEtatCaisse() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.caisse_deleted', ['field' => $json['num_caisse']])
                    ];

                    echo json_encode($response);
                    return;
                }
                $solde_caisse = $response['model']->getSolde();
                $seuil_caisse = $response['model']->getSeuil();

                //create sortie
                $demande_sortie_model = new DemandeSortie();
                $demande_sortie_model
                    ->setIdUtilsateur($json['id_utilisateur'])
                    ->setDateDs($date_ds)
                    ->setNumCaisse($json['num_caisse']);
                $response = $demande_sortie_model->createSortie($solde_caisse, $seuil_caisse, $json['articles']);

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

    //action - filter demande sortie
    public function filterDemandeSortie()
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

        //defaults
        $order_by_default = ['num', 'date', 'montant'];
        $arrange_default = ['ASC', 'DESC'];

        //status
        $status = strtolower(trim($_GET['status'] ?? 'active'));
        $status = ($status === 'deleted') ? 'deleted' : 'active';
        if ($is_loged_in->getRole() === 'caissier') {
            $status = 'active';
        }
        switch ($status) {
            case 'deleted':
                $status = 'supprimé';
                break;
            case 'active':
                $status = 'actif';
                break;
        }

        //num_caisse
        $num_caisse = 'all';
        $num_caisse = trim($_GET['num_caisse'] ?? 'all');
        $num_caisse = filter_var($num_caisse, FILTER_VALIDATE_INT);
        $num_caisse = ($num_caisse === false || $num_caisse < 0) ? 'all' : $num_caisse;

        //id_user
        $id_user = trim($_GET['id_user'] ?? 'all');
        $id_user = filter_var($id_user, FILTER_VALIDATE_INT);
        $id_user = ($id_user === false || $id_user < 10000) ? 'all' : $id_user;

        //oder_by
        $order_by = strtolower(trim($_GET['order_by']) ?? 'num');
        $order_by = in_array($order_by, $order_by_default, true) ? $order_by : 'num';
        switch ($order_by) {
            case 'num':
                $order_by = 'ds.num_ds';
                break;
            case 'date':
                $order_by = 'ds.date_ae';
                break;
            case 'montant':
                $order_by = 'montant_ds';
                break;
        }
        //arrange
        $arrange = strtoupper(trim($_GET['arrange'] ?? 'ASC'));
        $arrange = in_array($arrange, $arrange_default, true) ? $arrange : 'ASC';

        $date = new DateTime();

        //from
        $from = trim($_GET['from'] ?? '');
        //from not empty
        if ($from !== '') {
            $f = DateTime::createFromFormat('Y-m-d', $from);
            //from - invalid
            if (!$f) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //from - future
            if ($f > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $from = $f->format('Y-m-d');
        }
        //to
        $to = trim($_GET['to'] ?? '');
        //to not empty
        if ($to !== '') {
            $t = DateTime::createFromFormat('Y-m-d', $to);
            //to - invalid
            if (!$t) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date', ['field' => $from])
                ];

                echo json_encode($response);
                return;
            }
            //to - future
            if ($t > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $to = $t->format('Y-m-d');
        }

        //search_ds
        $search_ds = trim($_GET['search_ds'] ?? '');

        //parametters
        $params = [
            'status' => $status,
            'num_caisse' => $num_caisse,
            'id_user' => $id_user,
            'order_by' => $order_by,
            'arrange' => $arrange,
            'from' => $from,
            'to' => $to,
            'search_ds' => $search_ds
        ];

        //filter demande sortie
        $response = SortieRepositorie::filterDemandeSortie($params);

        echo json_encode($response);
        return;
    }

    //action - list connection sortie
    public function listConnectionSortie()
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

        //num_ds
        $num_ds = strtoupper(trim($_GET['num_ds'] ?? ''));

        try {
            //is num_ds exist ?
            $response = DemandeSortie::findById($num_ds);
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }
            //not found
            if (!$response['found']) {
                $response = [
                    'message_type' => 'invalud',
                    'message' => __('messages.not_found.sortie_num_ds', ['field' => $num_ds])
                ];

                echo json_encode($response);
                return;
            }

            //list connection sortie
            $demande_sortie_model = new DemandeSortie();
            $demande_sortie_model->setNumDs($num_ds);
            $response = $demande_sortie_model->listConnectionSortie();

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_listConnectionSortie',
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

    //action - list all demande sortie
    public function listAllDemandeSortie()
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

        //list all demande sortie
        $response = SortieRepositorie::listAllDemandeSortie();

        echo json_encode($response);
        return;
    }
    //action - list ligne ds and article
    public function listLdsArticle()
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

        //num_ds 
        $num_ds = strtoupper(trim($_GET['num_ds'] ?? ''));

        try {

            //is num_ds exist ?
            $response = DemandeSortie::findById($num_ds);
            //error
            if ($response['message_type'] === 'error') {
                echo json_encode($response);
                return;
            }
            //not found
            if (!$response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.not_found.sortie_num_ds', ['field' => $num_ds])
                ];

                echo json_encode($response);
                return;
            }

            //list lds article 
            $response = SortieRepositorie::ligneDs($num_ds);

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_ligneDs',
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
}
