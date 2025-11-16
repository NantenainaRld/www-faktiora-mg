<?php

//CLASS - caisse repositorie
class CaisseRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //filter caisse
    public static function filterCaisse($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        //sql
        $sql = "SELECT
            c.num_caisse,
            c.solde,
            c.seuil,
            c.etat_caisse,
            u.id_utilisateur,
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
                    caisse c LEFT JOIN ligne_caisse lc ON lc.num_caisse = c.num_caisse AND lc.date_fin IS NULL LEFT JOIN utilisateur u ON u.id_utilisateur = lc.id_utilisateur ";

        //date_by - 
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                $sql .= "LEFT JOIN facture f ON
                    f.num_caisse = c.num_caisse
        LEFT JOIN ligne_facture lf ON
            lf.num_facture = f.num_facture
        LEFT JOIN produit p ON
            p.id_produit = lf.id_produit 
            LEFT JOIN autre_entree ae ON
            ae.num_caisse = c.num_caisse 
            LEFT JOIN demande_sortie ds ON
            ds.num_caisse = c.num_caisse
            LEFT JOIN ligne_ds lds ON
            lds.id_ds = ds.id_ds ";
                break;
            // date_by - per
            case 'per':
                //per - 
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND DATE(f.date_facture) = CURDATE()
                LEFT JOIN ligne_facture lf ON
                    lf.num_facture = f.num_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND DATE(ae.date_ae)  = CURDATE() 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) = CURDATE() 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                        break;
                    //per - !DAY
                    default:
                        $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) 
                LEFT JOIN ligne_facture lf ON
                    lf.num_facture = f.num_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND {$params['per']}(ae.date_ae)  = {$params['per']}(CURDATE()) 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND {$params['per']}(ds.date_ds) = {$params['per']}(CURDATE()) 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                        break;
                }
                break;
            //date_by - between
            case 'between':
                //from - empty
                if (empty($params['from'])) {
                    //to - !empty
                    $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND DATE(f.date_facture) <= '{$params['to']}'
                LEFT JOIN ligne_facture lf ON
                    lf.num_facture = f.num_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) <= '{$params['to']}' 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) <= '{$params['to']}' 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                }
                //from - !empty
                else {
                    //to - empty
                    if (empty($params['to'])) {
                        $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND DATE(f.date_facture) >= '{$params['from']}'
                LEFT JOIN ligne_facture lf ON
                    lf.num_facture = f.num_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) >= '{$params['from']}' 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) >= '{$params['from']}' 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                    }
                    //to - !empty
                    else {
                        $sql .= "LEFT JOIN facture f ON
                                                    f.num_caisse = c.num_caisse AND DATE(f.date_facture) BETWEEN '{$params['from']}' AND '{$params['to']}' 
                                        LEFT JOIN ligne_facture lf ON
                                            lf.num_facture = f.num_facture
                                        LEFT JOIN produit p ON
                                            p.id_produit = lf.id_produit 
                                            LEFT JOIN autre_entree ae ON
                                            ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) BETWEEN '{$params['from']}' AND '{$params['to']}'  
                                            LEFT JOIN demande_sortie ds ON
                                            ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) BETWEEN '{$params['from']}' AND '{$params['to']}'  
                                            LEFT JOIN ligne_ds lds ON
                                            lds.id_ds = ds.id_ds ";
                    }
                }
                break;
            //date_by - month_year
            case 'month_year':
                //month - none
                if ($params['month'] === 'none') {
                    //year
                    $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND YEAR(f.date_facture) = {$params['year']}
                LEFT JOIN ligne_facture lf ON
                    lf.num_facture = f.num_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND YEAR(ae.date_ae) = {$params['year']} 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND YEAR(ds.date_ds) = {$params['year']} 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                }
                //month - !none
                else {
                    //mont && year
                    $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND MONTH(f.date_facture) = {$params['month']} AND YEAR(f.date_facture) = {$params['year']}
                LEFT JOIN ligne_facture lf ON
                    lf.num_facture = f.num_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND MONTH(ae.date_ae) = {$params['month']} AND YEAR(ae.date_ae) = {$params['year']} 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND MONTH(ds.date_ds) = {$params['month']} AND YEAR(ds.date_ds) = {$params['year']} 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                }
                break;
        }

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status - all no deleted
        if ($params['status'] === 'all') {
            $sql .= "AND c.etat_caisse != 'supprimé' ";
        }
        //status - no deleted
        elseif ($params['status'] !== 'deleted') {
            $sql .= "AND c.etat_caisse = '{$params['status']}' ";
        }

        //search_caisse
        $sql .= "AND (c.num_caisse LIKE '%{$params['search_caisse']}%' OR u.id_utilisateur LIKE '%{$params['search_caisse']}%') ";

        //group by
        $sql .= "GROUP BY c.num_caisse ";

        //order_by
        $sql .= "ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {
            //filter user
            $response = self::selectQuery($sql);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //nb_caisse
            $nb_caisse = count($response['data']);
            $nb_free = 0;
            $nb_occuped = 0;

            $i = 0;
            foreach ($response['data'] as $data) {
                //count free
                if ($response['data'][$i]['etat_caisse'] === 'libre') {
                    $nb_free++;
                }
                //count occuped
                elseif ($response['data'][$i]['etat_caisse'] === 'occupé') {
                    $nb_occuped++;
                }
                $i++;
            }

            //final
            $response['nb_caisse'] = $nb_caisse;
            $response['nb_free'] = $nb_free;
            $response['nb_occuped'] = $nb_occuped;

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_repositorie_filter_caisse', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }
}
