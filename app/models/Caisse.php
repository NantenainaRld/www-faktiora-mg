<?php

class Caisse extends Database
{

    public function __construct()
    {
        parent::__construct();
    }


    //===================== PUBLIC FUNCTION ================

    // add caisse
    public function addCaisse($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_caisse exist?
            $response = $this->isNumCaisseExist($json['num_caisse']);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //found
                if ($response['message'] === 'found') {
                    $response['message_type'] = 'invalid';
                    $response['message'] = "Cet numéro de caisse existe déjà .";
                }
                //not found
                else {
                    //add caisse
                    $response = $this->executeQuery("INSERT INTO caisse (num_caisse, solde, seuil) VALUES (:num_caisse, :solde, :seuil)", [
                        'num_caisse' => $json['num_caisse'],
                        'solde' => $json['solde'],
                        'seuil' => $json['seuil']
                    ]);

                    //error
                    if ($response['message_type'] === 'error') {
                        return $response;
                    }
                    //success
                    else {
                        $response['message'] = "Noveau caisse ajoutée avec succès .";
                    }
                }
            }
        } catch (Throwable $e) {
            $response['message'] = 'error';
            $response['message_type'] = "Error : " . $e->getMessage();
        }

        return $response;
    }
    //update caisse
    public function updateCaisse($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_caisse exist ?
            $response = $this->isNumCaisseExist($json['num_caisse']);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //not found
                if ($response['message'] === 'not found') {
                    $response['message_type'] = 'invalid';
                    $response['message'] = "La caisse choisie (numéro <b>{$json['num_caisse']}</b> n'existe pas .";
                }
                //found
                else {
                    //num_caisse_update exist?
                    $response = $this->isUpdateNumCaisseExist(
                        $json['update_num_caisse'],
                        $json['num_caisse']
                    );

                    //error
                    if ($response['message_type'] === 'error') {
                        return $response;
                    }
                    //success
                    else {
                        //found
                        if ($response['message'] === 'found') {
                            $response['message_type'] = 'invalid';
                            $response['message'] = "Le numéro du caisse <b>{$json['update_num_caisse']}</b> existe déjà .";
                        }
                        //not found
                        else {
                            //id_utilisateur empty
                            if (empty($json['id_utilisateur'])) {
                                //update caisse
                                $response = $this->executeQuery("UPDATE caisse SET num_caisse = :update_num_caisse, solde = :solde, seuil = :seuil WHERE num_caisse = :num_caisse", [
                                    'update_num_caisse' => $json['num_caisse_update'],
                                    'solde' => $json['solde'],
                                    'seuil' => $json['seuil'],
                                    'num_caisse' => $json['num_caisse']
                                ]);

                                //error
                                if ($response['message_type'] === 'error') {
                                    return $response;
                                }
                                //success
                                else {
                                    $response['message_type'] = 'success';
                                    $response['message'] = "Les informations du caisse numéro <b>{$json['num_caisse']}</b> ont été modifiées avec succès .";
                                }
                            }
                            //id_utilisateur not empty
                            else {
                                //user exist?
                                $user_model = new User();
                                $response = $user_model->isUserExist($json['id_utilisateur']);

                                //error
                                if ($response['message_type'] === 'error') {
                                    return $response;
                                }
                                //success
                                else {
                                    //not found
                                    if ($response['message'] === 'not found') {
                                        $response['message'] = "L'utilisateur avec l'ID <b>{$json['id_utilisateur']}</b> n'existe pas .";
                                    }
                                    //found
                                    else {
                                        //update caisse
                                        $response = $this->executeQuery("UPDATE caisse SET num_caisse = :update_num_caisse, solde = :solde, seuil = :seuil, id_utilisateur = :id WHERE num_caisse = :num_caisse", [
                                            'update_num_caisse' => $json['update_num_caisse'],
                                            'solde' => $json['solde'],
                                            'seuil' => $json['seuil'],
                                            'id' => $json['id_utilisateur'],
                                            'num_caisse' => $json['num_caisse']
                                        ]);

                                        //error
                                        if ($response['message_type'] === 'error') {
                                            return $response;
                                        }
                                        //success
                                        else {
                                            $response['message_type'] = 'success';
                                            $response['message'] = "Les informations du caisse numéro <b>{$json['num_caisse']}</b> ont été modifiées avec succès .";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error :  ' . $e->getMessage();
        }

        return $response;
    }
    //filter caisse
    public function filterCaisse($params)
    {
        $paramsQuery = [];

        $response = ['message_type' => 'success', 'message' => 'success'];
        $sql = "SELECT u.id_utilisateur, c.num_caisse,COUNT(c.num_caisse) AS nb_caisse, COUNT(f.num_facture) AS nb_factures, COUNT(ae.id_entree) AS nb_ae, (COUNT(f.num_facture) + COUNT(ae.id_entree)) AS nb_entrees, COUNT(s.id_sortie) AS nb_sorties, (COUNT(f.num_facture) + COUNT(ae.id_entree) + COUNT(s.id_sortie)) AS nb_transactions , COALESCE(SUM(f.montant_facture), 0) AS total_factures, COALESCE(SUM(ae.montant_entree), 0) AS total_ae, COALESCE(SUM(f.montant_facture) + SUM(ae.montant_entree) , 0) AS total_entrees, COALESCE(SUM(s.montant_sortie), 0) AS total_sorties, COALESCE(SUM(f.montant_facture) + + SUM(ae.montant_entree) + SUM(s.montant_sortie) , 0 )  AS total_transactions FROM caisse c LEFT JOIN utilisateur u ON u.id_utilisateur = c.id_utilisateur ";

        //per - none
        if ($params['per'] === 'none') {
            //from - empty
            if ($params['from'] === '') {
                //to - empty
                if ($params['to'] === '') {
                    //month - none
                    if ($params['month'] === 'none') {
                        //year - none
                        if ($params['year'] === 'none') {
                            $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse LEFT JOIN sortie s ON s.num_caisse = c.num_caisse ";
                        }
                        //year - 
                        else {
                            $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND YEAR(f.date_facture) = :year LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND YEAR(ae.date_entree) = :year LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND YEAR(s.date_sortie) = :year ";
                            $paramsQuery['year'] = $params['year'];
                        }
                    }
                    //month - 
                    else {
                        //year - none === now
                        if ($params['year'] === 'none') {
                            $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = YEAR(CURDATE()) LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND MONTH(ae.date_entree) = :month AND YEAR(ae.date_entree) = YEAR(CURDATE()) LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND MONTH(s.date_sortie) = :month AND YEAR(s.date_sortie) = YEAR(CURDATE()) ";
                            $paramsQuery['month'] = $params['month'];
                        }
                        //year - 
                        else {
                            $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND MONTH(f.date_facture) = :month AND YEAR(f.date_facture) = :year LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND MONTH(ae.date_entree) = :month AND YEAR(ae.date_entree) = :year LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND MONTH(s.date_sortie) = :month AND YEAR(s.date_sortie) = :year ";
                            $paramsQuery['month'] = $params['month'];
                            $paramsQuery['year'] = $params['year'];
                        }
                    }
                }
                //to - 
                else {
                    $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND DATE(f.date_facture) <= :to LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND DATE(ae.date_entree) <= :to LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND DATE(s.date_sortie) <= :to ";
                    $paramsQuery['to'] = $params['to'];
                }
            }
            //from -
            else {
                // to - empty
                if ($params['to'] === '') {
                    $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND DATE(f.date_facture) >= :from LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND DATE(ae.date_entree) >= :from LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND DATE(s.date_sortie) >= :from ";
                    $paramsQuery['from'] = $params['from'];
                }
                //to -
                else {
                    $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND DATE(f.date_facture) BETWEEN :from AND :to LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND DATE(ae.date_entree) BETWEEN :from AND :to LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND DATE(s.date_sortie)BETWEEN :from AND :to ";
                    $paramsQuery['from'] = $params['from'];
                    $paramsQuery['to'] = $params['to'];
                }
            }
        }
        //per - type
        else {
            //per - day
            if ($params['per'] === 'day') {
                $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND DATE(f.date_facture) = CURDATE() LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND DATE(ae.date_entree) = CURDATE() LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND DATE(s.date_sortie) = CURDATE() ";
            }
            //per - week year month
            else {
                $params['per'] = strtoupper($params['per']);
                $sql .= "LEFT JOIN facture f ON f.num_caisse = c.num_caisse AND {$params['per']}(f.date_facture) = {$params['per']}(CURDATE()) LEFT JOIN autre_entree ae ON ae.num_caisse = c.num_caisse AND {$params['per']}(ae.date_entree) = {$params['per']}(CURDATE()) LEFT JOIN sortie s ON s.num_caisse = c.num_caisse AND {$params['per']}(s.date_sortie) = {$params['per']}(CURDATE()) ";
            }
        }

        //where
        $sql .= "WHERE 1=1 ";

        //search_caisse
        if ($params['search_caisse'] !== '') {
            $params['search_caisse'] = "%" . $params['search_caisse'] . "%";
            $sql .= "AND (c.num_caisse LIKE :num_caisse OR u.id_utilisateur LIKE :id) ";
            $paramsQuery['num_caisse'] = $params['search_caisse'];
            $paramsQuery['id'] = $params['search_caisse'];
        }
        //type
        if ($params['type'] !== 'all') {
            //null
            if ($params['type'] === 'null') {
                $sql .= "AND c.id_utilisateur IS NULL ";
            }
            //!null
            else {
                $sql .= "AND c.id_utilisateur IS NOT NULL ";
            }
        }
        //group by id
        $sql .= "GROUP BY c.num_caisse ";

        //by - 
        if ($params['by'] !== 'none') {
            $sql .= "ORDER BY {$params['by']} " . strtoupper($params['order_by']);
        }
        //order - none
        else {
            $sql .= "ORDER BY c.num_caisse ASC ";
        }

        try {
            $response = $this->selectQuery($sql, $paramsQuery);
        } catch (Throwable $e) {
            $response = [
                'message_type' => 'error',
                'message' => 'Error : ' . $e->getMessage()
            ];
        }

        return $response;
    }
    //update solde seuil
    public function updateSoldeSeuil($params)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //nums exist?
            $response = $this->numTabExist($params['nums']);

            //error or invalid
            if ($response['message_type'] !== 'success') {
                return $response;
            }
            //success
            else {
                //placeholders
                $placeholders = str_repeat('?, ', count($params['nums']) - 1) . '?';
                $sql = "UPDATE caisse SET solde = ?, seuil = ? WHERE num_caisse IN ({$placeholders})";

                //update multi solde && seuil
                $response = $this->executeQuery($sql, array_merge([$params['solde'], $params['seuil']], $params['nums']));

                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
                //success
                else {
                    $response['message'] = "Solde et seuil sont modifiés pour les caisses : " . implode(', ', $params['nums']);
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
    //free caisse
    public function freeCaisse($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_tab exist?
            $response = $this->numTabExist($json);

            //error or invalid
            if ($response['message_type'] !== 'success') {
                return $response;
            }
            //success
            else {
                //placeholders
                $placeholders = str_repeat('?, ', count($json) - 1) . '?';

                //free caisse
                $response = $this->executeQuery("UPDATE caisse SET id_utilisateur = NULL WHERE num_caisse IN ({$placeholders})", $json);

                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
                //success
                else {
                    //sing
                    if (count($json) === 1) {
                        $response['message'] =  "Une caisse a été libérée avec succès .";
                    }
                    //plur
                    else {
                        $response['message'] = count($json) . " caisses sont libérées avec succès .";
                    }
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
    //delete caisses
    public function deleteCaisse($json)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_caisse tab exist?
            $response = $this->numTabExist($json);

            //error && invalid
            if ($response['message_type'] !== 'success') {
                return $response;
            }
            //success
            else {
                //placeholders
                $placeholders = str_repeat('?, ', count($json) - 1) . '?';

                //delete caisse
                $response = $this->executeQuery("DELETE FROM caisse WHERE num_caisse IN ({$placeholders})", $json);

                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
                //success
                else {
                    //sing
                    if (count($json) === 1) {
                        $response['message'] = "Une caisse a été supprimée avec succès .";
                    }
                    //plur
                    else {
                        $response['message'] = count($json) . " caisses ont été supprimées avec succès.";
                    }
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }

    //================= PRIVATE FUNCTION ======================

    //num_caisse exist?
    private function isNumCaisseExist($num_caisse)
    {
        $response  = [
            'message_type' => 'success',
            'message' => 'not found'
        ];
        try {
            //num_caisse
            $response = $this->selectQuery("SELECT num_caisse FROM caisse WHERE num_caisse = :num_caisse", ['num_caisse' => $num_caisse]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //found
                if (count($response['data']) >= 1) {
                    $response['message'] = 'found';
                }
                //not found
                else {
                    $response['message'] = 'not found';
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
    //num_caisse tab exist?
    private function numTabExist($tab)
    {
        $response  = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //placaeholders
            $placeholders = str_repeat('?, ', count($tab) - 1) . '?';
            //exist
            $response = $this->selectQuery("SELECT num_caisse FROM caisse WHERE num_caisse IN ({$placeholders})", $tab);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //tab founds
                $founds = array_column($response['data'], 'num_caisse');
                //tab not found
                $notFounds = array_values(array_diff($tab, $founds));

                // $response['message'] = $tab;
                // not found
                if (count($notFounds) >= 1) {
                    $response['message_type'] = 'invalid';

                    //sing
                    if (count($notFounds) === 1) {
                        $response['message'] = "Numéro de caisse non trouvé : " . $notFounds[0];
                    }
                    //plur
                    else {
                        $response['message'] = "Numéros de caisse non trouvés : " . implode(', ', $notFounds);
                    }
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
    //num_caisse update exist ?
    private function isUpdateNumCaisseExist($update_num_caisse, $num_caisse)
    {
        $response  = [
            'message_type' => 'success',
            'message' => 'not found'
        ];
        try {
            //num_caisse
            $response = $this->selectQuery("SELECT num_caisse FROM caisse WHERE num_caisse = :update_num_caisse AND num_caisse != :num_caisse", [
                'update_num_caisse' => $update_num_caisse,
                'num_caisse' => $num_caisse
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                //found
                if (count($response['data']) >= 1) {
                    $response['message'] = 'found';
                }
                //not found
                else {
                    $response['message'] = 'not found';
                }
            }
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
}
