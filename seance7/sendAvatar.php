<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once("lib/biblio.php");
require_once('lib/auth.php');
$infoAvatar = $bd->getAvatar($_SESSION['ident']->getLogin(), "images/avatar_def.png");
header("Content-Type: {$infoAvatar->type}");
//header("Content-Type: text/plain");
// print_r($infoAvatar);
fpassthru($infoAvatar->flux);
close($infoAvatar->flux);
return;

?>