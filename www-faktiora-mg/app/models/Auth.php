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
        $self = new Auth();

        // $_SESSION['auth'] = [];
        // $_SESSION['auth']['id_utilisateur'] = "U123278VW";

        //values - valid
        if (
            isset($_SESSION['auth']) &&
            isset($_SESSION['auth']['id_utilisateur']) &&
            (!empty($_SESSION['auth']['id_utilisateur']))
        ) {
            $self->id_utilisateur = trim($_SESSION['auth']['id_utilisateur']);

            try {
                $response = $self->selectQuery("SELECT id_utilisateur, role FROM utilisateur WHERE id_utilisateur = :id AND etat_utilisateur = 'connecté'", ['id' => $self->id_utilisateur]);

                //error
                if ($response['message_type'] === 'error') {
                    //redirect to error page
                    header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.auth_isLogedIn', ['field' => $response['message']]));

                    return;
                }

                //connected
                if (count($response['data']) >= 1) {
                    $self->id_utilisateur = $response['data'][0]['id_utilisateur'];
                    $self->loged = true;
                    $self->role = $response['data'][0]['role'];
                }
                //disconnected or deleted
                else {
                    $self->loged = false;

                    session_destroy();
                }

                return $self;
            } catch (Throwable $e) {
                //redirect to error page
                header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.auth_isLogedIn', ['field' => $e->getMessage()]));

                return;
            }
        }
        //values - invalid (not loged)
        else {
            $self->loged = false;

            return $self;
        }

        return $self;
    }
}
