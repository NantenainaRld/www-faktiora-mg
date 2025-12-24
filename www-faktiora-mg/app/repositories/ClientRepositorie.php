<?php

//CLASS - client repositorie
class ClientRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //filter client
    public static function filterClient($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $paramsQuery = [];

        //date_by - 
        $facture_cond = '';
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                break;
            //date_by - per
            case 'per':
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        //facture
                        $facture_cond = 'AND DATE(f.date_facture) = CURDATE() ';
                        break;
                    //per - !DAY
                    default:
                        //facture
                        $facture_cond = "AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) ";
                        break;
                }
                break;
            //date_by - between
            case 'between':
                //from - empty
                if ($params['from'] === '') {
                    //to - !empty
                    //facture
                    $facture_cond = "AND DATE(f.date_facture) <= :to ";
                    $paramsQuery['to'] = $params['to'];
                }
                //from - !empty
                else {
                    //to - empty
                    if ($params['to'] === '') {
                        //facture
                        $facture_cond = "AND DATE(f.date_facture) >= :from ";
                        $paramsQuery['from'] = $params['from'];
                    }
                    //to - !empty
                    else {
                        //facture
                        $facture_cond = "AND DATE(f.date_facture) BETWEEN :from AND :to  ";
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
                    //facture
                    $facture_cond = "AND YEAR(f.date_facture) = :year ";
                    $paramsQuery['year'] = $params['year'];
                }
                //month - !all
                else {
                    //year -
                    //facture
                    $facture_cond = "AND YEAR(f.date_facture) = :year AND MONTH(f.date_facture) = :month ";
                    $paramsQuery['month'] = $params['month'];
                    $paramsQuery['year'] = $params['year'];
                }
                break;
        }

        //sql
        $sql = "SELECT
                cl.id_client,
                CONCAT(cl.nom_client, ' ', cl.prenoms_client) AS fullname,
                cl.nom_client,
                cl.prenoms_client,
                cl.sexe_client,
                cl.telephone,
                cl.adresse,
                cl.etat_client,
                (SELECT COUNT(f.id_facture) 
                    FROM facture f 
                    WHERE f.id_client = cl.id_client AND f.etat_facture != 'supprimé' {$facture_cond}) 
                    AS nb_facture,
                (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                    FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                    WHERE f.id_client = cl.id_client AND f.etat_facture != 'supprimé' {$facture_cond}) 
                    AS total_facture 
                FROM client cl ";

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status - deleted
        if ($params['status'] === 'deleted') {
            $sql .= "AND cl.etat_client = 'supprimé' ";
        }
        //status - active
        elseif ($params['status'] === 'active') {
            $sql .= "AND cl.etat_client = 'actif' ";
        }

        //sexe - !all
        if ($params['sexe'] !== 'all') {
            $sql .= "AND cl.sexe_client = :sexe ";
            $paramsQuery['sexe'] = $params['sexe'];
        }

        //search client
        if ($params['search_client'] !== '') {
            $sql .= "AND (cl.id_client LIKE :search OR cl.nom_client LIKE :search OR cl.prenoms_client LIKE :search OR cl.telephone LIKE :search OR cl.adresse LIKE :search) ";
            $paramsQuery['search'] = "%" . $params['search_client'] . "%";
        }

        //group by and order by
        $sql .= "ORDER BY {$params['arrange_by']} {$params['order']} ";

        try {
            $response  = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.client_filterClient',
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
