<?php

//CLASS - produit repositorie
class ProduitRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //filter produit
    public static function filterProduit($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $sql = "SELECT p.id_produit, p.libelle_produit, p.prix_produit, p.nb_stock, COUNT(lf.id_produit) AS total_produit, COALESCE(SUM(lf.prix * lf.quantite_produit), 0) AS total_produit FROM produit p LEFT JOIN ligne_facture lf ON lf.id_produit = p.id_produit ";
        $paramsQuery = [];

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status
        $sql .= "AND p.etat_produit = :status ";
        $paramsQuery['status'] = $params['status'];

        //search_produit
        $sql .= "AND (p.libelle_produit LIKE :search OR p.id_produit LIKE :search) ";
        $paramsQuery['search'] = $params['search_produit'];

        //group by and order by
        $sql .= "GROUP BY p.id_produit ORDER BY {$params['order_by']} {$params['arrange']} ";

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
                'nb_produit' => count($response['data'])
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.produit_filterProduit',
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
