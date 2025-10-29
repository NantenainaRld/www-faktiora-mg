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
        // try {
        //     $this->$results = $this->selectQuery("SELECT * FROM produit");
        // } catch (Throwable $e) {
        //     die("Error : " . $e->getMessage());
        // }
        // print_r(count($this->results));
    }
}
