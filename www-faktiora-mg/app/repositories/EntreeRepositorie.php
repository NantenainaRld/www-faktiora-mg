<?php

//CLASS - entree repositorie
class EntreeRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //filter facture
    public static function filterFacture($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $paramsQuery = [];
        $sql = "SELECT f.num_facture, f.date_facture, f.id_utilisateur, f.num_caisse, f.id_client, COALESCE(SUM(lf.prix * lf.quantite_produit), 0)  AS montant_facture FROM facture f LEFT JOIN ligne_facture lf ON lf.id_facture = f.id_facture LEFT JOIN utilisateur u ON u.id_utilisateur = f.id_utilisateur LEFT JOIN client cl ON cl.id_client = f.id_client LEFT JOIN caisse c ON c.num_caisse = f.num_caisse  ";

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status
        $sql .= "AND f.etat_facture = :status ";
        $paramsQuery['status'] = $params['status'];

        //num_caisse - !all
        if ($params['num_caisse'] !== 'all') {
            $sql .= "AND f.num_caisse = :num_caisse ";
            $paramsQuery['num_caisse'] = $params['num_caisse'];
        }

        //id_user - !all
        if ($params['id_user'] !== 'all') {
            $sql .= "AND f.id_utilisateur = :id_user ";
            $paramsQuery['id_user'] = $params['id_user'];
        }

        //from - empty
        if ($params['from'] === '') {
            //to - not empty
            if ($params['to'] !== '') {
                $sql .= "AND DATE(f.date_facture) <= :to ";
                $paramsQuery['to'] = $params['to'];
            }
        }
        //from - not empty
        else {
            //to - empty
            if ($params['to'] === '') {
                $sql .= "AND DATE(f.date_facture) >= :from ";
                $paramsQuery['from'] = $params['from'];
            }
            //to - not empty
            else {
                $sql .= "AND DATE(f.date_facture) BETWEEN :from AND :to ";
                $paramsQuery['from'] = $params['from'];
                $paramsQuery['to'] = $params['to'];
            }
        }

        //search facture
        if ($params['search_facture'] !== '') {
            $sql .= "AND (f.num_facture LIKE :search) ";
            $paramsQuery['search'] = '%' . $params['search_facture'] . '%';
        }

        // group by and order by
        $sql .= "GROUP BY f.id_facture ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {

            $response  = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $total_montant = array_sum(array_column($response['data'], 'montant_facture'));

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_facture' => count($response['data']),
                'total_montant' => $total_montant
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_filterFacture',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //static - ligne facture
    public static function ligneFacture($num_facture)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];
        try {

            $response = parent::selectQuery("SELECT p.id_produit, p.libelle_produit, p.prix_produit, lf.quantite_produit, lf.prix, (lf.quantite_produit * lf.prix) AS prix_total, lf.id_lf FROM produit p JOIN ligne_facture lf ON lf.id_produit = p.id_produit JOIN facture f ON f.id_facture = lf.id_facture WHERE f.num_facture = :num_facture AND p.etat_produit != 'suprimé' ", ['num_facture' => $num_facture]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //montant lf
            $montant_facture = array_sum(array_column($response['data'], 'prix_total'));

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'montant_facture' => $montant_facture
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.entree_ligneFature',
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
