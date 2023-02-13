<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once('lib/auth.php');
// script accessible uniquement pour un utilisateur authentifié
require_once("lib/biblio.php");
$list = inputFilterString('list',FALSE);

if ($bd->updateInterest($_SESSION['ident']->getLogin(),$list))
     $reponse= array("status"=>"done","date" => (new DateTime())->format('Y-m-d H:i:s'));
 else
     $reponse= array("status"=>"error");
     
     
header("Content-type: application/json;charset=UTF-8");
echo json_encode($reponse);
return;

?>