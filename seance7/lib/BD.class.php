<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
/*
 * Classe consacrée à l'interfaçage avec la base de donnée.
 * À instancier avec une connexion PDO valide
 *
 **/
require('lib/hashUtil.php'); // on peut s'en passer si on utilise la fonction standard password_hash()
class BD {
  private $connexion;
  
  function __construct($connexion){
      $this->connexion = $connexion;
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // force déclenchement exception en cas d'erreur
      $this->connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);  
  }
  
  /*
   * Info sur une personne, sous forme de tableau associatif
   */
  function infoPersonne($login){
    $stmt = $this->connexion->prepare("select * from s10.users where login=:login");
    $stmt->bindValue(":login",$login);
    try {
      $stmt->execute();
      $res = $stmt->fetch();
      return $res;  
    } catch (PDOException $e){
          // print_r($stmt->errorInfo());   // debug
       return NULL;
    }

  }
  
  /*
   * Info sur une personne, sous forme d'objet Identité
   */
  function getIdentite($login){
    $info = $this->infoPersonne($login);
    return new Identite($login, $info['nom'], $info['prenom']);
  }
  /*
   * Liste des intérêts, sous forme de tableau
   */
   function getInterestsTab($login){
    $stmt = $this->connexion->prepare("select sujet from s10.interets where login=:login");
    $stmt->bindValue(":login",$login);
    $stmt->execute();
    try {
      $tab = array();
      while ($res = $stmt->fetch()){
        $tab[] = $res['sujet'];
      }
      return $tab;  
    } catch (PDOException $e){
          // print_r($stmt->errorInfo());   // debug
       return NULL;
    }

  }
  
  /*
   * Liste des intérêts, sous forme de chaîne de caractères (séparateur : virgule)
   */
  function getInterests($login){
    $tab = $this->getInterestsTab($login);
    return implode(",",$tab);
  }
  
  /*
   * Mise à jour des intérêts
   * $list est une chaîne (séparateur : virgule)
   */
  function updateInterest($login,$list){ 
    $tab = explode(',',$list);
    
    $stmt = $this->connexion->prepare("delete from s10.interets where login = :login");
    $stmt->bindValue(":login",$login);
    $stmt->execute();  // effacement de toutes les valeurs précédentes
   
    $stmt = $this->connexion->prepare("insert into s10.interets (sujet,login) values (:sujet,:login)");
    $stmt->bindValue(":login",$login);
    foreach ($tab as $sujet){
      $sujet = trim($sujet);
      if ($sujet !== ""){
        $stmt->bindValue(":sujet",trim($sujet));
        try {
          $stmt->execute();
        } catch (PDOException $e){
              // print_r( $stmt->errorInfo());   // debug
           return NULL;
        } 
      }
    }
    return TRUE;
  }
  
  /*
   * Renvoie une liste (tableau) d'utilisateurs
   */
  function searchByInterest($sujet){
    $stmt = $this->connexion->prepare("select login, nom, prenom 
         from s10.interets natural join s10.users where sujet = :sujet");
    $stmt->bindValue(":sujet",$sujet);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    try {
      if (! $stmt->execute())
        print_r($stmt->errorInfo());
      $res = array();
      while ($ligne = $stmt->fetch()){
        $res[] = $ligne;
      }
      return $res; 
    } catch (PDOException $e){
          // print_r( $stmt->errorInfo());   // debug
        return NULL;
    } 
  }
  
  /*
   * Ajout d'utilisateur
   */
  function addUser($login, $password, $nom, $prenom) {
    $stmt = $this->connexion->prepare(
        "insert into s10.users (login,password,nom,prenom) values (:login, :password, :nom, :prenom)");
    $stmt->bindValue(":login",$login);
    $stmt->bindValue(":nom",$nom);
    $stmt->bindValue(":prenom",$prenom);
    $stmt->bindValue(":password",crypt($password,randomSalt()));
    //   ou : $stmt->bindValue(":password",password_hash($password, PASSWORD_BCRYPT));
    try {
       return $stmt->execute();
    } catch (PDOException $e){
          // print_r($stmt->errorInfo());   // debug
       return NULL;
    }
  }
  
  /*
   * Mise à jour d'un profil :  password et/ou nom et/ou prenom  (ignorés si NULL ou "")
   */
  function  updateUser($login, $password, $nom, $prenom) {
    if ($password){
      $password = crypt($password,randomSalt());
       // on peut aussi iutiliser la fonction standard PHP :
       // $password = password_hash($password, PASSWORD_BCRYPT);
    }
    $infos = ['nom'=>$nom, 'prenom'=>$prenom, 'password'=>  $password]; // informations susceptibles d'être mises à jour
    foreach ($infos as $key=>$val){  // on élimine les informations non fournies
       if ($val===NULL || $val==="")
          unset($infos[$key]);
    }
    if (count($infos)==0) // aucun update à faire
       return NULL;
    $atts = array_keys($infos); 
    $attsString  = implode(',', $atts);  // liste des noms d'attributs à mettre à jour. ex : nom, prenom
    $valString  = implode(',', array_map(function($v){return ":".$v;} , $atts)); // construction de la liste des pseudos-variables (même nom que l'attribut)
                                                                              // ex = :nom, :prenom
    $sql = "update s10.users set ($attsString) = ($valString) where login=:login";
    $stmt = $this->connexion->prepare($sql);
    $stmt->bindValue("login",$login);
    foreach ($infos as $key=>$val){ // exécution des bind() pour la requête préparée
       $stmt->bindValue($key,$val);
    }
    try {
       return $stmt->execute();
    } catch (PDOException $e){
          // print_r( $stmt->errorInfo());   // debug
       return NULL;
    } 
  }
  /*
   * récupère l'avatar d'un utilisateur.
   * Si aucun avatar n'est présent dans la base une image par défaut est envoyée (ou NULL)`
   * Résultat : objet à 2 attributs : flux (flux data ouvert en lecture) et type (mimetype)
   */
  function getAvatar($login, $defaultFileName = NULL){
     $attsString = "type, contenu as avatar";
     $stmt = $this->connexion->prepare("select $attsString from s10.avatars where login = :login");
     $stmt->bindValue("login",$login);
     $stmt->bindColumn('type', $mimeType);
     $stmt->bindColumn('avatar', $flux, PDO::PARAM_LOB);
     $stmt->execute();
     if (! $stmt->fetch() ){ // il n'y a pas d'image dans la base
        if ($defaultFileName === NULL || !is_readable($defaultFileName))
           return NULL;
        $fi = new finfo(FILEINFO_MIME);
        $mimeType = $fi->file($defaultFileName);
        $flux = fopen($defaultFileName,'rb');
     }
     return (object)["type"=>$mimeType, "flux"=>$flux];
  }
  
  /*
   * exécute la commande $sql d'insertion ou mise à jour d'un avatar dans la base
   * $sql possède   3 pseudo-variables :login,  :data, :type
   */
  private function storeAvatar($login, $stream1, $type, $sql){
   $stmt = $this->connexion->prepare($sql);
   $stmt->bindValue(":login",$login);
   $stmt->bindValue(":type",$type);
   $stmt->bindValue(":data",$stream1,PDO::PARAM_LOB);
   $stmt->execute();
  }

  /*
   * enregistre une image avatar dans la base
   * $stream1 est un flux de données ouvert en lecture
   * $type est le mime type
   */
  function setAvatar($login, $stream1, $type){
     try {
        //echo "try insert---";
        $this->storeAvatar($login, $stream1, $type,
                           'insert into s10.avatars (login, type, contenu) values (:login, :type, :data)');
     } catch (PDOException $e) { // en cas de collision ("login"  est clé primaire)
        try {
           //echo "try update---";
           rewind($stream1); 
           $this->storeAvatar($login,  $stream1, $type,
                              'update s10.avatars set (type, contenu) = (:type, :data) where login=:login');
        } catch (PDOException $e) {
           
           return NULL;  
        }
     }
     return TRUE;
  }
  /*
   * Liste des utilisateurs s'intéressant au $sujet
   * 
   * Renvoie un tableau d'identifiants
   */
  function getByInterest($sujet){
    $sql = "select login from s10.interets where sujet=:sujet";
    $stmt = $this->connexion->prepare($sql);
    $stmt->bindValue(":sujet",$sujet);
    $stmt->execute();
    $res = array();
    $ligne = $stmt->fetch();
    while ($ligne){
      $res[] = $ligne['login'];
      $ligne = $stmt->fetch();    
    }
    return $res;
  }
  /*
   * Insère ou met à jour la table still_alive, avec la date actuelle
   */
  function stampUser($login){
    // try update---
    $sql ="update s10.still_alive set stamp=default where login=:login";
    $stmt = $this->connexion->prepare($sql);
    $stmt->bindValue(":login",$login);
    $stmt->execute();
    if ($stmt->rowCount() ==0) { // l'utilisateur était absent de la table
      // ---> insert
      $sql = "insert into s10.still_alive (login) values (:login)";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":login",$login);
      $stmt->execute();
    }
  }
  /*
   * Liste des utilisateurs présents dans still_alive
   */
  function getAliveUsers(){
    $sql ="select login from s10.still_alive";
    $stmt = $this->connexion->query($sql);
    $res = array();
    $ligne = $stmt->fetch();
    while ($ligne){
      $res[] = $ligne['login'];
      $ligne = $stmt->fetch();    
    }
    return $res;
  }
  /*
   * Supprime de  still_alive les utilisateurs n'ayant pas été pointés présents depuis plus de 30s
   */
  function cleanAlive(){
    $sql = "delete from s10.still_alive where now()-stamp > '30 s'::interval ";
    $this->connexion->exec($sql);
  }

}
?>
