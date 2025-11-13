<?php

//CLASS - auth
class Auth extends Database
{
    private $id_utilisateur = null;
    private $loged = false;
    private $role;

    public function __construct()
    {
        parent::__construct();
    }

    //==================== SETTERS ===============================

    //setter - id_utilisateur
    public function setIdUtilisateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }
    //setter - loged
    public function setLoged($loged)
    {
        $this->loged = $loged;
        return $this;
    }
    //setter - role
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    //==================== GETTERS ==============================

    //getter - id_utilisateur
    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }
    //getter - loged
    public function getLoged()
    {
        return $this->loged;
    }
    //getter - role
    public function getRole()
    {
        return $this->role;
    }

    //==================== PUBLIC FUNCTIONS ======================

    //static - is loged
    public static function isLogedIn()
    {
        $auth_model = new Auth();

        //values - valid
        if (
            isset($_SESSION['auth']) &&
            isset($_SESSION['auth']['id_utilisateur']) &&
            (!empty($_SESSION['auth']['id_utilisateur']))
        ) {
            $auth_model->loged = true;
            // $self = new Auth();
            // $self->loged = true;
            // $self->id_utilisateur = $_SESSION['auth']['id_utilisateur'];
        }
        //values - invalid (not loged)
        else {
            $auth_model->loged = false;
        }

        return $auth_model;
    }
}
