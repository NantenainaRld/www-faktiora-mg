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
        //sql
        $sql = "SELECT
    u.id_utilisateur,
    u.nom_utilisateur,
    u.prenoms_utilisateur,
    u.sexe_utilisateur,
    u.email_utilisateur,
    u.role,
    lc.num_caisse,
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
            utilisateur u ";

        //num_caisse - all
        if ($params['num_caisse'] === 'all') {
            $sql .= "LEFT JOIN ligne_caisse lc ON lc.id_utilisateur = u.id_utilisateur AND lc.date_fin IS NULL ";
        }
        //num_caisse - !all
        else {
            $sql .= "JOIN ligne_caisse lc ON lc.id_utilisateur = u.id_utilisateur AND lc.num_caisse = {$params['num_caisse']} ";
        }

        //date_by -  all
        if ($params['date_by'] === 'all') {
            //join
            $sql .= "LEFT JOIN facture f ON
            f.id_utilisateur = u.id_utilisateur AND f.num_caisse = lc.num_caisse 
 LEFT JOIN ligne_facture lf ON
    lf.num_facture = f.num_facture 
LEFT JOIN produit p ON
    p.id_produit = lf.id_produit
LEFT JOIN autre_entree ae ON
    ae.id_utilisateur = u.id_utilisateur AND ae.num_caisse = lc.num_caisse 
    LEFT JOIN demande_sortie ds ON
    ds.id_utilisateur = u.id_utilisateur AND ds.num_caisse = lc.num_caisse
    LEFT JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds ";
        }
        //date_by - per
        elseif ($params['date_by'] === 'per') {
            //per - day
            if ($params['per'] === 'DAY') {

                $sql .= "LEFT JOIN facture f ON
                f.id_utilisateur = u.id_utilisateur AND f.num_caisse = lc.num_caisse AND DATE(f.date_facture) = CURDATE() 
                LEFT JOIN ligne_facture lf ON
                lf.num_facture = f.num_facture 
                LEFT JOIN produit p ON
                p.id_produit = lf.id_produit
                LEFT JOIN autre_entree ae ON
                ae.id_utilisateur = u.id_utilisateur AND ae.num_caisse = lc.num_caisse AND DATE(ae.date_ae) = CURDATE() 
                LEFT JOIN demande_sortie ds ON
                ds.id_utilisateur = u.id_utilisateur AND ds.num_caisse = lc.num_caisse AND DATE(ds.date_ds) = CURDATE()
                LEFT JOIN ligne_ds lds ON
                lds.id_ds = ds.id_ds ";
            }
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
            $sql .= "AND u.role = '{$params['role']}' ";
        }

        //sexe - !all
        if ($params['sexe'] !== 'all') {
            $sql .= "AND u.sexe_utilisateur = '{$params['sexe']}' ";
        }

        //group by
        $sql .= "GROUP BY u.id_utilisateur ";

        //order_by
        $sql .= "ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {
            //filter user
            $response = self::selectQuery($sql);

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
                //role - admin
                if ($data['role'] === 'admin') {
                    $response['data'][$i]['nb_ae'] = "-";
                    $response['data'][$i]['nb_factures'] = "-";
                    $response['data'][$i]['nb_entrees'] = "-";
                    $response['data'][$i]['nb_sorties'] = "-";
                    $response['data'][$i]['nb_transactions'] = "-";
                    $response['data'][$i]['total_ae'] = "-";
                    $response['data'][$i]['total_factures'] = "-";
                    $response['data'][$i]['total_entrees'] = "-";
                    $response['data'][$i]['total_sorties'] = "-";
                    $response['data'][$i]['total_transactions'] = "-";
                    $response['data'][$i]['num_caisse'] = "-";
                }
                $i++;
            }

            //final
            $response['nb_user'] = $nb_user;
            $response['nb_admin'] = $nb_admin;
            $response['nb_caissier'] = $nb_caissier;

            return $response;
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_repositorie_filter_user', ['field' => $e->getMessage()]);
        }

        return $response;
    }
}
