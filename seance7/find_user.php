<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require('lib/biblio.php');
session_start();
$sujet = inputFilterString('interest');
header("Content-Type: application/json; charset=utf-8");
echo json_encode($bd->searchByInterest($sujet));


?>
