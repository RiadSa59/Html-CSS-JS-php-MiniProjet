<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
   require("lib/biblio.php");
   $login = inputFilterString('login');
   $password = inputFilterString('password');
   $nom = inputFilterString('nom');
   $prenom = inputFilterString('prenom');
   if ($bd->addUser($login,$password,$nom,$prenom))
     $message = "Le compte a été créé";
    else
     echo "Problème lors de la création du compte";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Création d'un compte utilisateur</title>
    </head>
    <body>
    <p class=message"><?php echo $message ?></p>
    <footer><p><a href="./">Retour à la page d'accueil</a></p><p><a href="logout.php">Se déconnecter</a></p>
    </body>
    
</html>
