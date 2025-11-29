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
        $sql = "SELECT cl.id_client, cl.nom_client, cl.prenoms_client, cl.sexe_client, cl.telephone, cl.adresse, COUNT(f.id_facture) AS nb_factures FROM client cl ";

        //date_by - 
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client ";
                break;
            //date_by - per
            case 'per':
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND DATE(f.date_facture) = CURDATE() ";
                        break;

                    //per - !DAY
                    default:
                        $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) ";
                        break;
                }
                break;
            //date_by - between
            case 'between':
                //from - empty
                if ($params['from'] === '') {
                    //to - !empty
                    $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND DATE(f.date_facture) <= :to ";
                    $paramsQuery['to'] = $params['to'];
                }
                //from - !empty
                else {
                    //to - empty
                    if ($params['to'] === '') {
                        $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND DATE(f.date_facture) >= :from ";
                        $paramsQuery['from'] = $params['from'];
                    }
                    //to - !empty
                    else {
                        $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND DATE(f.date_facture) BETWEEN :from AND :to ";
                        $paramsQuery['from'] = $params['from'];
                        $paramsQuery['to'] = $params['to'];
                    }
                }
                break;
            //date_by - month_year
            case 'month_year':
                //month - none
                if ($params['month'] === 'none') {
                    //year - 
                    $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND YEAR(f.date_facture) = :year ";
                    $paramsQuery['year'] = $params['year'];
                }
                //month - !none
                else {
                    //year -
                    $sql .= "LEFT JOIN facture f ON f.id_client = cl.id_client AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year ";
                    $paramsQuery['month'] = $params['month'];
                    $paramsQuery['year'] = $params['year'];
                }
                break;
        }

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status
        $sql .= "AND cl.etat_client = :status ";
        $paramsQuery['status'] = $params['status'];

        //sexe - !all
        if ($params['sexe'] !== 'all') {
            $sql .= "AND cl.sexe_client = :sexe ";
            $paramsQuery['sexe'] = $params['sexe'];
        }

        //search client
        if ($params['search_client'] !== '') {
            $sql .= "AND (cl.id_client LIKE :search OR cl.nom_client LIKE :search OR cl.prenoms_client LIKE :search OR cl.telephone LIKE :search OR cl.adresse LIKE :search) ";
            $paramsQuery['search'] = $params['search_client'];
        }

        //group by and order by
        $sql .= "GROUP BY cl.id_client ORDER BY {$params['order_by']} {$params['arrange']} ";

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
                'nb_client' => count($response['data'])
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
