<?php

class User extends Database
{
    private $results = [];
    public function __construct()
    {
        parent::__construct();
    }

    //add client
    public function addUser()
    {
        $this->results = $this->selectQuery("SELECT * FROM produit");
        print_r($this->results);
    }
}
