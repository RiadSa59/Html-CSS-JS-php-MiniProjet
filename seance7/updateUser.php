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
    $message = "Vos informations personnelles ont été mises à jour";
}
else


    $message = "Les données n'ont pu être enregistrées";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Mise à jour des données personnelles</title>
    </head>
    <body>
    <p class=message"><?php echo $message ?></p>
    <footer><p><a href="./">Retour à la page d'accueil</a></p><p><a href="logout.php">Se déconnecter</a></p>
    </body>
</html>