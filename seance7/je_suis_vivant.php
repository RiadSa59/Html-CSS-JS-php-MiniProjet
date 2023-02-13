<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once('lib/auth.php');
// script accessible uniquement pour un utilisateur authentifié
require_once("lib/biblio.php");
$personne = $_SESSION['ident'];
$bd->stampUser($personne->getLogin());
$bd->cleanAlive();
$res = $bd->getAliveUsers();


header("Content-type: application/json;charset=UTF-8");
echo json_encode($res);

return;
?>