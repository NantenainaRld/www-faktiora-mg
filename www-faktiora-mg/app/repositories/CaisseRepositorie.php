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

        $sql = "SELECT
            (SELECT lc1.id_utilisateur 
                FROM ligne_caisse lc1 
                WHERE lc1.num_caisse = c.num_caisse AND lc1.date_fin IS NULL LIMIT 1)
                AS id_utilisateur,
            c.num_caisse,
            c.solde,
            c.seuil,
            c.etat_caisse,
            (SELECT COUNT(ae.id_ae) 
                    FROM autre_entree ae 
                    WHERE ae.num_caisse = c.num_caisse AND ae.etat_ae != 'supprimé' {$ae_cond}) 
                    AS nb_ae,
            (SELECT COUNT(f.id_facture) 
                FROM facture f 
                WHERE f.num_caisse = c.num_caisse AND f.etat_facture != 'supprimé' {$facture_cond}) 
                    AS nb_facture,
            ((SELECT COUNT(ae.id_ae) 
                FROM autre_entree ae 
                WHERE ae.num_caisse = c.num_caisse AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                (SELECT COUNT(f.id_facture) 
                    FROM facture f 
                    WHERE f.num_caisse = c.num_caisse AND f.etat_facture != 'supprimé' {$facture_cond}))
                    AS nb_entree, 
            (SELECT COUNT(ds.id_ds) 
                FROM demande_sortie ds 
                WHERE ds.num_caisse = c.num_caisse AND ds.etat_ds != 'supprimé' {$sortie_cond}) 
                    AS nb_sortie,
            ((SELECT COUNT(ae.id_ae) 
                FROM autre_entree ae 
                WHERE ae.num_caisse = c.num_caisse AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                (SELECT COUNT(f.id_facture) 
                    FROM facture f 
                    WHERE f.num_caisse = c.num_caisse AND f.etat_facture != 'supprimé' {$facture_cond}) + 
                    (SELECT COUNT(ds.id_ds) 
                        FROM demande_sortie ds 
                        WHERE ds.num_caisse = c.num_caisse AND ds.etat_ds != 'supprimé' {$sortie_cond}) ) 
                    AS nb_transaction,
            (SELECT COALESCE(SUM(ae.montant_ae),0)
                FROM autre_entree ae 
                WHERE ae.num_caisse = c.num_caisse AND ae.etat_ae != 'supprimé' {$ae_cond}) 
                    AS total_ae,
            (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                WHERE f.num_caisse = c.num_caisse AND f.etat_facture != 'supprimé' {$facture_cond}) 
                    AS total_facture,
            ((SELECT COALESCE(SUM(ae.montant_ae),0)
                FROM autre_entree ae 
                WHERE ae.num_caisse = c.num_caisse AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                    FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                    WHERE f.num_caisse = c.num_caisse AND f.etat_facture != 'supprimé' {$facture_cond}) ) 
                    AS total_entree, 
            (SELECT COALESCE(SUM(lds.prix_article * lds.quantite_article),0) 
                FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds 
                WHERE ds.num_caisse = c.num_caisse AND ds.etat_ds != 'supprimé' {$sortie_cond}) 
                    AS total_sortie,
            ((SELECT COALESCE(SUM(ae.montant_ae),0)
                FROM autre_entree ae 
                WHERE ae.num_caisse = c.num_caisse AND ae.etat_ae != 'supprimé' {$ae_cond}) + 
                (SELECT COALESCE(SUM(lf.prix * lf.quantite_produit), 0) 
                    FROM facture f JOIN ligne_facture lf ON lf.id_facture = f.id_facture 
                    WHERE f.num_caisse = c.num_caisse AND f.etat_facture != 'supprimé' {$facture_cond}) + 
                    (SELECT COALESCE(SUM(lds.prix_article * lds.quantite_article),0) 
                        FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds 
                        WHERE ds.num_caisse = c.num_caisse AND ds.etat_ds != 'supprimé' {$sortie_cond})) 
                    AS total_transaction
                FROM
                    caisse c ";

        //where 1=1
        $sql .= "WHERE 1=1 ";

        //status - all 
        if ($params['status'] === 'all') {
        }
        //status - free
        elseif ($params['status'] === 'free') {
            $sql .= "AND c.etat_caisse = 'libre' ";
        }
        //status - occuped
        elseif ($params['status'] === 'occuped') {
            $sql .= "AND c.etat_caisse = 'occupé' ";
        }
        //status - deleted
        else {
            $sql .= "AND c.etat_caisse = 'supprimé' ";
        }

        //search_caisse
        if ($params['search_caisse'] !== '') {
            $sql .= "AND c.num_caisse LIKE :search ";
            $paramsQuery['search'] = "%" . $params['search_caisse'] . "%";
        }

        //arrange_by
        $sql .= "ORDER BY {$params['arrange_by']} {$params['order']} ";

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //nb_free
            $nb_free = 0;
            //nb_occuped
            $nb_occuped = 0;
            //nb_deleted
            $nb_deleted = 0;

            foreach ($response['data'] as &$data) {
                //count free
                if ($data['etat_caisse'] === 'libre') {
                    $nb_free++;
                }
                //count occuped
                elseif ($data['etat_caisse'] === 'occupé') {
                    $nb_occuped++;
                }
                //count deleted
                else {
                    $nb_deleted++;
                }

                //id_utilisateur - empty
                if (empty($data['id_utilisateur'])) {
                    $data['id_utilisateur'] = '-';
                }
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_caisse' => count($response['data']),
                'nb_free' => $nb_free,
                'nb_occuped' => $nb_occuped,
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
                    'errors.catch.caisse_filterCaisse',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }

    //cash report
    public static function cashReport($params)
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $paramsQuery = ['num_caisse' => $params['num_caisse']];
        $sql = "";

        //date_by - all
        if ($params['date_by'] === 'all') {
            $sql = "SELECT
    DATE,
    numero,
    libelle,
    decaissement,
    encaissement
FROM
    (
    SELECT
        DATE(ds.date_ds) AS DATE,
        CONCAT('LS-', lds.id_lds) AS numero,
        a.libelle_article AS libelle,
        (
            lds.quantite_article * lds.prix_article
        ) AS decaissement,
        '' AS encaissement
    FROM
        demande_sortie ds
    JOIN ligne_ds lds ON
        lds.id_ds = ds.id_ds
    JOIN article a ON
        a.id_article = lds.id_article
    WHERE
        a.etat_article != 'supprimé' 
        AND ds.etat_ds != 'supprimé' 
        AND ds.num_caisse = :num_caisse
    UNION ALL
SELECT
    DATE(ae.date_ae) AS DATE,
    ae.num_ae AS numero,
    ae.libelle_ae AS libelle,
    '' AS decaissement,
    ae.montant_ae AS encaissement
FROM
    autre_entree ae
WHERE
    ae.etat_ae != 'supprimé' 
    AND ae.num_caisse = :num_caisse
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT('LF-', lf.id_lf) AS numero,
    p.libelle_produit AS libelle,
    '' AS decaissement,
    (lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
JOIN produit p ON
    p.id_produit = lf.id_produit
WHERE
    p.etat_produit != 'supprimé' 
    AND f.etat_facture != 'supprimé' 
    AND f.num_caisse = :num_caisse
UNION ALL
SELECT
    DATE(ds.date_ds) AS DATE,
    CONCAT(ds.num_ds, '/TOTAL') AS numero,
    'Total sortie' AS libelle,
    SUM(
        lds.quantite_article * lds.prix_article
    ) AS decaissement,
    '' AS encaissement
FROM
    demande_sortie ds
JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds
WHERE
    ds.etat_ds != ' supprimé ' 
    AND ds.num_caisse = :num_caisse
GROUP BY
    ds.id_ds,
    ds.date_ds
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT(f.num_facture, '/TOTAL') AS numero,
    'Total facture' AS libelle,
    '' AS decaissement,
    SUM(lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
WHERE
    f.etat_facture != ' supprimé ' 
    AND f.num_caisse = :num_caisse
GROUP BY
    f.id_facture,
    f.date_facture
) AS DATA
ORDER BY
    DATE,
    CASE WHEN numero LIKE 'A2%' THEN 1 WHEN numero LIKE 'LF-%' THEN 2 WHEN numero LIKE 'F2%/TOTAL' THEN 3 WHEN numero LIKE 'LS-%' THEN 4 WHEN numero LIKE 'S2%/TOTAL' THEN 5
END,
numero;";
        }
        //date_by - !=all
        else {
            switch ($params['date_by']) {
                //per
                case 'per':
                    switch ($params['per']) {
                        //per - DAY
                        case 'DAY':
                            $sql = "SELECT
    DATE,
    numero,
    libelle,
    decaissement,
    encaissement
FROM
    (
    SELECT
        DATE(ds.date_ds) AS DATE,
        CONCAT('LS-', lds.id_lds) AS numero,
        a.libelle_article AS libelle,
        (
            lds.quantite_article * lds.prix_article
        ) AS decaissement,
        '' AS encaissement
    FROM
        demande_sortie ds
    JOIN ligne_ds lds ON
        lds.id_ds = ds.id_ds
    JOIN article a ON
        a.id_article = lds.id_article
    WHERE
        a.etat_article != 'supprimé' 
        AND ds.etat_ds != 'supprimé' 
        AND ds.num_caisse = :num_caisse 
        AND DATE(ds.date_ds) = CURDATE() 
    UNION ALL
SELECT
    DATE(ae.date_ae) AS DATE,
    ae.num_ae AS numero,
    ae.libelle_ae AS libelle,
    '' AS decaissement,
    ae.montant_ae AS encaissement
FROM
    autre_entree ae
WHERE
    ae.etat_ae != 'supprimé' 
    AND ae.num_caisse = :num_caisse 
    AND DATE(ae.date_ae) = CURDATE() 
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT('LF-', lf.id_lf) AS numero,
    p.libelle_produit AS libelle,
    '' AS decaissement,
    (lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
JOIN produit p ON
    p.id_produit = lf.id_produit
WHERE
    p.etat_produit != 'supprimé' 
    AND f.etat_facture != 'supprimé' 
    AND f.num_caisse = :num_caisse 
    AND DATE(f.date_facture) = CURDATE() 
UNION ALL
SELECT
    DATE(ds.date_ds) AS DATE,
    CONCAT(ds.num_ds, '/TOTAL') AS numero,
    'Total sortie' AS libelle,
    SUM(
        lds.quantite_article * lds.prix_article
    ) AS decaissement,
    '' AS encaissement
FROM
    demande_sortie ds
JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds
WHERE
    ds.etat_ds != ' supprimé ' 
    AND ds.num_caisse = :num_caisse 
    AND DATE(ds.date_ds) = CURDATE() 
GROUP BY
    ds.id_ds,
    ds.date_ds
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT(f.num_facture, '/TOTAL') AS numero,
    'Total facture' AS libelle,
    '' AS decaissement,
    SUM(lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
WHERE
    f.etat_facture != ' supprimé ' 
    AND f.num_caisse = :num_caisse 
    AND DATE(f.date_facture) = CURDATE() 
GROUP BY
    f.id_facture,
    f.date_facture
) AS DATA
ORDER BY
    DATE,
    CASE WHEN numero LIKE 'A2%' THEN 1 WHEN numero LIKE 'LF-%' THEN 2 WHEN numero LIKE 'F2%/TOTAL' THEN 3 WHEN numero LIKE 'LS-%' THEN 4 WHEN numero LIKE 'S2%/TOTAL' THEN 5
END,
numero;";
                            break;

                        //per - !DAY
                        default:
                            $sql = "SELECT
    DATE,
    numero,
    libelle,
    decaissement,
    encaissement
FROM
    (
    SELECT
        DATE(ds.date_ds) AS DATE,
        CONCAT('LS-', lds.id_lds) AS numero,
        a.libelle_article AS libelle,
        (
            lds.quantite_article * lds.prix_article
        ) AS decaissement,
        '' AS encaissement
    FROM
        demande_sortie ds
    JOIN ligne_ds lds ON
        lds.id_ds = ds.id_ds
    JOIN article a ON
        a.id_article = lds.id_article
    WHERE
        a.etat_article != 'supprimé' 
        AND ds.etat_ds != 'supprimé' 
        AND ds.num_caisse = :num_caisse 
        AND {$params['per']}(ds.date_ds) = {$params['per']}(CURDATE()) 
    UNION ALL
SELECT
    DATE(ae.date_ae) AS DATE,
    ae.num_ae AS numero,
    ae.libelle_ae AS libelle,
    '' AS decaissement,
    ae.montant_ae AS encaissement
FROM
    autre_entree ae
WHERE
    ae.etat_ae != 'supprimé' 
    AND ae.num_caisse = :num_caisse 
    AND {$params['per']}(ae.date_ae) = {$params['per']}(CURDATE()) 
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT('LF-', lf.id_lf) AS numero,
    p.libelle_produit AS libelle,
    '' AS decaissement,
    (lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
JOIN produit p ON
    p.id_produit = lf.id_produit
WHERE
    p.etat_produit != 'supprimé' 
    AND f.etat_facture != 'supprimé' 
    AND f.num_caisse = :num_caisse 
    AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) 
UNION ALL
SELECT
    DATE(ds.date_ds) AS DATE,
    CONCAT(ds.num_ds, '/TOTAL') AS numero,
    'Total sortie' AS libelle,
    SUM(
        lds.quantite_article * lds.prix_article
    ) AS decaissement,
    '' AS encaissement
FROM
    demande_sortie ds
JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds
WHERE
    ds.etat_ds != ' supprimé ' 
    AND ds.num_caisse = :num_caisse 
    AND {$params['per']}(ds.date_ds) = {$params['per']}(CURDATE()) 
GROUP BY
    ds.id_ds,
    ds.date_ds
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT(f.num_facture, '/TOTAL') AS numero,
    'Total facture' AS libelle,
    '' AS decaissement,
    SUM(lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
WHERE
    f.etat_facture != ' supprimé ' 
    AND f.num_caisse = :num_caisse 
    AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) 
GROUP BY
    f.id_facture,
    f.date_facture
) AS DATA
ORDER BY
    DATE,
    CASE WHEN numero LIKE 'A2%' THEN 1 WHEN numero LIKE 'LF-%' THEN 2 WHEN numero LIKE 'F2%/TOTAL' THEN 3 WHEN numero LIKE 'LS-%' THEN 4 WHEN numero LIKE 'S2%/TOTAL' THEN 5
END,
numero;";
                            break;
                    }
                    break;
                //between
                case 'between':
                    $sql = "SELECT
    DATE,
    numero,
    libelle,
    decaissement,
    encaissement
FROM
    (
    SELECT
        DATE(ds.date_ds) AS DATE,
        CONCAT('LS-', lds.id_lds) AS numero,
        a.libelle_article AS libelle,
        (
            lds.quantite_article * lds.prix_article
        ) AS decaissement,
        '' AS encaissement
    FROM
        demande_sortie ds
    JOIN ligne_ds lds ON
        lds.id_ds = ds.id_ds
    JOIN article a ON
        a.id_article = lds.id_article
    WHERE
        a.etat_article != 'supprimé' 
        AND ds.etat_ds != 'supprimé' 
        AND ds.num_caisse = :num_caisse 
        AND DATE(ds.date_ds) BETWEEN :from AND :to 
    UNION ALL
SELECT
    DATE(ae.date_ae) AS DATE,
    ae.num_ae AS numero,
    ae.libelle_ae AS libelle,
    '' AS decaissement,
    ae.montant_ae AS encaissement
FROM
    autre_entree ae
WHERE
    ae.etat_ae != 'supprimé' 
    AND ae.num_caisse = :num_caisse 
    AND DATE(ae.date_ae) BETWEEN :from AND :to 
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT('LF-', lf.id_lf) AS numero,
    p.libelle_produit AS libelle,
    '' AS decaissement,
    (lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
JOIN produit p ON
    p.id_produit = lf.id_produit
WHERE
    p.etat_produit != 'supprimé' 
    AND f.etat_facture != 'supprimé' 
    AND f.num_caisse = :num_caisse 
    AND DATE(f.date_facture) BETWEEN :from AND :to 
UNION ALL
SELECT
    DATE(ds.date_ds) AS DATE,
    CONCAT(ds.num_ds, '/TOTAL') AS numero,
    'Total sortie' AS libelle,
    SUM(
        lds.quantite_article * lds.prix_article
    ) AS decaissement,
    '' AS encaissement
FROM
    demande_sortie ds
JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds
WHERE
    ds.etat_ds != ' supprimé ' 
    AND ds.num_caisse = :num_caisse 
    AND DATE(ds.date_ds) BETWEEN :from AND :to 
GROUP BY
    ds.id_ds,
    ds.date_ds
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT(f.num_facture, '/TOTAL') AS numero,
    'Total facture' AS libelle,
    '' AS decaissement,
    SUM(lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
WHERE
    f.etat_facture != ' supprimé ' 
    AND f.num_caisse = :num_caisse 
    AND DATE(f.date_facture) BETWEEN :from AND :to 
GROUP BY
    f.id_facture,
    f.date_facture
) AS DATA
ORDER BY
    DATE,
    CASE WHEN numero LIKE 'A2%' THEN 1 WHEN numero LIKE 'LF-%' THEN 2 WHEN numero LIKE 'F2%/TOTAL' THEN 3 WHEN numero LIKE 'LS-%' THEN 4 WHEN numero LIKE 'S2%/TOTAL' THEN 5
END,
numero;";
                    $paramsQuery['from'] = $params['from'];
                    $paramsQuery['to'] = $params['to'];
                    break;
                //month_year
                case 'month_year':
                    //month - none
                    if ($params['month'] === 'none') {
                        $sql = "SELECT
    DATE,
    numero,
    libelle,
    decaissement,
    encaissement
FROM
    (
    SELECT
        DATE(ds.date_ds) AS DATE,
        CONCAT('LS-', lds.id_lds) AS numero,
        a.libelle_article AS libelle,
        (
            lds.quantite_article * lds.prix_article
        ) AS decaissement,
        '' AS encaissement
    FROM
        demande_sortie ds
    JOIN ligne_ds lds ON
        lds.id_ds = ds.id_ds
    JOIN article a ON
        a.id_article = lds.id_article
    WHERE
        a.etat_article != 'supprimé' 
        AND ds.etat_ds != 'supprimé' 
        AND ds.num_caisse = :num_caisse 
        AND YEAR(ds.date_ds) = :year 
    UNION ALL
SELECT
    DATE(ae.date_ae) AS DATE,
    ae.num_ae AS numero,
    ae.libelle_ae AS libelle,
    '' AS decaissement,
    ae.montant_ae AS encaissement
FROM
    autre_entree ae
WHERE
    ae.etat_ae != 'supprimé' 
    AND ae.num_caisse = :num_caisse 
    AND YEAR(ae.date_ae) = :year 
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT('LF-', lf.id_lf) AS numero,
    p.libelle_produit AS libelle,
    '' AS decaissement,
    (lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
JOIN produit p ON
    p.id_produit = lf.id_produit
WHERE
    p.etat_produit != 'supprimé' 
    AND f.etat_facture != 'supprimé' 
    AND f.num_caisse = :num_caisse 
    AND YEAR(f.date_facture) = :year 
UNION ALL
SELECT
    DATE(ds.date_ds) AS DATE,
    CONCAT(ds.num_ds, '/TOTAL') AS numero,
    'Total sortie' AS libelle,
    SUM(
        lds.quantite_article * lds.prix_article
    ) AS decaissement,
    '' AS encaissement
FROM
    demande_sortie ds
JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds
WHERE
    ds.etat_ds != ' supprimé ' 
    AND ds.num_caisse = :num_caisse 
    AND YEAR(ds.date_ds) = :year 
GROUP BY
    ds.id_ds,
    ds.date_ds
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT(f.num_facture, '/TOTAL') AS numero,
    'Total facture' AS libelle,
    '' AS decaissement,
    SUM(lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
WHERE
    f.etat_facture != ' supprimé ' 
    AND f.num_caisse = :num_caisse 
    AND YEAR(f.date_facture) = :year 
GROUP BY
    f.id_facture,
    f.date_facture
) AS DATA
ORDER BY
    DATE,
    CASE WHEN numero LIKE 'A2%' THEN 1 WHEN numero LIKE 'LF-%' THEN 2 WHEN numero LIKE 'F2%/TOTAL' THEN 3 WHEN numero LIKE 'LS-%' THEN 4 WHEN numero LIKE 'S2%/TOTAL' THEN 5
END,
numero;";
                        $paramsQuery['year'] = $params['year'];
                    }
                    //month - !none
                    else {
                        $sql = "SELECT
    DATE,
    numero,
    libelle,
    decaissement,
    encaissement
FROM
    (
    SELECT
        DATE(ds.date_ds) AS DATE,
        CONCAT('LS-', lds.id_lds) AS numero,
        a.libelle_article AS libelle,
        (
            lds.quantite_article * lds.prix_article
        ) AS decaissement,
        '' AS encaissement
    FROM
        demande_sortie ds
    JOIN ligne_ds lds ON
        lds.id_ds = ds.id_ds
    JOIN article a ON
        a.id_article = lds.id_article
    WHERE
        a.etat_article != 'supprimé' 
        AND ds.etat_ds != 'supprimé' 
        AND ds.num_caisse = :num_caisse 
        AND MONTH(ds.date_ds) = :month AND YEAR(ds.date_ds) = :year 
    UNION ALL
SELECT
    DATE(ae.date_ae) AS DATE,
    ae.num_ae AS numero,
    ae.libelle_ae AS libelle,
    '' AS decaissement,
    ae.montant_ae AS encaissement
FROM
    autre_entree ae
WHERE
    ae.etat_ae != 'supprimé' 
    AND ae.num_caisse = :num_caisse 
    AND MONTH(ae.date_ae) = :month AND YEAR(ae.date_ae) = :year 
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT('LF-', lf.id_lf) AS numero,
    p.libelle_produit AS libelle,
    '' AS decaissement,
    (lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
JOIN produit p ON
    p.id_produit = lf.id_produit
WHERE
    p.etat_produit != 'supprimé' 
    AND f.etat_facture != 'supprimé' 
    AND f.num_caisse = :num_caisse 
    AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year 
UNION ALL
SELECT
    DATE(ds.date_ds) AS DATE,
    CONCAT(ds.num_ds, '/TOTAL') AS numero,
    'Total sortie' AS libelle,
    SUM(
        lds.quantite_article * lds.prix_article
    ) AS decaissement,
    '' AS encaissement
FROM
    demande_sortie ds
JOIN ligne_ds lds ON
    lds.id_ds = ds.id_ds
WHERE
    ds.etat_ds != ' supprimé ' 
    AND ds.num_caisse = :num_caisse 
    AND MONTH(ds.date_ds) = :month AND YEAR(ds.date_ds) = :year 
GROUP BY
    ds.id_ds,
    ds.date_ds
UNION ALL
SELECT
    DATE(f.date_facture) AS DATE,
    CONCAT(f.num_facture, '/TOTAL') AS numero,
    'Total facture' AS libelle,
    '' AS decaissement,
    SUM(lf.quantite_produit * lf.prix) AS encaissement
FROM
    facture f
JOIN ligne_facture lf ON
    lf.id_facture = f.id_facture
WHERE
    f.etat_facture != ' supprimé ' 
    AND f.num_caisse = :num_caisse 
    AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year 
GROUP BY
    f.id_facture,
    f.date_facture
) AS DATA
ORDER BY
    DATE,
    CASE WHEN numero LIKE 'A2%' THEN 1 WHEN numero LIKE 'LF-%' THEN 2 WHEN numero LIKE 'F2%/TOTAL' THEN 3 WHEN numero LIKE 'LS-%' THEN 4 WHEN numero LIKE 'S2%/TOTAL' THEN 5
END,
numero;";
                        $paramsQuery['year'] = $params['year'];
                        $paramsQuery['month'] = $params['month'];
                    }
                    break;
            }
        }

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //total_entree
            $total_entree = 0;
            //total_sortie 
            $total_sortie = 0;
            foreach ($response['data'] as $data) {
                if ($data['encaissement'] !== '' && (strpos($data['numero'], '/TOTAL') !== false || strpos($data['numero'], 'A2') === 0)) {
                    $total_entree += (float)$data['encaissement'];
                }
                if ($data['decaissement'] !== '' && strpos($data['numero'], '/TOTAL')) {
                    $total_sortie += (float)$data['decaissement'];
                }
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'total_entree' => $total_entree,
                'total_sortie' => $total_sortie
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.caisse_cashReport',
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
