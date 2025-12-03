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
                    caisse c LEFT JOIN ligne_caisse lc ON lc.num_caisse = c.num_caisse LEFT JOIN utilisateur u ON u.id_utilisateur = lc.id_utilisateur ";

        //date_by - 
        switch ($params['date_by']) {

            //date_by - all
            case 'all':
                $sql .= "LEFT JOIN facture f ON
            f.num_caisse = c.num_caisse 
        LEFT JOIN ligne_facture lf ON
            lf.id_facture = f.id_facture
        LEFT JOIN produit p ON
            p.id_produit = lf.id_produit 
            LEFT JOIN autre_entree ae ON
            ae.num_caisse = c.num_caisse
            LEFT JOIN demande_sortie ds ON
            ds.num_caisse = c.num_caisse 
            LEFT JOIN ligne_ds lds ON
            lds.id_ds = ds.id_ds ";
                break;
            //date_by - per
            case 'per':
                switch ($params['per']) {

                    //per - DAY
                    case 'DAY':
                        $sql .= "LEFT JOIN facture f ON
            f.num_caisse = c.num_caisse AND DATE(f.date_facture) = CURDATE() 
        LEFT JOIN ligne_facture lf ON
            lf.id_facture = f.id_facture
        LEFT JOIN produit p ON
            p.id_produit = lf.id_produit 
            LEFT JOIN autre_entree ae ON
            ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) = CURDATE() 
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
                            lf.id_facture = f.id_facture
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
                if ($params['from'] === '') {
                    //to - !empty
                    $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND DATE(f.date_facture) <= :to
                LEFT JOIN ligne_facture lf ON
                    lf.id_facture = f.id_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) <= :to 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) <= :to 
                    LEFT JOIN ligne_ds lds ON
                    lds.id_ds = ds.id_ds ";
                    $paramsQuery['to'] = $params['to'];
                }
                //from - !empty
                else {
                    //to - empty
                    if ($params['to'] === '') {
                        $sql .= "LEFT JOIN facture f ON
                                f.num_caisse = c.num_caisse AND DATE(f.date_facture) >= :from
                    LEFT JOIN ligne_facture lf ON
                        lf.id_facture = f.id_facture
                    LEFT JOIN produit p ON
                        p.id_produit = lf.id_produit 
                        LEFT JOIN autre_entree ae ON
                        ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) >= :from 
                        LEFT JOIN demande_sortie ds ON
                        ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) >= :from
                        LEFT JOIN ligne_ds lds ON
                        lds.id_ds = ds.id_ds ";
                        $paramsQuery['from'] = $params['from'];
                    }
                    //to - !empty
                    else {
                        $sql .= "LEFT JOIN facture f ON
                                f.num_caisse = c.num_caisse AND DATE(f.date_facture) BETWEEN :from AND :to 
                    LEFT JOIN ligne_facture lf ON
                        lf.id_facture = f.id_facture
                    LEFT JOIN produit p ON
                        p.id_produit = lf.id_produit 
                        LEFT JOIN autre_entree ae ON
                        ae.num_caisse = c.num_caisse AND DATE(ae.date_ae) BETWEEN :from AND :to 
                        LEFT JOIN demande_sortie ds ON
                        ds.num_caisse = c.num_caisse AND DATE(ds.date_ds) BETWEEN :from AND :to 
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
                                f.num_caisse = c.num_caisse AND YEAR(f.date_facture) = :year
                    LEFT JOIN ligne_facture lf ON
                        lf.id_facture = f.id_facture
                    LEFT JOIN produit p ON
                        p.id_produit = lf.id_produit 
                        LEFT JOIN autre_entree ae ON
                        ae.num_caisse = c.num_caisse AND YEAR(ae.date_ae) = :year 
                        LEFT JOIN demande_sortie ds ON
                        ds.num_caisse = c.num_caisse AND YEAR(ds.date_ds) = :year 
                        LEFT JOIN ligne_ds lds ON
                        lds.id_ds = ds.id_ds ";
                    $paramsQuery['year'] = $params['year'];
                }
                //month - !none
                else {
                    //year -
                    $sql .= "LEFT JOIN facture f ON
                            f.num_caisse = c.num_caisse AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year
                LEFT JOIN ligne_facture lf ON
                    lf.id_facture = f.id_facture
                LEFT JOIN produit p ON
                    p.id_produit = lf.id_produit 
                    LEFT JOIN autre_entree ae ON
                    ae.num_caisse = c.num_caisse AND MONTH(ae.date_ae) = :month AND YEAR(ae.date_ae) = :year 
                    LEFT JOIN demande_sortie ds ON
                    ds.num_caisse = c.num_caisse AND MONTH(ds.date_ds) = :month AND YEAR(ds.date_ds) = :year
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
            $sql .= "AND c.etat_caisse != 'supprimé' ";
        }
        //status - free
        elseif ($params['status'] === 'libre') {
            $sql .= "AND c.etat_caisse != 'libre' ";
        }
        //status - occuped
        elseif ($params['status'] === 'occupé') {
            $sql .= "AND c.etat_caisse != 'occupé' ";
        }
        //status - deleted
        else {
            $sql .= "AND c.etat_caisse = 'supprimé' ";
        }

        //search_caisse
        if ($params['search_caisse'] !== '') {
            $sql .= "AND (c.num_caisse LIKE :search OR u.id_utilisateur LIKE :search) ";
            $paramsQuery['search'] = "%" . $params['search_caisse'] . "%";
        }

        //group by
        $sql .= "GROUP BY c.num_caisse ";

        //order_by
        $sql .= "ORDER BY {$params['order_by']} {$params['arrange']} ";

        try {

            $response = parent::selectQuery($sql, $paramsQuery);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //nb_user
            $nb_caisse = count($response['data']);
            $nb_free = 0;
            $nb_occuped = 0;

            $i = 0;
            foreach ($response['data'] as $data) {
                //count free
                if ($data['etat_caisse'] === 'libre') {
                    $nb_free++;
                }
                //count occuped
                else {
                    $nb_occuped++;
                }
                $i++;
            }

            $response = [
                'message_type' => 'success',
                'message' => 'success',
                'data' => $response['data'],
                'nb_caisse' => $nb_caisse,
                'nb_free' => $nb_free,
                'nb_occuped' => $nb_occuped,
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
