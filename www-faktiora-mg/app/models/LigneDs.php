<?php

//CLASS - ligne_ds
class LigneDs extends Database
{
    private $id_lds = null;
    private $prix_article = 1;
    private $quantie_article = 1;
    private $id_ds = "";
    private $id_article = "";

    public function __cons()
    {
        parent::__construct();
    }

    //===================== SETTERS ============================

    //setter - id_lds
    public function setIdLds($id_lds)
    {
        $this->id_lds = $id_lds;
        return $this;
    }

    //setter - prix_article
    public function setPrixArticle($prix_article)
    {
        $this->prix_article = $prix_article;
        return $this;
    }

    //setter - quantite article
    public function setQuantiteArticle($quantie_article)
    {
        $this->quantie_article = $quantie_article;
        return $this;
    }

    //setter - id_ds
    public function setIdDs($id_ds)
    {
        $this->id_ds = $id_ds;
        return $this;
    }

    //setter - id_article
    public function setIdArticle($id_article)
    {
        $this->id_article = $id_article;
        return $this;
    }

    //====================== PUBLIC FUNCTION ========================

    //create ligne_ds
    public function createLigneDs()
    {
        $response = [
            'message_type' => 'success',
            'message' => 'success'
        ];

        try {

            $response = parent::executeQuery(
                "INSERT INTO ligne_ds (prix_article, quantite_article, id_ds, id_article) VALUES (:prix, :quantite, :id_ds, :id_article) ",
                [
                    'prix' => $this->prix_article,
                    'quantite' => $this->quantie_article,
                    'id_ds' => $this->id_ds,
                    'id_article' => $this->id_article
                ]
            );

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            $response = [
                'message_type' => 'success',
                'message' => __('messages.success.sortie_createLigneDs')
            ];

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_createLigneDs',
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
