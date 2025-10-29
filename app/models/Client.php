<?php

class Client extends Database
{
    private $results = [];
    public function __construct()
    {
        parent::__construct();
    }

    public function alefa()
    {
        $this->results = $this->selectQuery("SELECT * FROM produit
             WHERE id_produit = :id", [':id' => "tes"]);
        print_r($this->results);
    }
}
