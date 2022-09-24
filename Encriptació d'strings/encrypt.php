<?php
$clau  = 'jordi';
$metode = 'aes-256-cbc';
$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
 $encriptar = function ($text) use ($metode, $clau, $iv) {
     return openssl_encrypt ($text, $metode, $clau, false, $iv);
 };
 
 $desencriptar = function ($text) use ($metode, $clau, $iv) {
     $text_encriptat = base64_decode($text);
     return openssl_decrypt($text, $metode, $clau, false, $iv);
 };
 
$text = "Hola sóc en Jordi";
$text_encriptat = $encriptar($text);
$text_desencriptat = $desencriptar($text_encriptat);
echo 'Encriptat: '. $text_encriptat . '<br>';
echo 'Desencriptat: '. $text_desencriptat . '<br>';
?>