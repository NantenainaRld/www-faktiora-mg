<?php

//CLASS - user repositorie
class UserRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //filter user
    public static function filterUser($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $paramsQuery = [];
        $sql = "SELECT
            u.id_utilisateur,
            u.nom_utilisateur,
            u.prenoms_utilisateur,
            u.sexe_utilisateur,
            u.email_utilisateur,
            u.role,
            c.num_caisse,
            CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'online' ELSE 'disconnected' END AS
        status,
            CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 0 ELSE TIMESTAMPDIFF(
                MINUTE,
                u.dernier_session,
                NOW())
            END AS minutes_ago,
            CASE WHEN TIMESTAMPDIFF(
                MINUTE,
                u.dernier_session,
                NOW()) < 60 THEN CONCAT(
                    TIMESTAMPDIFF(MINUTE, u.dernier_session,
                    NOW()),
                    ' m'
                ) 
                 WHEN TIMESTAMPDIFF(HOUR, u.dernier_session, NOW()) < 24 THEN CONCAT(
                    TIMESTAMPDIFF(HOUR, u.dernier_session, NOW()),
                    ' h') ELSE CONCAT(
                        TIMESTAMPDIFF(DAY, u.dernier_session, NOW()),
                        ' d')
                    END AS last_time,
                    COUNT(f.num_facture) AS nb_factures,
                    COUNT(ae.id_ae) AS nb_ae,
                    (
                        COUNT(f.num_facture) + COUNT(ae.id_ae)
                    ) AS nb_entrees,
                    COUNT(ds.id_ds) AS nb_sorties,
                    (
                        COUNT(f.num_facture) + COUNT(ae.id_ae) + COUNT(ds.id_ds)
                    ) AS nb_transactions,
                    COALESCE(
                        SUM(
                            lf.quantite_produit * p.prix_produit
                        ),
                        0
                    ) AS total_factures,
                    COALESCE(SUM(ae.montant_ae),
                    0) AS total_ae,
                    COALESCE(
                        SUM(
                            lf.quantite_produit * p.prix_produit
                        ) + SUM(ae.montant_ae),
                        0
                    ) AS total_entrees,
                    COALESCE(
                        SUM(
                            lds.quantite_article * lds.prix_article
                        ),
                        0
                    ) AS total_sorties,
                    COALESCE(
                        SUM(
                            lf.quantite_produit * p.prix_produit
                        ) + SUM(ae.montant_ae) + SUM(
                            lds.quantite_article * lds.prix_article
                        ),
                        0
                    ) AS total_transactions
                FROM
                    utilisateur u LEFT JOIN ligne_caisse lc ON lc.id_utilisateur = u.id_utilisateur AND lc.date_fin IS NULL LEFT JOIN caisse c ON c.num_caisse = lc.num_caisse ";

        //date_by - 
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                $sql .= "LEFT JOIN facture f ON
            f.id_utilisateur = u.id_utilisateur 
        LEFT JOIN ligne_facture lf ON
            lf.id_facture = f.id_facture
        LEFT JOIN produit p ON
            p.id_produit = lf.id_produit 
            LEFT JOIN autre_entree ae ON
            ae.id_utilisateur = u.id_utilisateur 
            LEFT JOIN demande_sortie ds ON
            ds.id_utilisateur = u.id_utilisateur 
            LEFT JOIN ligne_ds lds ON
            lds.id_ds = ds.id_ds ";
                break;
            //date_by - per
            case 'per':
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        $sql .= "LEFT JOIN facture f ON
            f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) = CURDATE() 
        LEFT JOIN ligne_facture lf ON
            lf.id_facture = f.id_facture
        LEFT JOIN produit p ON
            p.id_produit = lf.id_produit 
            LEFT JOIN autre_entree ae ON
            ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_ae) = CURDATE() 
            LEFT JOIN demande_sortie ds ON
            ds.id_utilisateur = u.id_utilisateur AND DATE(ds.date_ds) = CURDATE() 
            LEFT JOIN ligne_ds lds ON
            lds.id_ds = ds.id_ds ";
                        break;
                    //per - !DAY
                    default:
                        $sql .= "LEFT JOIN facture f ON
                                    f.id_utilisateur = u.id_utilisateur AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) 
                        LEFT JOIN ligne_facture lf ON
                            lf.id_facture = f.id_facture
                        LEFT JOIN produit p ON
                            p.id_produit = lf.id_produit 
                            LEFT JOIN autre_entree ae ON
                            ae.id_utilisateur = u.id_utilisateur AND {$params['per']}(ae.date_ae)  = {$params['per']}(CURDATE()) 
                            LEFT JOIN demande_sortie ds ON
                            ds.id_utilisateur = u.id_utilisateur AND {$params['per']}(ds.date_ds) = {$params['per']}(CURDATE()) 
                            LEFT JOIN ligne_ds lds ON
                            lds.id_ds = ds.id_ds ";
                        break;
                }
                break;
            //date_by - between
            case 'between':
                //from - empty
                if ($params['from'] === '') {
                    //to - !empty
                    $sql .= "LEFT JOIN facture f ON
                            f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) <= :to
                LEFT JOIN ligne_facture lf ON
                    lf.id_facture = f.id_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_ae) <= :to 
                    LEFT JOIN demande_sortie ds ON
                    ds.id_utilisateur = u.id_utilisateur AND DATE(ds.date_ds) <= :to 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                    $paramsQuery['to'] = $params['to'];
                }
                //from - !empty
                else {
                    //to - empty
                    if ($params['to'] === '') {
                        $sql .= "LEFT JOIN facture f ON
                                f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) >= :from
                    LEFT JOIN ligne_facture lf ON
                        lf.id_facture = f.id_facture
                    LEFT JOIN produit p ON
                        p.id_produit = lf.id_produit 
                        LEFT JOIN autre_entree ae ON
                        ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_ae) >= :from 
                        LEFT JOIN demande_sortie ds ON
                        ds.id_utilisateur = u.id_utilisateur AND DATE(ds.date_ds) >= :from
                        LEFT JOIN ligne_ds lds ON
                        lds.id_ds = ds.id_ds ";
                        $paramsQuery['from'] = $params['from'];
                    }
                    //to - !empty
                    else {
                        $sql .= "LEFT JOIN facture f ON
                                f.id_utilisateur = u.id_utilisateur AND DATE(f.date_facture) BETWEEN :from AND :to 
                    LEFT JOIN ligne_facture lf ON
                        lf.id_facture = f.id_facture
                    LEFT JOIN produit p ON
                        p.id_produit = lf.id_produit 
                        LEFT JOIN autre_entree ae ON
                        ae.id_utilisateur = u.id_utilisateur AND DATE(ae.date_ae) BETWEEN :from AND :to 
                        LEFT JOIN demande_sortie ds ON
                        ds.id_utilisateur = u.id_utilisateur AND DATE(ds.date_ds) BETWEEN :from AND :to 
                        LEFT JOIN ligne_ds lds ON
                        lds.id_ds = ds.id_ds ";
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
                    $sql .= "LEFT JOIN facture f ON
                                f.id_utilisateur = u.id_utilisateur AND YEAR(f.date_facture) = :year
                    LEFT JOIN ligne_facture lf ON
                        lf.id_facture = f.id_facture
                    LEFT JOIN produit p ON
                        p.id_produit = lf.id_produit 
                        LEFT JOIN autre_entree ae ON
                        ae.id_utilisateur = u.id_utilisateur AND YEAR(ae.date_ae) = :year 
                        LEFT JOIN demande_sortie ds ON
                        ds.id_utilisateur = u.id_utilisateur AND YEAR(ds.date_ds) = :year 
                        LEFT JOIN ligne_ds lds ON
                        lds.id_ds = ds.id_ds ";
                    $paramsQuery['year'] = $params['year'];
                }
                //month - !none
                else {
                    //year -
                    $sql .= "LEFT JOIN facture f ON
                            f.id_utilisateur = u.id_utilisateur AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year
                LEFT JOIN ligne_facture lf ON
                    lf.id_facture = f.id_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.id_utilisateur = u.id_utilisateur AND MONTH(ae.date_ae) = :month AND YEAR(ae.date_ae) = :year 
                    LEFT JOIN demande_sortie ds ON
                    ds.id_utilisateur = u.id_utilisateur AND MONTH(ds.date_ds) = :month AND YEAR(ds.date_ds) = :year
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                    $paramsQuery['month'] = $params['month'];
                    $paramsQuery['year'] = $params['year'];
                }
                break;
        }

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status - all no deleted
        if ($params['status'] === 'all') {
            $sql .= "AND u.etat_utilisateur != 'supprimé' ";
        }
        //status - connected
        elseif ($params['status'] === 'connected') {
            $sql .= "AND u.etat_utilisateur != 'supprimé' AND u.dernier_session >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        }
        //status - diconnected
        elseif ($params['status'] === 'disconnected') {
            $sql .= "AND u.etat_utilisateur != 'supprimé' AND u.dernier_session < DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        }
        //status - deleted
        else {
            $sql .= "AND u.etat_utilisateur = 'supprimé' ";
        }

        //role - !all
        if ($params['role'] !== 'all') {
            $sql .= "AND u.role = :role ";
            $paramsQuery['role'] = $params['role'];
        }

        //sexe - !all
        if ($params['sexe'] !== 'all') {
            $sql .= "AND u.sexe_utilisateur = :sexe ";
            $paramsQuery['sexe'] = $params['sexe'];
        }

        //num_caisse - !all
        if ($params['num_caisse'] !== 'all') {
            $sql .= "AND c.num_caisse = :num_caisse ";
            $paramsQuery['num_caisse'] = $params['num_caisse'];
        }

        //search_user
        if ($params['search_user'] !== '') {
            $sql .= "AND (u.id_utilisateur LIKE :search OR u.nom_utilisateur LIKE :search OR u.prenoms_utilisateur LIKE :search OR u.email_utilisateur LIKE :search ) ";
            $paramsQuery['search'] = "%" . $params['search_user'] . "%";
        }

        //group by
        $sql .= "GROUP BY u.id_utilisateur ";

        //order_by
        $sql .= "ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //nb_user
            $nb_user = count($response['data']);
            $nb_admin = 0;
            $nb_caissier = 0;

            $i = 0;
            foreach ($response['data'] as $data) {
                //count admin
                if ($data['role'] === 'admin') {
                    $nb_admin++;
                }
                //count caissier
                else {
                    $nb_caissier++;
                }
                //status - deleted
                if ($params['status'] === 'deleted') {
                    $response['data'][$i]['status'] = 'deleted';
                }
                //connected
                if ($response['data'][$i]['minutes_ago'] <= 0) {
                    $response['data'][$i]['last_time'] = '-';
                }
                $i++;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_user' => $nb_user,
                'nb_admin' => $nb_admin,
                'nb_caissier' => $nb_caissier
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.user_filterUser',
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
