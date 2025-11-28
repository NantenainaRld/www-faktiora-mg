<?php

//CLASS - sortie repositorie
class SortieRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //static - connection sortie
    public static function connectionSortie($num = "")
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $num = strtoupper(trim($num));
        $libelle = "correction/" . $num;
        $libelle = "%" . $libelle . "%";

        try {

            $response = parent::selectQuery(
                "SELECT ds.num_ds, a.libelle_article, ds.date_ds, lds.prix_article FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds JOIN article a ON a.id_article = lds.id_article WHERE (a.libelle_article LIKE :libelle ) AND ds.etat_ds !='supprimé' AND a.etat_article != 'supprimé' ",
                ['libelle' => $libelle]
            );

            //error
            if ($response['message_type'] === 'error') {
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
                    'errors.catch.sortie_connectionSortie',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - filter demande sortie
    public static function filterDemandeSortie($params)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        $sql = "SELECT ds.num_ds, ds.date_ds, ds.id_utilisateur, ds.num_caisse , COALESCE(SUM(lds.prix_article * lds.quantite_article), 0) AS montant_ds FROM demande_sortie ds LEFT JOIN ligne_ds lds ON lds.id_ds = ds.id_ds ";
        $paramsQuery = [];

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status
        $sql .= "AND ds.etat_ds = :status ";
        $paramsQuery['status'] = $params['status'];

        //num_caisse - !all
        if ($params['num_caisse'] !== 'all') {
            $sql .= "AND ds.num_caisse = :num_caisse ";
            $paramsQuery['num_caisse'] = $params['num_caisse'];
        }

        //id_user - !all
        if ($params['id_user'] !== 'all') {
            $sql .= "AND ds.id_utilisateur = :id_user ";
            $paramsQuery['id_user'] = $params['id_user'];
        }

        //from - empty
        if ($params['from'] === '') {
            //to - not empty
            if ($params['to'] !== '') {
                $sql .= "AND DATE(ds.date_ds) <= :to ";
                $paramsQuery['to'] = $params['to'];
            }
        }
        //from - not empty
        else {
            //to - empty
            if ($params['to'] === '') {
                $sql .= "AND DATE(ds.date_ds) >= :from ";
                $paramsQuery['from'] = $params['from'];
            }
            //to - not empty
            else {
                $sql .= "AND DATE(ds.date_ds) BETWEEN :from AND :to ";
                $paramsQuery['from'] = $params['from'];
                $paramsQuery['to'] = $params['to'];
            }
        }

        //search_ds
        if ($params['search_ds'] !== '') {
            $sql .= "AND (ds.num_ds LIKE :search ) ";
            $paramsQuery['search'] = "%" . $params['search_ds'] . "%";
        }

        //group by and order by
        $sql .= "GROUP BY ds.id_ds ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //count total
            $total = array_sum(array_column($response['data'], 'montant_ds'));

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_ds' => count($response['data']),
                'total' => $total
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_filterDemandeSortie',
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
