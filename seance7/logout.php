<?php
require_once('lib/auth.php');
unset($_SESSION['ident']);
session_destroy();
?>
<!DOCTYPE html">                                        
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">                              
  <head>                                                                                         
    <meta charset="UTF-8"/>
    <title>Déconnexion</title>
  </head>
  
<body><p> Vous êtes déconnecté </p></body>
</html>
