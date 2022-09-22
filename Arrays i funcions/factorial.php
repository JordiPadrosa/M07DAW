<?php

$array = [1, 2, 3, 4, 5];
print_r(factorialArray($array));
function factorialArray($array){
    if(is_array($array)){
        foreach ($array as $num){
            if(is_numeric($num)){
            $resultat[] = factorial($num);
            }
            else{
                return false;
            }
        }
    }else{
        return false;
    }
    return $resultat;  
}
function factorial($num){
    $factorial = 1; 
    for ($i = 1; $i <= $num; $i++){ 
      $factorial = $factorial * $i; 
    } 
    return $factorial;
}
?>