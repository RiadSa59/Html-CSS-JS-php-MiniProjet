<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
 require_once('lib/biblio.php');
 session_start();
 try {
   controleAuthentification();
 }
 catch(Exception $e)
 { 
   require('lib/formuLogin.php');
   exit();
 }
?>