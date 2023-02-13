<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once('lib/auth.php');
// script accessible uniquement pour un utilisateur authentifié
require_once("lib/biblio.php");
$personne = $_SESSION['ident'];
$password = inputFilterString('password',FALSE);
$nom = inputFilterString('nom',FALSE);
$prenom = inputFilterString('prenom',FALSE);
if ($bd->updateUser($personne->getLogin(),$password,$nom,$prenom)){
    // mise à jour des infos pour la session en cours 
    $_SESSION['ident'] = $bd->getIdentite($personne->getLogin());
    $reponse = array("status"=>"done", "identite" => $_SESSION['ident'], "date" => (new DateTime())->format('Y-m-d H:i:s'));
}
else


    $reponse = array("status"=>"error");
    
header("Content-type: application/json;charset=UTF-8");
echo json_encode($reponse);
return;

?>