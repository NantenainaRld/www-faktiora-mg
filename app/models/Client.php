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
        $this->results = $this->selectQuery("SELECT * FROM produit");
        print_r($this->results);
    }
}
