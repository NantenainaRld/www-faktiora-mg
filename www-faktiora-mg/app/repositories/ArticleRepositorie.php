<?php

//CLASS - article repositorie
class ArticleRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //filter article
    public static function filterArticle($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $sql = "SELECT a.id_article, a.libelle_article, COUNT(lds.id_article) AS quantite_article, COALESCE(SUM(lds.prix_article * lds.quantite_article), 0) AS total_article FROM article a LEFT JOIN ligne_ds lds ON lds.id_article = a.id_article ";
        $paramsQuery = [];

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status
        $sql .= "AND a.etat_article = :status ";
        $paramsQuery['status'] = $params['status'];

        //search_article
        $sql .= "AND (a.id_article LIKE :search OR a.libelle_article LIKE :search) ";
        $paramsQuery['search'] = $params['search_article'];

        //group by and order by
        $sql .= "GROUP BY a.id_article ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_article' => count($response['data'])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.article_filterArticle',
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
