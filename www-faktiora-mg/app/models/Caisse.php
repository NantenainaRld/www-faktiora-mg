<?php

class Caisse extends Database
{

    private $num_caisse = null;
    private $solde = 0;
    private $seuil = 0;
    private $etat_caisse = 'actif';

    public function __construct()
    {
        parent::__construct();
    }

    //===================== SETTERS =======================

    //setter - num_caisse
    public function setNumCaisse($num_caisse)
    {
        $this->num_caisse = $num_caisse;
        return $this;
    }
    //setter - solde
    public function setSolde($solde)
    {
        $this->solde = $solde;
        return $this;
    }
    //setter - seuil
    public function setSeuil($seuil)
    {
        $this->seuil = $seuil;
        return $this;
    }
    //setter - seuil
    public function  setEtatCaisse($etat_caisse)
    {
        $this->etat_caisse = $etat_caisse;
        return $this;
    }


    //===================== PUBLIC FUNCTION =============================

    // add caisse
    public function createCaisse()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //num_caisse exist?
            $response = self::isNumCaisseExist($this->num_caisse, false);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //found
            if ($response['found']) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.duplicate.num_caisse', ['field' => $this->num_caisse]);

                return $response;
            }

            //create caisse
            $response = self::executeQuery("INSERT INTO caisse (num_caisse, solde, seuil) VALUES (:num_caisse, :solde, :seuil) ", [
                'num_caisse' => $this->num_caisse,
                'solde' => $this->solde,
                'seuil' => $this->seuil
            ]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //success
            else {
                $response['message'] = __('messages.success.create_caisse', ['field' => $this->num_caisse]);

                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_create_caisse', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }

    //static - find by id
    public static function findById($num_caisse)
    {
        $response = ['message_type' => 'success', 'message' => 'success', 'found' => false];

        try {
            $response = self::selectQuery("SELECT * FROM caisse WHERE num_caisse = :num_caisse", ['num_caisse' => $num_caisse]);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response['found'] = false;
                $response['data'] = [];

                return $response;
            }
            //found
            else {
                $response['found'] = true;

                //model
                $caisse_model = new Caisse();
                $caisse_model->num_caisse = $response['data'][0]['num_caisse'];
                $caisse_model->solde = $response['data'][0]['solde'];
                $caisse_model->seuil = $response['data'][0]['seuil'];
                $caisse_model->etat_caisse = $response['data'][0]['etat_caisse'];

                $response['data'] = [];
                return $response;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_findById', ['field' => $e->getMessage()]);

            return $response;
        }

        return $response;
    }


    //update caisse
    // public function updateCaisse($json)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //num_caisse exist ?
    //         $response = $this->isNumCaisseExist($json['num_caisse']);

    //         //error
    //         if ($response['message_type'] === 'error') {
    //             return $response;
    //         }
    //         //success
    //         else {
    //             //not found
    //             if ($response['message'] === 'not found') {
    //                 $response['message_type'] = 'invalid';
    //                 $response['message'] = "La caisse choisie (numéro <b>{$json['num_caisse']}</b> n'existe pas .";
    //             }
    //             //found
    //             else {
    //                 //num_caisse_update exist?
    //                 $response = $this->isUpdateNumCaisseExist(
    //                     $json['update_num_caisse'],
    //                     $json['num_caisse']
    //                 );

    //                 //error
    //                 if ($response['message_type'] === 'error') {
    //                     return $response;
    //                 }
    //                 //success
    //                 else {
    //                     //found
    //                     if ($response['message'] === 'found') {
    //                         $response['message_type'] = 'invalid';
    //                         $response['message'] = "Le numéro du caisse <b>{$json['update_num_caisse']}</b> existe déjà .";
    //                     }
    //                     //not found
    //                     else {
    //                         //id_utilisateur empty
    //                         if (empty($json['id_utilisateur'])) {
    //                             //update caisse
    //                             $response = $this->executeQuery("UPDATE caisse SET num_caisse = :update_num_caisse, solde = :solde, seuil = :seuil WHERE num_caisse = :num_caisse", [
    //                                 'update_num_caisse' => $json['num_caisse_update'],
    //                                 'solde' => $json['solde'],
    //                                 'seuil' => $json['seuil'],
    //                                 'num_caisse' => $json['num_caisse']
    //                             ]);

    //                             //error
    //                             if ($response['message_type'] === 'error') {
    //                                 return $response;
    //                             }
    //                             //success
    //                             else {
    //                                 $response['message_type'] = 'success';
    //                                 $response['message'] = "Les informations du caisse numéro <b>{$json['num_caisse']}</b> ont été modifiées avec succès .";
    //                             }
    //                         }
    //                         //id_utilisateur not empty
    //                         else {
    //                             //user exist?
    //                             $user_model = new User();
    //                             $response = $user_model->isUserExist($json['id_utilisateur']);

    //                             //error
    //                             if ($response['message_type'] === 'error') {
    //                                 return $response;
    //                             }
    //                             //success
    //                             else {
    //                                 //not found
    //                                 if ($response['message'] === 'not found') {
    //                                     $response['message'] = "L'utilisateur avec l'ID <b>{$json['id_utilisateur']}</b> n'existe pas .";
    //                                 }
    //                                 //found
    //                                 else {
    //                                     //update caisse
    //                                     $response = $this->executeQuery("UPDATE caisse SET num_caisse = :update_num_caisse, solde = :solde, seuil = :seuil, id_utilisateur = :id WHERE num_caisse = :num_caisse", [
    //                                         'update_num_caisse' => $json['update_num_caisse'],
    //                                         'solde' => $json['solde'],
    //                                         'seuil' => $json['seuil'],
    //                                         'id' => $json['id_utilisateur'],
    //                                         'num_caisse' => $json['num_caisse']
    //                                     ]);

    //                                     //error
    //                                     if ($response['message_type'] === 'error') {
    //                                         return $response;
    //                                     }
    //                                     //success
    //                                     else {
    //                                         $response['message_type'] = 'success';
    //                                         $response['message'] = "Les informations du caisse numéro <b>{$json['num_caisse']}</b> ont été modifiées avec succès .";
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error :  ' . $e->getMessage();
    //     }

    //     return $response;
    // }

    // //update solde seuil
    // public function updateSoldeSeuil($params)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //nums exist?
    //         $response = $this->numTabExist($params['nums']);

    //         //error or invalid
    //         if ($response['message_type'] !== 'success') {
    //             return $response;
    //         }
    //         //success
    //         else {
    //             //placeholders
    //             $placeholders = str_repeat('?, ', count($params['nums']) - 1) . '?';
    //             $sql = "UPDATE caisse SET solde = ?, seuil = ? WHERE num_caisse IN ({$placeholders})";

    //             //update multi solde && seuil
    //             $response = $this->executeQuery($sql, array_merge([$params['solde'], $params['seuil']], $params['nums']));

    //             //error
    //             if ($response['message_type'] === 'error') {
    //                 return $response;
    //             }
    //             //success
    //             else {
    //                 $response['message'] = "Solde et seuil sont modifiés pour les caisses : " . implode(', ', $params['nums']);
    //             }
    //         }
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error : ' . $e->getMessage();
    //     }

    //     return $response;
    // }
    // //free caisse
    // public function freeCaisse($json)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //num_tab exist?
    //         $response = $this->numTabExist($json);

    //         //error or invalid
    //         if ($response['message_type'] !== 'success') {
    //             return $response;
    //         }
    //         //success
    //         else {
    //             //placeholders
    //             $placeholders = str_repeat('?, ', count($json) - 1) . '?';

    //             //free caisse
    //             $response = $this->executeQuery("UPDATE caisse SET id_utilisateur = NULL WHERE num_caisse IN ({$placeholders})", $json);

    //             //error
    //             if ($response['message_type'] === 'error') {
    //                 return $response;
    //             }
    //             //success
    //             else {
    //                 //sing
    //                 if (count($json) === 1) {
    //                     $response['message'] =  "Une caisse a été libérée avec succès .";
    //                 }
    //                 //plur
    //                 else {
    //                     $response['message'] = count($json) . " caisses sont libérées avec succès .";
    //                 }
    //             }
    //         }
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error : ' . $e->getMessage();
    //     }

    //     return $response;
    // }
    // //delete caisses
    // public function deleteCaisse($json)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //num_caisse tab exist?
    //         $response = $this->numTabExist($json);

    //         //error && invalid
    //         if ($response['message_type'] !== 'success') {
    //             return $response;
    //         }
    //         //success
    //         else {
    //             //placeholders
    //             $placeholders = str_repeat('?, ', count($json) - 1) . '?';

    //             //delete caisse
    //             $response = $this->executeQuery("DELETE FROM caisse WHERE num_caisse IN ({$placeholders})", $json);

    //             //error
    //             if ($response['message_type'] === 'error') {
    //                 return $response;
    //             }
    //             //success
    //             else {
    //                 //sing
    //                 if (count($json) === 1) {
    //                     $response['message'] = "Une caisse a été supprimée avec succès .";
    //                 }
    //                 //plur
    //                 else {
    //                     $response['message'] = count($json) . " caisses ont été supprimées avec succès.";
    //                 }
    //             }
    //         }
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error : ' . $e->getMessage();
    //     }

    //     return $response;
    // }
    // //affect caisse
    // public function affectCaisse($json)
    // {
    //     $response = [
    //         'message_type' => 'success',
    //         'message' => 'success'
    //     ];

    //     try {
    //         //num_caisse exist?
    //         $response = $this->isNumCaisseExist($json['num_caisse']);

    //         //error
    //         if ($response['message_type'] === 'error') {
    //             return $response;
    //         }
    //         //success
    //         else {
    //             //not found
    //             if ($response['message'] === 'not found') {
    //                 $response['message'] = "Numéro de caisse non trouvé : " . $json['num_caisse'];
    //             }
    //             //found
    //             else {
    //                 //user exist?
    //                 $user_model = new User();
    //                 $response = $user_model->isUserExist($json['id_utilisateur']);

    //                 //error
    //                 if ($response['message_type'] === 'error') {
    //                     return $response;
    //                 }
    //                 //success
    //                 else {
    //                     //not found
    //                     if ($response['message'] === 'not found') {
    //                         $response['message'] = "Utilisateur avec l'ID : <b>"  . $json['id_utilisateur'] . "</b> n'existe pas .";
    //                     }
    //                     //found
    //                     else {
    //                         //affect caisse
    //                         $response = $this->executeQuery("UPDATE caisse SET id_utilisateur = :id WHERE num_caisse = :num_caisse", [
    //                             'id' => $json['id_utilisateur'],
    //                             'num_caisse' => $json['num_caisse']
    //                         ]);

    //                         //error
    //                         if ($response['message_type'] === 'error') {
    //                             return $response;
    //                         }
    //                         //success
    //                         else {
    //                             //move user to the selected caisse
    //                             $response = $this->executeQuery("UPDATE caisse SET id_utilisateur = NULL WHERE id_utilisateur = :id AND num_caisse != :num_caisse", [
    //                                 'id' => $json['id_utilisateur'],
    //                                 'num_caisse' => $json['num_caisse']
    //                             ]);

    //                             //error
    //                             if ($response['message_type'] === 'error') {
    //                                 return $response;
    //                             }
    //                             //success
    //                             else {
    //                                 $response['message'] = "L'utilisateur <b>" . $json['id_utilisateur'] . "</b> a été mis au caisse numéro <b>" . $json['num_caisse'] . "</b>";
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     } catch (Throwable $e) {
    //         $response['message_type'] = 'error';
    //         $response['message'] = 'Error : ' . $e->getMessage();
    //     }

    //     return $response;
    // }

    //===================== PRIVATE FUNCTION ======================

    //static - is num_caisse exist?
    private static function isNumCaisseExist($num_caisse, $exclude = null)
    {
        $response  = [
            'message_type' => 'success',
            'message' => 'success',
            'found' => false
        ];

        $sql = "SELECT num_caisse FROM caisse WHERE num_caisse = :num ";
        $params = ['num' => $num_caisse];

        if ($exclude) {
            $sql .= "AND num_caisse != :num_caisse";
            $params['num_caisse'] = $exclude;
        }

        try {
            $response = self::selectQuery($sql, $params);

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //not found
            if (count($response['data']) <= 0) {
                $response['found'] = false;
            }
            //not found
            else {
                $response['found'] = true;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $response['message_type'] = 'error';
            $response['message'] = __('errors.catch.caisse_isNumCaisseExist', ['field' => $e->getMessage()]);

            return $response;
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
    //update solde
    public function updateSolde($num_caisse, $solde, $type)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {
            //increase
            if ($type === 'increase') {
                $response = $this->executeQuery("UPDATE caisse SET solde = solde + :solde WHERE num_caisse = :um_caisse", [
                    'solde' => $solde,
                    'num_caisse' => $num_caisse
                ]);
            }
            //decrease
            else {
                $response = $this->executeQuery("UPDATE caisse SET solde = solde - :solde WHERE num_caisse = :um_caisse", [
                    'solde' => $solde,
                    'num_caisse' => $num_caisse
                ]);
            }
            // $response
        } catch (Throwable $e) {
            $response['message_type'] = 'error';
            $response['message'] = 'Error : ' . $e->getMessage();
        }

        return $response;
    }
}
