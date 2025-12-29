<?php

//CLASS - article
class Article extends Database
{
    private $id_article = null;
    private $libelle_article = "";
    private $etat_article = 'actif';

    public function __construct()
    {
        parent::__construct();
    }

    //========================== SETTERS =========================

    //setter - id_article
    public function setIdArticle($id_article)
    {
        $this->id_article = $id_article;
        return $this;
    }

    //setter - libelle_article
    public function setLibelleArticle($libelle_article)
    {
        $this->libelle_article = $libelle_article;
        return $this;
    }

    //setter - etat_article
    public function setEtatArticle($etat_article)
    {
        $this->etat_article = $etat_article;
        return $this;
    }

    //============================= GETTERS ============================

    //getter - etat_article
    public function getEtatArticle()
    {
        return $this->etat_article;
    }

    //============================== PUBLIC FUNCTION ========================

    //create article
    public function createArticle($lower = null)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            if ($lower) {
                $this->libelle_article = strtolower($this->libelle_article);
            }

            //is libelle article exist ?
            $response = self::isLibelleArticleExist($this->libelle_article, null);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.article_libelle_article', ['field' => $this->libelle_article])
                ];

                return $response;
            }

            //create article
            $response = parent::executeQuery(
                "INSERT INTO article (libelle_article) VALUE (:libelle) ",
                ['libelle' => $this->libelle_article]
            );

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.article_createArticle'),
                'last_inserted' => $response['last_inserted']
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //static - list all article
    public static function listAllArticle()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            $response = parent::selectQuery("SELECT a.id_article, a.libelle_article, COALESCE(MAX(lds.prix_article),1) AS prix_article, COALESCE(MAX(lds.quantite_article),1) AS quantite_article FROM article a LEFT JOIN ligne_ds lds ON lds.id_article = a.id_article WHERE a.etat_article != 'supprimé' GROUP BY a.id_article ORDER BY a.id_article ASC");

            //error
            if ($response['message_type'] == 'error') {
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
                    'errors.catch.article_listAllArticle',
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
    public static function findById($id_article)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        try {

            $response = parent::selectQuery("SELECT * FROM article WHERE id_article = :id ", ['id' => $id_article]);

            //error
            if ($response['message_type'] === 'error') {
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
                $article_model = new Article();
                $article_model
                    ->setIdArticle($response['data'][0]['id_article'])
                    ->setLibelleArticle($response['data'][0]['libelle_article'])
                    ->setEtatArticle($response['data'][0]['etat_article']);

                $response = [
                    'message_type' => 'success',
                    'message' => 'success',
                    'found' => true,
                    'model' => $article_model
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
                    'errors.catch.article_findById',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //update article
    public function updateArticle()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

            //is libelle article exist ?
            $response = self::isLibelleArticleExist($this->libelle_article, $this->id_article);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.article_libelle_article', ['field' => $this->libelle_article])
                ];

                return $response;
            }

            //update article
            $response = parent::executeQuery("UPDATE article SET libelle_article = :libelle WHERE id_article = :id", [
                'libelle' => $this->libelle_article,
                'id' => $this->id_article
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.article_updateArticle', ['field' => $this->id_article])
            ];

            return $response;
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

            return $response;
        }

        return $response;
    }

    //static - delete all article
    public static function deleteAllArticle($ids_article)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($ids_article), '?'));
        $sql = "UPDATE article SET etat_article = 'supprimé' WHERE id_article IN ({$placeholders}) ";

        try {

            $response = parent::executeQuery($sql, $ids_article);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.article_deleteAllArticle_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.article_deleteAllArticle_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.article_deleteAllArticle_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.article_deleteAllArticle',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - permanent delete all article
    public static function permanentDeleteAllArticle($ids_article)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        $placeholders = implode(', ', array_fill(0, count($ids_article), '?'));
        $sql = "DELETE FROM article WHERE id_article IN ({$placeholders}) AND etat_article = 'supprimé' ";

        try {

            $response = parent::executeQuery($sql, $ids_article);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //success
            //0
            if ($response['row_count'] === 0) {
                $response['message'] = __('messages.success.article_deleteAllArticle_0');
            }
            //1
            elseif ($response['row_count'] === 1) {
                $response['message'] = __('messages.success.article_deleteAllArticle_1');
            }
            //plur
            else {
                $response['message'] = __('messages.success.article_deleteAllArticle_plur', ['field' => $response['row_count']]);
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
                    'errors.catch.article_deleteAllArticle',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //==================== PRIVATE FUNCTION ======================

    //static - is libelle article exist ?
    private static function isLibelleArticleExist($libelle_article, $exclude = null)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];
        $sql = "SELECT libelle_article FROM article WHERE libelle_article = :libelle ";
        $paramsQuery = ['libelle' => $libelle_article];
        if ($exclude) {
            $sql .= "AND id_article != :exclude";
            $paramsQuery['exclude'] = $exclude;
        }

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response['found'] = false;
            }
            //found
            else {
                $response['found'] = true;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'found' => $response['found']
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.article_isLibelleArticleExist',
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
