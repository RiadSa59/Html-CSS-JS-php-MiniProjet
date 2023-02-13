<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
 function random64String($n){
    static $charset =  "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./";
    $res ="";
    for ($i=0; $i<$n; $i++){
       $res .= $charset{rand(0,strlen($charset)-1)}; 
    }
    return $res;
 }
 
 function randomSalt(){
    return '$2a$10$'.random64String(22);
 }
 
?>