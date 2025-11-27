<?php

//CLASS - demande sortie
class DemandeSortie extends Database
{
    private $id_ds = null;
    private $num_ds = "";
    private $date_ds = "";
    private $etat_ds = 'actif';
    private $id_utilisateur = "";
    private $num_caisse = "";

    public function __cons()
    {
        parent::__construct();
    }

    //==================== SETTERS =========================

    //setter - num_ds
    public function setNumDs($num_ds)
    {
        $this->num_ds = $num_ds;
        return $this;
    }

    //setter - date_ds
    public function setDateDs($date_ds)
    {
        $this->date_ds = $date_ds;
        return $this;
    }

    //setter - etat_ds
    public function setEtatDs($etat_ds)
    {
        $this->etat_ds = $etat_ds;
        return $this;
    }

    //setter - id_utilisateur
    public function setIdUtilsateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    //setter - num_caisse
    public function setNumCaisse($num_caisse)
    {
        $this->num_caisse = $num_caisse;
        return $this;
    }

    //======================= PUBLIC FUNCTION ====================

    //create demande sortie
    public function createSortie($solde_caisse, $seuil_caisse, $articles)
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            //(prix_article * quantite) - solde < seuil ?
            $total_prix = 0;
            foreach ($articles as $index => $article) {
                $total_prix += (float)$article['prix_article'] * (int)$article['quantite_article'];
            }
            $reste = $solde_caisse - $total_prix;
            if ($reste < $seuil_caisse) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.sortie_reste_solde', ['field' => $seuil_caisse])
                ];

                return $response;
            }

            //create sortie
            $sql = "";
            $paramsQuery = [
                'id_utilisateur' => $this->id_utilisateur,
                'num_caisse' => $this->num_caisse
            ];
            //date_ds - empty
            if ($this->date_ds === '') {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (NOW(), :id_utilisateur, :num_caisse) ";
            }
            //date_ds - not empty
            else {
                $sql = "INSERT INTO demande_sortie (date_ds, id_utilisateur, num_caisse) VALUES (:date_ds, :id_utilisateur, :num_caisse) ";
                $paramsQuery['date_ds'] = $this->date_ds;
            }
            $response = parent::executeQuery($sql, $paramsQuery);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }
            //add num_ds
            $this->id_ds = $response['last_inserted'];
            $this->num_ds = 'S' . date('Ym') . '-' . $this->id_ds;
            $response = parent::executeQuery("UPDATE demande_sortie SET num_ds = :num WHERE id_ds = :id", [
                'num' => $this->num_ds,
                'id' => $this->id_ds
            ]);
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            //create ligne_ds
            $ligne_ds_model = new LigneDs();
            foreach ($articles as $index => $article) {
                $ligne_ds_model
                    ->setPrixArticle($article['prix_article'])
                    ->setQuantiteArticle($article['quantite_article'])
                    ->setIdDs($this->id_ds)
                    ->setIdArticle($article['id_article']);
                $ligne_ds_model->createLigneDs();
                //error
                if ($response['message_type'] === 'error') {
                    return $response;
                }
            }

            //update caisse solde
            $response = Caisse::updateSolde($this->num_caisse, $total_prix, 'decrease');
            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.sortie_createSortie')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_createSortie',
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
