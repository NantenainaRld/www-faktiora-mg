<?php

//CLASS - article
class Article extends Database
{
    private $id_article = null;
    private $libelle_article = "";
    private $etat_article = 'actif';

    public function __cons()
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
        $this->libelle_article = strtolower($libelle_article);
        return $this;
    }

    //setter - etat_article
    public function setEtatArticle($etat_article)
    {
        $this->etat_article = $etat_article;
        return $this;
    }

    //============================== PUBLIC FUNCTION ========================

    //create article
    public function createArticle()
    {
        $response = ['message_type' => 'success', 'message' => 'success'];

        try {

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
                'message' => __('messages.success.article_createArticle')
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
