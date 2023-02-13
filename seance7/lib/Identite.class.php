<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
class Identite implements JsonSerializable { 
  private $login;
  private $nom;
  private $prenom;
  public function __construct($login,$nom,$prenom)
  {
    $this->login = $login;
    $this->nom = $nom;
    $this->prenom = $prenom;
  }
  public function getLogin(){
    return $this->login;
  }
  public function getNom(){
    return $this->nom;
  }
  public function getPrenom(){
    return $this->prenom;
  }
  public function setNom($s){
    $this->nom = $s;
  }
  public function setPrenom($s){
    $this->prenom = $s;
  }
  public function toString(){
    return $this->getPrenom(). " " . $this->getNom();
  }
  public function jsonSerialize() {
        $tab = array();
        foreach ($this as $key=>$val){
          $tab[$key]=$val;
        }
        return $tab;
    }
}
return;
?> 