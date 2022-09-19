<?php
 
$sp = "kfhxivrozziuortghrvxrrkcrozxlwflrh";
$mr = " hv ovxozwozv vj o vfrfjvivfj h vmzvlo e hrxvhlmov oz ozx.vw z xve hv loqvn il hv lmnlg izxvwrhrvml ,hv b lh mv,rhhv mf w zrxvlrh.m";
 
echo decrypt($sp);
echo "<br>";
echo decrypt($mr);
 
function decrypt($sp){
    $alf = str_split("abcdefghijklmnopqrstuvwxyz", 1);
    $sp_array = str_split($sp, 1);
 
    foreach($sp_array as $lletra){
        if(in_array($lletra, $alf)){
            $temporal[] = $alf[count($alf) - array_search($lletra, $alf)-1];
        }
        else{
            $temporal[] = $lletra;
        }
    }
    $sp_array = implode("", $temporal);
    $sp_array = str_split($sp_array, 3);
    $sp_array = array_reverse($sp_array);
    $sp_array = implode("", $sp_array);
    $sp_array = strrev($sp_array);
    echo $sp_array;
     
}
?>
