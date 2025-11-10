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
        $sql = "SELECT u.id_utilisateur, u.nom_utilisateur, u.prenoms_utilisateur, u.sexe_utilisateur, u.email_utilisateur, u.role,
        CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) THEN '" . __('forms.status.online') . "' ELSE '" . __('forms.status.disconnected') . "' END AS status, CASE WHEN u.dernier_session >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) THEN 0 ELSE TIMESTAMPDIFF(MINUTE, u.dernier_session, NOW()) AS minutes_ago, CASE WHEN TIMESTAMPDIFF(MINUTE, u.dernier_session, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, u.dernier_session)), COUNT(f.num_facture) AS nb_factures, COUNT(ae.id_ae) AS nb_ae, (COUNT(f.num_facture) + COUNT(ae.id_entree)) AS nb_entrees, COUNT(ds.id_ds) AS nb_sorties, (COUNT(f.num_facture) + COUNT(ae.id_ae) + COUNT(ds.id_ds)) AS nb_transactions , COALESCE(SUM(lf.quantite_produit * p.prix_produit), 0) AS total_factures, COALESCE(SUM(ae.montant_ae), 0) AS total_ae, COALESCE(SUM(lf.quantite_produit * p.prix_produit) + SUM(ae.montant_ae) , 0) AS total_entrees, COALESCE(SUM(lds.quantite_article * lds.prix_article), 0) AS total_sorties, COALESCE(SUM(lf.quantite_produit * p.prix_produit) + SUM(ae.montant_ae) + SUM(lds.quantite_article  * lds.prix_article) , 0 )  AS total_transactions FROM utilisateur u ";

        try {
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.user_repositorie_filter_user', ['field' => $e->getMessage()]);
        }

        return $response;
    }
}
