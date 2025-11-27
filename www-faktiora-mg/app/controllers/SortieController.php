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
}
