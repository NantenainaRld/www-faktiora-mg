<?php

//dompdf
require_once LIBS_PATH . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
                    //articles - empty
                    if (count($json['articles']) <= 0) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.sortie_article_empty')
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //articles - not empty
                    else {
                        foreach ($value as &$line) {
                            foreach ($line as $article => &$art) {
                                $art = trim($art);
                            }
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
                $quantite = filter_var($article['quantite_article'], FILTER_VALIDATE_INT);
                if ($quantite === false || $quantite <= 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.sortie_quantite_article',
                            [
                                'id_article' => $article['id_article'],
                                'quantite_article' => $article['quantite_article']
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

        //defaults
        $date_by_default = [
            'per',
            'between',
            'month_year'
        ];
        $per_default = [
            'DAY',
            'WEEK',
            'MONTH',
            'YEAR'
        ];

        //num_caisse
        $num_caisse = "";
        //role - admin
        if ($is_loged_in->getRole() === 'admin') {
            $num_caisse = trim($_GET['num_caisse'] ?? 'all');
            $num_caisse = filter_var($num_caisse, FILTER_VALIDATE_INT);
            $num_caisse = (!$num_caisse || $num_caisse < 0) ? 'all' : $num_caisse;
        }
        //role - caissier
        else {
            //find user caisse
            $find_user_caisse = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());

            //error
            if ($find_user_caisse['message_type'] === 'error') {
                echo json_encode($response);

                return;
            }
            //not found user caisse
            elseif (!$find_user_caisse['found']) {
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'data' => [],
                    'nb_sortie' => 0,
                    'total_sortie' => 0,
                ];

                echo json_encode($response);

                return;
            }

            //found user caisse
            $num_caisse = $find_user_caisse['model']->getNumCaisse();
        }

        //id_utilisateur
        $id_utilisateur = trim($_GET['id_utilisateur'] ?? 'all');

        //date_by
        $date_by = strtolower(trim($_GET['date_by'] ?? 'all'));
        $date_by = in_array($date_by, $date_by_default, true) ? $date_by : 'all';
        //per
        $per = strtoupper(trim($_GET['per'] ?? 'DAY'));
        $per = in_array($per, $per_default, true) ? $per : 'DAY';
        //from
        $from = trim($_GET['from'] ?? '');
        //to
        $to = trim($_GET['to'] ?? '');
        $date = new DateTime();
        if ($date_by === 'between') {
            //from && to - empty
            if ($from === '' && $to === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.from_to')
                ];

                echo json_encode($response);
                return;
            }

            $date = new DateTime();

            //from - not empty
            if ($from !== '') {
                $from = DateTime::createFromFormat('Y-m-d', $from);
                //from - invalid
                if (!$from) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date', ['field' => $from])
                    ];

                    echo json_encode($response);
                    return;
                }
                //from - future
                if ($from > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            //to - not empty
            if ($to !== '') {
                $to = DateTime::createFromFormat('Y-m-d', $to);
                //from - invalid
                if (!$to) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date', ['field' => $from])
                    ];

                    echo json_encode($response);
                    return;
                }
                //to - future
                if ($to > $date) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date_future')
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            //from && to not empty - from > to
            if ($from !== '' && $to !== '' && $from > $to) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.from_to')
                ];

                echo json_encode($response);
                return;
            }

            //from not empty- reformat
            if ($from !== '') {
                $from = $from->format('Y-m-d');
            }

            //to not empty - reformat
            if ($to !== '') {
                $to = $to->format('Y-m-d');
            }
        }
        //month
        $month = trim($_GET['month'] ?? 'none');
        $month = filter_var($month, FILTER_VALIDATE_INT);
        $month = ($month === false || ($month < 1 || $month > 12)) ? 'none' : $month;
        //year
        $year = trim($_GET['year'] ?? date('Y'));
        $year = filter_var($year, FILTER_VALIDATE_INT);
        $year =  ($year === false || ($year < 1700 || $year > date('Y'))) ? ((new DateTime())->format('Y')) : $year;

        $params = [
            'num_caisse' => $num_caisse,
            'id_utilisateur' => $id_utilisateur,
            'date_by' => $date_by,
            'per' => $per,
            'from' => $from,
            'to' => $to,
            'month' => $month,
            'year' => $year,
        ];

        //list all demande sortie
        $response = DemandeSortie::listAllDemandeSortie($params);

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

    //action - update demande sortie
    public function updateDemandeSortie()
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
        //role not admin
        if ($is_loged_in->getRole() !== 'admin') {
            //redirect to sortie index
            header('Location: ' . SITE_URL . '/sortie');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);
            //trim
            $json = array_map(fn($x) => trim($x), $json);

            //date_ds - empty
            if ($json['date_ds'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.date')
                ];

                echo json_encode($response);
                return;
            }
            //date_ds - invalid
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
            $date = new DateTime();
            //date_ae - future
            if ($date_ds > $date) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.date_future')
                ];

                echo json_encode($response);
                return;
            }
            $date_ds = $date_ds->format('Y-m-d H:i:s');

            //id_utilisateur - empty
            if ($json['id_utilisateur'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.user_id')
                ];

                echo json_encode($response);
                return;
            }

            try {

                //is num_ds exist ?
                $json['num_ds'] = strtoupper($json['num_ds']);
                $response = DemandeSortie::findById($json['num_ds']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.sortie_num_ds', ['field' => $json['num_ds']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //is num_caisse exist ?
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
                        'message' => __('messages.not_found.user_id', ['field' => $json['id_utilisateur']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //update demande sortie
                $demande_sortie_model = new DemandeSortie();
                $demande_sortie_model
                    ->setNumDs($json['num_ds'])
                    ->setDateDs($json['date_ds'])
                    ->setIdUtilsateur($json['id_utilisateur'])
                    ->setNumCaisse($json['num_caisse']);
                $response = $demande_sortie_model->updateDemandeSortie();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.sortie_updateDemandeSortie',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to sortie index
        else {
            header('Location: ' . SITE_URL . '/sortie');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - delete all demande sortie
    public function deleteAllDemandeSortie()
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
            //redirect to sortie index
            header("Location: " . SITE_URL . '/sortie');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_ds = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_ds']));

            //nums_ds - empty
            if (count($nums_ds) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_nums_ds_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {
                //delete all demande sortie
                $response = DemandeSortie::deleteAllDemandeSortie($nums_ds);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.sortie_deleteAllDemandeSortie',
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
        //redirect to sortie index
        else {
            header('Location: ' . SITE_URL . '/sortie');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - permanent delete all demande sortie
    public function permanentDeleteAllDemandeSortie()
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
            //redirect to sortie index
            header("Location: " . SITE_URL . '/sortie');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $json = json_decode(file_get_contents('php://input'), true);

            $nums_ds = array_values(array_map(fn($x) => strtoupper(trim($x)), $json['nums_ds']));

            //nums_ds - empty
            if (count($nums_ds) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_nums_ds_empty')
                ];

                echo json_encode($response);
                return;
            }

            try {
                //permanent delete all demande sortie
                $response = DemandeSortie::permanentDeleteAllDemandeSortie($nums_ds);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.sortie_deleteAllDemandeSortie',
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
        //redirect to sortie index
        else {
            header('Location: ' . SITE_URL . '/sortie');
            return;
        }

        echo json_encode($response);
        return;
    }

    //action - correction  ae
    public function correctionAutreEntree()
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

            //libelle_article - empty
            if ($json['libelle_article'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_article - invalid
            if (strlen($json['libelle_article']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                //date_ds - empty
                if ($json['date_ds'] === '') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.date')
                    ];

                    echo json_encode($response);
                    return;
                }
                //date_ds -  invalid
                $date_ds = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ds']);
                if (!$date_ds) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date', ['field' => $json['date_ds']])
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
                $json['date_ds'] = $date_ds->format('Y-m-d H:i:s');
            }
            //role - caissier
            else {
                $json['date_ds'] = '';
            }

            //montant - empty
            if ($json['montant'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.montant')
                ];

                echo json_encode($response);
                return;
            }
            //montant - invalid
            $montant = filter_var($json['montant'], FILTER_VALIDATE_FLOAT);
            if (!$montant && $montant < 1) {
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

                //is num_ae exist ?
                $json['num_ae'] = strtoupper(trim($json['num_ae']));
                $response = AutreEntree::findById($json['num_ae']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.not_found.entree_num_ae', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //num_ae - deleted
                if ($response['model']->getEtatAe() === 'supprimé') {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.invalids.entree_ae_deleted', ['field' => $json['num_ae']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //montant > montant_ae
                if ($json['montant'] > $response['model']->getMontantAe()) {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.invalids.sortie_montant_ae')
                    ];

                    echo json_encode($response);
                    return;
                }
                $ae_num_caisse = $response['model']->getNumCaisse();

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //is num_caisse exist ?
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
                    //ae num_caisse != user num_caisse
                    if ($ae_num_caisse !== $num_caisse) {
                        $response = [
                            'message_type' => 'success',
                            'message' => __('messages.invalids.entree_correctionAutreEntree', ['field' => $json['num_ae']])
                        ];

                        echo json_encode($response);
                        return;
                    }
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
                }

                //find caisse
                $response = Caisse::findById($num_caisse);
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

                //correction autre entree
                $demande_sortie_model = new DemandeSortie();
                $demande_sortie_model
                    ->setDateDs($json['date_ds'])
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($num_caisse);
                $response = $demande_sortie_model->correction($solde_caisse, $seuil_caisse, 'correction/' . $json['num_ae'] . ' - ' . $json['libelle_article'], $json['montant']);

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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }
    }

    //action - correction  demande sortie 
    public function correctionDemandeSortie()
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

            //libelle_article - empty
            if ($json['libelle_article'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.libelle')
                ];

                echo json_encode($response);
                return;
            }
            //libelle_article - invalid
            if (strlen($json['libelle_article']) > 100) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.libelle')
                ];

                echo json_encode($response);
                return;
            }

            //role admin
            if ($is_loged_in->getRole() === 'admin') {
                //date_ds - empty
                if ($json['date_ds'] === '') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.empty.date')
                    ];

                    echo json_encode($response);
                    return;
                }
                //date_ds -  invalid
                $date_ds = DateTime::createFromFormat('Y-m-d\TH:i', $json['date_ds']);
                if (!$date_ds) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.date', ['field' => $json['date_ds']])
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
                $json['date_ds'] = $date_ds->format('Y-m-d H:i:s');
            }
            //role - caissier
            else {
                $json['date_ds'] = '';
            }

            //montant - empty
            if ($json['montant'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.montant')
                ];

                echo json_encode($response);
                return;
            }
            //montant - invalid
            $montant = filter_var($json['montant'], FILTER_VALIDATE_FLOAT);
            if (!$montant && $montant < 1) {
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

                //is num_ds exist ?
                $json['num_ds'] = strtoupper(trim($json['num_ds']));
                $response = DemandeSortie::findById($json['num_ds']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.not_found.sortie_num_ds', ['field' => $json['num_ds']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //num_ds - deleted
                if ($response['model']->getEtatDs() === 'supprimé') {
                    $response = [
                        'message_type' => 'success',
                        'message' => __('messages.invalids.sortie_deleted', ['field' => $json['num_ds']])
                    ];

                    echo json_encode($response);
                    return;
                }
                $ds_num_caisse = $response['model']->getNumCaisse();

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //is num_caisse exist ?
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
                    //ds num_caisse != user num_caisse
                    if ($ds_num_caisse !== $num_caisse) {
                        $response = [
                            'message_type' => 'success',
                            'message' => __('messages.invalids.sortie_correctionDemandeSortie', ['field' => $json['num_ds']])
                        ];

                        echo json_encode($response);
                        return;
                    }
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
                }

                //find caisse
                $response = Caisse::findById($num_caisse);
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

                //correction demande sortie
                $demande_sortie_model = new DemandeSortie();
                $demande_sortie_model
                    ->setDateDs($json['date_ds'])
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($num_caisse);
                $response = $demande_sortie_model->correction($solde_caisse, $seuil_caisse, 'correction/' . $json['num_ds'] . ' - ' . $json['libelle_article'], $json['montant']);

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

    //action - correction  facture
    public function correctionFacture()
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
                if ($data !== 'lf') {
                    $value = trim($value);
                } else {
                    //lf - empty
                    if (count($json['lf']) <= 0) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.success.sortie_correctionFacture_not_corrected')
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //lf - not empty
                    else {
                        foreach ($value as &$line) {
                            foreach ($line as $index => &$lf) {
                                $lf = trim($lf);
                            }
                        }
                    }
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
                if (!$date_ds) {
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

            //lf
            $lf = unserialize(serialize($json['lf']));
            $json['lf'] = [$json['lf'][0]];
            $ids_lf = [$json['lf'][0]['id_lf']];
            foreach ($lf as $index => $line) {
                // id_lf - exist
                if (in_array($line['id_lf'], $ids_lf, true)) {
                    continue;
                }
                $ids_lf[] = $line['id_lf'];
                $json['lf'][] = $line;
            }

            //does produits valides ?
            foreach ($json['lf'] as $index => $line) {
                //quantite - invalid
                $quantite = filter_var($line['quantite_produit'], FILTER_VALIDATE_INT);
                if ($quantite === false || $quantite < 0) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __(
                            'messages.invalids.entree_quantite_produit',
                            [
                                'id_produit' => $line['id_produit'],
                                'quantite_produit' => $line['quantite_produit']
                            ]
                        )
                    ];

                    echo json_encode($response);
                    return;
                }
            }

            try {

                //does num_facture exist ?
                $json['num_facture'] = strtoupper($json['num_facture']);
                $response = Facture::findById($json['num_facture']);
                //error
                if ($response['message_type'] === 'error') {
                    echo json_encode($response);
                    return;
                }
                //not found
                if (!$response['found']) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.not_found.entree_num_facture', ['field' => $json['num_facture']])
                    ];

                    echo json_encode($response);
                    return;
                }
                //facture - deleted
                if ($response['model']->getEtatFacture() === 'supprimé') {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.invalids.entree_facture_deleted', ['field' => $json['num_facture']])
                    ];

                    echo json_encode($response);
                    return;
                }

                //does lf valid ?
                $change = false;
                $produits = [];
                foreach ($json['lf'] as $index => $line) {
                    //does id_lf exist ?
                    $response = LigneFacture::findById($line['id_lf']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.entree_id_lf', ['field' => $line['id_lf']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //num_facture != lf id facture
                    if ($json['num_facture'] != $response['model']->getIdFacture()) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.entree_facture_id_lf', [
                                'id_lf' => $line['id_lf'],
                                'num_facture' => $json['num_facture']
                            ])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //id_produit != lf id_produit
                    if ($line['id_produit'] != $response['model']->getIdProduit()) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.entree_facture_lf_id_produit', [
                                'id_produit' => $line['id_produit'],
                                'id_lf' => $line['id_lf']
                            ])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //change
                    if ($response['model']->getQuantiteProduit() != $line['quantite_produit']) {
                        $change = true;
                        //quantite_produit > lf quantite_produit
                        if ($line['quantite_produit'] > $response['model']->getQuantiteProduit()) {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => __('messages.invalids.correction_quantite', ['field' => $line['id_lf']])
                            ];

                            echo json_encode($response);
                            return;
                        }

                        //calcul substraction
                        $p = [
                            'id_produit' => $line['id_produit'],
                            'nb' => ($response['model']->getQuantiteProduit() - $line['quantite_produit']),
                            'prix_produit' => $response['model']->getPrix()
                        ];
                        $produits[] = $p;
                    }
                    //does produit exist ?
                    $response = Produit::findById($line['id_produit']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.produit_id_produit', ['field' => $line['id_produit']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //produit - deleted
                    if ($response['model']->getEtatProduit() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.produit_deleted', ['field' => $line['id_produit']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                //not changed
                if (!$change) {
                    $response = [
                        'message_type' => 'invalid',
                        'message' => __('messages.success.sortie_correctionFacture_not_corrected')
                    ];

                    echo json_encode($response);
                    return;
                }

                //libelle_article && prix_article
                $libelle_article = "correction/{$json['num_facture']} - ";
                $prix_article = 0;
                foreach ($produits as $index => $line) {
                    $libelle_article .= "/produit {$line['id_produit']} : -{$line['nb']} ";
                    $prix_article += $line['prix_produit'] * $line['nb'];
                }

                //num_caisse
                $num_caisse = "";
                //role admin
                if ($is_loged_in->getRole() === 'admin') {
                    //does num_caisse exist ?
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
                    //facture num_caisse != user num_caisse
                    if ($json['num_caisse'] != $num_caisse) {
                        $response = [
                            'message_type' => 'success',
                            'message' => __('messages.invalids.sortie_correctionFacture', ['field' => $json['num_facture']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                //role admin - does user exist ?
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
                }

                //find caisse
                $response = Caisse::findById($num_caisse);
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

                //correction facture
                $demande_sortie_model = new DemandeSortie();
                $demande_sortie_model
                    ->setDateDs($date_ds)
                    ->setIdUtilsateur($id_utilisateur)
                    ->setNumCaisse($num_caisse);
                $response = $demande_sortie_model->correctionFacture($solde_caisse, $seuil_caisse, $libelle_article, $prix_article, $produits);

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.sortie_correctionFacture',
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
        //redirect to entree index
        else {
            header('Location: ' . SITE_URL . '/entree');
            return;
        }
    }

    //action - print demande_sortie
    public function printDemandeSortie()
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

        //config.json
        $config = json_decode(file_get_contents(PUBLIC_PATH . '/config/config.json'), true);

        //lang
        $lang = '';
        switch ($_COOKIE['lang']) {
            case 'en':
                $lang = 'en_US';
                break;
            case 'fr':
                $lang = 'fr_FR';
                break;
            case 'mg':
                $lang = 'mg_MG';
                break;
        }

        //num_ds
        $num_ds = strtoupper(trim($_GET['num_ds'] ?? ''));
        //num_ds - empty
        if ($num_ds === '') {
            $response = [
                'message_type' => 'invalid',
                'message' => __('messages.invalids.sortie_not_selected')
            ];

            echo json_encode($response);
            return;
        }

        try {

            //does num_ds exist ?
            $ds = DemandeSortie::findById($num_ds);
            //error
            if ($ds['message_type'] === 'error') {
                echo json_encode($ds);
                return;
            }
            //not found
            if (!$ds['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.not_found.sortie_num_ds', ['field' => $num_ds])
                ];

                echo json_encode($response);
                return;
            }

            //num_caisse
            $num_caisse = $ds['model']->getNumCaisse();
            //date_ds
            $date_ds = $ds['model']->getDateDs();
            $date_ds = new DateTime($date_ds);
            $formatter = new IntlDateFormatter(
                $lang,
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE
            );
            $date_ds = $formatter->format($date_ds);
            //id_utilisateur
            $id_utilisateur = $ds['model']->getIdUtilisateur();

            //role caissier
            if ($is_loged_in->getRole() === 'caissier') {
                //does caissier have caisse ?
                $response = LigneCaisse::findCaisse($is_loged_in->getIdUtilisateur());
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
            }

            //get connectionsortie
            $demande_sortie_model = new DemandeSortie();
            $demande_sortie_model->setNumDs($num_ds);
            $connection_sortie = $demande_sortie_model->listConnectionSortie();
            //not ds
            if (count($connection_sortie['lds']) <= 0) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_not_ds', ['field' => $num_ds])
                ];

                echo json_encode($response);
                return;
            }


            //strings
            $strings = [
                'sortie_title' => __('forms.titles.sortie'),
                'correction' => __('forms.labels.correction'),
                'on' => __('forms.labels.on'),
                'quantity' => __('forms.labels.quantity'),
                'unit_price' => __('forms.labels.unit_price'),
                'amount' => __('forms.labels.amount'),
                'inflow' => __('forms.labels.inflow'),
                'outflow' => __('forms.labels.outflow'),
                'cashier' => ucfirst(__('forms.labels.cashier')),
                'applicant' => __('forms.labels.applicant'),
                'supervisor' => __('forms.labels.supervisor'),
                'num' => __('forms.labels.num'),
                'label' => __('forms.labels.label'),
                'total' => __('forms.labels.total'),
                'cash' => __('forms.labels.cash')
            ];

            //header
            $html = "<!DOCTYPE html> 
                    <body>
                    <head>
                        <style>
                            body {
                                font-size: 11pt;
                            }
                            div {
                                width: 100%;
                            }
                            .center {
                                text-align: center;
                            }
                            .left {
                                text-align: left;
                            }
                            .table {
                                width: 100%;
                                margin: 40px 0;
                                border-collapse: collapse;
                            }
                            .thead {
                                text-align: center;
                                border: 1px solid black;
                            }
                            .thead th {
                                padding: 5px;
                                border: 1px solid black;
                            }
                            .table td {
                                padding-left: 5px;
                                padding-right: 5px;
                                border: 1px solid black;
                            }
                        </style>
                    </head>
                    <body>";

            //title
            if (!isset($config['enterprise_name'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.config', ['field' => 'enterprise_name'])
                ];

                echo json_encode($response);
                return;
            }
            if (!isset($config['currency_units'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.config', ['field' => 'currency_units'])
                ];

                echo json_encode($response);
                return;
            }
            //caissier numero
            $num_cashier = strtolower($strings['cashier']);
            $html .= "<div class='header'>
                        <h4 class='center'>{$config['enterprise_name']}</h4>
                        <h3 class='center' style='color: tomato;'><u>{$strings['sortie_title']}</u></h3>
                        <div style='display: flex; margin-top: 20px; text-align: center;'>
                            <span>{$strings['cash']} {$strings['num']} : {$num_caisse}</span>  -
                            <span style='margin-left: 10px;'>{$num_cashier} ID : {$id_utilisateur}</span>
                        </div>
                        <div style='display: flex; margin-top: 50px;'>
                            <span style='float: left;'><b>{$strings['num']} : </b>{$num_ds}</span>
                            <span style='float: right;'><b>{$strings['on']} : </b>{$date_ds}</span>
                        </div>";

            //table
            $total = $connection_sortie['montant_ds'];
            $html .= "<table class='table'>
                            <tr class='thead'>
                                <th>ID</th>
                                <th>{$strings['label']}</th>
                                <th>{$strings['quantity']}</th>
                                <th>{$strings['unit_price']} ({$config['currency_units']})</th>
                                <th>{$strings['amount']} ({$config['currency_units']})</th>
                            </tr>";
            $html .= "<tbody>";
            foreach ($connection_sortie['lds'] as $line) {
                $html .= "<tr>
                            <td>{$line['id_lds']}</td>
                            <td>{$line['libelle_article']}</td>
                            <td>{$line['quantite_article']}</td>
                            <td>{$line['prix_article']}</td>
                            <td>{$line['prix_total']}</td>
                        </tr>";
            }
            //montant_ds
            $html .= "<tr>
                            <td style='border: none;'></td>
                            <td style='border: none;'></td>
                            <td colspan='2'>{$strings['total']}</td>
                            <td>{$connection_sortie['montant_ds']}</td>
                        </tr>";

            //correction ae
            if (count($connection_sortie['autre_entree']) > 0) {
                $html .= "<tr>
                            <td style='text-align: center; border: none; padding: 20px; color: blue;' colspan='5'><b>{$strings['correction']}</b> ({$strings['inflow']})</td>
                        </tr>";
                foreach ($connection_sortie['autre_entree'] as $line) {
                    $html .= "<tr>
                            <td>{$line['num_ae']}</td>
                            <td>{$line['libelle_ae']}</td>
                            <td>1</td>
                            <td>{$line['montant_ae']}</td>
                            <td>{$line['montant_ae']}</td>
                        </tr>";
                    $total -= $line['montant_ae'];
                }
            }

            //correction ds
            if (count($connection_sortie['sortie']) > 0) {
                $html .= "<tr>
                            <td style='text-align: center; border: none; padding: 20px; color: tomato;' colspan='5'><b>{$strings['correction']}</b> ({$strings['outflow']})</td>
                        </tr>";
                foreach ($connection_sortie['sortie'] as $line) {
                    $html .= "<tr>
                            <td>{$line['num_ds']}</td>
                            <td>{$line['libelle_article']}</td>
                            <td>1</td>
                            <td>{$line['prix_article']}</td>
                            <td>{$line['prix_article']}</td>
                        </tr>";
                    $total += $line['prix_article'];
                }
            }

            $html .= "</tbody>
                    </table>";

            //total
            $html .= "<div><span style='float: left;'><b>{$strings['total']} :</b>  {$total} {$config['currency_units']}</span></div>";

            //sign
            $html .= "<div style='margin-top: 50px;'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='text-align: left'><u><b>{$strings['applicant']}</b></u></td>
                                <td style='text-align: center;'><u><b>{$strings['cashier']}</b></u></td>
                                <td style='text-align: right;'><u><b>{$strings['supervisor']}</b></u></td>
                            </tr>
                        </table>
                    </div>";

            //footer
            $html .= "</body>
                    </html>";

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $response = [
                'message_type' => 'success',
                'pdf' => base64_encode($dompdf->output()),
                'file_name' => str_replace(' ', '_', strtolower($strings['sortie_title'] . ' ' . $strings['on'] . ' ' . $date_ds . '.pdf')),
                'message' => __('messages.success.print')
            ];

            echo json_encode($response);
            return;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_printDemandeSortie',
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
