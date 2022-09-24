<?php
$n = 4;
$taula = creaMatriu($n);
echo mostraMatriu($taula, $n);
echo transposaMatriu($taula, $n);
function creaMatriu($n){
    for($i=0;$i<$n;$i++){
        for($y=0;$y<$n;$y++){
            if($y==$i){
                $taula[$i][$y] = "*";
            }elseif($i>$y){
                $taula[$i][$y] = rand(10,20);
            }else{
                $taula[$i][$y] = $i+$y;
            }
        }
    }
    return $taula;
}
function mostraMatriu($taula, $n){
    $string = "<table>";
    for ($i=0;$i<$n;$i++) {
        $string = $string."<tr>";
        for ($y=0;$y<$n;$y++) {
                $string = $string."<td>".$taula[$i][$y]."</td>";
        }
        $string = $string."</tr>";
    }
    $string = $string."</table><br>";
    return $string;
}
function transposaMatriu($taula, $n){
    for ($i=0;$i<$n;$i++){
        for ($y=0;$y<$n;$y++){
            if($y==$i){
                $taulaGirada[$i][$y] = "*";
            }elseif($i>$y){
                $taulaGirada[$i][$y] = $taula[$y][$i];
            }else{
                $taulaGirada[$i][$y] = $taula[$y][$i];
            }
        }
    }
    return mostraMatriu($taulaGirada,$n);
}

?>