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
    CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) THEN 'online' ELSE 'disconnected' AS
STATUS
    ,
    CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) THEN 0 ELSE TIMESTAMPDIFF(
        MINUTE,
        u.dernier_session,
        NOW())
    END AS minutes_ago,
    CASE WHEN TIMESTAMPDIFF(
        MINUTE,
        u.dernier_session,
        NOW()) < 60 THEN CONCAT(
            TIMESTAMPDIFF(MINUTE, u.dernier_session),
            NOW()),
            ' m'
        ) WHEN TIMESTAMPDIFF(HOUR, u.dernier_session, NOW()) < 24 THEN CONCAT(
            TIMESTAMPDIFF(HOUR, u.dernier_session, NOW()),
            ' h') ELSE CONCAT(
                TIMESTAMPDIFF(DAY, u.dernier_session, NOW()),
                ' d')
            END AS last_time,
            COUNT(f.num_facture) AS nb_factures,
            COUNT(ae.id_ae) AS nb_ae,
            (
                COUNT(f.num_facture) + COUNT(ae.id_entree)
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
            utilisateur u LEFT JOIN ligne_caisse lc ON lc.id_utilisateur = u.id_utilisateur AND lc.date_fin IS NULL ";

        //all
        if ($params['status'] === 'all') {
            $sql .= "LEFT JOIN facture f ON
    f.id_utilisateur = u.id_utilisateur
LEFT JOIN ligne_facture lf ON
    lf.num_facture = f.num_facture
LEFT JOIN autre_entree ae ON
    ae.id_utilisateur = u.id_utilisateur
LEFT JOIN demande_sortie ds ON
    ds.id_utilisateur = u.id_utilisateur
LEFT JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds ";
        }

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //all - no deleted
        if ($params['status'] === 'all') {
            $sql .= "AND etat_utilisateur != 'supprimé' ";
        }

        //group by
        $sql .= "GROUP BY u.id_utilisateur ";


        try {
            //db
            $db = new Database();
            //filter user
            // $response = $db->selectQuery($sql);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //nb_user
            $nb_user = count($response['data']);
            $nb_admin = 0;
            $nb_caissier = 0;

            foreach ($response['data'] as $data) {
                if ($data['role'] === 'admin') {
                    $nb_admin++;
                } else {
                    $nb_caissier++;
                }
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
