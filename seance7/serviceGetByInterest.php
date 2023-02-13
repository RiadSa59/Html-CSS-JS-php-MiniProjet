<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once("lib/biblio.php");
$interest = inputFilterString('interest',FALSE);

$res = $bd->getByInterest($interest);

header("Content-type: application/json;charset=UTF-8");
echo json_encode($res);

return;

?>