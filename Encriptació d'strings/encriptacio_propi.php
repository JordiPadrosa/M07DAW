<?php
$text = "Hola sóc en jordi";
$ip = $_SERVER['REMOTE_ADDR'];
if($ip == "::1"){
    $ip = "127.0.0.1";
}
$ip = str_replace(".","","}".$ip);
$text = strrev($text.$ip);
$text_encriptat = encriptar($text);
echo $text_encriptat;
echo "<br>";
echo desencriptar($text_encriptat);
function encriptar($string){
    $text_encriptat = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $text_encriptat .= substr('0'.$hexCode, -2);
    }
    return $text_encriptat;
}
function desencriptar($text_encriptat){
    $string='';
    for ($i=0; $i < strlen($text_encriptat)-1; $i+=2){
        $string .= chr(hexdec($text_encriptat[$i].$text_encriptat[$i+1]));
    }
    $string = strrev($string);
    $string = substr($string, 0, strpos($string, "}"));
    return $string;
}
