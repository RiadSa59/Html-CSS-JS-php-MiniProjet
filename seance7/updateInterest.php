<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once('lib/auth.php');
// script accessible uniquement pour un utilisateur authentifié
require_once("lib/biblio.php");
   $list = inputFilterString('list',FALSE);

   if ($bd->updateInterest($_SESSION['ident']->getLogin(),$list))
     $message = "Vos centres d'intérêt ont été mises à jour";
    else
     $message = "Les données n'ont pu être enregistrées";
?>
<!DOCTYPE HTML>
<html>
    <head>
        
        <meta charset="UTF-8" />
        <title>Mise à jour des centres d'intérêt</title>
    </head>
    <body>
    <p class=message"><?php echo $message ?></p>
    <footer><p><a href="./">Retour à la page d'accueil</a></p><p><a href="logout.php">Se déconnecter</a></p>
    </body>
</html>