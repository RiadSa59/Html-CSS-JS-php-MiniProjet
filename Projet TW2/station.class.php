<?php

class station
{
    private $CB;
    private $libelle;
    private $name;
    private $adresse;

    public function __construct($cb,$libel,$nom,$adress)
    {
        $CB = "OUI";

        if($cb == "SANS TPE"){
            $CB = "NON";
        }
        $this->CB=$CB;
        $this->libelle=$libel;
        $this->name=$nom;
        $this->adresse=$adress;
    }
    

    public function getCB(){
        return $this->CB;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getName(){
        return $this->name;
    }

    public function getAdresse(){
        return $this->adresse;
    }

}