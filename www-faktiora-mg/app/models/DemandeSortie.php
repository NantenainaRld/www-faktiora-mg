<?php

//CLASS - demande sortie
class DemandeSortie extends Database
{
    private $id_ds = null;
    private $num_ds = "";
    private $date_ds = "";
    private $etat_ds = 'actif';
    private $id_utilisateur = "";
    private $num_caisse = "";

    public function __construct()
    {
        parent::__construct();
    }

    //==================== SETTERS =========================

    //setter - id_ds
    public function setIdDs($id_ds)
    {
        $this->id_ds = $id_ds;
        return $this;
    }

    //setter - num_ds
    public function setNumDs($num_ds)
    {
        $this->num_ds = $num_ds;
        return $this;
    }

    //setter - date_ds
    public function setDateDs($date_ds)
    {
        $this->date_ds = $date_ds;
        return $this;
    }

    //setter - etat_ds
    public function setEtatDs($etat_ds)
    {
        $this->etat_ds = $etat_ds;
        return $this;
    }

    //setter - id_utilisateur
    public function setIdUtilsateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    //setter - num_caisse
    public function setNumCaisse($num_caisse)
    {
        $this->num_caisse = $num_caisse;
        return $this;
    }

    //======================= GETTERS =========================

    //getter - etat_ds
    public function getEtatDs()
    {
        return $this->etat_ds;
    }

    //getter  - num_caisse
    public function getNumCaisse()
    {
        return $this->num_caisse;
    }

    //getter - date_ds
    public function getDateDs()
    {
        return $this->date_ds;
    }

    //getter - id_utilisateur
    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }

    //======================= PUBLIC FUNCTION ====================

    //create demande sortie
    public function createSortie($solde_caisse, $seuil_caisse, $articles)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            //(prix_article * quantite) - solde < seuil ?
            $total_prix = 0;
            foreach ($articles as $index => $article) {
                $total_prix += (float)$article['prix_article'] * (int)$article['quantite_article'];
            }
            $reste = $solde_caisse - $total_prix;
            if ($reste < $seuil_caisse) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_reste_solde', ['field' => $seuil_caisse])
                ];

                return $response;
            }

            //create sortie
            $sql = "";
            $paramsQuery = [
                'id_utilisateur' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse
            ];
            //date_ds - empty
            if ($this->date_ds === '') {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (NOW(), :id_utilisateur, :num_caisse) ";
            }
            //date_ds - not empty
            else {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (:date_ds, :id_utilisateur, :num_caisse) ";
                $paramsQuery['date_ds'] = $this->date_ds;
            }
            $response = parent::executeQuery($sql, $paramsQuery);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //add num_ds
            $this->id_ds = $response['last_inserted'];
            $this->num_ds = 'S' . date('Ym') . '-' . $this->id_ds;
            $response = parent::executeQuery("UPDATE demande_sortie SET num_ds = :num WHERE id_ds = :id", [
                'num' => $this->num_ds,
                'id' => $this->id_ds
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //create ligne_ds
            $ligne_ds_model = new LigneDs();
            foreach ($articles as $index => $article) {
                $ligne_ds_model
                    ->setPrixArticle($article['prix_article'])
                    ->setQuantiteArticle($article['quantite_article'])
                    ->setIdDs($this->id_ds)
                    ->setIdArticle($article['id_article']);
                $ligne_ds_model->createLigneDs();
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
            }

            //update caisse solde
            $response = Caisse::updateSolde($this->num_caisse, $total_prix, 'decrease');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.sortie_createSortie')
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //static- list all demande sortie
    public static function listAllDemandeSortie($params)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        $paramsQuery = [];

        //date_by - 
        $ds_cond = '';
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                break;
            //date_by - per
            case 'per':
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        //ds
                        $ds_cond = 'AND DATE(ds.date_ds) = CURDATE() ';
                        break;
                    //per - !DAY
                    default:
                        //ds
                        $ds_cond = "AND {$params['per']}(ds.date_ds) = {$params['per']}(CURDATE()) ";
                        break;
                }
                break;
            //date_by - between
            case 'between':
                //from - empty
                if ($params['from'] === '') {
                    //to - !empty
                    //ds
                    $ds_cond = "AND DATE(ds.date_ds) <= :to ";
                    $paramsQuery['to'] = $params['to'];
                }
                //from - !empty
                else {
                    //to - empty
                    if ($params['to'] === '') {
                        //ds
                        $ds_cond = "AND DATE(ds.date_ds) >= :from ";
                        $paramsQuery['from'] = $params['from'];
                    }
                    //to - !empty
                    else {
                        //ds
                        $ds_cond = "AND DATE(ds.date_ds) BETWEEN :from AND :to  ";
                        $paramsQuery['from'] = $params['from'];
                        $paramsQuery['to'] = $params['to'];
                    }
                }
                break;
            //date_by - month_year
            case 'month_year':
                //month - all
                if ($params['month'] === 'all') {
                    //year - 
                    //ds
                    $ds_cond = "AND YEAR(ds.date_ds) = :year ";
                    $paramsQuery['year'] = $params['year'];
                }
                //month - !all
                else {
                    //year -
                    //ds
                    $ds_cond = "AND YEAR(ds.date_ds) = :year AND MONTH(ds.date_ds) = :month ";
                    $paramsQuery['month'] = $params['month'];
                    $paramsQuery['year'] = $params['year'];
                }
                break;
        }

        //num_caisse
        $caisse_cond = '';
        if ($params['num_caisse'] !== 'all') {
            $caisse_cond = " AND ds.num_caisse = :num_caisse ";
            $paramsQuery['num_caisse'] = $params['num_caisse'];
        }

        //id_utilisateur
        $user_cond = '';
        if ($params['id_utilisateur'] !== 'all') {
            $user_cond = " AND ds.id_utilisateur = :user_id ";
            $paramsQuery['user_id'] = $params['id_utilisateur'];
        }

        try {

            $response = parent::selectQuery("SELECT ds.num_ds, (lds.quantite_article * lds.prix_article) AS montant, DATE(date_ds) AS date FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds WHERE etat_ds != 'supprimé' AND ds.num_ds IS NOT NULL {$ds_cond} {$caisse_cond} {$user_cond} ORDER BY date ASC", $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //total
            $total = array_sum(array_column($response['data'], 'montant'));

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'total_sortie' => $total,
                'nb_sortie' => count($response['data']),
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_listAllDemandeSortie',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - find by id
    public static function findById($num_ds)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM demande_sortie WHERE num_ds = :num_ds ", ['num_ds' => $num_ds]);

            //error
            if ($response['message_type'] == 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => false
                ];

                return $response;
            }
            //found
            else {
                $demande_sortie_model = new DemandeSortie();
                $demande_sortie_model
                    ->setIdDs($response['data'][0]['id_ds'])
                    ->setNumDs($response['data'][0]['num_ds'])
                    ->setDateDs($response['data'][0]['date_ds'])
                    ->setEtatDs($response['data'][0]['etat_ds'])
                    ->setIdUtilsateur($response['data'][0]['id_utilisateur'])
                    ->setNumCaisse($response['data'][0]['num_caisse']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $demande_sortie_model
                ];

                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //list connection sortie
    public function listConnectionSortie()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //ligne ds
            $response = SortieRepositorie::ligneDs($this->num_ds);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            $lds = $response['data'];
            $montant_ds = $response['montant_lds'];

            //connection autre entree
            $response = AutreEntree::connectionAutreEntree($this->num_ds);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            $autre_entree = $response['data'];

            //connection sortie
            $response = SortieRepositorie::connectionSortie($this->num_ds);
            //error
            if ($response['message_type'] == 'error') {
                return $response;
            }
            $sortie = $response['data'];

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'lds' => $lds,
                'montant_ds' => $montant_ds,
                'autre_entree' => $autre_entree,
                'sortie' => $sortie
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //update demande_sortie
    public function updateDemandeSortie()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $response = parent::executeQuery("UPDATE demande_sortie SET date_ds = :date, id_utilisateur = :id_user, num_caisse = :num_caisse WHERE num_ds = :num_ds ", [
                'date' => $this->date_ds,
                'id_user' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse,
                'num_ds' => $this->num_ds
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __(
                    'messages.success.sortie_updateDemandeSortie',
                    ['field' => $this->num_ds]
                )
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //static - delete all demande sortie
    public static function deleteAllDemandeSortie($nums_ds)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_ds), '?'));
        $sql = "UPDATE demande_sortie SET etat_ds = 'supprimé' WHERE num_ds IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $nums_ds);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.sortie_deleteAllDemandeSortie_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.sortie_deleteAllDemandeSortie_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.sortie_deleteAllDemandeSortie_plur', ['field' => $response['row_count']]);
            }

            $response = [
                'message_type' => 'success',
                'message' => $response['message']
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //static - permanent delete all demande sortie
    public static function permanentDeleteAllDemandeSortie($nums_ds)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($nums_ds), '?'));
        $sql = "DELETE FROM demande_sortie WHERE num_ds IN ({$placeholders}) AND etat_ds = 'supprimé' ";

        try {

            $response = parent::executeQuery($sql, $nums_ds);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.sortie_deleteAllDemandeSortie_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.sortie_deleteAllDemandeSortie_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.sortie_deleteAllDemandeSortie_plur', ['field' => $response['row_count']]);
            }

            $response = [
                'message_type' => 'success',
                'message' => $response['message']
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //correction
    public function correction(
        $solde_caisse,
        $seuil_caisse,
        $libelle_article,
        $prix_article
    ) {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            //solde_caisse - prix_article < seuil
            $reste = $solde_caisse - $prix_article;
            if ($reste < $seuil_caisse) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_reste_solde', ['field' => $seuil_caisse])
                ];

                return $response;
            }

            //create article
            $article_model = new Article();
            $article_model
                ->setLibelleArticle($libelle_article);
            $response = $article_model->createArticle();
            //error or invalid
            if ($response['message_type'] === 'error' || $response['message_type'] === 'invalid') {
                return $response;
            }
            $id_article = $response['last_inserted'];

            //create sortie
            $sql = "";
            $paramsQuery = [
                'id_utilisateur' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse
            ];
            //date_ds - empty
            if ($this->date_ds === '') {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (NOW(), :id_utilisateur, :num_caisse) ";
            }
            //date_ds - not empty
            else {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (:date_ds, :id_utilisateur, :num_caisse) ";
                $paramsQuery['date_ds'] = $this->date_ds;
            }
            $response = parent::executeQuery($sql, $paramsQuery);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //add num_ds
            $this->id_ds = $response['last_inserted'];
            $this->num_ds = 'S' . date('Ym') . '-' . $this->id_ds;
            $response = parent::executeQuery("UPDATE demande_sortie SET num_ds = :num WHERE id_ds = :id", [
                'num' => $this->num_ds,
                'id' => $this->id_ds
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //create ligne_ds
            $ligne_ds_model = new LigneDs();
            $ligne_ds_model
                ->setPrixArticle($prix_article)
                ->setQuantiteArticle(1)
                ->setIdDs($this->id_ds)
                ->setIdArticle($id_article);
            $ligne_ds_model->createLigneDs();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //update caisse solde
            $response = Caisse::updateSolde($this->num_caisse, $prix_article, 'decrease');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.sortie_createSortie')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.correction',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //correction facture
    public function correctionFacture(
        $solde_caisse,
        $seuil_caisse,
        $libelle_article,
        $prix_article,
        $produits
    ) {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            //solde_caisse - prix_article < seuil
            $reste = $solde_caisse - $prix_article;
            if ($reste < $seuil_caisse) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_reste_solde', ['field' => $seuil_caisse])
                ];

                return $response;
            }

            //create article
            $article_model = new Article();
            $article_model
                ->setLibelleArticle($libelle_article);
            $response = $article_model->createArticle();
            //error or invalid
            if ($response['message_type'] === 'error' || $response['message_type'] === 'invalid') {
                return $response;
            }
            $id_article = $response['last_inserted'];

            //create sortie
            $sql = "";
            $paramsQuery = [
                'id_utilisateur' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse
            ];
            //date_ds - empty
            if ($this->date_ds === '') {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (NOW(), :id_utilisateur, :num_caisse) ";
            }
            //date_ds - not empty
            else {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (:date_ds, :id_utilisateur, :num_caisse) ";
                $paramsQuery['date_ds'] = $this->date_ds;
            }
            $response = parent::executeQuery($sql, $paramsQuery);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //add num_ds
            $this->id_ds = $response['last_inserted'];
            $this->num_ds = 'S' . date('Ym') . '-' . $this->id_ds;
            $response = parent::executeQuery("UPDATE demande_sortie SET num_ds = :num WHERE id_ds = :id", [
                'num' => $this->num_ds,
                'id' => $this->id_ds
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //create ligne_ds
            $ligne_ds_model = new LigneDs();
            $ligne_ds_model
                ->setPrixArticle($prix_article)
                ->setQuantiteArticle(1)
                ->setIdDs($this->id_ds)
                ->setIdArticle($id_article);
            $ligne_ds_model->createLigneDs();
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //update caisse solde
            $response = Caisse::updateSolde($this->num_caisse, $prix_article, 'decrease');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //update nb stock
            foreach ($produits as $index => $line) {
                $response = Produit::updateNbStock($line['id_produit'], $line['nb'], 'increase');
                //errorf
                if ($response['message_type'] === 'error') {
                    return $response;
                }
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.sortie_createSortie')
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }
}
