<?php
$string = "Hola sóc en jordi";
$ip = $_SERVER['REMOTE_ADDR'];
echo $string . $ip;
//echo strToHex($string);
function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}