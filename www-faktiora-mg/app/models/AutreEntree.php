<?php

//CLASS - autre entree
class AutreEntree extends Database
{
    private $id_ae = "";
    private $libelle_ae = "";
    private $date_ae = "";
    private $montant_ae = null;
    private $etat_ae = 'actif';
    private $id_utilisateur = "";
    private $num_caisse = null;

    public function __construct()
    {
        parent::__construct();
    }

    //====================== SETTERS ==============================

    //setter - id_ae
    public function setIdAe($id_ae)
    {
        $this->id_ae = $id_ae;
        return $this;
    }
    //setter - libelle_ae
    public function setLibelleAe($libelle_ae)
    {
        $this->libelle_ae = $libelle_ae;
        return $this;
    }
    //setter - dae_ae
    public function setDateAe($date_ae)
    {
        $this->date_ae = $date_ae;
        return $this;
    }
    //setter - montant_ae
    public function setMontantAe($montant_ae)
    {
        $this->montant_ae = $montant_ae;
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

    //====================== PUBLIC FUNCTION =======================

    //create autre entree
}
