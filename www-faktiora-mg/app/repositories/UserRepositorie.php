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

        //date_by - 
        $ae_cond = '';
        $facture_cond = '';
        $sortie_cond = '';
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                break;
            //date_by - per
            case 'per':
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        //ae
                        $ae_cond = 'AND DATE(ae.date_ae) = CURDATE() ';
                        //facture
                        $facture_cond = 'AND DATE(f.date_facture) = CURDATE() ';
                        //sortie
                        $sortie_cond = 'AND DATE(ds.date_ds) = CURDATE() ';
                        break;
                    //per - !DAY
                    default:
                        //ae
                        $ae_cond = "AND {$params['per']}(ae.date_ae) = {$params['per']}(CURDATE()) ";
                        //facture
                        $facture_cond = "AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) ";
                        //sortie
                        $sortie_cond = "AND {$params['per']}(ds.date_ds) = {$params['per']}(CURDATE()) ";
                        break;
                }
                break;
            //date_by - between
            case 'between':
                //from - empty
                if ($params['from'] === '') {
                    //to - !empty
                    //ae
                    $ae_cond = "AND DATE(ae.date_ae) <= :to ";
                    //facture
                    $facture_cond = "AND DATE(f.date_facture) <= :to ";
                    //sortie
                    $sortie_cond = "AND DATE(ds.date_ds) <= :to ";
                    $paramsQuery['to'] = $params['to'];
                }
                //from - !empty
                else {
                    //to - empty
                    if ($params['to'] === '') {
                        //ae
                        $ae_cond = "AND DATE(ae.date_ae) >= :from ";
                        //facture
                        $facture_cond = "AND DATE(f.date_facture) >= :from ";
                        //sortie
                        $sortie_cond = "AND DATE(ds.date_ds) >= :from ";
                        $paramsQuery['from'] = $params['from'];
                    }
                    //to - !empty
                    else {
                        //ae
                        $ae_cond = "AND DATE(ae.date_ae) BETWEEN :from AND :to  ";
                        //facture
                        $facture_cond = "AND DATE(f.date_facture) BETWEEN :from AND :to  ";
                        //sortie
                        $sortie_cond = "AND DATE(ds.date_ds) BETWEEN :from AND :to  ";
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
                    //ae
                    $ae_cond = "AND YEAR(ae.date_ae) = :year ";
                    //facture
                    $facture_cond = "AND YEAR(f.date_facture) = :year ";
                    //ds
                    $sortie_cond = "AND YEAR(ds.date_ds) = :year ";
                    $paramsQuery['year'] = $params['year'];
                }
                //month - !all
                else {
                    //year -
                    //ae
                    $ae_cond = "AND YEAR(ae.date_ae) = :year AND MONTH(ae.date_ae) = :month ";
                    //facture
                    $facture_cond = "AND YEAR(f.date_facture) = :year AND MONTH(f.date_facture) = :month ";
                    //ds
                    $sortie_cond = "AND YEAR(ds.date_ds) = :year AND MONTH(ds.date_ds) = :month ";
                    $paramsQuery['month'] = $params['month'];
                    $paramsQuery['year'] = $params['year'];
                }
                break;
        }

        //sql
        $sql = "SELECT
            u.id_utilisateur,
            CONCAT(u.nom_utilisateur, ' ', u.prenoms_utilisateur) AS fullname,
            u.email_utilisateur,
            u.nom_utilisateur,
            u.prenoms_utilisateur,
            u.role,
            c.num_caisse,
            u.etat_utilisateur,
            u.sexe_utilisateur,
            CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 0 ELSE TIMESTAMPDIFF(
                MINUTE,
                u.dernier_session,
                NOW())
            END AS minutes_ago,
                (SELECT COUNT(ae.id_ae) 
                    FROM autre_entree ae 
                    WHERE ae.id_utilisateur = u.id_utilisateur AND ae.etat_ae != 'supprimé' {$ae_cond}) 
                    AS nb_ae,
                (SELECT COUNT(f.id_facture) 
                    FROM facture f 
                    WHERE f.id_utilisateur = u.id_utilisateur AND f.etat_facture != 'supprimé' {$facture_cond}) 
                    AS nb_facture,
                ((SELECT COUNT(ae.id_ae) 
                    FROM autre_entree ae 
                    WHERE ae.id_utilisateur = u.id_utilisateur AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                    (SELECT COUNT(f.id_facture) 
                    FROM facture f 
                    WHERE f.id_utilisateur = u.id_utilisateur AND f.etat_facture != 'supprimé' {$facture_cond}))
                    AS nb_entree, 
                (SELECT COUNT(ds.id_ds) 
                    FROM demande_sortie ds 
                    WHERE ds.id_utilisateur = u.id_utilisateur AND ds.etat_ds != 'supprimé' {$sortie_cond}) 
                    AS nb_sortie,
                ((SELECT COUNT(ae.id_ae) 
                    FROM autre_entree ae 
                    WHERE ae.id_utilisateur = u.id_utilisateur AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                    (SELECT COUNT(f.id_facture) 
                    FROM facture f 
                    WHERE f.id_utilisateur = u.id_utilisateur AND f.etat_facture != 'supprimé' {$facture_cond}) + 
                    (SELECT COUNT(ds.id_ds) 
                    FROM demande_sortie ds 
                    WHERE ds.id_utilisateur = u.id_utilisateur AND ds.etat_ds != 'supprimé' {$sortie_cond}) ) 
                    AS nb_transaction,
                (SELECT COALESCE(SUM(ae.montant_ae),0)
                    FROM autre_entree ae 
                    WHERE ae.id_utilisateur = u.id_utilisateur AND ae.etat_ae != 'supprimé' {$ae_cond}) 
                    AS total_ae,
                (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                    FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                    WHERE f.id_utilisateur = u.id_utilisateur AND f.etat_facture != 'supprimé' {$facture_cond}) 
                    AS total_facture,
                ((SELECT COALESCE(SUM(ae.montant_ae),0)
                    FROM autre_entree ae 
                    WHERE ae.id_utilisateur = u.id_utilisateur AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                    (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                    FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                    WHERE f.id_utilisateur = u.id_utilisateur AND f.etat_facture != 'supprimé' {$facture_cond}) ) 
                    AS total_entree, 
                (SELECT COALESCE(SUM(lds.prix_article * lds.quantite_article),0) 
                    FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds 
                    WHERE ds.id_utilisateur = u.id_utilisateur AND ds.etat_ds != 'supprimé' {$sortie_cond}) 
                    AS total_sortie,
                ((SELECT COALESCE(SUM(ae.montant_ae),0)
                    FROM autre_entree ae 
                    WHERE ae.id_utilisateur = u.id_utilisateur AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                    (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                    FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                    WHERE f.id_utilisateur = u.id_utilisateur AND f.etat_facture != 'supprimé' {$facture_cond}) + 
                    (SELECT COALESCE(SUM(lds.prix_article * lds.quantite_article),0) 
                    FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds 
                    WHERE ds.id_utilisateur = u.id_utilisateur AND ds.etat_ds != 'supprimé' {$sortie_cond})) 
                    AS total_transaction 
                FROM utilisateur u ";

        //num_caisse - all
        if ($params['num_caisse'] === 'all') {
            $sql .= "LEFT JOIN ligne_caisse lc ON lc.id_utilisateur = u.id_utilisateur AND lc.date_fin IS NULL LEFT JOIN caisse c ON c.num_caisse = lc.num_caisse AND c.etat_caisse != 'supprimé' ";
        }
        //num_caisse - !all 
        else {
            $sql .= "JOIN ligne_caisse lc ON lc.id_utilisateur = u.id_utilisateur JOIN caisse c ON c.num_caisse = :num_caisse AND c.etat_caisse != 'supprimé' ";
            $paramsQuery['num_caisse'] = $params['num_caisse'];
        }

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status - all no deleted
        if ($params['status'] === 'all') {
            $sql .= "AND u.etat_utilisateur != 'supprimé' ";
        }
        //status - connected
        elseif ($params['status'] === 'connected') {
            $sql .= "AND u.etat_utilisateur != 'supprimé' AND u.dernier_session >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND u.etat_utilisateur = 'connecté' ";
        }
        //status - diconnected
        elseif ($params['status'] === 'disconnected') {
            $sql .= "AND u.etat_utilisateur != 'supprimé' AND u.dernier_session < DATE_SUB(NOW(), INTERVAL 5 MINUTE)  OR u.etat_utilisateur = 'déconnecté' ";
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

        //search_user
        if ($params['search_user'] !== '') {
            $sql .= "AND (u.id_utilisateur LIKE :search OR u.nom_utilisateur LIKE :search OR u.prenoms_utilisateur LIKE :search OR u.email_utilisateur LIKE :search ) ";
            $paramsQuery['search'] = "%" . $params['search_user'] . "%";
        }

        //group by && arrange by
        $sql .= " ORDER BY {$params['arrange_by']} {$params['order']} ";

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

            //nb_connected
            $nb_connected = 0;
            //nb_disconnected
            $nb_disconnected = 0;
            //count nb_deleted
            $nb_deleted = 0;

            foreach ($response['data'] as &$data) {
                //count admin
                if ($data['role'] === 'admin') {
                    $nb_admin++;
                }
                //count caissier
                else {
                    $nb_caissier++;
                }

                //count connected
                if ($data['minutes_ago'] <= 0 && $data['etat_utilisateur'] && $data['etat_utilisateur'] === 'connecté') {
                    $nb_connected++;
                    $data['etat_utilisateur'] = 'connected';
                }
                //count disconnected
                elseif ($data['minutes_ago'] > 0 && $data['etat_utilisateur'] === 'déconnecté') {
                    $nb_disconnected++;
                    $data['etat_utilisateur'] = 'disconnected';
                }
                //count deleted
                elseif ($data['etat_utilisateur'] === 'supprimé') {
                    $nb_deleted++;
                    $data['etat_utilisateur'] = 'deleted';
                }

                //num_caisse
                if (empty($data['num_caisse']) && $data['num_caisse'] !== 0) {
                    $data['num_caisse'] = '-';
                }
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_user' => $nb_user,
                'nb_admin' => $nb_admin,
                'nb_caissier' => $nb_caissier,
                'nb_connected' => $nb_connected,
                'nb_disconnected' => $nb_disconnected,
                'nb_deleted' => $nb_deleted
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
